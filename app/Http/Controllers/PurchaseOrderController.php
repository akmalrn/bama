<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ProcurementVendor;
use App\Models\Project;
use App\Models\ProjectCostEntry;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    /**
     * Display purchase orders list.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $vendorFilter = $request->query('vendor_id', 'all');
        $projectFilter = $request->query('project_id', 'all');
        $search = $request->query('search');

        $query = PurchaseOrder::with(['vendor', 'project', 'purchaseRequest', 'items', 'issuedByUser', 'logisticsDeliveries']);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($vendorFilter !== 'all') {
            $query->where('vendor_id', $vendorFilter);
        }

        if ($projectFilter !== 'all') {
            $query->where('project_id', $projectFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                    ->orWhere('payment_terms', 'like', "%{$search}%")
                    ->orWhereHas('vendor', function ($vq) use ($search) {
                        $vq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $allPOs = PurchaseOrder::all();
        $counts = [
            'all' => $allPOs->count(),
            'issued' => $allPOs->where('status', PurchaseOrder::STATUS_ISSUED)->count(),
            'in_delivery' => $allPOs->where('status', PurchaseOrder::STATUS_IN_DELIVERY)->count(),
            'completed' => $allPOs->where('status', PurchaseOrder::STATUS_COMPLETED)->count(),
            'draft' => $allPOs->where('status', PurchaseOrder::STATUS_DRAFT)->count(),
            'cancelled' => $allPOs->where('status', PurchaseOrder::STATUS_CANCELLED)->count(),
        ];

        $purchaseOrders = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $vendors = ProcurementVendor::where('status', '!=', ProcurementVendor::STATUS_BLACKLISTED)->orderBy('name')->get();
        $projects = Project::orderBy('title')->get();
        $approvedPRs = PurchaseRequest::whereIn('status', [PurchaseRequest::STATUS_APPROVED, PurchaseRequest::STATUS_PENDING_APPROVAL])
            ->with(['items', 'project'])
            ->orderByDesc('created_at')
            ->get();

        $paymentTermsList = PurchaseOrder::PAYMENT_TERMS;
        $nextPONumber = 'PO-'.date('Y').'-'.str_pad((string) (PurchaseOrder::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('pages.procurement.purchase-orders.index', compact(
            'purchaseOrders',
            'counts',
            'statusFilter',
            'vendorFilter',
            'projectFilter',
            'search',
            'vendors',
            'projects',
            'approvedPRs',
            'paymentTermsList',
            'nextPONumber'
        ));
    }

    /**
     * Store a newly created purchase order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'po_number' => ['required', 'string', 'max:50', 'unique:purchase_orders,po_number'],
            'purchase_request_id' => ['nullable', 'exists:purchase_requests,id'],
            'vendor_id' => ['required', 'exists:procurement_vendors,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'po_date' => ['required', 'date'],
            'delivery_deadline' => ['required', 'date', 'after_or_equal:po_date'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_terms' => ['required', 'string', 'max:60'],
            'delivery_address' => ['required', 'string'],
            'status' => ['required', 'in:draft,issued'],
            'terms_conditions' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.specifications' => ['nullable', 'string'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit' => ['required', 'string', 'max:30'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $qty = (float) $item['qty'];
                $unitPrice = (float) $item['unit_price'];
                $totalPrice = $qty * $unitPrice;
                $subtotal += $totalPrice;

                $itemsData[] = [
                    'item_name' => $item['item_name'],
                    'specifications' => $item['specifications'] ?? null,
                    'qty' => $qty,
                    'unit' => $item['unit'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'qty_received' => 0,
                ];
            }

            $discount = (float) ($validated['discount_amount'] ?? 0);
            $taxRate = (float) $validated['tax_rate'];
            $taxable = max(0, $subtotal - $discount);
            $taxAmount = round($taxable * ($taxRate / 100), 2);
            $grandTotal = $taxable + $taxAmount;

            $po = PurchaseOrder::create([
                'po_number' => $validated['po_number'],
                'purchase_request_id' => $validated['purchase_request_id'] ?? null,
                'vendor_id' => $validated['vendor_id'],
                'project_id' => $validated['project_id'] ?? null,
                'po_date' => $validated['po_date'],
                'delivery_deadline' => $validated['delivery_deadline'],
                'subtotal' => $subtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discount,
                'grand_total' => $grandTotal,
                'payment_terms' => $validated['payment_terms'],
                'delivery_address' => $validated['delivery_address'],
                'status' => $validated['status'],
                'terms_conditions' => $validated['terms_conditions'] ?? null,
                'issued_by' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $itemData) {
                $po->items()->create($itemData);
            }

            // Update Purchase Request status if converted
            if (! empty($validated['purchase_request_id'])) {
                PurchaseRequest::where('id', $validated['purchase_request_id'])->update([
                    'status' => PurchaseRequest::STATUS_CONVERTED_TO_PO,
                ]);
            }

            // Auto-create Commitment Entry in PMO Cost Management if attached to project
            if (! empty($validated['project_id'])) {
                $vendor = ProcurementVendor::find($validated['vendor_id']);
                ProjectCostEntry::create([
                    'project_id' => $validated['project_id'],
                    'entry_date' => $validated['po_date'],
                    'cost_category' => 'Material & Consumables',
                    'cost_subcategory' => 'Purchase Order '.$validated['po_number'],
                    'type' => ProjectCostEntry::TYPE_COMMITMENT,
                    'amount' => $grandTotal,
                    'payment_status' => 'unpaid',
                    'tax_amount' => $taxAmount,
                    'pph_amount' => 0.00,
                    'vendor' => $vendor?->name ?? 'Vendor',
                    'reference_no' => $validated['po_number'],
                    'notes' => 'Otomatis dicatat dari Purchase Order '.$validated['po_number'],
                ]);
            }
        });

        return redirect()->route('dashboard-admin.procurement.purchase-orders.index')
            ->with('success', "Purchase Order {$validated['po_number']} berhasil diterbitkan.");
    }

    /**
     * Update PO status.
     */
    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,issued,acknowledged,in_delivery,partially_received,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $updateData = ['status' => $validated['status']];
        if (! empty($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        $purchaseOrder->update($updateData);

        return redirect()->route('dashboard-admin.procurement.purchase-orders.index')
            ->with('success', "Status PO {$purchaseOrder->po_number} berhasil diperbarui menjadi {$validated['status']}.");
    }

    /**
     * Show / print preview of purchase order.
     */
    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['vendor', 'project.customer', 'purchaseRequest', 'items', 'issuedByUser', 'logisticsDeliveries']);

        return view('pages.procurement.purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Remove the specified purchase order.
     */
    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $number = $purchaseOrder->po_number;
        $purchaseOrder->delete();

        return redirect()->route('dashboard-admin.procurement.purchase-orders.index')
            ->with('success', "Purchase Order {$number} berhasil dihapus.");
    }
}
