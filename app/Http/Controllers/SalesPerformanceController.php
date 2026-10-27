<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Proposal;
use App\Models\SalesActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesPerformanceController extends Controller
{
    /**
     * Display the sales performance dashboard & leaderboard.
     */
    public function index(Request $request): View
    {
        $startDate = $request->query('start_date', now()->startOfYear()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());
        $ownerFilter = $request->query('owner_id');

        // Query sales users
        $usersQuery = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Sales', 'Sales Director', 'Sales Executive', 'Sales Manager', 'Super Admin', 'God Admin']);
        })->orWhereHas('opportunities');

        $users = $usersQuery->orderBy('name')->get();

        if ($users->isEmpty()) {
            $users = User::all();
        }

        // Aggregate performance stats per sales person
        $performances = $users->map(function ($user) use ($startDate, $endDate) {
            $oppQuery = Opportunity::where('owner_id', $user->id)
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);

            $totalOpportunities = (clone $oppQuery)->count();
            $totalOpportunityValue = (clone $oppQuery)->sum('estimated_value');

            $wonQuery = (clone $oppQuery)->where('stage', Opportunity::STAGE_CLOSED_WON);
            $wonCount = (clone $wonQuery)->count();
            $wonValue = (clone $wonQuery)->sum('estimated_value');

            $proposalQuery = Proposal::whereHas('opportunity', function ($q) use ($user, $startDate, $endDate) {
                $q->where('owner_id', $user->id)
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
            });
            $proposalCount = (clone $proposalQuery)->count();
            $proposalValue = (clone $proposalQuery)->sum('amount');

            $activityQuery = SalesActivity::where('user_id', $user->id)
                ->whereDate('activity_at', '>=', $startDate)
                ->whereDate('activity_at', '<=', $endDate);
            $activityCount = (clone $activityQuery)->count();
            $customerVisits = (clone $activityQuery)->where('type', SalesActivity::TYPE_CUSTOMER_VISIT)->count();

            $winRate = $totalOpportunities > 0 ? round(($wonCount / $totalOpportunities) * 100, 1) : 0;

            return [
                'user' => $user,
                'opportunities_count' => $totalOpportunities,
                'opportunities_value' => (float) $totalOpportunityValue,
                'won_count' => $wonCount,
                'won_value' => (float) $wonValue,
                'proposal_count' => $proposalCount,
                'proposal_value' => (float) $proposalValue,
                'activity_count' => $activityCount,
                'customer_visits' => $customerVisits,
                'win_rate' => $winRate,
            ];
        });

        if ($ownerFilter) {
            $performances = $performances->where('user.id', (int) $ownerFilter);
        }

        // Leaderboards
        $rankedByWonValue = $performances->sortByDesc('won_value')->values();
        $rankedByProposals = $performances->sortByDesc('proposal_count')->values();
        $rankedByActivities = $performances->sortByDesc('activity_count')->values();

        // Totals
        $grandWonValue = $performances->sum('won_value');
        $grandOppValue = $performances->sum('opportunities_value');
        $grandProposalValue = $performances->sum('proposal_value');
        $grandActivities = $performances->sum('activity_count');

        return view('pages.sales.performance.index', compact(
            'performances',
            'rankedByWonValue',
            'rankedByProposals',
            'rankedByActivities',
            'grandWonValue',
            'grandOppValue',
            'grandProposalValue',
            'grandActivities',
            'users',
            'startDate',
            'endDate',
            'ownerFilter'
        ));
    }
}
