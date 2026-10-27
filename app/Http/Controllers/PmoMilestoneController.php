<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMilestone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PmoMilestoneController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::accessibleBy()->orderBy('title')->get();

        $query = ProjectMilestone::with(['project.customer', 'project.projectManager.user'])
            ->whereHas('project', function ($q) {
                $q->accessibleBy();
            });

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->integer('project_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('milestone_code', 'like', "%{$search}%")
                    ->orWhere('deliverable', 'like', "%{$search}%");
            });
        }

        $milestones = $query->orderBy('target_date')->paginate(12)->withQueryString();

        // Stats
        $allMilestones = ProjectMilestone::whereHas('project', fn ($q) => $q->accessibleBy())->get();
        $totalMilestones = $allMilestones->count();
        $completedMilestones = $allMilestones->where('status', ProjectMilestone::STATUS_COMPLETED)->count();
        $inProgressMilestones = $allMilestones->where('status', ProjectMilestone::STATUS_IN_PROGRESS)->count();
        $overdueMilestones = $allMilestones->where('status', ProjectMilestone::STATUS_OVERDUE)->count();
        $pendingMilestones = $allMilestones->where('status', ProjectMilestone::STATUS_PENDING)->count();

        return view('pages.pmo.milestones.index', compact(
            'milestones',
            'projects',
            'totalMilestones',
            'completedMilestones',
            'inProgressMilestones',
            'overdueMilestones',
            'pendingMilestones'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'milestone_code' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'planned_start_date' => 'nullable|date',
            'target_date' => 'required|date',
            'actual_date' => 'nullable|date',
            'weight_percentage' => 'nullable|numeric|between:0,100',
            'progress_percentage' => 'nullable|integer|between:0,100',
            'status' => 'required|in:pending,in_progress,completed,overdue',
            'deliverable' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['milestone_code'])) {
            $count = ProjectMilestone::where('project_id', $validated['project_id'])->count() + 1;
            $validated['milestone_code'] = 'WBS-'.str_pad((string) $count, 2, '0', STR_PAD_LEFT);
        }

        $validated['weight_percentage'] = $validated['weight_percentage'] ?? 0;
        $validated['progress_percentage'] = $validated['progress_percentage'] ?? 0;

        ProjectMilestone::create($validated);

        return redirect()->back()->with('success', 'Milestone & WBS berhasil ditambahkan.');
    }

    public function update(Request $request, ProjectMilestone $milestone): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'milestone_code' => 'nullable|string|max:50',
            'planned_start_date' => 'nullable|date',
            'target_date' => 'required|date',
            'actual_date' => 'nullable|date',
            'weight_percentage' => 'nullable|numeric|between:0,100',
            'progress_percentage' => 'nullable|integer|between:0,100',
            'status' => 'required|in:pending,in_progress,completed,overdue',
            'deliverable' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $milestone->update($validated);

        return redirect()->back()->with('success', 'Milestone berhasil diperbarui.');
    }

    public function destroy(ProjectMilestone $milestone): RedirectResponse
    {
        $milestone->delete();

        return redirect()->back()->with('success', 'Milestone berhasil dihapus.');
    }
}
