<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\HcdTask;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HcdTaskController extends Controller
{
    /**
     * Display Task Monitoring board & register.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $priorityFilter = $request->query('priority', 'all');
        $employeeFilter = $request->query('employee_id', 'all');
        $projectFilter = $request->query('project_id', 'all');
        $search = $request->query('search');

        $query = HcdTask::with(['assignee.user', 'project', 'department']);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($priorityFilter !== 'all') {
            $query->where('priority', $priorityFilter);
        }

        if ($employeeFilter !== 'all') {
            $query->where('assigned_to', $employeeFilter);
        }

        if ($projectFilter !== 'all') {
            $query->where('project_id', $projectFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('task_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $allTasks = HcdTask::all();
        $counts = [
            'all' => $allTasks->count(),
            'pending' => $allTasks->where('status', HcdTask::STATUS_PENDING)->count(),
            'in_progress' => $allTasks->where('status', HcdTask::STATUS_IN_PROGRESS)->count(),
            'review' => $allTasks->where('status', HcdTask::STATUS_REVIEW)->count(),
            'completed' => $allTasks->where('status', HcdTask::STATUS_COMPLETED)->count(),
            'blocked' => $allTasks->where('status', HcdTask::STATUS_BLOCKED)->count(),
        ];

        $tasks = $query->orderBy('due_date')->paginate(15)->withQueryString();
        $employees = Employee::with('user')->orderBy('employee_number')->get();
        $projects = Project::orderBy('title')->get();
        $departments = Department::orderBy('name')->get();

        $nextTaskCode = 'TSK-' . date('Y') . '-' . str_pad((string) (HcdTask::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('pages.hcd.tasks.index', compact(
            'tasks',
            'counts',
            'statusFilter',
            'priorityFilter',
            'employeeFilter',
            'projectFilter',
            'search',
            'employees',
            'projects',
            'departments',
            'nextTaskCode'
        ));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:employees,id',
            'project_id' => 'nullable|exists:projects,id',
            'department_id' => 'nullable|exists:departments,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        $taskCount = HcdTask::count() + 1;
        $taskCode = 'TSK-' . date('Y') . '-' . str_pad((string) $taskCount, 3, '0', STR_PAD_LEFT);

        HcdTask::create([
            'task_code' => $taskCode,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'assigned_to' => $validated['assigned_to'],
            'project_id' => $validated['project_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'priority' => $validated['priority'],
            'status' => HcdTask::STATUS_PENDING,
            'due_date' => $validated['due_date'] ?? null,
            'estimated_hours' => $validated['estimated_hours'] ?? 0,
            'actual_hours' => 0,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('dashboard-admin.hcd.tasks.index')
            ->with('success', "Tugas {$taskCode} berhasil ditambahkan dan ditugaskan.");
    }

    /**
     * Update task status (AJAX or Form).
     */
    public function updateStatus(Request $request, HcdTask $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,review,completed,blocked',
            'actual_hours' => 'nullable|numeric|min:0',
        ]);

        $task->status = $validated['status'];
        if (isset($validated['actual_hours'])) {
            $task->actual_hours = $validated['actual_hours'];
        }

        if ($validated['status'] === HcdTask::STATUS_COMPLETED && !$task->completed_at) {
            $task->completed_at = Carbon::now();
        } elseif ($validated['status'] !== HcdTask::STATUS_COMPLETED) {
            $task->completed_at = null;
        }

        $task->save();

        return redirect()->back()
            ->with('success', "Status tugas {$task->task_code} berhasil diperbarui.");
    }

    /**
     * Remove the specified task.
     */
    public function destroy(HcdTask $task): RedirectResponse
    {
        $code = $task->task_code;
        $task->delete();

        return redirect()->route('dashboard-admin.hcd.tasks.index')
            ->with('success', "Tugas {$code} telah dihapus.");
    }
}
