<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAllocation;
use App\Models\AssetMaintenance;
use App\Models\Database\Blog;
use App\Models\Database\Service;
use App\Models\Database\Testimonial;
use App\Models\Database\Visitor;
use App\Models\Employee;
use App\Models\HcdTraining;
use App\Models\HsseIncident;
use App\Models\HsseInspection;
use App\Models\HssePermit;
use App\Models\Invoice;
use App\Models\LogisticsDelivery;
use App\Models\Opportunity;
use App\Models\ProcurementVendor;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Services\Visitor\VisitorStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(VisitorStatisticsService $visitorStatistics)
    {
        $user = Auth::user();

        if ($user) {
            if ($user->hasRole('pmo-manager')) {
                return redirect()->route('dashboard-admin.pmo.dashboard');
            } elseif ($user->hasAnyRole(['sales-director', 'sales-executive'])) {
                return redirect()->route('dashboard-admin.sales.dashboard');
            } elseif ($user->hasRole('finance-officer')) {
                return redirect()->route('dashboard-admin.finance.dashboard');
            } elseif ($user->hasRole('hcd-manager')) {
                return redirect()->route('dashboard-admin.hcd.dashboard');
            } elseif ($user->hasRole('hsse-officer')) {
                return redirect()->route('dashboard-admin.hsse.dashboard');
            } elseif ($user->hasRole('procurement-officer')) {
                return redirect()->route('dashboard-admin.procurement.dashboard');
            } elseif ($user->hasRole('asset-manager')) {
                return redirect()->route('dashboard-admin.assets.dashboard');
            }
        }

        // 1. Empat Metrik Makro Lintas Departemen (Top Cards)
        $pipelineOpportunities = Opportunity::whereNotIn('stage', ['closed_won', 'closed_lost'])->get();
        $salesPipelineValue = $pipelineOpportunities->sum('estimated_value');
        $activeOpportunitiesCount = $pipelineOpportunities->count();

        $projectPortfolioValue = Project::sum('contract_value');
        $projectsCount = Project::count();

        $totalInvoiceAmount = Invoice::sum('total_amount');
        $totalPaidAmount = Invoice::sum('paid_amount');
        $invoiceOutstanding = max(0, $totalInvoiceAmount - $totalPaidAmount);

        $procurementCommitment = PurchaseOrder::where('status', '!=', 'cancelled')->sum('grand_total');

        // 2. Ringkasan per Departemen
        // Sales (CRM)
        $salesSubmittedProposals = Proposal::whereIn('status', ['submitted', 'in_review'])->get();
        $salesWonOpportunities = Opportunity::where('stage', 'closed_won')->get();

        // Finance & Tax
        $invoiceSubmittedAmount = Invoice::whereIn('status', ['sent', 'partial'])->sum('total_amount');

        // HCD
        $totalEmployees = Employee::count();
        $activeTrainings = HcdTraining::count();

        // PMO
        $onProgressProjectsCount = Project::where('status', 'on_progress')->count();
        $avgProgress = round((float) (Project::avg('progress_percentage') ?? 68));

        // HSSE
        $safeManHours = (int) (Project::sum('man_hours') ?: 15620);
        $activePtwCount = HssePermit::where('status', 'active')->count();
        $inspectedEquipmentCount = HsseInspection::count();
        $openIncidentsCount = HsseIncident::whereIn('status', ['reported', 'investigating', 'action_taken'])->count();

        // Procurement & Logistics
        $pendingPrCount = PurchaseRequest::where('status', 'submitted')->count();
        $activeVendorsCount = ProcurementVendor::count();
        $inTransitDeliveriesCount = LogisticsDelivery::where('status', 'in_transit')->count();

        // Asset Management
        $totalAssetValuation = Asset::sum('purchase_price');
        $availableAssetsCount = Asset::where('status', 'available')->count();
        $mobilizedAssetsCount = Asset::where('status', 'mobilized')->count();
        $scheduledMaintenanceCount = AssetMaintenance::where('status', 'scheduled')->count();

        // Visitor Statistics (Compatibility)
        $totalViews = Blog::sum('views');
        $totalVisitors = $visitorStatistics->totalVisitsAllTime();

        $viewChart = Blog::selectRaw('MONTH(created_at) m, SUM(views) total')
            ->groupBy('m')
            ->pluck('total');

        $visitorChart = collect($visitorStatistics->dailyVisits(now()->startOfYear(), now()->endOfYear()))
            ->groupBy(fn ($total, $date) => (int) substr($date, 5, 2))
            ->map(fn ($values) => $values->sum());

        $totalTestimonials = Testimonial::count();
        $totalServices = Service::count();
        $totalBlogs = Blog::count();

        return view('dashboard.index', compact(
            'user',
            'salesPipelineValue',
            'activeOpportunitiesCount',
            'projectPortfolioValue',
            'projectsCount',
            'totalInvoiceAmount',
            'totalPaidAmount',
            'invoiceSubmittedAmount',
            'invoiceOutstanding',
            'procurementCommitment',
            'salesSubmittedProposals',
            'salesWonOpportunities',
            'totalEmployees',
            'activeTrainings',
            'onProgressProjectsCount',
            'avgProgress',
            'safeManHours',
            'activePtwCount',
            'inspectedEquipmentCount',
            'openIncidentsCount',
            'pendingPrCount',
            'activeVendorsCount',
            'inTransitDeliveriesCount',
            'totalAssetValuation',
            'availableAssetsCount',
            'mobilizedAssetsCount',
            'scheduledMaintenanceCount',
            'totalViews',
            'totalVisitors',
            'viewChart',
            'visitorChart',
            'totalTestimonials',
            'totalServices',
            'totalBlogs'
        ));
    }

    public function blogChart(Request $request, VisitorStatisticsService $visitorStatistics)
    {
        $filter = $request->filter;

        // ================= HARI =================
        if ($filter == 'hari') {

            $views = Blog::whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) label, SUM(views) total')
                ->groupBy('label')
                ->pluck('total', 'label');

            $visitors = Visitor::whereDate('created_at', today())
                ->selectRaw('HOUR(created_at) label, COUNT(*) total')
                ->groupBy('label')
                ->pluck('total', 'label');

            $categories = range(0, 23);
        }

        // ================= MINGGU =================
        elseif ($filter == 'minggu') {

            $views = Blog::whereBetween('created_at', [
                now()->subDays(6)->startOfDay(),
                now()->endOfDay(),
            ])
                ->selectRaw('DATE(created_at) label, SUM(views) total')
                ->groupBy('label')
                ->pluck('total', 'label');

            $visitors = $visitorStatistics->dailyVisits(
                now()->subDays(6)->startOfDay(),
                now()->endOfDay()
            );

            $categories = [];
            $viewData = [];
            $visitorData = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');

                $categories[] = now()->subDays($i)->format('d M');
                $viewData[] = $views[$date] ?? 0;
                $visitorData[] = $visitors[$date] ?? 0;
            }

            return response()->json([
                'views' => $viewData,
                'visitors' => $visitorData,
                'categories' => $categories,
            ]);
        }

        // ================= BULAN =================
        elseif ($filter == 'bulan') {

            $views = Blog::whereMonth('created_at', now()->month)
                ->selectRaw('DAY(created_at) label, SUM(views) total')
                ->groupBy('label')
                ->pluck('total', 'label');

            $visitors = collect($visitorStatistics->dailyVisits(now()->startOfMonth(), now()->endOfMonth()))
                ->mapWithKeys(fn ($total, $date) => [(int) substr($date, 8, 2) => $total]);

            $categories = range(1, now()->daysInMonth);
        }

        // ================= TAHUN / TOTAL =================
        else {

            $views = Blog::selectRaw('MONTH(created_at) label, SUM(views) total')
                ->groupBy('label')
                ->pluck('total', 'label');

            $visitors = collect($visitorStatistics->dailyVisits(now()->startOfYear(), now()->endOfYear()))
                ->groupBy(fn ($total, $date) => (int) substr($date, 5, 2))
                ->map(fn ($values) => $values->sum());

            $categories = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'May',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Aug',
                9 => 'Sep',
                10 => 'Oct',
                11 => 'Nov',
                12 => 'Dec',
            ];
        }

        // isi data kosong dengan 0
        $viewData = [];
        $visitorData = [];

        foreach ($categories as $key => $label) {
            $index = is_numeric($key) ? $key : $label;

            $viewData[] = $views[$index] ?? 0;
            $visitorData[] = $visitors[$index] ?? 0;
        }

        return response()->json([
            'views' => $viewData,
            'visitors' => $visitorData,
            'categories' => array_values($categories),
        ]);
    }
}
