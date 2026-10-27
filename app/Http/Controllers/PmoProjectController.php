<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Opportunity;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PmoProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::with(['customer', 'projectManager.user', 'milestones'])
            ->accessibleBy();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->date('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->date('end_date'));
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        // Summary stats
        $allAccessible = Project::accessibleBy()->get();
        $totalProjects = $allAccessible->count();
        $ongoingProjects = $allAccessible->where('status', Project::STATUS_ON_PROGRESS)->count();
        $completedProjects = $allAccessible->where('status', Project::STATUS_COMPLETED)->count();
        $totalContractValue = (float) $allAccessible->sum('contract_value');
        $totalSubmittedInvoice = (float) $allAccessible->sum('invoice_submitted');
        $totalPaidInvoice = (float) $allAccessible->sum('invoice_paid');

        // Form lookups
        $customers = Customer::orderBy('company_name')->get();
        $employees = Employee::with('user')->get();
        $opportunities = Opportunity::orderBy('title')->get();

        return view('pages.pmo.projects.index', compact(
            'projects',
            'totalProjects',
            'ongoingProjects',
            'completedProjects',
            'totalContractValue',
            'totalSubmittedInvoice',
            'totalPaidInvoice',
            'customers',
            'employees',
            'opportunities'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'project_manager_id' => 'required|exists:employees,id',
            'opportunity_id' => 'nullable|exists:opportunities,id',
            'project_code' => 'nullable|string|max:50|unique:projects,project_code',
            'contract_value' => 'required|numeric|min:0',
            'budget' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:'.implode(',', Project::STATUSES),
            'progress_percentage' => 'nullable|integer|between:0,100',
            'planned_progress_percentage' => 'nullable|integer|between:0,100',
            'location' => 'nullable|string|max:150',
            'category' => 'nullable|string|max:150',
            'sector' => 'nullable|string|max:150',
            'procurement_type' => 'nullable|string|max:100',
            'risk_level' => 'required|in:'.implode(',', Project::RISKS),
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['project_code'])) {
            $year = date('Y');
            $count = Project::whereYear('created_at', $year)->count() + 1;
            $validated['project_code'] = 'PRJ-'.$year.'-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);
        }

        $validated['budget'] = $validated['budget'] ?? $validated['contract_value'] * 0.85;
        $validated['progress_percentage'] = $validated['progress_percentage'] ?? 0;
        $validated['planned_progress_percentage'] = $validated['planned_progress_percentage'] ?? 0;

        Project::create($validated);

        return redirect()->route('dashboard-admin.pmo.projects.index')
            ->with('success', 'Project baru berhasil didaftarkan.');
    }

    public function show(Project $project): View
    {
        $project->load([
            'customer',
            'projectManager.user',
            'opportunity.owner',
            'milestones' => fn ($q) => $q->orderBy('target_date'),
            'members.employee.user',
            'documents' => fn ($q) => $q->latest(),
        ]);

        $customers = Customer::orderBy('company_name')->get();
        $employees = Employee::with('user')->get();

        return view('pages.pmo.projects.show', compact('project', 'customers', 'employees'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'project_manager_id' => 'required|exists:employees,id',
            'opportunity_id' => 'nullable|exists:opportunities,id',
            'contract_value' => 'required|numeric|min:0',
            'budget' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:'.implode(',', Project::STATUSES),
            'progress_percentage' => 'nullable|integer|between:0,100',
            'planned_progress_percentage' => 'nullable|integer|between:0,100',
            'location' => 'nullable|string|max:150',
            'category' => 'nullable|string|max:150',
            'sector' => 'nullable|string|max:150',
            'procurement_type' => 'nullable|string|max:100',
            'risk_level' => 'required|in:'.implode(',', Project::RISKS),
            'readiness_percentage' => 'nullable|integer|between:0,100',
            'quality_percentage' => 'nullable|integer|between:0,100',
            'hsse_percentage' => 'nullable|integer|between:0,100',
            'man_hours' => 'nullable|integer|min:0',
            'invoice_submitted' => 'nullable|numeric|min:0',
            'invoice_paid' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->back()->with('success', 'Data project berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $code = $project->project_code;
        $project->delete();

        return redirect()->route('dashboard-admin.pmo.projects.index')
            ->with('success', "Project {$code} berhasil dihapus.");
    }
}
