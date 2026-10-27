<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Project;
use App\Models\ProjectCostEntry;
use App\Models\TaxFiling;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceDashboardController extends Controller
{
    /**
     * Display the Executive Finance & Taxes Dashboard.
     */
    public function index(Request $request): View
    {
        $year = (int) $request->query('year', date('Y'));
        $now = Carbon::now();

        // 1. High-Level KPI Calculations
        $totalInvoiced = (float) Invoice::whereYear('invoice_date', $year)->sum('total_amount');
        $totalCashIn = (float) InvoicePayment::whereYear('payment_date', $year)->sum('amount');
        
        $totalCashOut = (float) ProjectCostEntry::whereYear('entry_date', $year)
            ->where('payment_status', 'paid')
            ->sum('amount');

        $netCashFlow = $totalCashIn - $totalCashOut;

        // Total Outstanding AR (Piutang Klien)
        $allInvoices = Invoice::all();
        $totalReceivables = (float) $allInvoices->sum(function (Invoice $inv) {
            return $inv->balance;
        });

        // Total Payables AP (Hutang Vendor Unpaid)
        $totalPayables = (float) ProjectCostEntry::where('payment_status', 'unpaid')->sum('amount');

        // Net Working Capital
        $netWorkingCapital = $totalReceivables - $totalPayables;

        // Tax Status
        $outputTaxYear = (float) Invoice::whereYear('invoice_date', $year)->sum('tax_amount');
        $inputTaxYear = (float) ProjectCostEntry::whereYear('entry_date', $year)->sum('tax_amount');
        $netPpnYear = $outputTaxYear - $inputTaxYear;

        // 2. 12-Month Cash In vs Cash Out Trend (For ApexChart)
        $monthsLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyCashIn = [];
        $monthlyCashOut = [];
        $monthlyNetFlow = [];

        for ($m = 1; $m <= 12; $m++) {
            $in = (float) InvoicePayment::whereYear('payment_date', $year)->whereMonth('payment_date', $m)->sum('amount');
            $out = (float) ProjectCostEntry::whereYear('entry_date', $year)
                ->whereMonth('entry_date', $m)
                ->where('payment_status', 'paid')
                ->sum('amount');

            $monthlyCashIn[] = round($in / 1000000, 2); // in Millions (Juta Rupiah)
            $monthlyCashOut[] = round($out / 1000000, 2);
            $monthlyNetFlow[] = round(($in - $out) / 1000000, 2);
        }

        // 3. AR Aging Analysis (Usia Piutang Belum Tertagih)
        $unpaidInvoices = Invoice::with('customer')
            ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_CANCELLED])
            ->get();

        $agingBuckets = [
            'current' => 0.0, // Belum jatuh tempo / 0-30 hari
            'days_31_60' => 0.0, // Overdue 1-30 hari
            'days_61_90' => 0.0, // Overdue 31-60 hari
            'days_over_90' => 0.0, // Overdue > 60 hari
        ];

        foreach ($unpaidInvoices as $inv) {
            $bal = (float) $inv->balance;
            if ($bal <= 0) {
                continue;
            }

            if (! $inv->due_date || $inv->due_date->isFuture()) {
                $agingBuckets['current'] += $bal;
            } else {
                $daysOver = (int) $inv->due_date->diffInDays($now);
                if ($daysOver <= 30) {
                    $agingBuckets['days_31_60'] += $bal;
                } elseif ($daysOver <= 60) {
                    $agingBuckets['days_61_90'] += $bal;
                } else {
                    $agingBuckets['days_over_90'] += $bal;
                }
            }
        }

        // 4. Top Projects by Profitability & Margin
        $projects = Project::with(['customer', 'costEntries'])->get();
        $projectMargins = $projects->map(function (Project $p) {
            $contractVal = (float) $p->contract_value;
            $actualCost = (float) $p->costEntries()->where('type', ProjectCostEntry::TYPE_ACTUAL)->sum('amount');
            if ($actualCost <= 0) {
                $actualCost = (float) $p->actual_cost;
            }

            $grossProfit = $contractVal - $actualCost;
            $marginPct = $contractVal > 0 ? round(($grossProfit / $contractVal) * 100, 1) : 0;

            return [
                'project' => $p,
                'contract_value' => $contractVal,
                'actual_cost' => $actualCost,
                'gross_profit' => $grossProfit,
                'margin_pct' => $marginPct,
            ];
        })->sortByDesc('margin_pct')->values();

        // 5. Upcoming Invoices Due (Next 14 Days)
        $upcomingDueInvoices = Invoice::with(['customer', 'project'])
            ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_CANCELLED])
            ->whereNotNull('due_date')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // 6. Recent Cash Transactions Feed (Latest 5 Inflows & 5 Outflows)
        $recentCashIn = InvoicePayment::with(['invoice.customer'])
            ->latest('payment_date')
            ->take(5)
            ->get();

        $recentCashOut = ProjectCostEntry::with(['project'])
            ->where('payment_status', 'paid')
            ->latest('entry_date')
            ->take(5)
            ->get();

        return view('pages.finance.dashboard.index', compact(
            'year',
            'totalInvoiced',
            'totalCashIn',
            'totalCashOut',
            'netCashFlow',
            'totalReceivables',
            'totalPayables',
            'netWorkingCapital',
            'outputTaxYear',
            'inputTaxYear',
            'netPpnYear',
            'monthsLabels',
            'monthlyCashIn',
            'monthlyCashOut',
            'monthlyNetFlow',
            'agingBuckets',
            'projectMargins',
            'upcomingDueInvoices',
            'recentCashIn',
            'recentCashOut'
        ));
    }
}
