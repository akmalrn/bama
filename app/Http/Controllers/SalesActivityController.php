<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\SalesActivity;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesActivityController extends Controller
{
    /**
     * Display a listing of sales activities.
     */
    public function index(Request $request): View
    {
        $query = SalesActivity::with(['opportunity.customer', 'user'])->latest('activity_at');

        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->query('user_id'));
        }

        if ($request->filled('opportunity_id')) {
            $query->where('opportunity_id', $request->query('opportunity_id'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('opportunity', function ($oq) use ($search) {
                        $oq->where('title', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($cq) use ($search) {
                                $cq->where('company_name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('activity_at', '>=', $request->query('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('activity_at', '<=', $request->query('end_date'));
        }

        $activities = $query->paginate(15)->withQueryString();

        // Summary metrics
        $allActivities = SalesActivity::with(['opportunity.customer'])->get();
        $totalActivities = $allActivities->count();
        $customerTouchpoints = $allActivities->pluck('opportunity.customer.id')->filter()->unique()->count();
        $recentCount = $allActivities->where('activity_at', '>=', now()->subDays(7))->count();
        $todayCount = $allActivities->where('activity_at', '>=', now()->startOfDay())->count();

        // Chart / breakdown by type
        $typeCounts = [
            'outside_meeting' => $allActivities->where('type', SalesActivity::TYPE_OUTSIDE_MEETING)->count(),
            'proposal_submitted' => $allActivities->where('type', SalesActivity::TYPE_PROPOSAL_SUBMITTED)->count(),
            'online_presentation' => $allActivities->where('type', SalesActivity::TYPE_ONLINE_PRESENTATION)->count(),
            'customer_visit' => $allActivities->where('type', SalesActivity::TYPE_CUSTOMER_VISIT)->count(),
        ];

        $opportunities = Opportunity::with('customer')->orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('pages.sales.activities.index', compact(
            'activities',
            'totalActivities',
            'customerTouchpoints',
            'recentCount',
            'todayCount',
            'typeCounts',
            'opportunities',
            'users'
        ));
    }

    /**
     * Store a newly created sales activity.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'opportunity_id' => 'required|exists:opportunities,id',
            'type' => 'required|in:'.implode(',', SalesActivity::TYPES),
            'title' => 'required|string|max:255',
            'activity_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id() ?? 1;

        SalesActivity::create($validated);

        return redirect()->route('dashboard-admin.sales.activities.index')
            ->with('success', 'Aktivitas Sales berhasil dicatat.');
    }

    /**
     * Update the specified sales activity.
     */
    public function update(Request $request, SalesActivity $activity): RedirectResponse
    {
        $validated = $request->validate([
            'opportunity_id' => 'required|exists:opportunities,id',
            'type' => 'required|in:'.implode(',', SalesActivity::TYPES),
            'title' => 'required|string|max:255',
            'activity_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $activity->update($validated);

        return redirect()->route('dashboard-admin.sales.activities.index')
            ->with('success', 'Aktivitas Sales berhasil diperbarui.');
    }

    /**
     * Remove the specified sales activity.
     */
    public function destroy(SalesActivity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()->route('dashboard-admin.sales.activities.index')
            ->with('success', 'Aktivitas Sales berhasil dihapus.');
    }
}
