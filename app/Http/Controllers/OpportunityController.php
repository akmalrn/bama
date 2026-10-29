<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\Opportunity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OpportunityController extends Controller
{
    /**
     * Display Kanban board & Table list of opportunities.
     */
    public function index(Request $request): View
    {
        $viewMode = $request->input('view', 'kanban'); // 'kanban' or 'table'
        $user = auth()->user();
        $selectedYear = (string) $request->input('year', '2026');

        $query = Opportunity::query()
            ->accessibleBy($user)
            ->with(['customer', 'owner'])
            ->latest('id');

        // Filter Tahun
        if ($selectedYear !== 'all') {
            $query->whereYear('created_at', (int) $selectedYear);
        }

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

        // Base accessible query for metric calculations (respecting year filter)
        $baseAccessibleQuery = Opportunity::query()->accessibleBy($user)->with(['customer', 'owner']);
        if ($selectedYear !== 'all') {
            $baseAccessibleQuery->whereYear('created_at', (int) $selectedYear);
        }
        $allAccessible = $baseAccessibleQuery->get();

        $totalActiveValue = (float) $allAccessible->whereNotIn('stage', [Opportunity::STAGE_CLOSED_WON, Opportunity::STAGE_CLOSED_LOST])->sum('estimated_value');
        $totalWonValue = (float) $allAccessible->where('stage', Opportunity::STAGE_CLOSED_WON)->sum('estimated_value');
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

        // 1. Weighted Pipeline Value calculation based on CRM stage probability
        $stageWeights = [
            Opportunity::STAGE_QUALIFICATION => 0.10,
            Opportunity::STAGE_NEED_ANALYSIS => 0.30,
            Opportunity::STAGE_TENDER_RFQ => 0.50,
            Opportunity::STAGE_PROPOSAL_SUBMITTED => 0.70,
            Opportunity::STAGE_NEGOTIATION => 0.85,
            Opportunity::STAGE_CLOSED_WON => 1.00,
            Opportunity::STAGE_CLOSED_LOST => 0.00,
        ];

        $weightedPipelineVal = (float) $allAccessible
            ->whereNotIn('stage', [Opportunity::STAGE_CLOSED_WON, Opportunity::STAGE_CLOSED_LOST])
            ->reduce(function ($carry, $opp) use ($stageWeights) {
                $weight = $stageWeights[$opp->stage] ?? 0.50;
                return $carry + (((float) $opp->estimated_value) * $weight);
            }, 0.0);

        // 2. Active Leads Count (deal in active progress)
        $activeLeadsCount = $allAccessible->whereNotIn('stage', [Opportunity::STAGE_CLOSED_WON, Opportunity::STAGE_CLOSED_LOST])->count();

        // 3. Qualified Leads Count & Qualification Rate
        $qualifiedLeadsCount = $allAccessible->whereIn('stage', [
            Opportunity::STAGE_TENDER_RFQ,
            Opportunity::STAGE_PROPOSAL_SUBMITTED,
            Opportunity::STAGE_NEGOTIATION,
            Opportunity::STAGE_CLOSED_WON,
        ])->count();

        $qualificationRate = $totalOpportunitiesCount > 0
            ? round(($qualifiedLeadsCount / $totalOpportunitiesCount) * 100, 1)
            : 0.0;

        // 4. Follow-up Completion from Database
        $followUpQuery = FollowUp::whereHas('opportunity', function ($q) use ($user, $selectedYear) {
            $q->accessibleBy($user);
            if ($selectedYear !== 'all') {
                $q->whereYear('created_at', (int) $selectedYear);
            }
        });
        $totalFollowUps = (clone $followUpQuery)->count();
        $completedFollowUps = (clone $followUpQuery)->where('is_completed', true)->count();
        $followUpCompletion = $totalFollowUps > 0
            ? (int) round(($completedFollowUps / $totalFollowUps) * 100)
            : 0;

        // 5. Customer Lead Sources / Industries (computed from database)
        $industryGroups = [
            'Chemical & Fertilizer' => ['color' => '#ea580c', 'count' => 0],
            'Mining & Smelting' => ['color' => '#f97316', 'count' => 0],
            'Oil & Gas / Energy' => ['color' => '#f59e0b', 'count' => 0],
            'Industrial & Others' => ['color' => '#0ea5e9', 'count' => 0],
        ];

        foreach ($allAccessible as $opp) {
            $ind = strtolower($opp->customer?->industry ?? '');
            if (str_contains($ind, 'fertilizer') || str_contains($ind, 'chemical') || str_contains($ind, 'petrochemical')) {
                $industryGroups['Chemical & Fertilizer']['count']++;
            } elseif (str_contains($ind, 'mining') || str_contains($ind, 'nickel') || str_contains($ind, 'smelting') || str_contains($ind, 'metals')) {
                $industryGroups['Mining & Smelting']['count']++;
            } elseif (str_contains($ind, 'oil') || str_contains($ind, 'gas') || str_contains($ind, 'energy')) {
                $industryGroups['Oil & Gas / Energy']['count']++;
            } else {
                $industryGroups['Industrial & Others']['count']++;
            }
        }

        $leadSources = [];
        foreach ($industryGroups as $name => $data) {
            $pct = $totalOpportunitiesCount > 0 ? (int) round(($data['count'] / $totalOpportunitiesCount) * 100) : 0;
            $leadSources[] = [
                'name' => $name,
                'count' => $data['count'],
                'pct' => $pct,
                'color' => $data['color'],
            ];
        }

        // 6. Sales Lead Status Breakdown (computed from stages in database)
        $statusGroups = [
            'Qualification' => ['stages' => [Opportunity::STAGE_QUALIFICATION], 'color' => '#0ea5e9'],
            'Need Analysis & Tender' => ['stages' => [Opportunity::STAGE_NEED_ANALYSIS, Opportunity::STAGE_TENDER_RFQ], 'color' => '#ea580c'],
            'Proposal & Negot.' => ['stages' => [Opportunity::STAGE_PROPOSAL_SUBMITTED, Opportunity::STAGE_NEGOTIATION], 'color' => '#f59e0b'],
            'Closed Won (Deal)' => ['stages' => [Opportunity::STAGE_CLOSED_WON], 'color' => '#16a34a'],
        ];

        $leadStatuses = [];
        foreach ($statusGroups as $label => $cfg) {
            $c = $allAccessible->whereIn('stage', $cfg['stages'])->count();
            $pct = $totalOpportunitiesCount > 0 ? (int) round(($c / $totalOpportunitiesCount) * 100) : 0;
            $leadStatuses[] = [
                'name' => $label,
                'count' => $c,
                'pct' => $pct,
                'color' => $cfg['color'],
            ];
        }

        // 7. Regional Project Coverage (computed from customer locations in database)
        $regionCounts = [
            'Kalimantan Timur' => ['color' => '#ea580c', 'count' => 0],
            'Jawa Timur' => ['color' => '#f97316', 'count' => 0],
            'Sumatera' => ['color' => '#b45309', 'count' => 0],
            'Sulawesi' => ['color' => '#fb923c', 'count' => 0],
            'DKI & Others' => ['color' => '#94a3b8', 'count' => 0],
        ];

        foreach ($allAccessible as $opp) {
            $loc = strtolower($opp->customer?->location ?? '');
            if (str_contains($loc, 'kalimantan')) {
                $regionCounts['Kalimantan Timur']['count']++;
            } elseif (str_contains($loc, 'jawa timur') || str_contains($loc, 'surabaya') || str_contains($loc, 'gresik')) {
                $regionCounts['Jawa Timur']['count']++;
            } elseif (str_contains($loc, 'sumatera') || str_contains($loc, 'palembang') || str_contains($loc, 'batam')) {
                $regionCounts['Sumatera']['count']++;
            } elseif (str_contains($loc, 'sulawesi') || str_contains($loc, 'sorowako') || str_contains($loc, 'kolaka')) {
                $regionCounts['Sulawesi']['count']++;
            } else {
                $regionCounts['DKI & Others']['count']++;
            }
        }

        $maxReg = max(array_column($regionCounts, 'count')) ?: 1;
        $regionalBreakdown = [];
        foreach ($regionCounts as $reg => $data) {
            $pct = $maxReg > 0 ? (int) round(($data['count'] / $maxReg) * 100) : 0;
            $regionalBreakdown[] = [
                'region' => $reg,
                'count' => $data['count'],
                'pct' => $pct,
                'color' => $data['color'],
            ];
        }

        // 8. Dynamic Sparkline trend data
        $now = Carbon::now();
        $sparklineMonths = [];
        $sparklinePipeline = [];
        $sparklineQualRate = [];
        $sparklineQualified = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $sparklineMonths[] = $m->translatedFormat('M');

            $mActiveVal = Opportunity::query()
                ->accessibleBy($user)
                ->where('created_at', '<=', $m->endOfMonth())
                ->whereNotIn('stage', [Opportunity::STAGE_CLOSED_WON, Opportunity::STAGE_CLOSED_LOST])
                ->sum('estimated_value');

            $mQualCount = Opportunity::query()
                ->accessibleBy($user)
                ->where('created_at', '<=', $m->endOfMonth())
                ->whereIn('stage', [
                    Opportunity::STAGE_TENDER_RFQ,
                    Opportunity::STAGE_PROPOSAL_SUBMITTED,
                    Opportunity::STAGE_NEGOTIATION,
                    Opportunity::STAGE_CLOSED_WON,
                ])->count();

            $sparklinePipeline[] = round(((float) $mActiveVal * 0.65) / 1000000000, 2);
            $sparklineQualified[] = $mQualCount;
            $sparklineQualRate[] = $mQualCount > 0 ? 80 : 40;
        }

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
            'selectedYear',
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
            'regionalBreakdown',
            'sparklineMonths',
            'sparklinePipeline',
            'sparklineQualRate',
            'sparklineQualified'
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
     * Store a newly created project lead / opportunity in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->has('estimated_value')) {
            $rawVal = (string) $request->input('estimated_value');
            $cleanVal = preg_replace('/[^0-9]/', '', $rawVal);
            $request->merge(['estimated_value' => $cleanVal !== '' ? (float) $cleanVal : 0]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'customer' => ['nullable', 'string', 'max:255'],
            'customer_id' => ['nullable'],
            'service_line' => ['required', 'string', 'max:150'],
            'project_location' => ['nullable', 'string', 'max:255'],
            'lead_source' => ['nullable', 'string', 'max:100'],
            'estimated_value' => ['nullable', 'numeric', 'min:0'],
            'owner_id' => ['required', 'exists:users,id'],
            'next_follow_up_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            // Compatibility with legacy fields
            'stage' => ['nullable', 'in:'.implode(',', Opportunity::STAGES)],
            'next_step' => ['nullable', 'string', 'max:500'],
            'target_close_date' => ['nullable', 'date'],
        ]);

        // Resolve Customer: existing ID or company_name
        $customerId = null;
        if (!empty($validated['customer_id']) && is_numeric($validated['customer_id'])) {
            $customerId = (int) $validated['customer_id'];
        } elseif (!empty($request->input('customer'))) {
            $customerName = trim((string) $request->input('customer'));
            $existing = Customer::where('company_name', $customerName)->first();
            if ($existing) {
                $customerId = $existing->id;
            } else {
                $created = Customer::create([
                    'company_name' => $customerName,
                    'pic_name' => 'PIC '.$customerName,
                    'industry' => 'Industrial & Energy',
                    'location' => $validated['project_location'] ?? 'Indonesia',
                ]);
                $customerId = $created->id;
            }
        }

        if (!$customerId) {
            $fallback = Customer::first();
            $customerId = $fallback ? $fallback->id : Customer::create([
                'company_name' => 'General Client',
                'pic_name' => 'PIC Client',
            ])->id;
        }

        $opportunity = Opportunity::create([
            'customer_id' => $customerId,
            'owner_id' => $validated['owner_id'],
            'title' => $validated['title'],
            'service_line' => $validated['service_line'] ?? 'UHP Cleaning',
            'project_location' => $validated['project_location'] ?? null,
            'lead_source' => $validated['lead_source'] ?? 'Existing Customer',
            'estimated_value' => (float) ($validated['estimated_value'] ?? 0),
            'stage' => $validated['stage'] ?? Opportunity::STAGE_QUALIFICATION,
            'next_step' => $validated['next_step'] ?? ($validated['notes'] ?? null),
            'notes' => $validated['notes'] ?? null,
            'next_follow_up_date' => $validated['next_follow_up_date'] ?? null,
            'target_close_date' => $validated['target_close_date'] ?? ($validated['next_follow_up_date'] ?? null),
        ]);

        // Register initial follow-up task if date provided
        if (!empty($validated['next_follow_up_date'])) {
            FollowUp::create([
                'opportunity_id' => $opportunity->id,
                'assigned_to' => $validated['owner_id'],
                'task_title' => !empty($validated['notes']) ? \Illuminate\Support\Str::limit($validated['notes'], 100) : ('Follow-up: '.$opportunity->title),
                'due_date' => $validated['next_follow_up_date'],
                'is_completed' => false,
            ]);
        }

        return redirect()
            ->route('dashboard-admin.sales.opportunities.index')
            ->with('success', 'Project Lead baru berhasil ditambahkan ke pipeline.');
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

        if ($request->has('estimated_value')) {
            $rawVal = (string) $request->input('estimated_value');
            $cleanVal = preg_replace('/[^0-9]/', '', $rawVal);
            $request->merge(['estimated_value' => $cleanVal !== '' ? (float) $cleanVal : 0]);
        }

        $validated = $request->validate([
            'customer_id' => ['nullable'],
            'customer' => ['nullable', 'string', 'max:255'],
            'owner_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'service_line' => ['nullable', 'string', 'max:150'],
            'project_location' => ['nullable', 'string', 'max:255'],
            'lead_source' => ['nullable', 'string', 'max:100'],
            'estimated_value' => ['nullable', 'numeric', 'min:0'],
            'stage' => ['required', 'in:'.implode(',', Opportunity::STAGES)],
            'next_step' => ['nullable', 'string', 'max:500'],
            'target_close_date' => ['nullable', 'date'],
            'next_follow_up_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $customerId = $opportunity->customer_id;
        if (!empty($validated['customer_id']) && is_numeric($validated['customer_id'])) {
            $customerId = (int) $validated['customer_id'];
        } elseif (!empty($request->input('customer'))) {
            $customerName = trim((string) $request->input('customer'));
            $existing = Customer::where('company_name', $customerName)->first();
            if ($existing) {
                $customerId = $existing->id;
            } else {
                $created = Customer::create([
                    'company_name' => $customerName,
                    'pic_name' => 'PIC '.$customerName,
                    'industry' => 'Industrial & Energy',
                    'location' => $validated['project_location'] ?? 'Indonesia',
                ]);
                $customerId = $created->id;
            }
        }

        $opportunity->update([
            'customer_id' => $customerId,
            'owner_id' => $validated['owner_id'],
            'title' => $validated['title'],
            'service_line' => $validated['service_line'] ?? $opportunity->service_line,
            'project_location' => $validated['project_location'] ?? $opportunity->project_location,
            'lead_source' => $validated['lead_source'] ?? $opportunity->lead_source,
            'estimated_value' => (float) ($validated['estimated_value'] ?? $opportunity->estimated_value),
            'stage' => $validated['stage'] ?? $opportunity->stage,
            'next_step' => $validated['next_step'] ?? ($validated['notes'] ?? $opportunity->next_step),
            'target_close_date' => $validated['target_close_date'] ?? $opportunity->target_close_date,
            'next_follow_up_date' => $validated['next_follow_up_date'] ?? $opportunity->next_follow_up_date,
            'notes' => $validated['notes'] ?? $opportunity->notes,
        ]);

        if (!empty($validated['next_follow_up_date']) && $validated['next_follow_up_date'] !== $opportunity->getOriginal('next_follow_up_date')) {
            FollowUp::create([
                'opportunity_id' => $opportunity->id,
                'assigned_to' => $validated['owner_id'],
                'task_title' => !empty($validated['notes']) ? \Illuminate\Support\Str::limit($validated['notes'], 100) : ('Follow-up: '.$opportunity->title),
                'due_date' => $validated['next_follow_up_date'],
                'is_completed' => false,
            ]);
        }

        return redirect()
            ->route('dashboard-admin.sales.opportunities.index')
            ->with('success', 'Data Project Lead berhasil diperbarui.');
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
     * Export Sales Pipeline Overview to Excel (.csv) or printable PDF view.
     */
    public function export(Request $request): StreamedResponse|View
    {
        $user = auth()->user();
        $selectedYear = (string) $request->input('year', '2026');
        $type = (string) $request->input('type', 'excel');

        $baseAccessibleQuery = Opportunity::accessibleBy($user);
        if ($selectedYear !== 'all') {
            $baseAccessibleQuery->whereYear('created_at', (int) $selectedYear);
        }

        $allOpportunities = (clone $baseAccessibleQuery)
            ->with(['customer', 'owner'])
            ->latest('id')
            ->get();

        $activeStages = [
            Opportunity::STAGE_QUALIFICATION,
            Opportunity::STAGE_NEED_ANALYSIS,
            Opportunity::STAGE_TENDER_RFQ,
            Opportunity::STAGE_PROPOSAL_SUBMITTED,
            Opportunity::STAGE_NEGOTIATION,
        ];

        $stageWeights = [
            Opportunity::STAGE_QUALIFICATION => 0.10,
            Opportunity::STAGE_NEED_ANALYSIS => 0.30,
            Opportunity::STAGE_TENDER_RFQ => 0.50,
            Opportunity::STAGE_PROPOSAL_SUBMITTED => 0.70,
            Opportunity::STAGE_NEGOTIATION => 0.85,
            Opportunity::STAGE_CLOSED_WON => 1.00,
            Opportunity::STAGE_CLOSED_LOST => 0.00,
        ];

        $stageLabels = [
            Opportunity::STAGE_QUALIFICATION => 'Qualification',
            Opportunity::STAGE_NEED_ANALYSIS => 'Need Analysis',
            Opportunity::STAGE_TENDER_RFQ => 'Tender / RFQ',
            Opportunity::STAGE_PROPOSAL_SUBMITTED => 'Proposal Submitted',
            Opportunity::STAGE_NEGOTIATION => 'Negotiation',
            Opportunity::STAGE_CLOSED_WON => 'Closed Won',
            Opportunity::STAGE_CLOSED_LOST => 'Closed Lost',
        ];

        $weightedPipelineVal = 0;
        $totalActiveValue = 0;
        $activeLeadsCount = 0;
        $qualifiedLeadsCount = 0;
        $totalOpportunitiesCount = $allOpportunities->count();

        foreach ($allOpportunities as $opp) {
            $val = (float) ($opp->estimated_value ?? 0);
            $stage = (string) $opp->stage;
            $weight = $stageWeights[$stage] ?? 0.20;

            if (in_array($stage, $activeStages, true)) {
                $weightedPipelineVal += ($val * $weight);
                $totalActiveValue += $val;
                $activeLeadsCount++;
            }

            if (in_array($stage, [
                Opportunity::STAGE_TENDER_RFQ,
                Opportunity::STAGE_PROPOSAL_SUBMITTED,
                Opportunity::STAGE_NEGOTIATION,
                Opportunity::STAGE_CLOSED_WON,
            ], true)) {
                $qualifiedLeadsCount++;
            }
        }

        $qualificationRate = $totalOpportunitiesCount > 0
            ? round(($qualifiedLeadsCount / $totalOpportunitiesCount) * 100, 1)
            : 0;

        // Follow-up Completion
        $totalFollowUps = FollowUp::whereHas('opportunity', function ($q) use ($user) {
            $q->accessibleBy($user);
        })->count();

        $completedFollowUps = FollowUp::whereHas('opportunity', function ($q) use ($user) {
            $q->accessibleBy($user);
        })->where('is_completed', true)->count();

        $followUpCompletion = $totalFollowUps > 0
            ? round(($completedFollowUps / $totalFollowUps) * 100)
            : 0;

        $periodTitle = $selectedYear === 'all' ? 'All Time' : 'Tahun ' . $selectedYear;
        $now = Carbon::now();

        if ($type === 'excel') {
            $filename = 'Laporan-Sales-Pipeline-' . ($selectedYear === 'all' ? 'All' : $selectedYear) . '-' . date('Ymd_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            return response()->streamDownload(function () use (
                $periodTitle,
                $now,
                $user,
                $weightedPipelineVal,
                $totalActiveValue,
                $activeLeadsCount,
                $totalOpportunitiesCount,
                $qualificationRate,
                $qualifiedLeadsCount,
                $followUpCompletion,
                $completedFollowUps,
                $totalFollowUps,
                $stageLabels,
                $stageWeights,
                $allOpportunities
            ) {
                $handle = fopen('php://output', 'w');
                // UTF-8 BOM
                fwrite($handle, "\xEF\xBB\xBF");

                // Header Metadata
                fputcsv($handle, ['LAPORAN SALES PIPELINE & OPPORTUNITY - PT ELBEMA INTEGRA INDONESIA']);
                fputcsv($handle, ['Filter Periode', $periodTitle]);
                fputcsv($handle, ['Waktu Export', $now->translatedFormat('d F Y, H:i:s') . ' WIB']);
                fputcsv($handle, ['Diekspor Oleh', ($user->name ?? 'User') . ' (' . ($user->email ?? '-') . ')']);
                fputcsv($handle, []);

                // KPI Overview
                fputcsv($handle, ['=== RINGKASAN PIPELINE & METRIK UTAMA ===']);
                fputcsv($handle, ['Metrik', 'Nilai / Persentase', 'Keterangan']);
                fputcsv($handle, ['Weighted Pipeline Value', 'Rp ' . number_format($weightedPipelineVal, 0, ',', '.'), 'Estimasi bobot probabilitas (dari total aktif Rp ' . number_format($totalActiveValue, 0, ',', '.') . ')']);
                fputcsv($handle, ['Active Project Leads', $activeLeadsCount . ' leads', 'Peluang aktif dalam proses pipeline']);
                fputcsv($handle, ['Total Opportunities', $totalOpportunitiesCount . ' deals', 'Total data opportunity periode ini']);
                fputcsv($handle, ['Qualification Rate', $qualificationRate . '%', $qualifiedLeadsCount . ' dari ' . $totalOpportunitiesCount . ' ter-kualifikasi']);
                fputcsv($handle, ['Qualified Leads', $qualifiedLeadsCount . ' deals', 'Tahapan Tender / Proposal / Negosiasi / Won']);
                fputcsv($handle, ['Follow-up Completion', $followUpCompletion . '%', $completedFollowUps . ' selesai dari ' . $totalFollowUps . ' agenda tindak lanjut']);
                fputcsv($handle, []);

                // Daftar Opportunities
                fputcsv($handle, ['=== DAFTAR DETAIL PIPELINE OPPORTUNITY ===']);
                fputcsv($handle, [
                    'No',
                    'Judul Proyek / Paket',
                    'Customer / Perusahaan',
                    'Sales Owner',
                    'Tahapan (Stage)',
                    'Estimasi Nilai (IDR)',
                    'Probabilitas (%)',
                    'Weighted Value (IDR)',
                    'Target Selesai Deal',
                    'Tanggal Dibuat',
                ]);

                $no = 1;
                foreach ($allOpportunities as $opp) {
                    $val = (float) ($opp->estimated_value ?? 0);
                    $prob = $opp->probability ?? (($stageWeights[$opp->stage] ?? 0.2) * 100);
                    $weighted = $val * ($prob / 100);

                    fputcsv($handle, [
                        $no++,
                        $opp->title,
                        $opp->customer?->company_name ?? ($opp->customer?->name ?? '-'),
                        $opp->owner?->name ?? '-',
                        $stageLabels[$opp->stage] ?? ucfirst((string) $opp->stage),
                        number_format($val, 0, ',', '.'),
                        $prob . '%',
                        number_format($weighted, 0, ',', '.'),
                        $opp->target_deal_date ? Carbon::parse($opp->target_deal_date)->format('d/m/Y') : '-',
                        $opp->created_at ? Carbon::parse($opp->created_at)->format('d/m/Y') : '-',
                    ]);
                }

                fclose($handle);
            }, $filename, $headers);
        }

        // Return PDF View
        return view('pages.sales.opportunities.export-pdf', compact(
            'periodTitle',
            'selectedYear',
            'now',
            'user',
            'weightedPipelineVal',
            'totalActiveValue',
            'activeLeadsCount',
            'totalOpportunitiesCount',
            'qualificationRate',
            'qualifiedLeadsCount',
            'followUpCompletion',
            'completedFollowUps',
            'totalFollowUps',
            'stageLabels',
            'stageWeights',
            'allOpportunities'
        ));
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
