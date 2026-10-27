<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ProjectCostEntry;
use App\Models\TaxFiling;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class FinanceTaxController extends Controller
{
    /**
     * Display corporate finance tax management, PPN reconciliation & withholding taxes.
     */
    public function index(Request $request): View
    {
        $year = (int) $request->query('year', date('Y'));
        $month = $request->query('month');
        $activeTab = $request->query('tab', 'ppn'); // ppn, pph, filings

        // 1. Cross-module PPN Aggregations (Full Year or Selected Month)
        $invoicePpnQuery = Invoice::whereYear('invoice_date', $year);
        $costPpnQuery = ProjectCostEntry::whereYear('entry_date', $year);

        if ($month) {
            $invoicePpnQuery->whereMonth('invoice_date', (int) $month);
            $costPpnQuery->whereMonth('entry_date', (int) $month);
        }

        $totalOutputTax = (float) (clone $invoicePpnQuery)->sum('tax_amount'); // PPN Keluaran
        $totalInputTax = (float) (clone $costPpnQuery)->sum('tax_amount'); // PPN Masukan
        $netPpnPayable = $totalOutputTax - $totalInputTax; // Kurang / Lebih Bayar

        // 2. Withholding Tax Aggregations (PPh 23 & PPh 4(2))
        $pphKreditClient = (float) (clone $invoicePpnQuery)->where('pph_type', '!=', 'none')->sum('pph_amount');
        $pphPotongVendor = (float) (clone $costPpnQuery)->sum('pph_amount');

        // 3. Tax Filings KPI
        $filingsQuery = TaxFiling::where('tax_period_year', $year);
        if ($month) {
            $filingsQuery->where('tax_period_month', (int) $month);
        }
        $filings = (clone $filingsQuery)->orderBy('tax_period_month', 'desc')->paginate(15)->withQueryString();

        $totalTaxPaid = (float) TaxFiling::where('tax_period_year', $year)
            ->whereIn('status', [TaxFiling::STATUS_PAID, TaxFiling::STATUS_REPORTED])
            ->sum('tax_due');
        $totalReportedCount = TaxFiling::where('tax_period_year', $year)
            ->where('status', TaxFiling::STATUS_REPORTED)
            ->count();

        // 4. Monthly PPN Reconciliation Table (Jan - Dec)
        $monthlyPpn = [];
        $monthsName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        for ($m = 1; $m <= 12; $m++) {
            $mInvoices = Invoice::whereYear('invoice_date', $year)->whereMonth('invoice_date', $m);
            $mCosts = ProjectCostEntry::whereYear('entry_date', $year)->whereMonth('entry_date', $m);

            $dppSales = (float) (clone $mInvoices)->sum('subtotal');
            $pk = (float) (clone $mInvoices)->sum('tax_amount');

            $dppPurchases = (float) (clone $mCosts)->where('tax_amount', '>', 0)->sum('amount');
            $pm = (float) (clone $mCosts)->sum('tax_amount');

            $net = $pk - $pm;

            // Check if filing exists for this month
            $filing = TaxFiling::where('tax_period_year', $year)
                ->where('tax_period_month', $m)
                ->where('tax_type', TaxFiling::TYPE_PPN)
                ->first();

            $monthlyPpn[] = [
                'month_num' => $m,
                'month_name' => $monthsName[$m],
                'dpp_sales' => $dppSales,
                'output_tax' => $pk,
                'dpp_purchases' => $dppPurchases,
                'input_tax' => $pm,
                'net_amount' => $net,
                'status' => $filing ? $filing->status : ($net > 0 ? 'unfiled' : 'nihil'),
                'filing' => $filing,
            ];
        }

        // 5. Withholding Tax Transactions (Invoices with PPh & Costs with PPh)
        $withholdingInvoices = Invoice::with(['customer', 'project'])
            ->whereYear('invoice_date', $year)
            ->where('pph_type', '!=', 'none')
            ->latest('invoice_date')
            ->get();

        $withholdingCosts = ProjectCostEntry::with(['project'])
            ->whereYear('entry_date', $year)
            ->where('pph_amount', '>', 0)
            ->latest('entry_date')
            ->get();

        return view('pages.finance.tax.index', compact(
            'year',
            'month',
            'activeTab',
            'totalOutputTax',
            'totalInputTax',
            'netPpnPayable',
            'pphKreditClient',
            'pphPotongVendor',
            'totalTaxPaid',
            'totalReportedCount',
            'monthlyPpn',
            'withholdingInvoices',
            'withholdingCosts',
            'filings'
        ));
    }

    /**
     * Store tax payment or SPT filing.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tax_period_month' => ['required', 'integer', 'between:1,12'],
            'tax_period_year' => ['required', 'integer', 'min:2020', 'max:2035'],
            'tax_type' => ['required', 'string', 'in:ppn,pph_21,pph_23,pph_4_2,pph_badan'],
            'gross_base_amount' => ['nullable', 'numeric', 'min:0'],
            'output_tax' => ['nullable', 'numeric', 'min:0'],
            'input_tax' => ['nullable', 'numeric', 'min:0'],
            'tax_due' => ['required', 'numeric', 'min:0'],
            'ntpn' => ['nullable', 'string', 'max:50'],
            'payment_date' => ['nullable', 'date'],
            'reporting_date' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:draft,paid,reported'],
            'bpe_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'bpe_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $bpePath = null;
        if ($request->hasFile('bpe_file')) {
            $file = $request->file('bpe_file');
            $fileName = 'bpe_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('uploads/taxes');
            if (! File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $fileName);
            $bpePath = 'uploads/taxes/' . $fileName;
        }

        TaxFiling::create([
            'tax_period_month' => $validated['tax_period_month'],
            'tax_period_year' => $validated['tax_period_year'],
            'tax_type' => $validated['tax_type'],
            'gross_base_amount' => (float) ($validated['gross_base_amount'] ?? 0),
            'output_tax' => (float) ($validated['output_tax'] ?? 0),
            'input_tax' => (float) ($validated['input_tax'] ?? 0),
            'tax_due' => (float) $validated['tax_due'],
            'ntpn' => $validated['ntpn'] ?? null,
            'payment_date' => $validated['payment_date'] ?? null,
            'reporting_date' => $validated['reporting_date'] ?? null,
            'status' => $validated['status'],
            'bpe_number' => $validated['bpe_number'] ?? null,
            'bpe_file' => $bpePath,
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('dashboard-admin.finance.tax.index', ['tab' => 'filings'])
            ->with('success', 'Data pelaporan pajak & NTPN berhasil disimpan.');
    }

    /**
     * Remove tax filing record.
     */
    public function destroy(TaxFiling $tax): RedirectResponse
    {
        if ($tax->bpe_file && File::exists(public_path($tax->bpe_file))) {
            File::delete(public_path($tax->bpe_file));
        }

        $tax->delete();

        return redirect()->route('dashboard-admin.finance.tax.index', ['tab' => 'filings'])
            ->with('success', 'Arsip pelaporan pajak berhasil dihapus.');
    }
}
