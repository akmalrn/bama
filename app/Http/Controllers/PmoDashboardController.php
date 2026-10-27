<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PmoDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::with(['customer', 'projectManager.user'])
            ->accessibleBy()
            ->latest()
            ->get();

        $totalCount = $projects->count();
        $totalValue = (float) $projects->sum('contract_value');
        $totalBudget = (float) $projects->sum('budget');
        $totalActualCost = (float) $projects->sum('actual_cost');
        $totalManHours = (int) $projects->sum('man_hours');

        // Status counts and values
        $ongoingProjects = $projects->where('status', Project::STATUS_ON_PROGRESS);
        $completedProjects = $projects->where('status', Project::STATUS_COMPLETED);
        $planningProjects = $projects->where('status', Project::STATUS_PLANNING);
        $otherProjects = $projects->whereNotIn('status', [Project::STATUS_ON_PROGRESS, Project::STATUS_COMPLETED]);

        $ongoingCount = $ongoingProjects->count();
        $ongoingValue = (float) $ongoingProjects->sum('contract_value');

        $completedCount = $completedProjects->count();
        $completedValue = (float) $completedProjects->sum('contract_value');

        $closedCount = $otherProjects->count();
        $closedValue = (float) $otherProjects->sum('contract_value');

        // Invoicing & Cash collection
        $invoiceSubmitted = (float) $projects->sum('invoice_submitted');
        $invoicePaid = (float) $projects->sum('invoice_paid');
        $outstanding = max(0.0, $invoiceSubmitted - $invoicePaid);
        $collectionRate = $invoiceSubmitted > 0 ? round(($invoicePaid / $invoiceSubmitted) * 100, 1) : 0;

        // Breakdowns helper
        $breakdownBy = function (string $column) use ($projects, $totalValue) {
            $grouped = $projects->groupBy(fn ($p) => $p->{$column} ?: 'Lainnya');
            $result = [];
            foreach ($grouped as $label => $items) {
                $count = $items->count();
                $val = (float) $items->sum('contract_value');
                $pct = $totalValue > 0 ? round(($val / $totalValue) * 100, 1) : 0;
                $result[] = [
                    'label' => $label,
                    'count' => $count,
                    'value' => $val,
                    'percentage' => $pct,
                ];
            }
            usort($result, fn ($a, $b) => $b['value'] <=> $a['value']);

            return $result;
        };

        $sectorBreakdown = $breakdownBy('sector');
        $categoryBreakdown = $breakdownBy('category');
        $locationBreakdown = $breakdownBy('location');
        $procurementBreakdown = $breakdownBy('procurement_type');

        // EVM Buckets
        $cpiBelow = $projects->filter(fn ($p) => $p->cpi < 0.995);
        $cpiEqual = $projects->filter(fn ($p) => $p->cpi >= 0.995 && $p->cpi <= 1.005);
        $cpiAbove = $projects->filter(fn ($p) => $p->cpi > 1.005);

        $spiBelow = $projects->filter(fn ($p) => $p->spi < 0.995);
        $spiEqual = $projects->filter(fn ($p) => $p->spi >= 0.995 && $p->spi <= 1.005);
        $spiAbove = $projects->filter(fn ($p) => $p->spi > 1.005);

        $cpiBuckets = [
            ['label' => '< 1', 'class' => 'below', 'count' => $cpiBelow->count(), 'value' => (float) $cpiBelow->sum('contract_value'), 'sub' => 'Over budget / Cost overrun'],
            ['label' => '= 1', 'class' => '', 'count' => $cpiEqual->count(), 'value' => (float) $cpiEqual->sum('contract_value'), 'sub' => 'On budget'],
            ['label' => '> 1', 'class' => 'above', 'count' => $cpiAbove->count(), 'value' => (float) $cpiAbove->sum('contract_value'), 'sub' => 'Cost efficient / Under budget'],
        ];

        $spiBuckets = [
            ['label' => '< 1', 'class' => 'below', 'count' => $spiBelow->count(), 'value' => (float) $spiBelow->sum('contract_value'), 'sub' => 'Behind schedule'],
            ['label' => '= 1', 'class' => '', 'count' => $spiEqual->count(), 'value' => (float) $spiEqual->sum('contract_value'), 'sub' => 'On schedule'],
            ['label' => '> 1', 'class' => 'above', 'count' => $spiAbove->count(), 'value' => (float) $spiAbove->sum('contract_value'), 'sub' => 'Ahead of schedule'],
        ];

        // S-Curve Trend Data (Monthly grouping)
        $trendMonths = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthNum = str_pad((string) $m, 2, '0', STR_PAD_LEFT);
            $monthName = date('M', mktime(0, 0, 0, $m, 10));
            $ongoingInMonth = $projects->filter(function ($p) use ($m) {
                $startM = $p->start_date ? (int) $p->start_date->format('n') : 1;
                $endM = $p->end_date ? (int) $p->end_date->format('n') : 12;

                return $m >= $startM && $m <= $endM && $p->status === Project::STATUS_ON_PROGRESS;
            })->count();

            $completedInMonth = $projects->filter(function ($p) use ($m) {
                $endM = $p->end_date ? (int) $p->end_date->format('n') : 12;

                return $m >= $endM && $p->status === Project::STATUS_COMPLETED;
            })->count();

            $trendMonths[] = [
                'name' => $monthName,
                'ongoing' => $ongoingInMonth,
                'completed' => $completedInMonth,
                'total' => $ongoingInMonth + $completedInMonth,
            ];
        }

        return view('pages.pmo.dashboard', compact(
            'projects',
            'totalCount',
            'totalValue',
            'totalBudget',
            'totalActualCost',
            'totalManHours',
            'ongoingCount',
            'ongoingValue',
            'completedCount',
            'completedValue',
            'closedCount',
            'closedValue',
            'invoiceSubmitted',
            'invoicePaid',
            'outstanding',
            'collectionRate',
            'sectorBreakdown',
            'categoryBreakdown',
            'locationBreakdown',
            'procurementBreakdown',
            'cpiBuckets',
            'spiBuckets',
            'trendMonths'
        ));
    }
}
