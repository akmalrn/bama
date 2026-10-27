<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PmoDocumentController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'category' => 'required|string|max:100',
            'uploaded_date' => 'required|date',
            'file' => 'required|file|max:5120', // max 5MB
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $fileName = time().'_'.preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$extension;

        $targetDir = public_path('uploads/pmo_documents');
        if (! file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file->move($targetDir, $fileName);
        $filePath = 'uploads/pmo_documents/'.$fileName;

        $fileSizeFormatted = round($file->getSize() / 1024, 0).' KB';
        if ($file->getSize() >= 1048576) {
            $fileSizeFormatted = round($file->getSize() / 1048576, 1).' MB';
        }

        ProjectDocument::create([
            'project_id' => $project->id,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'file_path' => $filePath,
            'uploaded_date' => $validated['uploaded_date'],
            'file_size' => $fileSizeFormatted,
        ]);

        return redirect()->back()->with('success', 'Dokumen project berhasil diunggah.');
    }

    public function destroy(ProjectDocument $document): RedirectResponse
    {
        if ($document->file_path && file_exists(public_path($document->file_path))) {
            @unlink(public_path($document->file_path));
        }

        $document->delete();

        return redirect()->back()->with('success', 'Dokumen project berhasil dihapus.');
    }
}
