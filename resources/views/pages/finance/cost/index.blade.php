@extends('layout.layout')

@php
    $title = 'Project Cost Management';
    $subTitle = 'Finance & Tax';
    $script = '';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0">Project Cost Management & Budgeting (RAB)</h6>
        <p class="text-neutral-500 text-sm mb-0">Pengendalian anggaran proyek, monitoring realisasi beban 10 kategori, margin keuntungan, dan manajemen tagihan vendor (AP).</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary btn-sm px-16 py-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createCostModal">
            <iconify-icon icon="solar:card-send-bold" class="text-lg"></iconify-icon>
            <span>+ Catat Beban Proyek</span>
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 radius-8 mb-24" role="alert">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl flex-shrink-0"></iconify-icon>
        <div class="text-sm fw-medium">{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show radius-8 mb-24" role="alert">
        <div class="d-flex align-items-center gap-2 mb-1">
            <iconify-icon icon="solar:danger-triangle-bold" class="text-xl flex-shrink-0"></iconify-icon>
            <span class="text-sm fw-bold">Gagal menyimpan data pengeluaran:</span>
        </div>
        <ul class="mb-0 text-xs ps-24">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="row g-3 mb-24">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Approved Budget (RAB)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">Rp {{ number_format($totalApprovedBudget, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:calculator-minimalistic-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Plafond anggaran disetujui</span>
                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200">{{ $projects->count() }} Proyek</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Actual Cost (Realisasi)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">Rp {{ number_format($totalActualCost, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-info-100 text-info-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:dollar-minimalistic-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Biaya operasional terserap</span>
                    <span class="badge bg-info-50 text-info-700 border border-info-200">
                        {{ $totalApprovedBudget > 0 ? round(($totalActualCost / $totalApprovedBudget) * 100, 1) : 0 }}% Terserap
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Hutang Vendor (AP Unpaid)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">Rp {{ number_format($unpaidVendorBills, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Menunggu pelunasan vendor</span>
                    <span class="badge bg-warning-50 text-warning-main border border-warning-200">{{ $unpaidBillsCount }} Tagihan</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Gross Profit Portfolio</span>
                        <h4 class="fw-bold mb-0 mt-2 text-success-main fs-5">{{ $portfolioMarginPct }}%</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:chart-square-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Margin laba kotor proyek</span>
                    <span class="badge bg-success-50 text-success-700 border border-success-200">
                        Rp {{ number_format($portfolioGrossProfit, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm radius-12 mb-24">
    <div class="card-body p-16">
        <ul class="nav nav-pills gap-2">
            <li class="nav-item">
                <a class="nav-link py-8 px-20 text-xs radius-8 {{ $activeTab === 'projects' ? 'active' : '' }}" 
                   href="{{ route('dashboard-admin.finance.cost.index', ['tab' => 'projects']) }}">
                    <iconify-icon icon="solar:folder-with-files-bold" class="me-1 text-sm"></iconify-icon>
                    Analisis Anggaran & Margin per Proyek
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-8 px-20 text-xs radius-8 {{ $activeTab === 'categories' ? 'active' : '' }}" 
                   href="{{ route('dashboard-admin.finance.cost.index', ['tab' => 'categories']) }}">
                    <iconify-icon icon="solar:pie-chart-2-bold" class="me-1 text-sm"></iconify-icon>
                    Alokasi 10 Kategori Biaya
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-8 px-20 text-xs radius-8 {{ $activeTab === 'journal' ? 'active' : '' }}" 
                   href="{{ route('dashboard-admin.finance.cost.index', ['tab' => 'journal']) }}">
                    <iconify-icon icon="solar:bill-list-bold" class="me-1 text-sm"></iconify-icon>
                    Jurnal Pengeluaran & Tagihan Vendor (AP)
                </a>
            </li>
        </ul>
    </div>
</div>

@if($activeTab === 'projects')
    
    <div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
        <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="text-sm fw-semibold mb-0 text-primary-light">Ringkasan Anggaran (RAB), Biaya Aktual, dan Margin Profitabilitas Proyek</h6>
                <span class="text-2xs text-secondary-light">Memantau efisiensi serapan dana proyek dan mendeteksi potensi pembengkakan biaya (cost overrun).</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">NAMA PROYEK & KLIEN</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">NILAI KONTRAK</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">APPROVED RAB</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">REALISASI BEBAN</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">SISA ANGGARAN</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light" width="160">SERAPAN ANGGARAN</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light">GROSS MARGIN</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projectAnalysis as $item)
                            @php
                                $p = $item['project'];
                                $burnRate = $item['burn_rate'];
                                $burnColor = 'bg-success-main';
                                $statusBadge = 'bg-success-focus text-success-600 border border-success-subtle';
                                $statusText = 'Healthy';

                                if ($burnRate > 100) {
                                    $burnColor = 'bg-danger-main';
                                    $statusBadge = 'bg-danger-focus text-danger-main border border-danger-subtle';
                                    $statusText = 'Overrun';
                                } elseif ($burnRate >= 80) {
                                    $burnColor = 'bg-warning-main';
                                    $statusBadge = 'bg-warning-focus text-warning-main border border-warning-subtle';
                                    $statusText = 'Warning';
                                }
                            @endphp
                            <tr>
                                <td class="px-16 py-12">
                                    <span class="fw-semibold text-sm text-primary-light d-block text-truncate" style="max-width: 250px;" title="{{ $p->name }}">
                                        {{ $p->name }}
                                    </span>
                                    <small class="text-2xs text-secondary-light">{{ $p->customer->company_name ?? 'Internal / Klien Umum' }}</small>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-semibold text-sm font-monospace text-primary-light">
                                        Rp {{ number_format($item['contract_value'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-semibold text-sm font-monospace text-primary-600">
                                        Rp {{ number_format($item['budget'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-bold text-sm font-monospace text-primary-light">
                                        Rp {{ number_format($item['actual'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-bold text-sm font-monospace {{ $item['variance'] >= 0 ? 'text-success-main' : 'text-danger-main' }}">
                                        Rp {{ number_format($item['variance'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12">
                                    <div class="d-flex align-items-center justify-content-between text-2xs mb-1">
                                        <span>{{ $burnRate }}%</span>
                                        <span class="text-secondary-light">Burn Rate</span>
                                    </div>
                                    <div class="progress h-6-px bg-neutral-100 radius-4">
                                        <div class="progress-bar {{ $burnColor }}" style="width: {{ min(100, $burnRate) }}%"></div>
                                    </div>
                                </td>
                                <td class="px-16 py-12 text-center">
                                    <span class="fw-bold text-sm font-monospace {{ $item['margin_pct'] >= 30 ? 'text-success-main' : ($item['margin_pct'] > 0 ? 'text-warning-main' : 'text-danger-main') }} d-block">
                                        {{ $item['margin_pct'] }}%
                                    </span>
                                    <small class="text-2xs text-secondary-light">
                                        Rp {{ number_format($item['gross_profit'], 0, ',', '.') }}
                                    </small>
                                </td>
                                <td class="px-16 py-12 text-center">
                                    <span class="badge {{ $statusBadge }} px-8 py-3 radius-4 text-xs fw-semibold">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-32 text-secondary-light">Belum ada proyek terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@elseif($activeTab === 'categories')
    
    <div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
        <div class="card-header border-bottom py-14 px-20">
            <h6 class="text-sm fw-semibold mb-0 text-primary-light">Struktur & Realisasi Anggaran Berdasarkan 10 Kategori Biaya Proyek</h6>
            <span class="text-2xs text-secondary-light">Distribusi biaya operasional teknis standar industri migas & konstruksi rekayasa.</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">KATEGORI BIAYA</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">BUDGET ALOKASI</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">REALISASI AKTUAL</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">SISA ANGGARAN (VARIANCE)</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light" width="220">PERSENTASE SERAPAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categoryBreakdown as $idx => $cat)
                            @php
                                $pct = $cat['burn_pct'];
                                $barColor = $pct > 100 ? 'bg-danger-main' : ($pct >= 80 ? 'bg-warning-main' : 'bg-primary-600');
                            @endphp
                            <tr>
                                <td class="px-16 py-12">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-24-px h-24-px rounded-circle bg-primary-50 text-primary-600 fw-bold d-flex align-items-center justify-content-center text-2xs">
                                            {{ $idx + 1 }}
                                        </span>
                                        <span class="fw-semibold text-sm text-primary-light">{{ $cat['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-semibold text-sm font-monospace text-primary-light">
                                        Rp {{ number_format($cat['budget'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-bold text-sm font-monospace text-info-main">
                                        Rp {{ number_format($cat['actual'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-bold text-sm font-monospace {{ $cat['variance'] >= 0 ? 'text-success-main' : 'text-danger-main' }}">
                                        Rp {{ number_format($cat['variance'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12">
                                    <div class="d-flex align-items-center justify-content-between text-xs mb-1">
                                        <span class="fw-bold text-primary-light">{{ $pct }}%</span>
                                        <span class="text-2xs text-secondary-light">Terserap</span>
                                    </div>
                                    <div class="progress h-6-px bg-neutral-100 radius-4">
                                        <div class="progress-bar {{ $barColor }}" style="width: {{ min(100, $pct) }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@elseif($activeTab === 'journal')
    
    <div class="card border-0 shadow-sm radius-12 mb-24">
        <div class="card-body p-20">
            <form method="GET" action="{{ route('dashboard-admin.finance.cost.index') }}" class="row g-2 align-items-center">
                <input type="hidden" name="tab" value="journal">
                <div class="col-lg-3 col-md-6">
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control form-control-sm ps-36 text-sm" placeholder="Cari vendor, no PO, notes..." value="{{ $search }}">
                        <iconify-icon icon="solar:magnifer-outline" class="position-absolute top-50 start-0 translate-middle-y ms-12 text-secondary-light"></iconify-icon>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <select name="project_id" class="form-select form-select-sm text-sm">
                        <option value="">Semua Proyek</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <select name="category" class="form-select form-select-sm text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categoriesList as $cat)
                            <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <select name="payment_status" class="form-select form-select-sm text-sm">
                        <option value="">Status Bayar</option>
                        <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                        <option value="unpaid" {{ $paymentStatus === 'unpaid' ? 'selected' : '' }}>Unpaid (Hutang AP)</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-16 w-100">Filter</button>
                    @if(request()->anyFilled(['search', 'project_id', 'category', 'payment_status', 'date_start', 'date_end']))
                        <a href="{{ route('dashboard-admin.finance.cost.index', ['tab' => 'journal']) }}" class="btn btn-light btn-sm px-12" title="Reset">
                            <iconify-icon icon="solar:restart-outline"></iconify-icon>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
        <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="text-sm fw-semibold mb-0 text-primary-light">Daftar Jurnal Biaya & Tagihan Rekanan/Vendor (AP)</h6>
            <span class="text-xs text-secondary-light">Menampilkan {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} transaksi</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">TANGGAL</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">PROYEK</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">KATEGORI & VENDOR</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">NO. REF / PO</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">NOMINAL BEBAN</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light">STATUS BAYAR</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light" width="100">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                            <tr>
                                <td class="px-16 py-12">
                                    <span class="fw-semibold text-xs text-primary-light d-block">{{ $t->entry_date->format('d M Y') }}</span>
                                    <small class="badge bg-neutral-100 text-secondary-light border border-neutral-200 text-3xs">{{ strtoupper($t->type) }}</small>
                                </td>
                                <td class="px-16 py-12">
                                    <span class="fw-medium text-xs text-primary-light d-block text-truncate" style="max-width: 200px;" title="{{ $t->project->name ?? '-' }}">
                                        {{ $t->project->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-16 py-12">
                                    <span class="fw-semibold text-xs text-info-main d-block">{{ $t->cost_category }}</span>
                                    <small class="text-2xs text-secondary-light d-block text-truncate" style="max-width: 200px;">Vendor: {{ $t->vendor ?? 'Internal Staff' }}</small>
                                </td>
                                <td class="px-16 py-12">
                                    <span class="fw-medium text-xs font-monospace text-primary-light">{{ $t->reference_no ?? '-' }}</span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-bold text-sm font-monospace text-primary-light d-block">
                                        Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </span>
                                    @if($t->tax_amount > 0)
                                        <small class="text-3xs text-secondary-light">PPN: Rp {{ number_format($t->tax_amount, 0, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td class="px-16 py-12 text-center">
                                    <span class="badge {{ $t->payment_status === 'paid' ? 'bg-success-focus text-success-main border border-success-subtle' : 'bg-warning-focus text-warning-main border border-warning-subtle' }} px-8 py-3 radius-4 text-xs fw-semibold">
                                        {{ $t->payment_status === 'paid' ? 'Paid' : 'Unpaid (AP)' }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @if($t->payment_status !== 'paid')
                                            <form action="{{ route('dashboard-admin.finance.cost.paid', $t->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-32-px h-32-px rounded-circle bg-success-focus text-success-main d-inline-flex justify-content-center align-items-center border-0" title="Tandai Sudah Dibayar (Settle AP)">
                                                    <iconify-icon icon="solar:check-circle-bold"></iconify-icon>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('dashboard-admin.finance.cost.destroy', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus">
                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-32 text-secondary-light">Belum ada transaksi pengeluaran pada filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="px-20 py-16 border-top d-flex justify-content-end">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endif


<div class="modal fade" id="createCostModal" tabindex="-1" aria-labelledby="createCostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light d-flex align-items-center gap-2" id="createCostModalLabel">
                    <iconify-icon icon="solar:card-send-bold" class="text-primary-600 text-xl"></iconify-icon>
                    Input Beban / Biaya Proyek (Project Expense & AP)
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.finance.cost.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Proyek Terkait <span class="text-danger">*</span></label>
                            <select name="project_id" class="form-select text-sm" required>
                                <option value="">Pilih Proyek</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Kategori Biaya (10 Kategori Standar) <span class="text-danger">*</span></label>
                            <select name="cost_category" class="form-select text-sm" required>
                                @foreach($categoriesList as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                            <input type="date" name="entry_date" class="form-control text-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Tipe Biaya <span class="text-danger">*</span></label>
                            <select name="type" class="form-select text-sm" required>
                                <option value="actual" selected>Actual (Realisasi Kas Keluar)</option>
                                <option value="commitment">Commitment (Komitmen PO Terbit)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Status Pembayaran <span class="text-danger">*</span></label>
                            <select name="payment_status" class="form-select text-sm" required>
                                <option value="paid" selected>Paid (Sudah Dibayar)</option>
                                <option value="unpaid">Unpaid (Hutang Vendor / AP)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Nominal Beban (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-sm">Rp</span>
                                <input type="number" step="0.01" name="amount" class="form-control text-sm font-monospace text-end fw-bold" placeholder="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">PPN Masukan (Jika ada Faktur Pajak Vendor)</label>
                            <div class="input-group">
                                <span class="input-group-text text-sm">Rp</span>
                                <input type="number" step="0.01" name="tax_amount" class="form-control text-sm font-monospace text-end" value="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Nama Vendor / Rekanan / Penerima</label>
                            <input type="text" name="vendor" class="form-control text-sm" placeholder="Contoh: PT Sumber Material Teknik">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">No. PO / No. Faktur Tagihan Vendor</label>
                            <input type="text" name="reference_no" class="form-control text-sm" placeholder="Contoh: PO-2026/ELB/009">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Upload Kuitansi / Bukti Bayar / Nota</label>
                            <input type="file" name="attachment" class="form-control form-control-sm text-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Keterangan / Catatan Pekerjaan</label>
                            <input type="text" name="notes" class="form-control form-control-sm text-sm" placeholder="Detail pembelian material...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-light btn-sm px-16" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-20">Simpan Beban Proyek</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
