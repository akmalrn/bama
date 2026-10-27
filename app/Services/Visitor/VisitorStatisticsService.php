<?php

namespace App\Services\Visitor;

use App\Models\Database\Visitor;
use App\Models\Database\VisitorArchive;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;

class VisitorStatisticsService
{
    public function dailyVisits(mixed $from, mixed $to): array
    {
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $start = CarbonImmutable::parse($from, $timezone)->startOfDay();
        $end = CarbonImmutable::parse($to, $timezone)->endOfDay();
        $archives = VisitorArchive::query()
            ->where('status', 'completed')
            ->overlapping($start->toDateString(), $end->toDateString())
            ->orderBy('period_start')
            ->get();

        $daily = [];
        $windows = [];

        foreach ($archives as $archive) {
            $archiveStart = CarbonImmutable::parse($archive->period_start)->startOfDay()->max($start);
            $archiveEnd = CarbonImmutable::parse($archive->period_end)->endOfDay()->min($end);
            $windows[] = [$archiveStart, $archiveEnd];

            if ($archive->daily_breakdown) {
                foreach ($archive->daily_breakdown as $date => $values) {
                    if ($date >= $start->toDateString() && $date <= $end->toDateString()) {
                        $daily[$date] = ($daily[$date] ?? 0) + (int) ($values['total_visits'] ?? 0);
                    }
                }
            } else {
                $fallbackDate = $archiveEnd->toDateString();
                $daily[$fallbackDate] = ($daily[$fallbackDate] ?? 0) + $archive->total_visits;
            }
        }

        $rawQuery = Visitor::query()->whereBetween('created_at', [$start, $end]);
        $this->excludeWindows($rawQuery, $windows);

        $rawQuery
            ->selectRaw('DATE(created_at) as visit_date, COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'visit_date')
            ->each(function ($total, $date) use (&$daily) {
                $daily[$date] = ($daily[$date] ?? 0) + (int) $total;
            });

        ksort($daily);

        return $daily;
    }

    public function totalVisitsBetween(mixed $from, mixed $to): int
    {
        return array_sum($this->dailyVisits($from, $to));
    }

    public function totalVisitsAllTime(): int
    {
        $archives = VisitorArchive::query()->where('status', 'completed')->get();
        $rawQuery = Visitor::query();
        $windows = $archives->map(fn (VisitorArchive $archive) => [
            CarbonImmutable::parse($archive->period_start)->startOfDay(),
            CarbonImmutable::parse($archive->period_end)->endOfDay(),
        ])->all();
        $this->excludeWindows($rawQuery, $windows);

        return (int) $archives->sum('total_visits') + $rawQuery->count();
    }

    private function excludeWindows(Builder $query, array $windows): void
    {
        foreach ($windows as [$start, $end]) {
            $query->whereNotBetween('created_at', [$start, $end]);
        }
    }
}
