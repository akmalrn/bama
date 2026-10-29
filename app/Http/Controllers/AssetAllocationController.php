<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAllocation;
use App\Models\Employee;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetAllocationController extends Controller
{
    public function index(Request $request): View
    {
        $query = AssetAllocation::with(['asset', 'project', 'requester', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('allocation_number', 'like', "%{$s}%")
                    ->orWhere('destination_site', 'like', "%{$s}%")
                    ->orWhere('letter_of_handover_number', 'like', "%{$s}%")
                    ->orWhereHas('asset', function ($qa) use ($s) {
                        $qa->where('name', 'like', "%{$s}%")
                            ->orWhere('asset_code', 'like', "%{$s}%");
                    });
            });
        }

        $allocations = $query->latest('start_date')->paginate(10)->withQueryString();

        // Metrics
        $totalAllocations = AssetAllocation::count();
        $activeAllocations = AssetAllocation::where('status', 'active')->count();
        $returnedAllocations = AssetAllocation::where('status', 'returned')->count();

        $availableAssets = Asset::where('status', 'available')->orderBy('name')->get();
        $allAssets = Asset::orderBy('name')->get();
        $projects = Project::select('id', 'title')->orderBy('title')->get();
        $employees = Employee::with('user')->orderBy('employee_number')->get();

        return view('pages.assets.allocations.index', compact(
            'allocations',
            'totalAllocations',
            'activeAllocations',
            'returnedAllocations',
            'availableAssets',
            'allAssets',
            'projects',
            'employees'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'project_id' => ['required', 'exists:projects,id'],
            'requested_by' => ['nullable', 'exists:employees,id'],
            'approved_by' => ['nullable', 'exists:employees,id'],
            'start_date' => ['required', 'date'],
            'expected_return_date' => ['required', 'date', 'after_or_equal:start_date'],
            'destination_site' => ['required', 'string', 'max:150'],
            'courier_vehicle' => ['nullable', 'string', 'max:100'],
            'letter_of_handover_number' => ['nullable', 'string', 'max:80'],
            'condition_out' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $year = date('Y');
        $last = AssetAllocation::where('allocation_number', 'like', "ALC-{$year}-%")->latest('id')->first();
        $nextSeq = 1;
        if ($last && preg_match("/ALC-{$year}-(\d+)/", $last->allocation_number, $matches)) {
            $nextSeq = ((int) $matches[1]) + 1;
        }
        $validated['allocation_number'] = sprintf('ALC-%s-%03d', $year, $nextSeq);
        $validated['status'] = 'active';

        $allocation = AssetAllocation::create($validated);

        // Update asset status to mobilized & location
        $asset = Asset::findOrFail($validated['asset_id']);
        $asset->update([
            'status' => 'mobilized',
            'assigned_project_id' => $validated['project_id'],
            'current_location' => $validated['destination_site'],
        ]);

        return redirect()->route('dashboard-admin.assets.allocations.index')
            ->with('success', "Alokasi {$allocation->allocation_number} berhasil dibuat dan asset telah dimobilisasi.");
    }

    public function returnAsset(Request $request, AssetAllocation $allocation): RedirectResponse
    {
        $validated = $request->validate([
            'actual_return_date' => ['required', 'date'],
            'condition_in' => ['required', 'string'],
            'return_location' => ['required', 'string', 'max:150'],
            'notes' => ['nullable', 'string'],
        ]);

        $allocation->update([
            'actual_return_date' => $validated['actual_return_date'],
            'condition_in' => $validated['condition_in'],
            'status' => 'returned',
            'notes' => $allocation->notes . "\n[Pengembalian]: " . ($validated['notes'] ?? '-'),
        ]);

        // Release asset back to available status
        $asset = $allocation->asset;
        if ($asset) {
            $asset->update([
                'status' => 'available',
                'assigned_project_id' => null,
                'current_location' => $validated['return_location'],
            ]);
        }

        return redirect()->route('dashboard-admin.assets.allocations.index')
            ->with('success', "Asset {$asset?->asset_code} berhasil diterima kembali di gudang/workshop.");
    }
}
