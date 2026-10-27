<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PmoTeamController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::accessibleBy()->orderBy('title')->get();
        $employees = Employee::with(['user', 'position', 'department'])->get();

        $query = ProjectMember::with(['project.customer', 'employee.user', 'employee.position', 'employee.department'])
            ->whereHas('project', function ($q) {
                $q->accessibleBy();
            });

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->integer('project_id'));
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->integer('employee_id'));
        }

        if ($request->filled('role')) {
            $query->where('role', 'like', '%'.$request->string('role')->toString().'%');
        }

        $members = $query->latest()->paginate(12)->withQueryString();

        // Summary statistics
        $allMembers = ProjectMember::whereHas('project', fn ($q) => $q->accessibleBy())->get();
        $totalAssignments = $allMembers->count();
        $uniqueEmployees = $allMembers->pluck('employee_id')->unique()->count();
        $activeProjectsWithTeam = $allMembers->pluck('project_id')->unique()->count();
        $averageRating = $allMembers->whereNotNull('rating')->avg('rating') ?: 0;

        return view('pages.pmo.teams.index', compact(
            'members',
            'projects',
            'employees',
            'totalAssignments',
            'uniqueEmployees',
            'activeProjectsWithTeam',
            'averageRating'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'employee_id' => 'required|exists:employees,id',
            'role' => 'required|string|max:150',
            'discipline' => 'nullable|string|max:150',
            'assigned_date' => 'nullable|date',
            'rating' => 'nullable|numeric|between:1,5',
            'performance_notes' => 'nullable|string',
            'status' => 'required|in:active,completed,released',
        ]);

        $exists = ProjectMember::where('project_id', $validated['project_id'])
            ->where('employee_id', $validated['employee_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Karyawan ini sudah ditugaskan pada project tersebut.');
        }

        $validated['assigned_date'] = $validated['assigned_date'] ?? now()->toDateString();

        ProjectMember::create($validated);

        return redirect()->back()->with('success', 'Personel berhasil ditugaskan ke project.');
    }

    public function update(Request $request, ProjectMember $member): RedirectResponse
    {
        $validated = $request->validate([
            'role' => 'required|string|max:150',
            'discipline' => 'nullable|string|max:150',
            'assigned_date' => 'nullable|date',
            'rating' => 'nullable|numeric|between:1,5',
            'performance_notes' => 'nullable|string',
            'status' => 'required|in:active,completed,released',
        ]);

        $member->update($validated);

        return redirect()->back()->with('success', 'Data penugasan tim berhasil diperbarui.');
    }

    public function destroy(ProjectMember $member): RedirectResponse
    {
        $member->delete();

        return redirect()->back()->with('success', 'Penugasan personel berhasil dihapus.');
    }
}
