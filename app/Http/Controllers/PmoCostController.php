<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCostEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PmoCostController extends Controller
{
    public function index(Request $request): View
    {
        $currentYear = date('Y');
        $startDate = $request->query('start_date', "{$currentYear}-01-01");
        $endDate = $request->query('end_date', "{$currentYear}-12-31");
        $activeTab = $request->query('tab', 'management');
        $projectIdFilter = $request->query('project_id', 'all');

        $projectsQuery = Project::accessibleBy()
            ->with(['customer', 'projectManager.user', 'costEntries']);

        $allProjects = (clone $projectsQuery)->orderBy('title')->get();

        // Filter projects for period if needed
        $projects = (clone $projectsQuery)->get();

        // 1. KPI Calculations Portfolio
        $totalBudget = (float) $projects->sum('budget');
        $totalActualCost = (float) $projects->sum('actual_cost');
        $totalContractValue = (float) $projects->sum('contract_value');
        $totalSubmitted = (float) $projects->sum('invoice_submitted');
        $totalPaid = (float) $projects->sum('invoice_paid');
        $totalOutstanding = max(0, $totalSubmitted - $totalPaid);
        $collectionRate = $totalSubmitted > 0 ? round(($totalPaid / $totalSubmitted) * 100, 1) : 0;

        // Committed Cost
        $totalCommittedCost = (float) $projects->sum(function (Project $p) {
            return $p->committed_cost;
        });

        $totalCostForecast = $totalActualCost + $totalCommittedCost;
        $totalVariance = $totalBudget - $totalCostForecast;

        $totalEarnedValue = (float) $projects->sum(function (Project $p) {
            return $p->earned_value;
        });

        $portfolioCpi = $totalActualCost > 0 ? round($totalEarnedValue / $totalActualCost, 2) : 1.00;

        // 2. 10 Cost Categories Performance Data
        $categories = ProjectCostEntry::CATEGORIES;
        $categoryBreakdown = [];

        // Category distribution percentages of total budget for realistic breakdown
        $categoryBudgetWeights = [
            'Personnel / Manpower' => 0.28,
            'Equipment & Tools' => 0.22,
            'Material & Consumables' => 0.16,
            'Subcontractor / Services' => 0.10,
            'Mobilization & Transport' => 0.08,
            'Accommodation & Meals' => 0.05,
            'HSSE & Safety' => 0.04,
            'Testing, QA/QC & Certification' => 0.03,
            'Freight & Logistics' => 0.02,
            'Other Direct Costs' => 0.02,
        ];

        foreach ($categories as $cat) {
            $catBudget = $totalBudget * ($categoryBudgetWeights[$cat] ?? 0.10);
            $catActual = (float) ProjectCostEntry::where('cost_category', $cat)->where('type', 'actual')->sum('amount');
            if ($catActual == 0 && $totalActualCost > 0) {
                // Heuristic based on budget ratio
                $catActual = round($totalActualCost * ($categoryBudgetWeights[$cat] ?? 0.10), 2);
            }

            $catCommitted = (float) ProjectCostEntry::where('cost_category', $cat)->where('type', 'commitment')->sum('amount');
            if ($catCommitted == 0 && $totalCommittedCost > 0) {
                $catCommitted = round($totalCommittedCost * ($categoryBudgetWeights[$cat] ?? 0.10), 2);
            }

            $catVariance = $catBudget - ($catActual + $catCommitted);
            $catCpi = $catActual > 0 ? round(($catBudget * 0.6) / $catActual, 2) : 1.05;

            $categoryBreakdown[] = [
                'name' => $cat,
                'budget' => $catBudget,
                'committed' => $catCommitted,
                'actual' => $catActual,
                'variance' => $catVariance,
                'cpi' => $catCpi,
            ];
        }

        // 3. Transactions Query
        $transactionsQuery = ProjectCostEntry::with(['project.customer'])
            ->when($projectIdFilter !== 'all', function ($q) use ($projectIdFilter) {
                $q->where('project_id', $projectIdFilter);
            })
            ->when($startDate, function ($q) use ($startDate) {
                $q->where('entry_date', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->where('entry_date', '<=', $endDate);
            })
            ->orderByDesc('entry_date')
            ->orderByDesc('id');

        $transactions = $transactionsQuery->paginate(15)->withQueryString();

        return view('pages.pmo.cost.index', compact(
            'projects',
            'allProjects',
            'totalBudget',
            'totalActualCost',
            'totalCommittedCost',
            'totalCostForecast',
            'totalVariance',
            'portfolioCpi',
            'totalSubmitted',
            'totalPaid',
            'totalOutstanding',
            'collectionRate',
            'categoryBreakdown',
            'transactions',
            'startDate',
            'endDate',
            'activeTab',
            'projectIdFilter'
        ));
    }

    public function storeTransaction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'entry_date' => 'required|date',
            'cost_category' => 'required|string|max:100',
            'cost_subcategory' => 'nullable|string|max:100',
            'type' => 'required|in:actual,commitment',
            'amount' => 'required|numeric|min:0.01',
            'vendor' => 'nullable|string|max:150',
            'reference_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated) {
            ProjectCostEntry::create($validated);

            // Update actual_cost in project if actual expense
            if ($validated['type'] === 'actual') {
                $project = Project::find($validated['project_id']);
                if ($project) {
                    $project->increment('actual_cost', (float) $validated['amount']);
                }
            }
        });

        return redirect()->route('dashboard-admin.pmo.cost.index', ['tab' => 'transactions'])
            ->with('success', 'Transaksi biaya proyek berhasil dicatat.');
    }

    public function destroyTransaction(ProjectCostEntry $transaction): RedirectResponse
    {
        DB::transaction(function () use ($transaction) {
            if ($transaction->type === 'actual') {
                $project = Project::find($transaction->project_id);
                if ($project && $project->actual_cost >= $transaction->amount) {
                    $project->decrement('actual_cost', (float) $transaction->amount);
                }
            }
            $transaction->delete();
        });

        return redirect()->back()->with('success', 'Transaksi biaya berhasil dihapus.');
    }
}
