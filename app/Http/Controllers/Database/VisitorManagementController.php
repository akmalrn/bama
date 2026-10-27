<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateVisitorSettingsRequest;
use App\Models\Database\VisitorArchive;
use App\Models\Database\VisitorSetting;
use App\Services\Visitor\VisitorArchiveService;
use App\Services\Visitor\VisitorCleanupService;
use App\Services\Visitor\VisitorPeriodService;
use App\Services\Visitor\VisitorStatisticsService;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class VisitorManagementController extends Controller
{
    public function history(Request $request, VisitorStatisticsService $statistics): View
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'interval' => ['nullable', Rule::in(['daily', 'weekly', 'monthly', 'custom'])],
            'search' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', Rule::in(['period_start', 'period_end', 'total_visits', 'unique_visitors', 'total_pages_viewed', 'archived_at'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
        ]);

        $query = VisitorArchive::query();

        $query->when($validated['from'] ?? null, fn ($query, $from) => $query->whereDate('period_end', '>=', $from));
        $query->when($validated['to'] ?? null, fn ($query, $to) => $query->whereDate('period_start', '<=', $to));
        $query->when($validated['interval'] ?? null, fn ($query, $interval) => $query->where('archive_interval', $interval));
        $query->when($validated['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('period_start', 'like', "%{$search}%")
                    ->orWhere('period_end', 'like', "%{$search}%")
                    ->orWhere('archive_interval', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        });

        $sort = $validated['sort'] ?? 'period_start';
        $direction = $validated['direction'] ?? 'desc';
        $archives = $query->orderBy($sort, $direction)->paginate(15)->withQueryString();

        $now = CarbonImmutable::now(config('app.timezone'));
        $lastWeekStart = $now->startOfWeek()->subWeek();
        $lastWeekEnd = $lastWeekStart->endOfWeek();
        $previousWeekStart = $lastWeekStart->subWeek();
        $previousWeekEnd = $previousWeekStart->endOfWeek();
        $lastWeek = $statistics->totalVisitsBetween($lastWeekStart, $lastWeekEnd);
        $previousWeek = $statistics->totalVisitsBetween($previousWeekStart, $previousWeekEnd);
        $change = $previousWeek > 0
            ? (($lastWeek - $previousWeek) / $previousWeek) * 100
            : ($lastWeek > 0 ? 100 : 0);
        $monthTotal = $statistics->totalVisitsBetween($now->startOfMonth(), $now->endOfDay());

        $summary = [
            'last_week' => $lastWeek,
            'previous_week' => $previousWeek,
            'change' => $change,
            'month_total' => $monthTotal,
            'daily_average' => $monthTotal / max(1, $now->day),
        ];

        return view('pages.visitors.history', compact('archives', 'summary'));
    }

    public function settings(VisitorCleanupService $cleanupService): View
    {
        $settings = VisitorSetting::current();
        $cleanupPreview = $cleanupService->preview($settings);
        $timezones = timezone_identifiers_list();

        return view('pages.visitors.settings', compact('settings', 'cleanupPreview', 'timezones'));
    }

    public function update(UpdateVisitorSettingsRequest $request): RedirectResponse
    {
        $settings = VisitorSetting::query()->updateOrCreate(
            ['singleton_key' => 1],
            $request->validated()
        );

        Log::info('Visitor settings updated.', [
            'user_id' => $request->user()->id,
            'settings_id' => $settings->id,
        ]);

        return back()->with('success', 'Pengaturan visitor berhasil disimpan.');
    }

    public function archiveNow(
        Request $request,
        VisitorPeriodService $periodService,
        VisitorArchiveService $archiveService,
    ): RedirectResponse {
        try {
            $settings = VisitorSetting::current();
            $period = $periodService->completedPeriod($settings->archive_interval, timezone: $settings->timezone);
            $result = $archiveService->archive($period['from'], $period['to'], $period['interval']);

            Log::info('Manual visitor archive requested.', [
                'user_id' => $request->user()->id,
                'status' => $result['status'],
            ]);

            $message = $result['status'] === 'already_archived'
                ? 'Periode tersebut sudah pernah diarsipkan; tidak ada data yang diduplikasi.'
                : "Arsip berhasil dibuat untuk {$result['stats']['total_visits']} kunjungan.";

            return back()->with('success', $message);
        } catch (Throwable $exception) {
            Log::error('Manual visitor archive failed.', [
                'user_id' => $request->user()->id,
                'exception' => $exception,
            ]);

            return back()->with('error', 'Arsip gagal dijalankan: '.$exception->getMessage());
        }
    }

    public function previewCleanup(Request $request, VisitorCleanupService $cleanupService): RedirectResponse
    {
        $preview = $cleanupService->preview(VisitorSetting::current());

        Log::info('Manual visitor cleanup preview requested.', [
            'user_id' => $request->user()->id,
            'candidate_count' => $preview['candidate_count'],
        ]);

        return back()->with('cleanup_preview', $this->serializablePreview($preview));
    }

    public function cleanupNow(Request $request, VisitorCleanupService $cleanupService): RedirectResponse
    {
        $request->validate([
            'confirmation' => ['required', 'in:DELETE_VISITORS'],
        ]);

        try {
            $result = $cleanupService->cleanup(VisitorSetting::current());

            Log::warning('Manual visitor cleanup completed.', [
                'user_id' => $request->user()->id,
                'deleted_count' => $result['deleted_count'],
            ]);

            return back()->with('success', "Pembersihan selesai. {$result['deleted_count']} record visitor dihapus.");
        } catch (Throwable $exception) {
            Log::error('Manual visitor cleanup failed.', [
                'user_id' => $request->user()->id,
                'exception' => $exception,
            ]);

            return back()->with('error', 'Pembersihan gagal: '.$exception->getMessage());
        }
    }

    private function serializablePreview(array $preview): array
    {
        unset($preview['windows']);

        return $preview;
    }
}
