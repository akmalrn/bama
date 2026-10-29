<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetMaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $today = Carbon::today();
        $query = AssetMaintenance::with(['asset', 'technician']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('maintenance_number', 'like', "%{$s}%")
                    ->orWhere('title', 'like', "%{$s}%")
                    ->orWhere('vendor_name', 'like', "%{$s}%")
                    ->orWhere('certificate_number', 'like', "%{$s}%")
                    ->orWhereHas('asset', function ($qa) use ($s) {
                        $qa->where('name', 'like', "%{$s}%")
                            ->orWhere('asset_code', 'like', "%{$s}%");
                    });
            });
        }

        $maintenances = $query->latest('scheduled_date')->paginate(10)->withQueryString();

        // Metrics
        $totalCount = AssetMaintenance::count();
        $scheduledCount = AssetMaintenance::where('status', 'scheduled')->count();
        $inProgressCount = AssetMaintenance::where('status', 'in_progress')->count();
        $completedCount = AssetMaintenance::where('status', 'completed')->count();
        $overdueCount = AssetMaintenance::whereIn('status', ['scheduled', 'in_progress'])
            ->where('scheduled_date', '<', $today)
            ->count();

        $assets = Asset::orderBy('name')->get();
        $employees = Employee::with('user')->orderBy('employee_number')->get();

        return view('pages.assets.maintenance.index', compact(
            'maintenances',
            'totalCount',
            'scheduledCount',
            'inProgressCount',
            'completedCount',
            'overdueCount',
            'assets',
            'employees'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'type' => ['required', 'string', 'in:preventive,corrective,calibration,overhaul,certification'],
            'title' => ['required', 'string', 'max:255'],
            'scheduled_date' => ['required', 'date'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:scheduled,in_progress,completed'],
            'vendor_name' => ['nullable', 'string', 'max:150'],
            'technician_employee_id' => ['nullable', 'exists:employees,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $year = date('Y');
        $last = AssetMaintenance::where('maintenance_number', 'like', "MTC-{$year}-%")->latest('id')->first();
        $nextSeq = 1;
        if ($last && preg_match("/MTC-{$year}-(\d+)/", $last->maintenance_number, $matches)) {
            $nextSeq = ((int) $matches[1]) + 1;
        }
        $validated['maintenance_number'] = sprintf('MTC-%s-%03d', $year, $nextSeq);
        $validated['cost'] = $validated['cost'] ?? 0;

        $mtc = AssetMaintenance::create($validated);

        // If in_progress, update asset status
        if ($validated['status'] === 'in_progress') {
            $asset = Asset::find($validated['asset_id']);
            $asset?->update(['status' => 'under_maintenance']);
        }

        return redirect()->route('dashboard-admin.assets.maintenance.index')
            ->with('success', "Jadwal perawatan {$mtc->maintenance_number} berhasil didaftarkan.");
    }

    public function complete(Request $request, AssetMaintenance $maintenance): RedirectResponse
    {
        $validated = $request->validate([
            'completed_date' => ['required', 'date'],
            'action_taken' => ['required', 'string'],
            'parts_replaced' => ['nullable', 'string'],
            'certificate_number' => ['nullable', 'string', 'max:100'],
            'next_due_date' => ['nullable', 'date'],
            'actual_cost' => ['nullable', 'numeric', 'min:0'],
            'condition_after' => ['required', 'string', 'in:good,fair'],
        ]);

        $maintenance->update([
            'status' => 'completed',
            'completed_date' => $validated['completed_date'],
            'action_taken' => $validated['action_taken'],
            'parts_replaced' => $validated['parts_replaced'],
            'certificate_number' => $validated['certificate_number'],
            'next_due_date' => $validated['next_due_date'],
            'cost' => $validated['actual_cost'] ?? $maintenance->cost,
        ]);

        // Update asset condition, dates & availability
        $asset = $maintenance->asset;
        if ($asset) {
            $updates = [
                'condition' => $validated['condition_after'],
            ];
            if ($asset->status === 'under_maintenance') {
                $updates['status'] = 'available';
            }

            if ($maintenance->type === 'calibration') {
                $updates['last_calibration_date'] = $validated['completed_date'];
                if ($validated['next_due_date']) {
                    $updates['next_calibration_date'] = $validated['next_due_date'];
                }
            } else {
                $updates['last_maintenance_date'] = $validated['completed_date'];
                if ($validated['next_due_date']) {
                    $updates['next_maintenance_date'] = $validated['next_due_date'];
                }
            }

            $asset->update($updates);
        }

        return redirect()->route('dashboard-admin.assets.maintenance.index')
            ->with('success', "Perawatan {$maintenance->maintenance_number} berhasil diselesaikan.");
    }
}
