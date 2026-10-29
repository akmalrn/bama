@extends('layout.layout')

@php
    $title = 'Finance & Taxes Dashboard';
    $subTitle = 'Finance & Tax';
    $script = '';
@endphp

@section('content')

<style>
    .finance-kpi-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .finance-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
    }
</style>


<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">Departemen Keuangan & Pajak</span>
            <span class="text-secondary-light text-xs">• PT ELBEMA Integra Indonesia</span>
        </div>
        <h5 class="fw-bold mb-0 text-primary-light">Finance & Taxes Executive Dashboard</h5>
        <p class="text-neutral-500 text-sm mb-0">Arus kas terpadu (Cash In vs Cash Out), jadwal aging piutang, hutang vendor, margin proyek, dan kewajiban fiskal.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <form method="GET" action="{{ route('dashboard-admin.finance.dashboard') }}" class="d-flex align-items-center gap-2">
            <select name="year" class="form-select form-select-sm text-sm" onchange="this.form.submit()">
                @for($y = date('Y') + 1; $y >= 2024; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun Anggaran {{ $y }}</option>
                @endfor
            </select>
        </form>
        <a href="{{ route('dashboard-admin.finance.invoices.index') }}" class="btn btn-primary btn-sm px-14 py-8 d-flex align-items-center gap-2">
            <iconify-icon icon="solar:document-add-bold"></iconify-icon>
            <span>Invoice Monitoring</span>
        </a>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative finance-kpi-card">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider {{ $netCashFlow >= 0 ? 'text-success-main' : 'text-danger-main' }}">
                            Net Cash Flow (Bersih)
                        </span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">
                            Rp {{ number_format($netCashFlow, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center {{ $netCashFlow >= 0 ? 'bg-success-100 text-success-main' : 'bg-danger-100 text-danger-main' }} text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Kas Masuk: Rp {{ number_format($totalCashIn, 0, ',', '.') }}</span>
                    <span class="badge {{ $netCashFlow >= 0 ? 'bg-success-50 text-success-700 border border-success-200' : 'bg-danger-50 text-danger-700 border border-danger-200' }}">
                        {{ $netCashFlow >= 0 ? 'Surplus' : 'Defisit' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative finance-kpi-card">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Total Piutang (AR Waiting)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">
                            Rp {{ number_format($totalReceivables, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:hourglass-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Faktur termin tertagih</span>
                    <span class="badge bg-warning-50 text-warning-main border border-warning-200">Komersial AR</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative finance-kpi-card">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-danger-main">Hutang Vendor (AP Unpaid)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">
                            Rp {{ number_format($totalPayables, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-danger-100 text-danger-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:card-send-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Kewajiban bayar rekanan</span>
                    <span class="badge bg-danger-50 text-danger-700 border border-danger-200">Vendor Bills</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative finance-kpi-card">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Net Working Capital</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">
                            Rp {{ number_format($netWorkingCapital, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:chart-square-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Likuiditas: Piutang dikurangi Hutang</span>
                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200">Solvabilitas</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-xl-8 col-lg-7">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
            <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Trend Arus Kas Bulanan (Cash In vs Cash Out) — {{ $year }}</h6>
                    <span class="text-2xs text-secondary-light">Kompilasi penerimaan pembayaran klien vs realisasi beban operasional proyek (Juta Rupiah).</span>
                </div>
                <div class="d-flex align-items-center gap-3 text-xs">
                    <span class="d-flex align-items-center gap-1">
                        <span class="w-10-px h-10-px rounded-circle bg-success-main"></span> Cash In
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span class="w-10-px h-10-px rounded-circle bg-danger-main"></span> Cash Out
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span class="w-10-px h-10-px rounded-circle bg-primary-600"></span> Net Flow
                    </span>
                </div>
            </div>
            <div class="card-body p-20">
                <div id="cashFlowMonthlyChart" style="min-height: 280px;"></div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-4 col-lg-5">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
            <div class="card-header border-bottom py-14 px-20">
                <h6 class="text-sm fw-semibold mb-0 text-primary-light">Jadwal Aging Piutang (AR Aging)</h6>
                <span class="text-2xs text-secondary-light">Klasifikasi keterlambatan pelunasan faktur invoice klien.</span>
            </div>
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                @php
                    $totBuckets = max(1, array_sum($agingBuckets));
                    $pctCurrent = round(($agingBuckets['current'] / $totBuckets) * 100, 1);
                    $pct30 = round(($agingBuckets['days_31_60'] / $totBuckets) * 100, 1);
                    $pct60 = round(($agingBuckets['days_61_90'] / $totBuckets) * 100, 1);
                    $pct90 = round(($agingBuckets['days_over_90'] / $totBuckets) * 100, 1);
                @endphp
                <div class="d-flex flex-column gap-3">
                    
                    <div>
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span class="fw-semibold text-primary-light">Lancar (Current / 0 - 30 Hari)</span>
                            <span class="fw-bold font-monospace text-success-main">Rp {{ number_format($agingBuckets['current'], 0, ',', '.') }}</span>
                        </div>
                        <div class="progress h-6-px bg-neutral-100 radius-4">
                            <div class="progress-bar bg-success-main" style="width: {{ $pctCurrent }}%"></div>
                        </div>
                    </div>

                    
                    <div>
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span class="fw-semibold text-primary-light">Overdue 1 - 30 Hari</span>
                            <span class="fw-bold font-monospace text-warning-main">Rp {{ number_format($agingBuckets['days_31_60'], 0, ',', '.') }}</span>
                        </div>
                        <div class="progress h-6-px bg-neutral-100 radius-4">
                            <div class="progress-bar bg-warning-main" style="width: {{ $pct30 }}%"></div>
                        </div>
                    </div>

                    
                    <div>
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span class="fw-semibold text-primary-light">Overdue 31 - 60 Hari</span>
                            <span class="fw-bold font-monospace text-danger-main">Rp {{ number_format($agingBuckets['days_61_90'], 0, ',', '.') }}</span>
                        </div>
                        <div class="progress h-6-px bg-neutral-100 radius-4">
                            <div class="progress-bar bg-danger-main" style="width: {{ $pct60 }}%"></div>
                        </div>
                    </div>

                    
                    <div>
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span class="fw-semibold text-primary-light">Kritis (> 60 Hari Overdue)</span>
                            <span class="fw-bold font-monospace text-danger-main">Rp {{ number_format($agingBuckets['days_over_90'], 0, ',', '.') }}</span>
                        </div>
                        <div class="progress h-6-px bg-neutral-100 radius-4">
                            <div class="progress-bar bg-danger-800" style="width: {{ $pct90 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200 mt-16 text-2xs text-secondary-light">
                    <iconify-icon icon="solar:info-circle-outline" class="text-primary-600 me-1"></iconify-icon>
                    Gunakan modul <strong>Invoice Monitoring</strong> untuk mengirimkan surat pengingat penagihan resmi kepada PIC klien yang mengalami keterlambatan pembayaran.
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
            <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Efisiensi & Profitabilitas Proyek (Top Gross Margin)</h6>
                    <span class="text-2xs text-secondary-light">Proyek dengan perolehan keuntungan kotor tertinggi.</span>
                </div>
                <a href="{{ route('dashboard-admin.finance.cost.index') }}" class="btn btn-light btn-sm text-xs py-4 px-10">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle text-xs">
                        <thead class="bg-neutral-100">
                            <tr>
                                <th class="py-10 px-16">PROYEK</th>
                                <th class="py-10 px-16 text-end">NILAI KONTRAK</th>
                                <th class="py-10 px-16 text-end">BIAYA AKTUAL</th>
                                <th class="py-10 px-16 text-center">MARGIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projectMargins->take(5) as $pm)
                                <tr>
                                    <td class="px-16 py-10">
                                        <span class="fw-semibold text-primary-light d-block text-truncate" style="max-width: 180px;" title="{{ $pm['project']->name }}">
                                            {{ $pm['project']->name }}
                                        </span>
                                        <small class="text-secondary-light text-2xs">{{ $pm['project']->customer->company_name ?? '-' }}</small>
                                    </td>
                                    <td class="px-16 py-10 text-end font-monospace">Rp {{ number_format($pm['contract_value'], 0, ',', '.') }}</td>
                                    <td class="px-16 py-10 text-end font-monospace text-secondary-light">Rp {{ number_format($pm['actual_cost'], 0, ',', '.') }}</td>
                                    <td class="px-16 py-10 text-center">
                                        <span class="badge {{ $pm['margin_pct'] >= 30 ? 'bg-success-focus text-success-main border border-success-subtle' : 'bg-warning-focus text-warning-main border border-warning-subtle' }} px-8 py-3 radius-4 fw-bold">
                                            {{ $pm['margin_pct'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-24 text-secondary-light">Belum ada data proyek.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
            <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Penagihan Jatuh Tempo Terdekat (Next 14 Days)</h6>
                    <span class="text-2xs text-secondary-light">Prioritas follow-up pelunasan kas masuk minggu ini.</span>
                </div>
                <a href="{{ route('dashboard-admin.finance.invoices.index') }}" class="btn btn-light btn-sm text-xs py-4 px-10">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle text-xs">
                        <thead class="bg-neutral-100">
                            <tr>
                                <th class="py-10 px-16">NO. INVOICE & KLIEN</th>
                                <th class="py-10 px-16 text-center">JATUH TEMPO</th>
                                <th class="py-10 px-16 text-end">SISA PIUTANG</th>
                                <th class="py-10 px-16 text-center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingDueInvoices as $ui)
                                <tr>
                                    <td class="px-16 py-10">
                                        <span class="fw-bold font-monospace text-primary-600 d-block">{{ $ui->invoice_number }}</span>
                                        <small class="text-secondary-light text-2xs">{{ $ui->customer->company_name ?? '-' }}</small>
                                    </td>
                                    <td class="px-16 py-10 text-center">
                                        <span class="fw-semibold text-primary-light">{{ $ui->due_date ? $ui->due_date->format('d M Y') : '-' }}</span>
                                    </td>
                                    <td class="px-16 py-10 text-end font-monospace fw-bold text-danger-main">
                                        Rp {{ number_format($ui->balance, 0, ',', '.') }}
                                    </td>
                                    <td class="px-16 py-10 text-center">
                                        <span class="badge {{ $ui->is_overdue ? 'bg-danger-focus text-danger-main border border-danger-subtle' : 'bg-info-focus text-info-main border border-info-subtle' }} px-8 py-3 radius-4 text-3xs fw-semibold">
                                            {{ $ui->is_overdue ? 'Overdue' : 'Unpaid' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-24 text-secondary-light">Tidak ada invoice jatuh tempo dalam waktu dekat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
            <div class="card-header border-bottom py-14 px-20">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:wallet-money-bold" class="text-success-main text-lg"></iconify-icon>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Transaksi Kas Masuk Terbaru (Collections)</h6>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle text-xs">
                        <thead class="bg-neutral-100">
                            <tr>
                                <th class="py-10 px-16">TANGGAL</th>
                                <th class="py-10 px-16">NO. INVOICE & KLIEN</th>
                                <th class="py-10 px-16 text-end">NOMINAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCashIn as $rc)
                                <tr>
                                    <td class="px-16 py-10 text-secondary-light">{{ $rc->payment_date->format('d M Y') }}</td>
                                    <td class="px-16 py-10">
                                        <span class="fw-bold font-monospace text-primary-light d-block">{{ $rc->invoice->invoice_number ?? '-' }}</span>
                                        <small class="text-secondary-light text-2xs">{{ $rc->invoice->customer->company_name ?? '-' }}</small>
                                    </td>
                                    <td class="px-16 py-10 text-end font-monospace fw-bold text-success-main">
                                        Rp {{ number_format($rc->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-20 text-secondary-light">Belum ada kas masuk tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
            <div class="card-header border-bottom py-14 px-20">
                <div class="d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:card-send-bold" class="text-danger-main text-lg"></iconify-icon>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Realisasi Kas Keluar Terbaru (Project Expenses)</h6>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle text-xs">
                        <thead class="bg-neutral-100">
                            <tr>
                                <th class="py-10 px-16">TANGGAL</th>
                                <th class="py-10 px-16">PROYEK & VENDOR</th>
                                <th class="py-10 px-16 text-end">NOMINAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCashOut as $ro)
                                <tr>
                                    <td class="px-16 py-10 text-secondary-light">{{ $ro->entry_date->format('d M Y') }}</td>
                                    <td class="px-16 py-10">
                                        <span class="fw-bold text-primary-light d-block text-truncate" style="max-width: 180px;" title="{{ $ro->project->name ?? '-' }}">{{ $ro->project->name ?? '-' }}</span>
                                        <small class="text-secondary-light text-2xs">Vendor: {{ $ro->vendor ?? 'Internal' }}</small>
                                    </td>
                                    <td class="px-16 py-10 text-end font-monospace fw-bold text-danger-main">
                                        Rp {{ number_format($ro->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-20 text-secondary-light">Belum ada kas keluar tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartEl = document.getElementById('cashFlowMonthlyChart');
    if (chartEl && typeof ApexCharts !== 'undefined') {
        const options = {
            series: [
                {
                    name: 'Cash In (Kas Masuk)',
                    type: 'column',
                    data: @json($monthlyCashIn)
                },
                {
                    name: 'Cash Out (Kas Keluar)',
                    type: 'column',
                    data: @json($monthlyCashOut)
                },
                {
                    name: 'Net Cash Flow',
                    type: 'line',
                    data: @json($monthlyNetFlow)
                }
            ],
            chart: {
                height: 290,
                type: 'line',
                stacked: false,
                toolbar: { show: false },
                zoom: { enabled: false },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#10b981', '#ef4444', '#3b82f6'],
            stroke: {
                width: [0, 0, 3],
                curve: 'smooth'
            },
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    borderRadius: 4
                }
            },
            dataLabels: { enabled: false },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 2
            },
            xaxis: {
                categories: @json($monthsLabels),
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 500 }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return val.toFixed(0) + ' Jt';
                    },
                    style: { colors: '#94a3b8', fontSize: '11px' }
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return 'Rp ' + (val * 1000000).toLocaleString('id-ID');
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '11px'
            }
        };

        const chart = new ApexCharts(chartEl, options);
        chart.render();
    }
});
</script>
@endsection
