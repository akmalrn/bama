<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAllocation;
use App\Models\AssetMaintenance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $today = Carbon::today();

        // 1. Executive Metrics
        $totalAssets = Asset::count();
        $totalValuation = Asset::sum('purchase_price');
        $mobilizedAssets = Asset::where('status', 'mobilized')->count();
        $availableAssets = Asset::where('status', 'available')->count();
        $underMaintenance = Asset::where('status', 'under_maintenance')->count();

        // Utilization rate
        $utilizationRate = $totalAssets > 0 ? round(($mobilizedAssets / $totalAssets) * 100, 1) : 0;

        // Alerts: Maintenance & Calibration due in 30 days or overdue
        $urgentMaintenanceCount = AssetMaintenance::whereIn('status', ['scheduled', 'in_progress'])
            ->where('scheduled_date', '<=', $today->copy()->addDays(30))
            ->count();

        // 2. Breakdown per Kategori
        $categoryBreakdown = Asset::selectRaw('category, count(*) as count, sum(purchase_price) as total_val')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        // 3. Status Alokasi Lapangan Aktif
        $activeAllocations = AssetAllocation::with(['asset', 'project', 'requester'])
            ->where('status', 'active')
            ->latest('start_date')
            ->take(5)
            ->get();

        // 4. Jadwal Perawatan & Kalibrasi Terdekat
        $upcomingMaintenances = AssetMaintenance::with(['asset', 'technician'])
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        // 5. Unit Kritis / Perlu Perhatian (Maintenance Needed or Damaged)
        $flaggedAssets = Asset::whereIn('condition', ['maintenance_needed', 'damaged'])
            ->orWhere('status', 'under_maintenance')
            ->take(5)
            ->get();

        return view('pages.assets.dashboard', compact(
            'totalAssets',
            'totalValuation',
            'mobilizedAssets',
            'availableAssets',
            'underMaintenance',
            'utilizationRate',
            'urgentMaintenanceCount',
            'categoryBreakdown',
            'activeAllocations',
            'upcomingMaintenances',
            'flaggedAssets'
        ));
    }
}
