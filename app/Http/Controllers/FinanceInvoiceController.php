<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class FinanceInvoiceController extends Controller
{
    /**
     * Display a listing of invoices and AR monitoring.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $customerId = $request->query('customer_id');
        $projectId = $request->query('project_id');
        $search = $request->query('search');
        $dateStart = $request->query('date_start');
        $dateEnd = $request->query('date_end');

        $query = Invoice::with(['customer', 'project', 'payments', 'creator'])->latest('invoice_date');

        if ($status && $status !== 'all') {
            if ($status === 'overdue') {
                $query->where('due_date', '<', now()->toDateString())
                    ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_CANCELLED]);
            } else {
                $query->where('status', $status);
            }
        }

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($dateStart) {
            $query->whereDate('invoice_date', '>=', $dateStart);
        }

        if ($dateEnd) {
            $query->whereDate('invoice_date', '<=', $dateEnd);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('company_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('project', function ($pq) use ($search) {
                        $pq->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Summary Calculations across all active invoices (unfiltered by pagination)
        $allInvoices = Invoice::all();
        $totalInvoiced = (float) $allInvoices->sum('total_amount');
        $totalPaid = (float) $allInvoices->sum('paid_amount');
        $totalOutstanding = max(0, $totalInvoiced - $totalPaid);
        $totalOverdueCount = $allInvoices->filter(fn ($inv) => $inv->is_overdue)->count();

        // Counts for tabs
        $counts = [
            'all' => $allInvoices->count(),
            'draft' => $allInvoices->where('status', Invoice::STATUS_DRAFT)->count(),
            'sent' => $allInvoices->where('status', Invoice::STATUS_SENT)->count(),
            'partial' => $allInvoices->where('status', Invoice::STATUS_PARTIAL)->count(),
            'paid' => $allInvoices->where('status', Invoice::STATUS_PAID)->count(),
            'overdue' => $totalOverdueCount,
        ];

        $invoices = $query->paginate(15)->withQueryString();
        $customers = Customer::orderBy('company_name')->get();
        $projects = Project::orderBy('title')->get();

        // Generate next recommended invoice number
        $nextInvNumber = 'INV/' . date('Y/m') . '/' . str_pad((string) (Invoice::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('pages.finance.invoices.index', compact(
            'invoices',
            'customers',
            'projects',
            'totalInvoiced',
            'totalPaid',
            'totalOutstanding',
            'totalOverdueCount',
            'counts',
            'nextInvNumber',
            'status',
            'customerId',
            'projectId',
            'search',
            'dateStart',
            'dateEnd'
        ));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_number' => ['required', 'string', 'max:50', 'unique:invoices,invoice_number'],
            'customer_id' => ['required', 'exists:customers,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:invoice_date'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pph_type' => ['nullable', 'string', 'in:none,pph_21,pph_23,pph_4_2'],
            'pph_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'in:draft,sent,partial,paid,overdue,cancelled'],
            'notes' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,zip,doc,docx,xls,xlsx', 'max:10240'],
        ]);

        $subtotal = (float) $validated['subtotal'];
        $taxRate = (float) ($validated['tax_rate'] ?? 11.0);
        $taxAmount = round($subtotal * ($taxRate / 100), 2);

        $pphType = $validated['pph_type'] ?? 'none';
        $pphRate = (float) ($validated['pph_rate'] ?? 0.0);
        $pphAmount = round($subtotal * ($pphRate / 100), 2);

        $totalAmount = $subtotal + $taxAmount; // Total bruto tagihan (termasuk PPN)

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/invoices');
            if (! File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $fileName);
            $attachmentPath = 'uploads/invoices/' . $fileName;
        }

        Invoice::create([
            'invoice_number' => $validated['invoice_number'],
            'customer_id' => $validated['customer_id'],
            'project_id' => $validated['project_id'] ?? null,
            'title' => $validated['title'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'pph_type' => $pphType,
            'pph_rate' => $pphRate,
            'pph_amount' => $pphAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => 0.00,
            'status' => $validated['status'] ?? Invoice::STATUS_DRAFT,
            'payment_terms' => $validated['payment_terms'] ?? 'Net 30 Days',
            'notes' => $validated['notes'] ?? null,
            'attachment_path' => $attachmentPath,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('dashboard-admin.finance.invoices.index')
            ->with('success', "Invoice {$validated['invoice_number']} berhasil diterbitkan.");
    }

    /**
     * Show detail invoice with payments history (for modal AJAX or view).
     */
    public function show(Invoice $invoice): JsonResponse
    {
        $invoice->load(['customer', 'project', 'payments.creator', 'creator']);

        return response()->json([
            'invoice' => $invoice,
            'formatted_subtotal' => 'Rp ' . number_format((float) $invoice->subtotal, 0, ',', '.'),
            'formatted_tax_amount' => 'Rp ' . number_format((float) $invoice->tax_amount, 0, ',', '.'),
            'formatted_pph_amount' => 'Rp ' . number_format((float) $invoice->pph_amount, 0, ',', '.'),
            'formatted_total_amount' => 'Rp ' . number_format((float) $invoice->total_amount, 0, ',', '.'),
            'formatted_paid_amount' => 'Rp ' . number_format((float) $invoice->paid_amount, 0, ',', '.'),
            'formatted_balance' => 'Rp ' . number_format((float) $invoice->balance, 0, ',', '.'),
            'customer_name' => $invoice->customer->company_name ?? '-',
            'customer_pic' => $invoice->customer->pic_name ?? '-',
            'customer_npwp' => $invoice->customer->npwp ?? '-',
            'project_name' => $invoice->project->name ?? '-',
            'payments' => $invoice->payments,
        ]);
    }

    /**
     * Update the specified invoice.
     */
    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_number' => ['required', 'string', 'max:50', 'unique:invoices,invoice_number,' . $invoice->id],
            'customer_id' => ['required', 'exists:customers,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:invoice_date'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pph_type' => ['nullable', 'string', 'in:none,pph_21,pph_23,pph_4_2'],
            'pph_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:draft,sent,partial,paid,overdue,cancelled'],
            'notes' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,zip,doc,docx,xls,xlsx', 'max:10240'],
        ]);

        $subtotal = (float) $validated['subtotal'];
        $taxRate = (float) ($validated['tax_rate'] ?? 11.0);
        $taxAmount = round($subtotal * ($taxRate / 100), 2);

        $pphType = $validated['pph_type'] ?? 'none';
        $pphRate = (float) ($validated['pph_rate'] ?? 0.0);
        $pphAmount = round($subtotal * ($pphRate / 100), 2);

        $totalAmount = $subtotal + $taxAmount;

        $attachmentPath = $invoice->attachment_path;
        if ($request->hasFile('attachment')) {
            if ($attachmentPath && File::exists(public_path($attachmentPath))) {
                File::delete(public_path($attachmentPath));
            }
            $file = $request->file('attachment');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/invoices');
            if (! File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $fileName);
            $attachmentPath = 'uploads/invoices/' . $fileName;
        }

        $invoice->update([
            'invoice_number' => $validated['invoice_number'],
            'customer_id' => $validated['customer_id'],
            'project_id' => $validated['project_id'] ?? null,
            'title' => $validated['title'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'pph_type' => $pphType,
            'pph_rate' => $pphRate,
            'pph_amount' => $pphAmount,
            'total_amount' => $totalAmount,
            'status' => $validated['status'],
            'payment_terms' => $validated['payment_terms'] ?? 'Net 30 Days',
            'notes' => $validated['notes'] ?? null,
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('dashboard-admin.finance.invoices.index')
            ->with('success', "Invoice {$invoice->invoice_number} berhasil diperbarui.");
    }

    /**
     * Record a payment against the invoice.
     */
    public function addPayment(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string', 'max:50'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
            'receipt' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $fileName = 'receipt_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/receipts');
            if (! File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $fileName);
            $receiptPath = 'uploads/receipts/' . $fileName;
        }

        DB::transaction(function () use ($invoice, $validated, $receiptPath) {
            InvoicePayment::create([
                'invoice_id' => $invoice->id,
                'payment_date' => $validated['payment_date'],
                'amount' => (float) $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference_no' => $validated['reference_no'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'receipt_file' => $receiptPath,
                'created_by' => Auth::id(),
            ]);

            $totalPaid = (float) $invoice->payments()->sum('amount');
            $invoice->paid_amount = $totalPaid;

            if ($totalPaid >= (float) $invoice->total_amount) {
                $invoice->status = Invoice::STATUS_PAID;
            } elseif ($totalPaid > 0) {
                $invoice->status = Invoice::STATUS_PARTIAL;
            }

            $invoice->save();
        });

        return redirect()->route('dashboard-admin.finance.invoices.index')
            ->with('success', 'Pembayaran sebesar Rp ' . number_format((float) $validated['amount'], 0, ',', '.') . ' berhasil dicatat.');
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $num = $invoice->invoice_number;

        if ($invoice->attachment_path && File::exists(public_path($invoice->attachment_path))) {
            File::delete(public_path($invoice->attachment_path));
        }

        foreach ($invoice->payments as $payment) {
            if ($payment->receipt_file && File::exists(public_path($payment->receipt_file))) {
                File::delete(public_path($payment->receipt_file));
            }
        }

        $invoice->delete();

        return redirect()->route('dashboard-admin.finance.invoices.index')
            ->with('success', "Invoice {$num} berhasil dihapus.");
    }
}
