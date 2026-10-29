<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\LogisticsDelivery;
use App\Models\ProcurementVendor;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementDashboardController extends Controller
{
    /**
     * Display the Procurement & Logistics Executive Dashboard.
     */
    public function index(Request $request): View
    {
       
        $allPOs = PurchaseOrder::with(['vendor', 'project'])->get();
        $totalPOValue = (float) $allPOs->whereNotIn('status', [PurchaseOrder::STATUS_CANCELLED])->sum('grand_total');
        $activePOCount = $allPOs->whereIn('status', [PurchaseOrder::STATUS_ISSUED, PurchaseOrder::STATUS_ACKNOWLEDGED, PurchaseOrder::STATUS_IN_DELIVERY, PurchaseOrder::STATUS_PARTIALLY_RECEIVED])->count();
        $thisMonthPOValue = (float) $allPOs->filter(fn ($po) => $po->po_date && Carbon::parse($po->po_date)->isCurrentMonth())->sum('grand_total');

        $allPRs = PurchaseRequest::with(['project', 'requestedBy.user'])->get();
        $totalPRCount = $allPRs->count();
        $pendingPRCount = $allPRs->where('status', PurchaseRequest::STATUS_PENDING_APPROVAL)->count();
        $urgentPRCount = $allPRs->where('priority', 'urgent')->whereIn('status', [PurchaseRequest::STATUS_PENDING_APPROVAL, PurchaseRequest::STATUS_APPROVED])->count();

        $allVendors = ProcurementVendor::all();
        $totalVendors = $allVendors->count();
        $activeVendors = $allVendors->where('status', ProcurementVendor::STATUS_ACTIVE)->count();

        $allDeliveries = LogisticsDelivery::with(['purchaseOrder.vendor', 'project'])->get();
        $inTransitDeliveriesCount = $allDeliveries->where('status', LogisticsDelivery::STATUS_IN_TRANSIT)->count();
        $completedDeliveriesCount = $allDeliveries->where('status', LogisticsDelivery::STATUS_INSPECTED_AND_RECEIVED)->count();

       
        $vendorSpending = $allPOs->whereNotIn('status', [PurchaseOrder::STATUS_CANCELLED])
            ->groupBy(fn ($po) => $po->vendor?->category ?? 'Other')
            ->map(fn ($group) => (float) $group->sum('grand_total'));

       
        $prStatusCounts = [
            'draft' => $allPRs->where('status', PurchaseRequest::STATUS_DRAFT)->count(),
            'pending_approval' => $allPRs->where('status', PurchaseRequest::STATUS_PENDING_APPROVAL)->count(),
            'approved' => $allPRs->where('status', PurchaseRequest::STATUS_APPROVED)->count(),
            'converted_to_po' => $allPRs->where('status', PurchaseRequest::STATUS_CONVERTED_TO_PO)->count(),
            'rejected' => $allPRs->where('status', PurchaseRequest::STATUS_REJECTED)->count(),
        ];

        $poStatusCounts = [
            'draft' => $allPOs->where('status', PurchaseOrder::STATUS_DRAFT)->count(),
            'issued' => $allPOs->where('status', PurchaseOrder::STATUS_ISSUED)->count(),
            'in_delivery' => $allPOs->where('status', PurchaseOrder::STATUS_IN_DELIVERY)->count(),
            'completed' => $allPOs->where('status', PurchaseOrder::STATUS_COMPLETED)->count(),
            'cancelled' => $allPOs->where('status', PurchaseOrder::STATUS_CANCELLED)->count(),
        ];

       
        $recentPOs = $allPOs->sortByDesc('created_at')->take(5);
        $urgentPRs = $allPRs->whereIn('status', [PurchaseRequest::STATUS_PENDING_APPROVAL, PurchaseRequest::STATUS_APPROVED])
            ->sortByDesc(fn ($pr) => $pr->priority === 'urgent' ? 2 : 1)
            ->take(5);
        $activeShipments = $allDeliveries->whereIn('status', [LogisticsDelivery::STATUS_PREPARING, LogisticsDelivery::STATUS_IN_TRANSIT])->take(5);

       
        $topVendors = $allVendors->map(function ($vendor) use ($allPOs) {
            $orders = $allPOs->where('vendor_id', $vendor->id)->whereNotIn('status', [PurchaseOrder::STATUS_CANCELLED]);

            return [
                'vendor' => $vendor,
                'total_orders' => $orders->count(),
                'total_spend' => (float) $orders->sum('grand_total'),
            ];
        })->sortByDesc('total_spend')->take(5);

        return view('pages.procurement.dashboard.index', compact(
            'totalPOValue',
            'activePOCount',
            'thisMonthPOValue',
            'totalPRCount',
            'pendingPRCount',
            'urgentPRCount',
            'totalVendors',
            'activeVendors',
            'inTransitDeliveriesCount',
            'completedDeliveriesCount',
            'vendorSpending',
            'prStatusCounts',
            'poStatusCounts',
            'recentPOs',
            'urgentPRs',
            'activeShipments',
            'topVendors'
        ));
    }
}
