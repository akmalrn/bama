<?php

namespace App\Services\Visitor;

use App\Models\Database\Visitor;
use App\Models\Database\VisitorArchive;
use App\Models\Database\VisitorSetting;
use Carbon\CarbonImmutable;
use Generator;

class VisitorPeriodService
{
    public function completedPeriod(string $interval, ?CarbonImmutable $now = null, ?string $timezone = null): array
    {
        $timezone ??= config('app.timezone', 'Asia/Jakarta');
        $now = ($now ?? CarbonImmutable::now($timezone))->setTimezone($timezone);

        return match ($interval) {
            'daily' => [
                'from' => $now->subDay()->startOfDay(),
                'to' => $now->subDay()->endOfDay(),
                'interval' => 'daily',
            ],
            'monthly' => [
                'from' => $now->startOfMonth()->subMonth()->startOfMonth(),
                'to' => $now->startOfMonth()->subMonth()->endOfMonth(),
                'interval' => 'monthly',
            ],
            default => [
                'from' => $now->startOfWeek(CarbonImmutable::MONDAY)->subWeek()->startOfDay(),
                'to' => $now->startOfWeek(CarbonImmutable::MONDAY)->subDay()->endOfDay(),
                'interval' => 'weekly',
            ],
        };
    }

    public function isDue(VisitorSetting $settings, ?CarbonImmutable $now = null): bool
    {
        if (! $settings->archive_enabled && ! $settings->auto_delete_enabled) {
            return false;
        }

        $now = ($now ?? CarbonImmutable::now($settings->timezone))->setTimezone($settings->timezone);
        [$hour, $minute] = array_map('intval', explode(':', substr((string) $settings->archive_time, 0, 5)));
        $scheduled = $now->setTime($hour, $minute);

        if ($now->lt($scheduled)) {
            return false;
        }

        $dateMatches = match ($settings->archive_interval) {
            'weekly' => $now->dayOfWeekIso === $settings->archive_day,
            'monthly' => $now->day === min($settings->archive_month_day, $now->daysInMonth),
            default => true,
        };

        if (! $dateMatches) {
            return false;
        }

        $period = $this->completedPeriod($settings->archive_interval, $now, $settings->timezone);

        $archivePending = $settings->archive_enabled && ! VisitorArchive::query()
            ->whereDate('period_start', $period['from']->toDateString())
            ->whereDate('period_end', $period['to']->toDateString())
            ->exists();

        $lastCleanup = $settings->last_cleanup_at
            ? CarbonImmutable::parse($settings->last_cleanup_at)->setTimezone($settings->timezone)
            : null;
        $cleanupPending = $settings->auto_delete_enabled
            && (! $lastCleanup || ! $lastCleanup->isSameDay($now));

        return $archivePending || $cleanupPending;
    }

    public function pendingCompletedPeriods(VisitorSetting $settings, ?CarbonImmutable $now = null): Generator
    {
        $now = ($now ?? CarbonImmutable::now($settings->timezone))->setTimezone($settings->timezone);
        $lastPeriod = $this->completedPeriod($settings->archive_interval, $now, $settings->timezone);
        $oldestDate = Visitor::query()->min('created_at');

        if (! $oldestDate) {
            yield $lastPeriod;

            return;
        }

        $cursor = CarbonImmutable::parse($oldestDate, $settings->timezone);
        $cursor = match ($settings->archive_interval) {
            'monthly' => $cursor->startOfMonth(),
            'weekly' => $cursor->startOfWeek(CarbonImmutable::MONDAY),
            default => $cursor->startOfDay(),
        };

        while ($cursor->lte($lastPeriod['from'])) {
            $end = match ($settings->archive_interval) {
                'monthly' => $cursor->endOfMonth(),
                'weekly' => $cursor->addDays(6)->endOfDay(),
                default => $cursor->endOfDay(),
            };

            if ($end->lte($lastPeriod['to'])) {
                yield [
                    'from' => $cursor->startOfDay(),
                    'to' => $end,
                    'interval' => $settings->archive_interval,
                ];
            }

            $cursor = match ($settings->archive_interval) {
                'monthly' => $cursor->addMonth()->startOfMonth(),
                'weekly' => $cursor->addWeek()->startOfWeek(CarbonImmutable::MONDAY),
                default => $cursor->addDay()->startOfDay(),
            };
        }
    }
}
