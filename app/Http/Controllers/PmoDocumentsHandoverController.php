<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PmoDocumentsHandoverController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('search', '');

        $projectsQuery = Project::accessibleBy()
            ->with(['customer', 'projectManager.user', 'documents'])
            ->when($statusFilter !== 'all', function ($q) use ($statusFilter) {
                $q->where('handover_status', $statusFilter);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('project_code', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($c) use ($search) {
                            $c->where('name', 'like', "%{$search}%");
                        });
                });
            });

        $projects = $projectsQuery->get();
        $allProjects = Project::accessibleBy()->orderBy('title')->get();

        // 1. KPIs
        $totalDossiers = $projects->count();
        $totalDocsCount = ProjectDocument::count();
        // Expecting an average of 5 core documents per project (Contract, BAST, Technical Drawing, QA/QC, Certificate)
        $expectedTotalDocs = max(1, $totalDossiers * 5);
        $docsCompletePercentage = min(100, (int) round(($totalDocsCount / $expectedTotalDocs) * 100));

        $totalPunchOpen = (int) $projects->sum('punch_list_open_count');
        $handoverReady = $projects->filter(function ($p) {
            return in_array(strtolower((string) $p->handover_status), ['handed over', 'ready', 'approved'], true);
        })->count();

        // 2. All Project Documents List (paginated)
        $documents = ProjectDocument::with('project.customer')
            ->orderByDesc('uploaded_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('pages.pmo.documents.handover', compact(
            'projects',
            'allProjects',
            'totalDossiers',
            'docsCompletePercentage',
            'totalPunchOpen',
            'handoverReady',
            'documents',
            'statusFilter',
            'search'
        ));
    }

    public function updateStatus(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'turnover_dossier_status' => 'required|string|max:50',
            'handover_status' => 'required|string|max:50',
            'punch_list_open_count' => 'required|integer|min:0',
            'certificates_status' => 'required|string|max:100',
        ]);

        $project->update($validated);

        return redirect()->back()->with('success', 'Status serah terima (handover dossier) proyek berhasil diperbarui.');
    }

    public function storeDocument(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:200',
            'category' => 'required|string|max:100',
            'uploaded_date' => 'required|date',
            'file' => 'required|file|mimes:pdf,docx,xlsx,zip|max:20480',
        ]);

        $file = $request->file('file');
        $path = $file->store('project_documents', 'public');
        $size = number_format($file->getSize() / 1024, 1).' KB';

        ProjectDocument::create([
            'project_id' => $validated['project_id'],
            'name' => $validated['name'],
            'category' => $validated['category'],
            'file_path' => $path,
            'uploaded_date' => $validated['uploaded_date'],
            'file_size' => $size,
        ]);

        return redirect()->back()->with('success', 'Dokumen proyek berhasil diunggah.');
    }

    public function destroyDocument(ProjectDocument $document): RedirectResponse
    {
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Dokumen proyek berhasil dihapus.');
    }
}
