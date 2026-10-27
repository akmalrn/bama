<?php

namespace App\Services\Visitor;

use App\Models\Database\Visitor;
use App\Models\Database\VisitorArchive;
use App\Models\Database\VisitorSetting;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class VisitorCleanupService
{
    public function preview(
        VisitorSetting $settings,
        mixed $from = null,
        mixed $to = null,
        bool $force = false,
    ): array {
        [$start, $end, $retentionCutoff] = $this->bounds($settings, $from, $to, $force);

        if (! $start || ! $end || $start->gt($end)) {
            return $this->emptyPreview($retentionCutoff);
        }

        $windows = $this->archivedWindows($start, $end);
        $rangeQuery = Visitor::query()->whereBetween('created_at', [$start, $end]);
        $totalInRange = (clone $rangeQuery)->count();

        if ($windows === []) {
            return [
                ...$this->emptyPreview($retentionCutoff),
                'range_start' => $start->toDateTimeString(),
                'range_end' => $end->toDateTimeString(),
                'total_in_range' => $totalInRange,
                'unarchived_records' => $totalInRange,
                'all_archived' => $totalInRange === 0,
            ];
        }

        $candidateQuery = $this->applyWindows(Visitor::query(), $windows);
        $candidateCount = (clone $candidateQuery)->count();

        return [
            'range_start' => $start->toDateTimeString(),
            'range_end' => $end->toDateTimeString(),
            'retention_cutoff' => $retentionCutoff->toDateTimeString(),
            'candidate_count' => $candidateCount,
            'total_in_range' => $totalInRange,
            'unarchived_records' => max(0, $totalInRange - $candidateCount),
            'all_archived' => $totalInRange === $candidateCount,
            'candidate_first_at' => (clone $candidateQuery)->min('created_at'),
            'candidate_last_at' => (clone $candidateQuery)->max('created_at'),
            'windows' => $windows,
        ];
    }

    public function cleanup(
        VisitorSetting $settings,
        mixed $from = null,
        mixed $to = null,
        bool $force = false,
        bool $dryRun = false,
    ): array {
        $preview = $this->preview($settings, $from, $to, $force);

        if ($dryRun) {
            return ['status' => 'dry_run', 'deleted_count' => 0, ...$preview];
        }

        $lock = Cache::lock('visitors:cleanup', 300);

        if (! $lock->get()) {
            throw new RuntimeException('Proses pembersihan visitor sedang dijalankan oleh proses lain.');
        }

        try {
            $deleted = DB::transaction(function () use ($preview, $settings) {
                $deleted = $preview['windows'] === []
                    ? 0
                    : $this->applyWindows(Visitor::query(), $preview['windows'])->delete();

                $settings->forceFill(['last_cleanup_at' => now()])->save();

                return $deleted;
            }, 3);

            Log::info('Visitor cleanup completed.', [
                'deleted_count' => $deleted,
                'range_start' => $preview['range_start'],
                'range_end' => $preview['range_end'],
            ]);

            return ['status' => 'cleaned', 'deleted_count' => $deleted, ...$preview];
        } finally {
            $lock->release();
        }
    }

    private function bounds(VisitorSetting $settings, mixed $from, mixed $to, bool $force): array
    {
        $timezone = $settings->timezone ?: config('app.timezone', 'Asia/Jakarta');
        $cutoff = match ($settings->retention_unit) {
            'weeks' => CarbonImmutable::now($timezone)->subWeeks($settings->retention_value),
            'months' => CarbonImmutable::now($timezone)->subMonthsNoOverflow($settings->retention_value),
            default => CarbonImmutable::now($timezone)->subDays($settings->retention_value),
        };

        $oldest = Visitor::query()->min('created_at');
        $start = $from
            ? CarbonImmutable::parse($from, $timezone)->startOfDay()
            : ($oldest ? CarbonImmutable::parse($oldest, $timezone) : null);
        $requestedEnd = $to
            ? CarbonImmutable::parse($to, $timezone)->endOfDay()
            : ($force ? CarbonImmutable::now($timezone)->subDay()->endOfDay() : $cutoff);
        $end = $force ? $requestedEnd : $requestedEnd->min($cutoff);

        return [$start, $end, $cutoff];
    }

    private function archivedWindows(CarbonImmutable $start, CarbonImmutable $end): array
    {
        $windows = VisitorArchive::query()
            ->where('status', 'completed')
            ->overlapping($start->toDateString(), $end->toDateString())
            ->orderBy('period_start')
            ->get(['period_start', 'period_end'])
            ->map(fn (VisitorArchive $archive) => [
                'start' => CarbonImmutable::parse($archive->period_start)->startOfDay()->max($start),
                'end' => CarbonImmutable::parse($archive->period_end)->endOfDay()->min($end),
            ])
            ->filter(fn (array $window) => $window['start']->lte($window['end']))
            ->values()
            ->all();

        return $this->mergeWindows($windows);
    }

    private function mergeWindows(array $windows): array
    {
        $merged = [];

        foreach ($windows as $window) {
            $lastIndex = array_key_last($merged);

            if ($lastIndex !== null && $window['start']->lte($merged[$lastIndex]['end']->addSecond())) {
                $merged[$lastIndex]['end'] = $merged[$lastIndex]['end']->max($window['end']);
            } else {
                $merged[] = $window;
            }
        }

        return $merged;
    }

    private function applyWindows(Builder $query, array $windows): Builder
    {
        return $query->where(function (Builder $query) use ($windows) {
            foreach ($windows as $window) {
                $query->orWhereBetween('created_at', [$window['start'], $window['end']]);
            }
        });
    }

    private function emptyPreview(CarbonImmutable $retentionCutoff): array
    {
        return [
            'range_start' => null,
            'range_end' => null,
            'retention_cutoff' => $retentionCutoff->toDateTimeString(),
            'candidate_count' => 0,
            'total_in_range' => 0,
            'unarchived_records' => 0,
            'all_archived' => true,
            'candidate_first_at' => null,
            'candidate_last_at' => null,
            'windows' => [],
        ];
    }
}
