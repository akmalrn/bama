<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\HcdProfessional;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HcdProfessionalController extends Controller
{
    /**
     * Display Professional Database (Talent Pool).
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $categoryFilter = $request->query('category', 'all');
        $search = $request->query('search');

        $query = HcdProfessional::with('currentProject');

        if ($statusFilter !== 'all') {
            $query->where('availability_status', $statusFilter);
        }

        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('certifications_summary', 'like', "%{$search}%");
            });
        }

        $allProfessionals = HcdProfessional::all();
        $counts = [
            'all' => $allProfessionals->count(),
            'available' => $allProfessionals->where('availability_status', HcdProfessional::AVAILABILITY_AVAILABLE)->count(),
            'mobilized' => $allProfessionals->where('availability_status', HcdProfessional::AVAILABILITY_MOBILIZED)->count(),
            'standby' => $allProfessionals->where('availability_status', HcdProfessional::AVAILABILITY_STANDBY)->count(),
            'blacklisted' => $allProfessionals->where('availability_status', HcdProfessional::AVAILABILITY_BLACKLISTED)->count(),
        ];

        $professionals = $query->orderByDesc('rating')->paginate(12)->withQueryString();
        $projects = Project::orderBy('title')->get();
        $categories = [
            'Mechanical & Piping',
            'Surface Preparation',
            'Inspection & QA/QC',
            'Machining & Turning',
            'HSSE Field',
            'Electrical & Instrumentation',
        ];

        return view('pages.hcd.professionals.index', compact(
            'professionals',
            'counts',
            'projects',
            'categories',
            'statusFilter',
            'categoryFilter',
            'search'
        ));
    }

    /**
     * Store new professional to database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'category' => 'required|string|max:60',
            'contact_phone' => 'required|string|max:50',
            'contact_email' => 'nullable|email|max:100',
            'city' => 'nullable|string|max:100',
            'experience_years' => 'required|integer|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'availability_status' => 'required|in:available,mobilized,standby,blacklisted',
            'current_project_id' => 'nullable|exists:projects,id',
            'certifications_summary' => 'nullable|string',
            'rating' => 'nullable|numeric|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        HcdProfessional::create($validated);

        return redirect()->route('dashboard-admin.hcd.professionals.index')
            ->with('success', "Tenaga ahli {$validated['full_name']} berhasil ditambahkan ke database.");
    }

    /**
     * Update professional profile.
     */
    public function update(Request $request, HcdProfessional $professional): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'category' => 'required|string|max:60',
            'contact_phone' => 'required|string|max:50',
            'contact_email' => 'nullable|email|max:100',
            'city' => 'nullable|string|max:100',
            'experience_years' => 'required|integer|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'availability_status' => 'required|in:available,mobilized,standby,blacklisted',
            'current_project_id' => 'nullable|exists:projects,id',
            'certifications_summary' => 'nullable|string',
            'rating' => 'nullable|numeric|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $professional->update($validated);

        return redirect()->back()
            ->with('success', "Data tenaga ahli {$professional->full_name} berhasil diperbarui.");
    }

    /**
     * Delete professional from database.
     */
    public function destroy(HcdProfessional $professional): RedirectResponse
    {
        $name = $professional->full_name;
        $professional->delete();

        return redirect()->route('dashboard-admin.hcd.professionals.index')
            ->with('success', "Tenaga ahli {$name} telah dihapus dari database.");
    }
}
