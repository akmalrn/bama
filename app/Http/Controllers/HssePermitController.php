<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HssePermit;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HssePermitController extends Controller
{
    /**
     * Display Permit to Work (PTW) & JSA list.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $typeFilter = $request->query('permit_type', 'all');
        $riskFilter = $request->query('risk_level', 'all');
        $projectFilter = $request->query('project_id', 'all');
        $search = $request->query('search');

        $query = HssePermit::with(['project', 'applicant.user', 'safetyOfficer.user', 'creator']);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($typeFilter !== 'all') {
            $query->where('permit_type', $typeFilter);
        }

        if ($riskFilter !== 'all') {
            $query->where('risk_level', $riskFilter);
        }

        if ($projectFilter !== 'all') {
            $query->where('project_id', $projectFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('permit_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $allPermits = HssePermit::all();
        $counts = [
            'all' => $allPermits->count(),
            'active' => $allPermits->where('status', HssePermit::STATUS_ACTIVE)->count(),
            'approved' => $allPermits->where('status', HssePermit::STATUS_APPROVED)->count(),
            'under_review' => $allPermits->where('status', HssePermit::STATUS_UNDER_REVIEW)->count(),
            'closed' => $allPermits->where('status', HssePermit::STATUS_CLOSED)->count(),
            'draft' => $allPermits->where('status', HssePermit::STATUS_DRAFT)->count(),
        ];

        $permits = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $projects = Project::orderBy('title')->get();
        $employees = Employee::with('user')->orderBy('employee_number')->get();

        $nextPermitNumber = 'PTW-' . date('Y') . '-' . str_pad((string) (HssePermit::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('pages.hsse.permits.index', compact(
            'permits',
            'counts',
            'statusFilter',
            'typeFilter',
            'riskFilter',
            'projectFilter',
            'search',
            'projects',
            'employees',
            'nextPermitNumber'
        ));
    }

    /**
     * Store a newly created permit to work.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'permit_number' => ['required', 'string', 'max:50', 'unique:hsse_permits,permit_number'],
            'title' => ['required', 'string', 'max:255'],
            'permit_type' => ['required', 'string', 'max:100'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'location' => ['required', 'string', 'max:255'],
            'applicant_id' => ['nullable', 'exists:employees,id'],
            'safety_officer_id' => ['nullable', 'exists:employees,id'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after_or_equal:start_time'],
            'risk_level' => ['required', 'in:Low,Medium,High,Critical'],
            'jsa_content' => ['nullable', 'string'],
            'gas_test_required' => ['nullable', 'boolean'],
            'gas_test_o2' => ['nullable', 'string', 'max:20'],
            'gas_test_lel' => ['nullable', 'string', 'max:20'],
            'gas_test_co' => ['nullable', 'string', 'max:20'],
            'gas_test_h2s' => ['nullable', 'string', 'max:20'],
            'ppe_requirements' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,under_review,approved,active,closed,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['gas_test_required'] = $request->boolean('gas_test_required');
        $validated['created_by'] = Auth::id();

        HssePermit::create($validated);

        return redirect()->route('dashboard-admin.hsse.permits.index')
            ->with('success', "Izin Kerja (PTW) {$validated['permit_number']} berhasil diterbitkan.");
    }

    /**
     * Update permit status (approval/close workflow).
     */
    public function updateStatus(Request $request, HssePermit $permit): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,under_review,approved,active,closed,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        $updateData = ['status' => $validated['status']];
        if (!empty($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        $permit->update($updateData);

        return redirect()->route('dashboard-admin.hsse.permits.index')
            ->with('success', "Status izin {$permit->permit_number} berhasil diperbarui menjadi {$validated['status']}.");
    }

    /**
     * Remove the specified permit.
     */
    public function destroy(HssePermit $permit): RedirectResponse
    {
        $number = $permit->permit_number;
        $permit->delete();

        return redirect()->route('dashboard-admin.hsse.permits.index')
            ->with('success', "Izin Kerja {$number} berhasil dihapus.");
    }
}
