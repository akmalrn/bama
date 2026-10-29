<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\PurchaseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseRequestController extends Controller
{
    /**
     * Display purchase requests list.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $priorityFilter = $request->query('priority', 'all');
        $projectFilter = $request->query('project_id', 'all');
        $categoryFilter = $request->query('category', 'all');
        $search = $request->query('search');

        $query = PurchaseRequest::with(['project', 'requestedBy.user', 'approvedBy.user', 'createdByUser', 'items', 'purchaseOrder']);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($priorityFilter !== 'all') {
            $query->where('priority', $priorityFilter);
        }

        if ($projectFilter !== 'all') {
            $query->where('project_id', $projectFilter);
        }

        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pr_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('justification', 'like', "%{$search}%");
            });
        }

        $allPRs = PurchaseRequest::all();
        $counts = [
            'all' => $allPRs->count(),
            'pending_approval' => $allPRs->where('status', PurchaseRequest::STATUS_PENDING_APPROVAL)->count(),
            'approved' => $allPRs->where('status', PurchaseRequest::STATUS_APPROVED)->count(),
            'converted_to_po' => $allPRs->where('status', PurchaseRequest::STATUS_CONVERTED_TO_PO)->count(),
            'draft' => $allPRs->where('status', PurchaseRequest::STATUS_DRAFT)->count(),
            'rejected' => $allPRs->where('status', PurchaseRequest::STATUS_REJECTED)->count(),
        ];

        $purchaseRequests = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $projects = Project::orderBy('title')->get();
        $employees = Employee::with('user')->orderBy('employee_number')->get();
        $categories = PurchaseRequest::CATEGORIES;

        $nextPRNumber = 'PR-'.date('Y').'-'.str_pad((string) (PurchaseRequest::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('pages.procurement.purchase-requests.index', compact(
            'purchaseRequests',
            'counts',
            'statusFilter',
            'priorityFilter',
            'projectFilter',
            'categoryFilter',
            'search',
            'projects',
            'employees',
            'categories',
            'nextPRNumber'
        ));
    }

    /**
     * Store a newly created purchase request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pr_number' => ['required', 'string', 'max:50', 'unique:purchase_requests,pr_number'],
            'title' => ['required', 'string', 'max:255'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'requested_by' => ['nullable', 'exists:employees,id'],
            'request_date' => ['required', 'date'],
            'required_date' => ['required', 'date', 'after_or_equal:request_date'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'category' => ['required', 'string', 'max:80'],
            'justification' => ['required', 'string'],
            'status' => ['required', 'in:draft,pending_approval'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.specifications' => ['nullable', 'string'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit' => ['required', 'string', 'max:30'],
            'items.*.estimated_unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated) {
            $total = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $qty = (float) $item['qty'];
                $unitPrice = (float) $item['estimated_unit_price'];
                $subtotal = $qty * $unitPrice;
                $total += $subtotal;

                $itemsData[] = [
                    'item_name' => $item['item_name'],
                    'specifications' => $item['specifications'] ?? null,
                    'qty' => $qty,
                    'unit' => $item['unit'],
                    'estimated_unit_price' => $unitPrice,
                    'estimated_total_price' => $subtotal,
                ];
            }

            $pr = PurchaseRequest::create([
                'pr_number' => $validated['pr_number'],
                'title' => $validated['title'],
                'project_id' => $validated['project_id'] ?? null,
                'requested_by' => $validated['requested_by'] ?? null,
                'request_date' => $validated['request_date'],
                'required_date' => $validated['required_date'],
                'priority' => $validated['priority'],
                'category' => $validated['category'],
                'estimated_total' => $total,
                'status' => $validated['status'],
                'justification' => $validated['justification'],
                'created_by' => Auth::id(),
            ]);

            foreach ($itemsData as $itemData) {
                $pr->items()->create($itemData);
            }
        });

        return redirect()->route('dashboard-admin.procurement.purchase-requests.index')
            ->with('success', "Purchase Request {$validated['pr_number']} berhasil diajukan.");
    }

    /**
     * Update PR status (approval workflow).
     */
    public function updateStatus(Request $request, PurchaseRequest $purchaseRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending_approval,approved,rejected,draft'],
            'approval_notes' => ['nullable', 'string'],
        ]);

        $updateData = [
            'status' => $validated['status'],
            'approval_notes' => $validated['approval_notes'] ?? $purchaseRequest->approval_notes,
        ];

        if ($validated['status'] === PurchaseRequest::STATUS_APPROVED) {
            // Find employee id of current user if available
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->first();
            $updateData['approved_by'] = $employee?->id;
        }

        $purchaseRequest->update($updateData);

        return redirect()->route('dashboard-admin.procurement.purchase-requests.index')
            ->with('success', "Status PR {$purchaseRequest->pr_number} berhasil diperbarui menjadi {$validated['status']}.");
    }

    /**
     * Remove the specified purchase request.
     */
    public function destroy(PurchaseRequest $purchaseRequest): RedirectResponse
    {
        if ($purchaseRequest->status === PurchaseRequest::STATUS_CONVERTED_TO_PO) {
            return redirect()->route('dashboard-admin.procurement.purchase-requests.index')
                ->with('error', "PR {$purchaseRequest->pr_number} tidak dapat dihapus karena telah diterbitkan PO.");
        }

        $number = $purchaseRequest->pr_number;
        $purchaseRequest->delete();

        return redirect()->route('dashboard-admin.procurement.purchase-requests.index')
            ->with('success', "Purchase Request {$number} berhasil dihapus.");
    }
}
