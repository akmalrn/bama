<?php

namespace App\Services\Visitor;

use App\Models\Database\Visitor;
use App\Models\Database\VisitorArchive;
use App\Models\Database\VisitorSetting;
use Carbon\CarbonImmutable;
use DomainException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class VisitorArchiveService
{
    public function __construct(private readonly VisitorCleanupService $cleanupService) {}

    public function preview(mixed $from, mixed $to, ?string $timezone = null): array
    {
        [$start, $end] = $this->normalizePeriod($from, $to, $timezone);
        $query = Visitor::query()->whereBetween('created_at', [$start, $end]);

        $totals = (clone $query)->selectRaw(
            'COUNT(*) as total_visits, COUNT(DISTINCT ip_address) as unique_visitors, COUNT(url) as total_pages_viewed'
        )->first();

        $daily = (clone $query)
            ->selectRaw('DATE(created_at) as visit_date, COUNT(*) as total_visits, COUNT(DISTINCT ip_address) as unique_visitors, COUNT(url) as total_pages_viewed')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('visit_date')
            ->get()
            ->mapWithKeys(fn ($row) => [
                $row->visit_date => [
                    'total_visits' => (int) $row->total_visits,
                    'unique_visitors' => (int) $row->unique_visitors,
                    'total_pages_viewed' => (int) $row->total_pages_viewed,
                ],
            ])->all();

        return [
            'period_start' => $start->toDateString(),
            'period_end' => $end->toDateString(),
            'total_visits' => (int) ($totals->total_visits ?? 0),
            'unique_visitors' => (int) ($totals->unique_visitors ?? 0),
            'total_pages_viewed' => (int) ($totals->total_pages_viewed ?? 0),
            'daily_breakdown' => $daily,
        ];
    }

    public function archive(
        mixed $from,
        mixed $to,
        string $interval = 'custom',
        bool $dryRun = false,
        bool $deleteAfterArchive = true,
    ): array {
        [$start, $end] = $this->normalizePeriod($from, $to);
        $stats = $this->preview($start, $end);

        $existing = VisitorArchive::query()
            ->whereDate('period_start', $stats['period_start'])
            ->whereDate('period_end', $stats['period_end'])
            ->first();

        if ($existing) {
            return ['status' => 'already_archived', 'archive' => $existing, 'stats' => $stats];
        }

        if (VisitorArchive::query()->overlapping($stats['period_start'], $stats['period_end'])->exists()) {
            throw new DomainException('Periode beririsan dengan arsip yang sudah ada. Arsip tumpang tindih tidak diizinkan.');
        }

        if ($dryRun) {
            return ['status' => 'dry_run', 'archive' => null, 'stats' => $stats];
        }

        $lock = Cache::lock("visitors:archive:{$stats['period_start']}:{$stats['period_end']}", 300);

        if (! $lock->get()) {
            throw new RuntimeException('Proses arsip untuk periode ini sedang dijalankan oleh proses lain.');
        }

        try {
            $transactionResult = DB::transaction(function () use ($start, $end, $stats, $interval) {
                $existing = VisitorArchive::query()
                    ->whereDate('period_start', $stats['period_start'])
                    ->whereDate('period_end', $stats['period_end'])
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    return ['archive' => $existing, 'stats' => $stats, 'created' => false];
                }

                if (VisitorArchive::query()->overlapping($stats['period_start'], $stats['period_end'])->lockForUpdate()->exists()) {
                    throw new DomainException('Periode beririsan dengan arsip yang sudah ada.');
                }

                $transactionStats = $this->preview($start, $end);
                $archive = VisitorArchive::query()->create([
                    ...$transactionStats,
                    'archive_interval' => $interval,
                    'status' => 'completed',
                    'archived_at' => now(),
                ]);

                VisitorSetting::query()->where('singleton_key', 1)->update([
                    'last_archived_at' => now(),
                ]);

                return ['archive' => $archive, 'stats' => $transactionStats, 'created' => true];
            }, 3);

            $archive = $transactionResult['archive'];
            $stats = $transactionResult['stats'];

            if (! $transactionResult['created']) {
                return ['status' => 'already_archived', 'archive' => $archive, 'stats' => $stats];
            }

            Log::info('Visitor archive completed.', [
                'archive_id' => $archive->id,
                'period_start' => $stats['period_start'],
                'period_end' => $stats['period_end'],
                'total_visits' => $stats['total_visits'],
            ]);

            $settings = VisitorSetting::current();
            $cleanup = null;

            if ($deleteAfterArchive && $settings->auto_delete_enabled && $settings->delete_after_archive) {
                $cleanup = $this->cleanupService->cleanup(
                    $settings,
                    $archive->period_start,
                    $archive->period_end,
                    force: true,
                );
            }

            return ['status' => 'archived', 'archive' => $archive, 'stats' => $stats, 'cleanup' => $cleanup];
        } finally {
            $lock->release();
        }
    }

    private function normalizePeriod(mixed $from, mixed $to, ?string $timezone = null): array
    {
        $timezone ??= VisitorSetting::current()->timezone ?? config('app.timezone', 'Asia/Jakarta');
        $start = CarbonImmutable::parse($from, $timezone)->startOfDay();
        $end = CarbonImmutable::parse($to, $timezone)->endOfDay();

        if ($start->gt($end)) {
            throw new DomainException('Tanggal awal tidak boleh melewati tanggal akhir.');
        }

        if ($end->gte(CarbonImmutable::now($timezone)->startOfDay())) {
            throw new DomainException('Hanya periode yang sudah selesai yang dapat diarsipkan.');
        }

        return [$start, $end];
    }
}
