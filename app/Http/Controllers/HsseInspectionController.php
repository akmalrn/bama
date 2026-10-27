<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HsseInspection;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HsseInspectionController extends Controller
{
    /**
     * Display equipment and PPE inspection log.
     */
    public function index(Request $request): View
    {
        $resultFilter = $request->query('result', 'all');
        $categoryFilter = $request->query('category', 'all');
        $tagFilter = $request->query('tag_color', 'all');
        $projectFilter = $request->query('project_id', 'all');
        $search = $request->query('search');

        $query = HsseInspection::with(['project', 'inspector.user']);

        if ($resultFilter !== 'all') {
            $query->where('result', $resultFilter);
        }

        if ($categoryFilter !== 'all') {
            $query->where('item_category', $categoryFilter);
        }

        if ($tagFilter !== 'all') {
            $query->where('tag_color', $tagFilter);
        }

        if ($projectFilter !== 'all') {
            $query->where('project_id', $projectFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('inspection_number', 'like', "%{$search}%")
                    ->orWhere('item_name', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $allInspections = HsseInspection::all();
        $counts = [
            'all' => $allInspections->count(),
            'passed' => $allInspections->where('result', HsseInspection::RESULT_PASSED)->count(),
            'passed_notes' => $allInspections->where('result', HsseInspection::RESULT_PASSED_WITH_NOTES)->count(),
            'quarantined' => $allInspections->where('result', HsseInspection::RESULT_FAILED_QUARANTINED)->count(),
        ];

        $inspections = $query->orderByDesc('inspection_date')->paginate(15)->withQueryString();
        $projects = Project::orderBy('title')->get();
        $inspectors = Employee::with('user')->orderBy('employee_number')->get();

        $nextInspectionNumber = 'INSP-' . date('Y') . '-' . str_pad((string) (HsseInspection::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('pages.hsse.inspections.index', compact(
            'inspections',
            'counts',
            'resultFilter',
            'categoryFilter',
            'tagFilter',
            'projectFilter',
            'search',
            'projects',
            'inspectors',
            'nextInspectionNumber'
        ));
    }

    /**
     * Store a new inspection record.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'inspection_number' => ['required', 'string', 'max:50', 'unique:hsse_inspections,inspection_number'],
            'item_name' => ['required', 'string', 'max:255'],
            'item_category' => ['required', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'location' => ['required', 'string', 'max:255'],
            'inspector_id' => ['nullable', 'exists:employees,id'],
            'inspection_date' => ['required', 'date'],
            'next_inspection_date' => ['nullable', 'date', 'after_or_equal:inspection_date'],
            'result' => ['required', 'in:passed,passed_with_notes,failed_quarantined'],
            'checklist_findings' => ['nullable', 'string'],
            'corrective_action' => ['nullable', 'string'],
            'tag_color' => ['required', 'in:Green,Yellow,Red'],
        ]);

        HsseInspection::create($validated);

        return redirect()->route('dashboard-admin.hsse.inspections.index')
            ->with('success', "Data inspeksi {$validated['inspection_number']} ({$validated['item_name']}) berhasil dicatat.");
    }

    /**
     * Delete an inspection record.
     */
    public function destroy(HsseInspection $inspection): RedirectResponse
    {
        $number = $inspection->inspection_number;
        $inspection->delete();

        return redirect()->route('dashboard-admin.hsse.inspections.index')
            ->with('success', "Inspeksi {$number} berhasil dihapus.");
    }
}
