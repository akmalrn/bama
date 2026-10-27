<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCostEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class FinanceProjectCostController extends Controller
{
    /**
     * Display the Finance Project Cost Management & Budgeting.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'projects'); // projects, categories, journal
        $projectId = $request->query('project_id');
        $category = $request->query('category');
        $paymentStatus = $request->query('payment_status');
        $search = $request->query('search');
        $dateStart = $request->query('date_start');
        $dateEnd = $request->query('date_end');

        // All active projects for dropdown
        $projects = Project::with(['customer', 'costEntries'])->orderBy('title')->get();

        // 1. KPI Top Calculations
        $totalApprovedBudget = (float) $projects->sum('budget');
        $totalActualCost = (float) ProjectCostEntry::where('type', ProjectCostEntry::TYPE_ACTUAL)->sum('amount');
        if ($totalActualCost <= 0) {
            $totalActualCost = (float) $projects->sum('actual_cost');
        }

        $totalContractValue = (float) $projects->sum('contract_value');
        $portfolioGrossProfit = max(0, $totalContractValue - $totalActualCost);
        $portfolioMarginPct = $totalContractValue > 0 ? round(($portfolioGrossProfit / $totalContractValue) * 100, 1) : 0;

        $unpaidVendorBills = (float) ProjectCostEntry::where('payment_status', 'unpaid')->sum('amount');
        $unpaidBillsCount = ProjectCostEntry::where('payment_status', 'unpaid')->count();

        // 2. Project Cost Analysis Summary
        $projectAnalysis = $projects->map(function (Project $p) {
            $contractVal = (float) $p->contract_value;
            $budget = (float) $p->budget;
            $actual = (float) $p->costEntries()->where('type', ProjectCostEntry::TYPE_ACTUAL)->sum('amount');
            if ($actual <= 0) {
                $actual = (float) $p->actual_cost;
            }

            $committed = (float) $p->committed_cost;
            $variance = $budget - ($actual + $committed);
            $grossProfit = $contractVal - $actual;
            $marginPct = $contractVal > 0 ? round(($grossProfit / $contractVal) * 100, 1) : 0;
            $budgetBurnRate = $budget > 0 ? round(($actual / $budget) * 100, 1) : 0;

            return [
                'project' => $p,
                'contract_value' => $contractVal,
                'budget' => $budget,
                'actual' => $actual,
                'committed' => $committed,
                'variance' => $variance,
                'gross_profit' => $grossProfit,
                'margin_pct' => $marginPct,
                'burn_rate' => $budgetBurnRate,
            ];
        });

        if ($projectId) {
            $projectAnalysis = $projectAnalysis->where('project.id', (int) $projectId);
        }

        // 3. 10 Cost Categories Breakdown
        $categoriesList = ProjectCostEntry::CATEGORIES;
        $categoryBreakdown = [];
        $weights = [
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

        foreach ($categoriesList as $cat) {
            $catBudget = $totalApprovedBudget * ($weights[$cat] ?? 0.10);
            $catActual = (float) ProjectCostEntry::where('cost_category', $cat)
                ->where('type', ProjectCostEntry::TYPE_ACTUAL)
                ->sum('amount');

            if ($catActual == 0 && $totalActualCost > 0) {
                $catActual = round($totalActualCost * ($weights[$cat] ?? 0.10), 2);
            }

            $catVariance = $catBudget - $catActual;
            $catPct = $catBudget > 0 ? round(($catActual / $catBudget) * 100, 1) : 0;

            $categoryBreakdown[] = [
                'name' => $cat,
                'budget' => $catBudget,
                'actual' => $catActual,
                'variance' => $catVariance,
                'burn_pct' => $catPct,
            ];
        }

        // 4. Cost Transactions / Journal Query
        $journalQuery = ProjectCostEntry::with(['project.customer'])->latest('entry_date');

        if ($projectId) {
            $journalQuery->where('project_id', $projectId);
        }

        if ($category) {
            $journalQuery->where('cost_category', $category);
        }

        if ($paymentStatus) {
            $journalQuery->where('payment_status', $paymentStatus);
        }

        if ($dateStart) {
            $journalQuery->whereDate('entry_date', '>=', $dateStart);
        }

        if ($dateEnd) {
            $journalQuery->whereDate('entry_date', '<=', $dateEnd);
        }

        if ($search) {
            $journalQuery->where(function ($q) use ($search) {
                $q->where('vendor', 'like', "%{$search}%")
                    ->orWhere('reference_no', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhere('cost_category', 'like', "%{$search}%")
                    ->orWhereHas('project', function ($pq) use ($search) {
                        $pq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $transactions = $journalQuery->paginate(15)->withQueryString();

        return view('pages.finance.cost.index', compact(
            'projects',
            'projectAnalysis',
            'categoryBreakdown',
            'transactions',
            'totalApprovedBudget',
            'totalActualCost',
            'portfolioGrossProfit',
            'portfolioMarginPct',
            'unpaidVendorBills',
            'unpaidBillsCount',
            'activeTab',
            'projectId',
            'category',
            'paymentStatus',
            'search',
            'dateStart',
            'dateEnd',
            'categoriesList'
        ));
    }

    /**
     * Store a newly created cost entry or vendor bill.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'entry_date' => ['required', 'date'],
            'cost_category' => ['required', 'string'],
            'cost_subcategory' => ['nullable', 'string', 'max:100'],
            'type' => ['required', 'in:actual,commitment'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_status' => ['required', 'in:paid,unpaid'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'pph_amount' => ['nullable', 'numeric', 'min:0'],
            'vendor' => ['nullable', 'string', 'max:150'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,zip,doc,docx,xls,xlsx', 'max:10240'],
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = 'cost_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/costs');
            if (! File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $fileName);
            $attachmentPath = 'uploads/costs/' . $fileName;
        }

        DB::transaction(function () use ($validated, $attachmentPath) {
            $entry = ProjectCostEntry::create([
                'project_id' => $validated['project_id'],
                'entry_date' => $validated['entry_date'],
                'cost_category' => $validated['cost_category'],
                'cost_subcategory' => $validated['cost_subcategory'] ?? null,
                'type' => $validated['type'],
                'amount' => (float) $validated['amount'],
                'payment_status' => $validated['payment_status'],
                'tax_amount' => (float) ($validated['tax_amount'] ?? 0),
                'pph_amount' => (float) ($validated['pph_amount'] ?? 0),
                'vendor' => $validated['vendor'] ?? null,
                'reference_no' => $validated['reference_no'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'attachment_path' => $attachmentPath,
            ]);

            // Sync actual_cost on Project
            $this->syncProjectCost((int) $validated['project_id']);
        });

        return redirect()->route('dashboard-admin.finance.cost.index', ['tab' => 'journal'])
            ->with('success', 'Biaya pengeluaran proyek berhasil dicatat.');
    }

    /**
     * Update cost entry.
     */
    public function update(Request $request, ProjectCostEntry $cost): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'entry_date' => ['required', 'date'],
            'cost_category' => ['required', 'string'],
            'cost_subcategory' => ['nullable', 'string', 'max:100'],
            'type' => ['required', 'in:actual,commitment'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_status' => ['required', 'in:paid,unpaid'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'pph_amount' => ['nullable', 'numeric', 'min:0'],
            'vendor' => ['nullable', 'string', 'max:150'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,zip,doc,docx,xls,xlsx', 'max:10240'],
        ]);

        $attachmentPath = $cost->attachment_path;
        if ($request->hasFile('attachment')) {
            if ($attachmentPath && File::exists(public_path($attachmentPath))) {
                File::delete(public_path($attachmentPath));
            }
            $file = $request->file('attachment');
            $fileName = 'cost_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/costs');
            if (! File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $fileName);
            $attachmentPath = 'uploads/costs/' . $fileName;
        }

        DB::transaction(function () use ($cost, $validated, $attachmentPath) {
            $oldProjectId = $cost->project_id;

            $cost->update([
                'project_id' => $validated['project_id'],
                'entry_date' => $validated['entry_date'],
                'cost_category' => $validated['cost_category'],
                'cost_subcategory' => $validated['cost_subcategory'] ?? null,
                'type' => $validated['type'],
                'amount' => (float) $validated['amount'],
                'payment_status' => $validated['payment_status'],
                'tax_amount' => (float) ($validated['tax_amount'] ?? 0),
                'pph_amount' => (float) ($validated['pph_amount'] ?? 0),
                'vendor' => $validated['vendor'] ?? null,
                'reference_no' => $validated['reference_no'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'attachment_path' => $attachmentPath,
            ]);

            $this->syncProjectCost((int) $cost->project_id);
            if ($oldProjectId != $cost->project_id) {
                $this->syncProjectCost((int) $oldProjectId);
            }
        });

        return redirect()->route('dashboard-admin.finance.cost.index', ['tab' => 'journal'])
            ->with('success', 'Biaya pengeluaran proyek berhasil diperbarui.');
    }

    /**
     * Mark unpaid vendor bill as paid.
     */
    public function markAsPaid(ProjectCostEntry $cost): RedirectResponse
    {
        $cost->update(['payment_status' => 'paid']);

        return redirect()->route('dashboard-admin.finance.cost.index', ['tab' => 'journal'])
            ->with('success', "Biaya ref {$cost->reference_no} telah ditandai lunas (Paid).");
    }

    /**
     * Remove cost entry.
     */
    public function destroy(ProjectCostEntry $cost): RedirectResponse
    {
        $projectId = $cost->project_id;

        if ($cost->attachment_path && File::exists(public_path($cost->attachment_path))) {
            File::delete(public_path($cost->attachment_path));
        }

        $cost->delete();
        $this->syncProjectCost((int) $projectId);

        return redirect()->route('dashboard-admin.finance.cost.index', ['tab' => 'journal'])
            ->with('success', 'Biaya pengeluaran proyek berhasil dihapus.');
    }

    /**
     * Helper to recalculate actual cost on project.
     */
    private function syncProjectCost(int $projectId): void
    {
        $project = Project::find($projectId);
        if ($project) {
            $totalActual = (float) ProjectCostEntry::where('project_id', $projectId)
                ->where('type', ProjectCostEntry::TYPE_ACTUAL)
                ->sum('amount');

            $project->actual_cost = $totalActual;
            $project->save();
        }
    }
}
