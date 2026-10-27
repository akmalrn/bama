<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Opportunity;
use App\Models\Proposal;
use App\Models\SalesActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesDashboardController extends Controller
{
    /**
     * Display the Sales CRM Dashboard.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $period = $request->query('period', 'tahun_ini');

        // 1. Date Range Handling
        $now = Carbon::now();
        switch ($period) {
            case 'bulan_ini':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $periodLabel = 'Bulan Ini ('.$now->translatedFormat('F Y').')';
                break;
            case 'kuartal_ini':
                $startDate = $now->copy()->startOfQuarter();
                $endDate = $now->copy()->endOfQuarter();
                $periodLabel = 'Kuartal Ini (Q'.$now->quarter.' '.$now->year.')';
                break;
            case 'tahun_ini':
            default:
                $period = 'tahun_ini';
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $periodLabel = 'Tahun Ini ('.$now->year.')';
                break;
        }

        $activeStages = [
            Opportunity::STAGE_QUALIFICATION,
            Opportunity::STAGE_NEED_ANALYSIS,
            Opportunity::STAGE_TENDER_RFQ,
            Opportunity::STAGE_PROPOSAL_SUBMITTED,
            Opportunity::STAGE_NEGOTIATION,
        ];

        // 2. KPI Cards Calculations (Using scopeAccessibleBy)
        $accessibleOppsQuery = Opportunity::accessibleBy($user);

        // A. Pipeline Aktif (all active stages)
        $activePipelineQuery = (clone $accessibleOppsQuery)
            ->whereIn('stage', $activeStages);

        $activePipelineValue = (float) $activePipelineQuery->sum('estimated_value');
        $activePipelineCount = $activePipelineQuery->count();

        // B. Proposal Terkirim (in selected period)
        $proposalsQuery = Proposal::whereHas('opportunity', function ($q) use ($user) {
            $q->accessibleBy($user);
        })->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('submitted_at', [$startDate, $endDate])
                ->orWhere(function ($sub) use ($startDate, $endDate) {
                    $sub->whereNull('submitted_at')
                        ->whereBetween('created_at', [$startDate, $endDate]);
                });
        });

        $proposalsCount = (clone $proposalsQuery)->count();
        $proposalsValue = (float) (clone $proposalsQuery)->sum('amount');

        // C. Closed Won (in selected period)
        $closedWonQuery = (clone $accessibleOppsQuery)
            ->where('stage', Opportunity::STAGE_CLOSED_WON)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('updated_at', [$startDate, $endDate])
                    ->orWhereBetween('created_at', [$startDate, $endDate]);
            });

        $closedWonValue = (float) (clone $closedWonQuery)->sum('estimated_value');
        $closedWonCount = (clone $closedWonQuery)->count();

        // D. Closed Lost & Win Rate Calculation
        $closedLostQuery = (clone $accessibleOppsQuery)
            ->where('stage', Opportunity::STAGE_CLOSED_LOST)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('updated_at', [$startDate, $endDate])
                    ->orWhereBetween('created_at', [$startDate, $endDate]);
            });

        $closedLostCount = (clone $closedLostQuery)->count();
        $totalDecidedDeals = $closedWonCount + $closedLostCount;
        $winRate = $totalDecidedDeals > 0
            ? round(($closedWonCount / $totalDecidedDeals) * 100, 1)
            : ($closedWonCount > 0 ? 100.0 : 0.0);

        // 3. Sales Funnel Grouping
        $stageLabels = [
            Opportunity::STAGE_QUALIFICATION => [
                'name' => 'Qualification',
                'color' => '#6366f1',
                'badge' => 'badge-stage-qualification',
                'icon' => 'solar:target-bold-duotone',
            ],
            Opportunity::STAGE_NEED_ANALYSIS => [
                'name' => 'Need Analysis',
                'color' => '#0ea5e9',
                'badge' => 'badge-stage-need_analysis',
                'icon' => 'solar:magnifer-zoom-in-bold-duotone',
            ],
            Opportunity::STAGE_TENDER_RFQ => [
                'name' => 'Tender / RFQ',
                'color' => '#eab308',
                'badge' => 'badge-stage-tender_rfq',
                'icon' => 'solar:document-add-bold-duotone',
            ],
            Opportunity::STAGE_PROPOSAL_SUBMITTED => [
                'name' => 'Proposal Submitted',
                'color' => '#f97316',
                'badge' => 'badge-stage-proposal_submitted',
                'icon' => 'solar:plain-bold-duotone',
            ],
            Opportunity::STAGE_NEGOTIATION => [
                'name' => 'Negotiation',
                'color' => '#8b5cf6',
                'badge' => 'badge-stage-negotiation',
                'icon' => 'solar:handshake-bold-duotone',
            ],
            Opportunity::STAGE_CLOSED_WON => [
                'name' => 'Closed Won',
                'color' => '#22c55e',
                'badge' => 'badge-stage-closed_won',
                'icon' => 'solar:cup-star-bold-duotone',
            ],
            Opportunity::STAGE_CLOSED_LOST => [
                'name' => 'Closed Lost',
                'color' => '#ef4444',
                'badge' => 'badge-stage-closed_lost',
                'icon' => 'solar:close-circle-bold-duotone',
            ],
        ];

        $stageGrouped = (clone $accessibleOppsQuery)
            ->selectRaw('stage, COUNT(*) as count, SUM(estimated_value) as total_val')
            ->groupBy('stage')
            ->get()
            ->keyBy('stage');

        $totalAllOpps = (clone $accessibleOppsQuery)->count();
        $totalAllValue = (float) (clone $accessibleOppsQuery)->sum('estimated_value');

        $salesFunnel = [];
        foreach ($stageLabels as $stageKey => $meta) {
            $count = (int) ($stageGrouped[$stageKey]->count ?? 0);
            $val = (float) ($stageGrouped[$stageKey]->total_val ?? 0);
            $percentage = $totalAllOpps > 0 ? round(($count / $totalAllOpps) * 100, 1) : 0;

            $salesFunnel[$stageKey] = [
                'stage' => $stageKey,
                'name' => $meta['name'],
                'color' => $meta['color'],
                'badge' => $meta['badge'],
                'icon' => $meta['icon'],
                'count' => $count,
                'value' => $val,
                'percentage' => $percentage,
            ];
        }

        // 4. Priority Active Opportunities (Top 10 highest estimated value)
        $priorityOpportunities = (clone $accessibleOppsQuery)
            ->whereIn('stage', $activeStages)
            ->with(['customer', 'owner'])
            ->orderByDesc('estimated_value')
            ->take(10)
            ->get();

        // 5. Recent Activities & Upcoming Follow-ups
        $recentActivities = SalesActivity::whereHas('opportunity', function ($q) use ($user) {
            $q->accessibleBy($user);
        })
            ->with(['opportunity.customer', 'user'])
            ->latest('activity_at')
            ->take(5)
            ->get();

        $upcomingFollowUps = FollowUp::whereHas('opportunity', function ($q) use ($user) {
            $q->accessibleBy($user);
        })
            ->where('is_completed', false)
            ->with(['opportunity.customer', 'assignee'])
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // 6. Monthly Performance Chart Data (apr - okt)
        $chartCategories = ['Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'];
        $chartProposals = [1.0, 1.2, 1.1, 1.5, 1.4, 1.8, 1.6];
        $chartClosedWon = [0.7, 0.8, 0.7, 1.1, 0.9, 1.3, 1.2];

        return view('pages.sales.dashboard', compact(
            'period',
            'periodLabel',
            'activePipelineValue',
            'activePipelineCount',
            'proposalsCount',
            'proposalsValue',
            'closedWonValue',
            'closedWonCount',
            'winRate',
            'closedLostCount',
            'salesFunnel',
            'totalAllOpps',
            'totalAllValue',
            'priorityOpportunities',
            'recentActivities',
            'upcomingFollowUps',
            'stageLabels',
            'chartCategories',
            'chartProposals',
            'chartClosedWon'
        ));
    }
}
