<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetInventoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Asset::with(['assignedProject', 'picEmployee']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('asset_code', 'like', "%{$s}%")
                    ->orWhere('serial_number', 'like', "%{$s}%")
                    ->orWhere('brand_model', 'like', "%{$s}%")
                    ->orWhere('current_location', 'like', "%{$s}%");
            });
        }

        $assets = $query->latest('id')->paginate(10)->withQueryString();

        // Metrik filter
        $totalCount = Asset::count();
        $availableCount = Asset::where('status', 'available')->count();
        $mobilizedCount = Asset::where('status', 'mobilized')->count();
        $maintenanceCount = Asset::where('status', 'under_maintenance')->count();

        $categories = Asset::select('category')->distinct()->pluck('category');
        $projects = Project::select('id', 'title')->orderBy('title')->get();
        $employees = Employee::with('user')->orderBy('employee_number')->get();

        return view('pages.assets.inventory.index', compact(
            'assets',
            'totalCount',
            'availableCount',
            'mobilizedCount',
            'maintenanceCount',
            'categories',
            'projects',
            'employees'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:80'],
            'brand_model' => ['nullable', 'string', 'max:150'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'year_acquired' => ['nullable', 'integer', 'min:1990', 'max:2030'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'condition' => ['required', 'string', 'in:good,fair,maintenance_needed,damaged'],
            'status' => ['required', 'string', 'in:available,mobilized,under_maintenance,decommissioned'],
            'current_location' => ['required', 'string', 'max:150'],
            'assigned_project_id' => ['nullable', 'exists:projects,id'],
            'pic_employee_id' => ['nullable', 'exists:employees,id'],
            'last_maintenance_date' => ['nullable', 'date'],
            'next_maintenance_date' => ['nullable', 'date'],
            'last_calibration_date' => ['nullable', 'date'],
            'next_calibration_date' => ['nullable', 'date'],
            'technical_specifications' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        // Generate asset_code AST-YYYY-xxx
        $year = date('Y');
        $lastAsset = Asset::where('asset_code', 'like', "AST-{$year}-%")->latest('id')->first();
        $nextSeq = 1;
        if ($lastAsset && preg_match("/AST-{$year}-(\d+)/", $lastAsset->asset_code, $matches)) {
            $nextSeq = ((int) $matches[1]) + 1;
        }
        $validated['asset_code'] = sprintf('AST-%s-%03d', $year, $nextSeq);

        Asset::create($validated);

        return redirect()->route('dashboard-admin.assets.inventory.index')
            ->with('success', "Asset {$validated['asset_code']} berhasil didaftarkan ke inventaris.");
    }

    public function update(Request $request, Asset $asset): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:80'],
            'brand_model' => ['nullable', 'string', 'max:150'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'year_acquired' => ['nullable', 'integer', 'min:1990', 'max:2030'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'condition' => ['required', 'string', 'in:good,fair,maintenance_needed,damaged'],
            'status' => ['required', 'string', 'in:available,mobilized,under_maintenance,decommissioned'],
            'current_location' => ['required', 'string', 'max:150'],
            'assigned_project_id' => ['nullable', 'exists:projects,id'],
            'pic_employee_id' => ['nullable', 'exists:employees,id'],
            'last_maintenance_date' => ['nullable', 'date'],
            'next_maintenance_date' => ['nullable', 'date'],
            'last_calibration_date' => ['nullable', 'date'],
            'next_calibration_date' => ['nullable', 'date'],
            'technical_specifications' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $asset->update($validated);

        return redirect()->route('dashboard-admin.assets.inventory.index')
            ->with('success', "Data asset {$asset->asset_code} berhasil diperbarui.");
    }

    public function destroy(Asset $asset): RedirectResponse
    {
        if ($asset->status === 'mobilized') {
            return redirect()->route('dashboard-admin.assets.inventory.index')
                ->with('error', "Asset {$asset->asset_code} sedang dimobilisasi ke proyek aktif dan tidak dapat dihapus.");
        }

        $code = $asset->asset_code;
        $asset->delete();

        return redirect()->route('dashboard-admin.assets.inventory.index')
            ->with('success', "Asset {$code} berhasil dihapus dari inventaris.");
    }
}
