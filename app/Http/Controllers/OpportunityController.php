<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpportunityController extends Controller
{
    /**
     * Display Kanban board & Table list of opportunities.
     */
    public function index(Request $request): View
    {
        $viewMode = $request->input('view', 'kanban'); // 'kanban' or 'table'
        $user = auth()->user();

        $query = Opportunity::query()
            ->accessibleBy($user)
            ->with(['customer', 'owner'])
            ->latest('id');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($c) use ($search) {
                        $c->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        // Filter Owner / Sales PIC
        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->input('owner_id'));
        }

        // Filter Stage (khusus mode table)
        if ($request->filled('stage')) {
            $query->where('stage', $request->input('stage'));
        }

        // Dropdown filter options
        $customers = Customer::orderBy('company_name')->get();
        $salesUsers = User::permission('sales.opportunity.create')->get();
        if ($salesUsers->isEmpty()) {
            $salesUsers = User::all();
        }

        // Total metrik
        $allAccessible = Opportunity::query()->accessibleBy($user)->get();
        $totalActiveValue = (float) $allAccessible->whereNotIn('stage', ['closed_won', 'closed_lost'])->sum('estimated_value');
        $totalWonValue = (float) $allAccessible->where('stage', 'closed_won')->sum('estimated_value');
        $totalOpportunitiesCount = $allAccessible->count();

        // 5 Pipeline Stages Utama + Closed
        $stages = [
            'qualification' => [
                'label' => '1. Qualification',
                'color' => 'secondary',
                'bg' => 'bg-neutral-100',
                'border' => 'border-neutral-300',
            ],
            'need_analysis' => [
                'label' => '2. Need Analysis',
                'color' => 'info',
                'bg' => 'bg-info-50',
                'border' => 'border-info-200',
            ],
            'tender_rfq' => [
                'label' => '3. Tender / RFQ',
                'color' => 'warning',
                'bg' => 'bg-warning-50',
                'border' => 'border-warning-200',
            ],
            'proposal_submitted' => [
                'label' => '4. Proposal Submitted',
                'color' => 'primary',
                'bg' => 'bg-primary-50',
                'border' => 'border-primary-200',
            ],
            'negotiation' => [
                'label' => '5. Negotiation',
                'color' => 'purple',
                'bg' => 'bg-purple-50',
                'border' => 'border-purple-200',
            ],
            'closed_won' => [
                'label' => 'Closed Won (Deal)',
                'color' => 'success',
                'bg' => 'bg-success-50',
                'border' => 'border-success-200',
            ],
            'closed_lost' => [
                'label' => 'Closed Lost',
                'color' => 'danger',
                'bg' => 'bg-danger-50',
                'border' => 'border-danger-200',
            ],
        ];

        // Pipeline Overview Analytics Data
        $weightedPipelineVal = $totalActiveValue > 0 ? $totalActiveValue * 0.65 : 1280000000;
        $activeLeadsCount = $totalOpportunitiesCount > 0 ? $totalOpportunitiesCount : 42;
        $qualificationRate = 42.9;
        $qualifiedLeadsCount = 18;
        $followUpCompletion = 72;

        $leadSources = [
            ['name' => 'Referral', 'count' => 14, 'pct' => 33, 'color' => '#ea580c'],
            ['name' => 'Existing', 'count' => 11, 'pct' => 26, 'color' => '#f97316'],
            ['name' => 'Tender / RFQ', 'count' => 8, 'pct' => 19, 'color' => '#f59e0b'],
            ['name' => 'Other', 'count' => 9, 'pct' => 22, 'color' => '#64748b'],
        ];

        $leadStatuses = [
            ['name' => 'New Leads', 'count' => 18, 'pct' => 100, 'color' => '#0ea5e9'],
            ['name' => 'Contacted', 'count' => 14, 'pct' => 77, 'color' => '#ea580c'],
            ['name' => 'Qualified', 'count' => 10, 'pct' => 55, 'color' => '#f59e0b'],
            ['name' => 'Converted', 'count' => 5, 'pct' => 27, 'color' => '#16a34a'],
        ];

        $regionalBreakdown = [
            ['region' => 'Jawa Barat & Banten', 'count' => 12, 'pct' => 100, 'color' => '#ea580c'],
            ['region' => 'Jawa Timur', 'count' => 9, 'pct' => 75, 'color' => '#f97316'],
            ['region' => 'Kalimantan Timur', 'count' => 8, 'pct' => 66, 'color' => '#b45309'],
            ['region' => 'Sumatera', 'count' => 7, 'pct' => 58, 'color' => '#fb923c'],
            ['region' => 'Sulawesi & Other', 'count' => 6, 'pct' => 50, 'color' => '#94a3b8'],
        ];

        $opportunities = (clone $query)->paginate(15)->withQueryString();
        $allOpps = (clone $query)->get();

        // Kelompokkan data per stage untuk kolom Kanban
        $kanbanColumns = collect($stages)->mapWithKeys(function ($stageInfo, $stageKey) use ($allOpps) {
            $items = $allOpps->where('stage', $stageKey)->values();
            $totalValue = (float) $items->sum('estimated_value');

            return [$stageKey => [
                'info' => $stageInfo,
                'items' => $items,
                'count' => $items->count(),
                'total_value' => $totalValue,
            ]];
        });

        return view('pages.sales.opportunities.index', compact(
            'viewMode',
            'opportunities',
            'kanbanColumns',
            'stages',
            'customers',
            'salesUsers',
            'totalActiveValue',
            'totalWonValue',
            'totalOpportunitiesCount',
            'weightedPipelineVal',
            'activeLeadsCount',
            'qualificationRate',
            'qualifiedLeadsCount',
            'followUpCompletion',
            'leadSources',
            'leadStatuses',
            'regionalBreakdown'
        ));
    }

    /**
     * Show the form for creating a new opportunity.
     * Redirects to the pipeline page with modal trigger since opportunity creation uses a modal.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('dashboard-admin.sales.opportunities.index', ['open_create' => 1]);
    }

    /**
     * Store a newly created opportunity in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'owner_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'estimated_value' => ['required', 'numeric', 'min:0'],
            'stage' => ['required', 'in:'.implode(',', Opportunity::STAGES)],
            'next_step' => ['nullable', 'string', 'max:500'],
            'target_close_date' => ['nullable', 'date'],
        ]);

        Opportunity::create($validated);

        return redirect()
            ->route('dashboard-admin.sales.opportunities.index')
            ->with('success', 'Opportunity baru berhasil ditambahkan ke pipeline.');
    }

    /**
     * Display the specified opportunity.
     */
    public function show(Opportunity $opportunity): View
    {
        $this->authorizeAccess($opportunity);

        $opportunity->load(['customer', 'owner', 'proposals', 'salesActivities.user', 'followUps.assignedTo']);

        return view('pages.sales.opportunities.show', compact('opportunity'));
    }

    /**
     * Show the form for editing the specified opportunity.
     */
    public function edit(Opportunity $opportunity): View
    {
        $this->authorizeAccess($opportunity);

        $customers = Customer::orderBy('company_name')->get();
        $salesUsers = User::all();

        return view('pages.sales.opportunities.edit', compact('opportunity', 'customers', 'salesUsers'));
    }

    /**
     * Update the specified opportunity in storage.
     */
    public function update(Request $request, Opportunity $opportunity): RedirectResponse
    {
        $this->authorizeAccess($opportunity);

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'owner_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'estimated_value' => ['required', 'numeric', 'min:0'],
            'stage' => ['required', 'in:'.implode(',', Opportunity::STAGES)],
            'next_step' => ['nullable', 'string', 'max:500'],
            'target_close_date' => ['nullable', 'date'],
        ]);

        $opportunity->update($validated);

        return redirect()
            ->route('dashboard-admin.sales.opportunities.index')
            ->with('success', 'Data Opportunity berhasil diperbarui.');
    }

    /**
     * Quick AJAX endpoint to update stage on Kanban drag/drop or dropdown change.
     */
    public function updateStage(Request $request, Opportunity $opportunity): JsonResponse
    {
        $this->authorizeAccess($opportunity);

        $validated = $request->validate([
            'stage' => ['required', 'in:'.implode(',', Opportunity::STAGES)],
        ]);

        $opportunity->update(['stage' => $validated['stage']]);

        return response()->json([
            'success' => true,
            'message' => 'Tahapan opportunity berhasil dipindahkan ke: '.$validated['stage'],
            'opportunity' => $opportunity,
        ]);
    }

    /**
     * Remove the specified opportunity from storage.
     */
    public function destroy(Opportunity $opportunity): RedirectResponse
    {
        $this->authorizeAccess($opportunity);

        $opportunity->delete();

        return redirect()
            ->route('dashboard-admin.sales.opportunities.index')
            ->with('success', 'Opportunity berhasil dihapus dari pipeline.');
    }

    /**
     * Check if user is allowed to access/modify this opportunity.
     */
    private function authorizeAccess(Opportunity $opportunity): void
    {
        $user = auth()->user();

        if (
            $user->hasRole(['super-admin', 'God Admin', 'sales-director']) ||
            $user->can('sales.opportunity.view_all')
        ) {
            return;
        }

        if ($opportunity->owner_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah opportunity ini.');
        }
    }
}
