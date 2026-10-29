<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HcdTask;
use App\Models\Project;
use App\Models\UserTimesheet;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyWorkspaceController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $today = Carbon::today();

        // 1. Tugas Saya (Assigned Tasks)
        $myTasksQuery = HcdTask::with(['project', 'department'])
            ->where(function ($q) use ($employee, $user) {
                if ($employee) {
                    $q->where('assigned_to', $employee->id)
                      ->orWhere('created_by', $user->id);
                } else {
                    $q->where('created_by', $user->id);
                }
            });

        $totalMyTasks = (clone $myTasksQuery)->count();
        $pendingTasks = (clone $myTasksQuery)->whereIn('status', ['pending', 'in_progress', 'review'])->count();
        $completedTasks = (clone $myTasksQuery)->where('status', 'completed')->count();

        $recentTasks = (clone $myTasksQuery)->latest('due_date')->take(6)->get();

        // 2. Timesheet Minggu Ini
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();

        $weeklyTimesheets = UserTimesheet::with(['project', 'task'])
            ->where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->latest('date')
            ->get();

        $weeklyTotalHours = $weeklyTimesheets->sum('hours');
        $monthlyTotalHours = UserTimesheet::where('user_id', $user->id)
            ->whereMonth('date', $today->month)
            ->whereYear('date', $today->year)
            ->sum('hours');

        // 3. Proyek Aktif Yang Diikuti
        $activeProjects = Project::where(function ($q) use ($employee) {
            if ($employee) {
                $q->where('project_manager_id', $employee->id)
                  ->orWhere('status', 'on_progress');
            } else {
                $q->where('status', 'on_progress');
            }
        })->take(4)->get();

        $allProjects = Project::select('id', 'title')->orderBy('title')->get();

        return view('pages.workspace.index', compact(
            'user',
            'employee',
            'totalMyTasks',
            'pendingTasks',
            'completedTasks',
            'recentTasks',
            'weeklyTimesheets',
            'weeklyTotalHours',
            'monthlyTotalHours',
            'activeProjects',
            'allProjects'
        ));
    }

    public function storeTimesheet(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'hours' => ['required', 'numeric', 'min:0.5', 'max:24'],
            'work_type' => ['required', 'string', 'max:50'],
            'activity_summary' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:150'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'task_id' => ['nullable', 'exists:hcd_tasks,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = $user->id;
        $validated['employee_id'] = $employee?->id;
        $validated['status'] = 'submitted';

        UserTimesheet::create($validated);

        return redirect()->route('dashboard-admin.workspace.index')
            ->with('success', 'Catatan jam kerja / timesheet berhasil disimpan.');
    }

    public function updateTaskStatus(Request $request, HcdTask $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,review,completed'],
            'actual_hours' => ['nullable', 'numeric', 'min:0'],
        ]);

        $task->status = $validated['status'];
        if (isset($validated['actual_hours'])) {
            $task->actual_hours = $validated['actual_hours'];
        }
        if ($validated['status'] === 'completed') {
            $task->completed_at = now();
        }
        $task->save();

        return redirect()->route('dashboard-admin.workspace.index')
            ->with('success', "Status tugas #{$task->task_code} berhasil diperbarui.");
    }
}
