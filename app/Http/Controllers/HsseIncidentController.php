<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HsseIncident;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HsseIncidentController extends Controller
{
    /**
     * Display incident & near-miss reports with CAPA tracking.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $typeFilter = $request->query('incident_type', 'all');
        $projectFilter = $request->query('project_id', 'all');
        $search = $request->query('search');

        $query = HsseIncident::with(['project', 'reporter.user', 'injuredPerson.user']);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($typeFilter !== 'all') {
            $query->where('incident_type', $typeFilter);
        }

        if ($projectFilter !== 'all') {
            $query->where('project_id', $projectFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('incident_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $allIncidents = HsseIncident::all();
        $counts = [
            'all' => $allIncidents->count(),
            'reported' => $allIncidents->where('status', HsseIncident::STATUS_REPORTED)->count(),
            'investigating' => $allIncidents->where('status', HsseIncident::STATUS_INVESTIGATING)->count(),
            'action_pending' => $allIncidents->where('status', HsseIncident::STATUS_ACTION_PENDING)->count(),
            'closed' => $allIncidents->where('status', HsseIncident::STATUS_CLOSED)->count(),
            'near_miss' => $allIncidents->where('incident_type', 'Near Miss')->count(),
            'lti' => $allIncidents->where('incident_type', 'Lost Time Injury (LTI)')->count(),
        ];

        $incidents = $query->orderByDesc('incident_date')->paginate(15)->withQueryString();
        $projects = Project::orderBy('title')->get();
        $employees = Employee::with('user')->orderBy('employee_number')->get();

        $nextIncidentNumber = 'INC-' . date('Y') . '-' . str_pad((string) (HsseIncident::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('pages.hsse.incidents.index', compact(
            'incidents',
            'counts',
            'statusFilter',
            'typeFilter',
            'projectFilter',
            'search',
            'projects',
            'employees',
            'nextIncidentNumber'
        ));
    }

    /**
     * Store a new incident or near miss report.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'incident_number' => ['required', 'string', 'max:50', 'unique:hsse_incidents,incident_number'],
            'title' => ['required', 'string', 'max:255'],
            'incident_type' => ['required', 'string', 'max:100'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'incident_date' => ['required', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'reporter_id' => ['nullable', 'exists:employees,id'],
            'injured_person_id' => ['nullable', 'exists:employees,id'],
            'description' => ['required', 'string'],
            'root_cause' => ['nullable', 'string'],
            'immediate_action' => ['nullable', 'string'],
            'capa_action' => ['nullable', 'string'],
            'target_date' => ['nullable', 'date'],
            'status' => ['required', 'in:reported,investigating,action_pending,closed'],
            'lost_workdays' => ['nullable', 'integer', 'min:0'],
        ]);

        HsseIncident::create($validated);

        return redirect()->route('dashboard-admin.hsse.incidents.index')
            ->with('success', "Laporan insiden {$validated['incident_number']} berhasil didaftarkan.");
    }

    /**
     * Update CAPA & resolution status.
     */
    public function updateCapa(Request $request, HsseIncident $incident): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:reported,investigating,action_pending,closed'],
            'root_cause' => ['nullable', 'string'],
            'capa_action' => ['nullable', 'string'],
            'target_date' => ['nullable', 'date'],
            'lost_workdays' => ['nullable', 'integer', 'min:0'],
        ]);

        $incident->update($validated);

        return redirect()->route('dashboard-admin.hsse.incidents.index')
            ->with('success', "Tindakan perbaikan (CAPA) insiden {$incident->incident_number} berhasil diperbarui.");
    }

    /**
     * Delete an incident report.
     */
    public function destroy(HsseIncident $incident): RedirectResponse
    {
        $number = $incident->incident_number;
        $incident->delete();

        return redirect()->route('dashboard-admin.hsse.incidents.index')
            ->with('success', "Laporan insiden {$number} berhasil dihapus.");
    }
}
