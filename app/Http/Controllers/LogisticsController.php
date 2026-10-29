<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\LogisticsDelivery;
use App\Models\Project;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogisticsController extends Controller
{
    /**
     * Display logistics tracking and expedition list.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $projectFilter = $request->query('project_id', 'all');
        $search = $request->query('search');

        $query = LogisticsDelivery::with(['purchaseOrder.vendor', 'project']);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($projectFilter !== 'all') {
            $query->where('project_id', $projectFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('delivery_number', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%")
                    ->orWhere('expedition_name', 'like', "%{$search}%")
                    ->orWhere('origin', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }

        $allDeliveries = LogisticsDelivery::all();
        $counts = [
            'all' => $allDeliveries->count(),
            'in_transit' => $allDeliveries->where('status', LogisticsDelivery::STATUS_IN_TRANSIT)->count(),
            'arrived_at_site' => $allDeliveries->where('status', LogisticsDelivery::STATUS_ARRIVED_AT_SITE)->count(),
            'inspected_and_received' => $allDeliveries->where('status', LogisticsDelivery::STATUS_INSPECTED_AND_RECEIVED)->count(),
            'preparing' => $allDeliveries->where('status', LogisticsDelivery::STATUS_PREPARING)->count(),
            'issue_damaged' => $allDeliveries->where('status', LogisticsDelivery::STATUS_ISSUE_DAMAGED)->count(),
        ];

        $deliveries = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $projects = Project::orderBy('title')->get();
        $purchaseOrders = PurchaseOrder::with(['vendor', 'project'])
            ->whereNotIn('status', [PurchaseOrder::STATUS_CANCELLED])
            ->orderByDesc('created_at')
            ->get();

        $nextDeliveryNumber = 'LOG-'.date('Y').'-'.str_pad((string) (LogisticsDelivery::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('pages.procurement.logistics.index', compact(
            'deliveries',
            'counts',
            'statusFilter',
            'projectFilter',
            'search',
            'projects',
            'purchaseOrders',
            'nextDeliveryNumber'
        ));
    }

    /**
     * Store a newly created delivery record.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'delivery_number' => ['required', 'string', 'max:50', 'unique:logistics_deliveries,delivery_number'],
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'expedition_name' => ['required', 'string', 'max:150'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'shipment_date' => ['required', 'date'],
            'estimated_arrival_date' => ['required', 'date', 'after_or_equal:shipment_date'],
            'recipient_name' => ['nullable', 'string', 'max:150'],
            'recipient_phone' => ['nullable', 'string', 'max:50'],
            'driver_name' => ['nullable', 'string', 'max:150'],
            'driver_phone' => ['nullable', 'string', 'max:50'],
            'vehicle_plate' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:preparing,in_transit,arrived_at_site,inspected_and_received,issue_damaged'],
            'notes' => ['nullable', 'string'],
        ]);

        $delivery = LogisticsDelivery::create($validated);

        // Auto update PO status to in_delivery if PO is currently issued/acknowledged
        if (! empty($validated['purchase_order_id']) && $validated['status'] === LogisticsDelivery::STATUS_IN_TRANSIT) {
            PurchaseOrder::where('id', $validated['purchase_order_id'])
                ->whereIn('status', [PurchaseOrder::STATUS_ISSUED, PurchaseOrder::STATUS_ACKNOWLEDGED, PurchaseOrder::STATUS_DRAFT])
                ->update(['status' => PurchaseOrder::STATUS_IN_DELIVERY]);
        }

        return redirect()->route('dashboard-admin.procurement.logistics.index')
            ->with('success', "Surat Jalan / Pengiriman {$validated['delivery_number']} berhasil dicatat.");
    }

    /**
     * Update delivery status.
     */
    public function updateStatus(Request $request, LogisticsDelivery $delivery): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:preparing,in_transit,arrived_at_site,inspected_and_received,issue_damaged'],
            'actual_arrival_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === LogisticsDelivery::STATUS_INSPECTED_AND_RECEIVED || $validated['status'] === LogisticsDelivery::STATUS_ARRIVED_AT_SITE) {
            $updateData['actual_arrival_date'] = $validated['actual_arrival_date'] ?? now()->toDateString();
        }

        if (! empty($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        $delivery->update($updateData);

        // If inspected and received, check if related PO should be completed
        if ($validated['status'] === LogisticsDelivery::STATUS_INSPECTED_AND_RECEIVED && $delivery->purchase_order_id) {
            $po = PurchaseOrder::find($delivery->purchase_order_id);
            if ($po && $po->status !== PurchaseOrder::STATUS_COMPLETED) {
                $po->update(['status' => PurchaseOrder::STATUS_COMPLETED]);
            }
        }

        return redirect()->route('dashboard-admin.procurement.logistics.index')
            ->with('success', "Status pengiriman {$delivery->delivery_number} berhasil diperbarui.");
    }

    /**
     * Remove the specified delivery record.
     */
    public function destroy(LogisticsDelivery $delivery): RedirectResponse
    {
        $number = $delivery->delivery_number;
        $delivery->delete();

        return redirect()->route('dashboard-admin.procurement.logistics.index')
            ->with('success', "Data pengiriman {$number} berhasil dihapus.");
    }
}
