<?php

namespace App\Console\Commands;

use App\Models\Database\VisitorSetting;
use App\Services\Visitor\VisitorArchiveService;
use App\Services\Visitor\VisitorPeriodService;
use Illuminate\Console\Command;
use Throwable;

class ArchiveVisitors extends Command
{
    protected $signature = 'visitors:archive
        {--from= : Tanggal awal periode (YYYY-MM-DD)}
        {--to= : Tanggal akhir periode (YYYY-MM-DD)}
        {--force : Jalankan walaupun arsip otomatis nonaktif}
        {--dry-run : Tampilkan estimasi tanpa mengubah database}';

    protected $description = 'Meringkas visitor pada periode yang sudah selesai ke tabel arsip';

    public function handle(VisitorPeriodService $periodService, VisitorArchiveService $archiveService): int
    {
        $settings = VisitorSetting::current();

        if (! $settings->archive_enabled && ! $this->option('force')) {
            $this->warn('Arsip otomatis nonaktif. Gunakan --force untuk menjalankan manual.');

            return self::SUCCESS;
        }

        $from = $this->option('from');
        $to = $this->option('to');

        if (($from && ! $to) || ($to && ! $from)) {
            $this->error('Opsi --from dan --to harus digunakan bersama.');

            return self::INVALID;
        }

        try {
            $period = $from
                ? ['from' => $from, 'to' => $to, 'interval' => 'custom']
                : $periodService->completedPeriod($settings->archive_interval, timezone: $settings->timezone);
            $result = $archiveService->archive(
                $period['from'],
                $period['to'],
                $period['interval'],
                (bool) $this->option('dry-run'),
            );

            $this->table(
                ['Periode', 'Kunjungan', 'Unique', 'Page view', 'Status'],
                [[
                    $result['stats']['period_start'].' s/d '.$result['stats']['period_end'],
                    $result['stats']['total_visits'],
                    $result['stats']['unique_visitors'],
                    $result['stats']['total_pages_viewed'],
                    $result['status'],
                ]]
            );

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}
