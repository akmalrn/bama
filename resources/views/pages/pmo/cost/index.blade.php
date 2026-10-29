@extends('layout.layout')

@php
    $title = 'Cost Management';
    $subTitle = 'Project Management Office — Budget Control, Actual Costs, Commitments & Ledger';
@endphp

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-24">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">PMO Controls</span>
            <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-8 py-2 text-xs fw-semibold">Cost Control &amp; Ledger</span>
        </div>
        <h4 class="mb-4 fw-bold text-primary-light">Cost Management</h4>
        <p class="text-secondary-light mb-0 text-sm">
            Kontrol biaya portfolio, kinerja per kategori pengeluaran (10 Kategori), dan pencatatan transaksi biaya proyek.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#newCostTransactionModal">
            <iconify-icon icon="solar:add-circle-bold-duotone" class="text-base"></iconify-icon>
            + Transaksi Biaya
        </button>
        <a href="{{ route('dashboard-admin.pmo.dashboard') }}" class="btn btn-outline-secondary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold">
            <iconify-icon icon="solar:arrow-left-outline" class="text-base"></iconify-icon>
            PMO Dashboard
        </a>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.pmo.cost.index') }}" class="d-flex flex-wrap align-items-end justify-content-between gap-3">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div>
                    <label class="form-label text-xs fw-bold text-secondary-light mb-4">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-sm radius-8" style="min-width: 150px;">
                </div>
                <div>
                    <label class="form-label text-xs fw-bold text-secondary-light mb-4">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-sm radius-8" style="min-width: 150px;">
                </div>
                <div class="pt-20">
                    <button type="submit" class="btn btn-sm btn-primary px-16 radius-8">Terapkan Filter</button>
                    <a href="{{ route('dashboard-admin.pmo.cost.index', ['tab' => $activeTab]) }}" class="btn btn-sm btn-outline-secondary px-12 radius-8 ms-1">Reset</a>
                </div>
            </div>

            <div class="text-xs text-secondary-light">
                Snapshot Periode: <strong class="text-primary-light">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
            </div>
        </form>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-body p-0 px-20">
        <ul class="nav bordered-tab border-bottom-0 d-flex flex-wrap nav-pills mb-0" id="costTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 py-16 px-20 {{ $activeTab === 'management' ? 'active' : '' }}" href="{{ route('dashboard-admin.pmo.cost.index', ['tab' => 'management', 'start_date' => $startDate, 'end_date' => $endDate]) }}">
                    <iconify-icon icon="solar:chart-2-bold-duotone" class="text-xl"></iconify-icon>
                    <span>Dashboard Portfolio</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 py-16 px-20 {{ $activeTab === 'projects' ? 'active' : '' }}" href="{{ route('dashboard-admin.pmo.cost.index', ['tab' => 'projects', 'start_date' => $startDate, 'end_date' => $endDate]) }}">
                    <iconify-icon icon="solar:folder-with-files-bold-duotone" class="text-xl"></iconify-icon>
                    <span>Biaya per Project</span>
                    <span class="badge {{ $activeTab === 'projects' ? 'bg-primary-50 text-primary-600 border border-primary-200' : 'bg-neutral-100 text-secondary-light' }} rounded-pill px-8 py-2 text-xs">{{ $projects->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 py-16 px-20 {{ $activeTab === 'transactions' ? 'active' : '' }}" href="{{ route('dashboard-admin.pmo.cost.index', ['tab' => 'transactions', 'start_date' => $startDate, 'end_date' => $endDate]) }}">
                    <iconify-icon icon="solar:bill-list-bold-duotone" class="text-xl"></iconify-icon>
                    <span>Transaction Register / Ledger</span>
                </a>
            </li>
        </ul>
    </div>
</div>

@if ($activeTab === 'management')
    
    <div class="row g-3 mb-24">
        
        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card radius-12 border-0 shadow-sm h-100 p-16 transition-all">
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light">Approved Budget</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-lg flex-shrink-0">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="mb-8">
                    <h5 class="fw-bold text-primary-light mb-0 text-nowrap" style="letter-spacing: -0.3px;" title="Rp {{ number_format($totalBudget, 0, ',', '.') }}">
                        Rp {{ number_format($totalBudget / 1000000000, 2, ',', '.') }} M
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Portfolio Total BAC</span>
                    <span class="badge bg-primary-50 text-primary-700 px-6 py-2 radius-4 text-xs fw-semibold">Baseline</span>
                </div>
            </div>
        </div>

        
        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card radius-12 border-0 shadow-sm h-100 p-16 transition-all">
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-bold text-uppercase tracking-wider text-danger-main">Actual Cost</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-danger-50 text-danger-600 text-lg flex-shrink-0">
                        <iconify-icon icon="solar:card-send-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="mb-8">
                    <h5 class="fw-bold text-danger-main mb-0 text-nowrap" style="letter-spacing: -0.3px;" title="Rp {{ number_format($totalActualCost, 0, ',', '.') }}">
                        Rp {{ number_format($totalActualCost / 1000000000, 2, ',', '.') }} M
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>{{ $totalBudget > 0 ? round(($totalActualCost / $totalBudget) * 100, 1) : 0 }}% dari budget</span>
                    <span class="badge bg-danger-50 text-danger-700 px-6 py-2 radius-4 text-xs fw-semibold">AC</span>
                </div>
            </div>
        </div>

        
        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card radius-12 border-0 shadow-sm h-100 p-16 transition-all">
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Committed Cost</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-warning-50 text-warning-main text-lg flex-shrink-0">
                        <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="mb-8">
                    <h5 class="fw-bold text-warning-main mb-0 text-nowrap" style="letter-spacing: -0.3px;" title="Rp {{ number_format($totalCommittedCost, 0, ',', '.') }}">
                        Rp {{ number_format($totalCommittedCost / 1000000000, 2, ',', '.') }} M
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Open PO &amp; Komitmen</span>
                    <span class="badge bg-warning-50 text-warning-700 px-6 py-2 radius-4 text-xs fw-semibold">PO Open</span>
                </div>
            </div>
        </div>

        
        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card radius-12 border-0 shadow-sm h-100 p-16 transition-all">
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Total Forecast</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-info-50 text-info-main text-lg flex-shrink-0">
                        <iconify-icon icon="solar:chart-square-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="mb-8">
                    <h5 class="fw-bold text-info-main mb-0 text-nowrap" style="letter-spacing: -0.3px;" title="Rp {{ number_format($totalCostForecast, 0, ',', '.') }}">
                        Rp {{ number_format($totalCostForecast / 1000000000, 2, ',', '.') }} M
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Actual + Committed</span>
                    <span class="badge bg-info-50 text-info-700 px-6 py-2 radius-4 text-xs fw-semibold">EAC</span>
                </div>
            </div>
        </div>

        
        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card radius-12 border-0 shadow-sm h-100 p-16 transition-all">
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-bold text-uppercase tracking-wider {{ $totalVariance >= 0 ? 'text-success-main' : 'text-danger-main' }}">Cost Variance</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center {{ $totalVariance >= 0 ? 'bg-success-50 text-success-main' : 'bg-danger-50 text-danger-600' }} text-lg flex-shrink-0">
                        <iconify-icon icon="solar:shield-check-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="mb-8">
                    <h5 class="fw-bold {{ $totalVariance >= 0 ? 'text-success-main' : 'text-danger-main' }} mb-0 text-nowrap" style="letter-spacing: -0.3px;" title="Rp {{ number_format($totalVariance, 0, ',', '.') }}">
                        Rp {{ number_format($totalVariance / 1000000000, 2, ',', '.') }} M
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>{{ $totalVariance >= 0 ? 'Under Budget' : 'Over Budget' }}</span>
                    <span class="badge {{ $totalVariance >= 0 ? 'bg-success-50 text-success-700' : 'bg-danger-50 text-danger-700' }} px-6 py-2 radius-4 text-xs fw-semibold">
                        {{ $totalVariance >= 0 ? 'Hemat' : 'Defisit' }}
                    </span>
                </div>
            </div>
        </div>

        
        <div class="col-xxl-2 col-xl-4 col-md-4 col-sm-6">
            <div class="card radius-12 border-0 shadow-sm h-100 p-16 transition-all">
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-bold text-uppercase tracking-wider text-purple-main">Portfolio CPI</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-purple-50 text-purple-main text-lg flex-shrink-0">
                        <iconify-icon icon="solar:graph-up-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="mb-8">
                    <h5 class="fw-bold {{ $portfolioCpi >= 1.0 ? 'text-success-main' : 'text-danger-main' }} mb-0 text-nowrap" style="letter-spacing: -0.3px;">
                        {{ number_format($portfolioCpi, 2) }}
                    </h5>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>{{ $portfolioCpi >= 1.0 ? 'Efisien (>= 1.0)' : 'Perlu Pengawasan' }}</span>
                    <span class="badge {{ $portfolioCpi >= 1.0 ? 'bg-success-50 text-success-700' : 'bg-danger-50 text-danger-700' }} px-6 py-2 radius-4 text-xs fw-semibold">
                        {{ $portfolioCpi >= 1.0 ? 'On Track' : 'Alert' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-24">
        <div class="col-lg-12">
            <div class="card radius-12 border-0 shadow-sm p-20">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-20 gap-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span class="w-8-px h-8-px radius-circle bg-success-main"></span>
                            <h6 class="fw-bold text-primary-light mb-0">Invoice &amp; Cash Collection Monitoring</h6>
                        </div>
                        <p class="text-xs text-secondary-light mb-0">Realisasi penagihan termin proyek ke customer versus penerimaan kas riil.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-xs text-secondary-light">Collection Rate:</span>
                        <span class="badge bg-success-50 text-success-700 border border-success-200 px-12 py-4 text-xs fw-bold radius-8 d-inline-flex align-items-center gap-1">
                            <iconify-icon icon="solar:check-circle-bold-duotone" class="text-sm"></iconify-icon>
                            {{ $collectionRate }}%
                        </span>
                    </div>
                </div>

                <div class="row g-3 text-center mb-20">
                    <div class="col-md-4">
                        <div class="p-16 bg-neutral-50 border border-neutral-200 radius-12 text-start transition-all">
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <span class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light">Invoice Submitted</span>
                                <div class="w-28-px h-28-px radius-6 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-sm">
                                    <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                                </div>
                            </div>
                            <h5 class="fw-bold text-primary-light mb-4 text-nowrap" style="letter-spacing: -0.3px;">
                                Rp {{ number_format($totalSubmitted, 0, ',', '.') }}
                            </h5>
                            <span class="text-xs text-secondary-light">Total penagihan termin diterbitkan</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-16 bg-success-50 border border-success-200 radius-12 text-start transition-all">
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <span class="text-xs fw-bold text-uppercase tracking-wider text-success-700">Cash Collected (Diterima)</span>
                                <div class="w-28-px h-28-px radius-6 d-flex justify-content-center align-items-center bg-success-600 text-white text-sm">
                                    <iconify-icon icon="solar:hand-money-bold-duotone"></iconify-icon>
                                </div>
                            </div>
                            <h5 class="fw-bold text-success-700 mb-4 text-nowrap" style="letter-spacing: -0.3px;">
                                Rp {{ number_format($totalPaid, 0, ',', '.') }}
                            </h5>
                            <span class="text-xs text-success-700 fw-semibold">{{ $collectionRate }}% kas telah masuk ke rekening</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-16 bg-warning-50 border border-warning-200 radius-12 text-start transition-all">
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-700">Outstanding AR (Piutang)</span>
                                <div class="w-28-px h-28-px radius-6 d-flex justify-content-center align-items-center bg-warning-main text-white text-sm">
                                    <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                                </div>
                            </div>
                            <h5 class="fw-bold text-warning-700 mb-4 text-nowrap" style="letter-spacing: -0.3px;">
                                Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
                            </h5>
                            <span class="text-xs text-warning-700 fw-semibold">{{ round(100 - $collectionRate, 1) }}% saldo invoice belum lunas</span>
                        </div>
                    </div>
                </div>

                
                <div class="bg-neutral-50 p-12 radius-8 border border-neutral-200">
                    <div class="d-flex justify-content-between align-items-center text-xs mb-6">
                        <span class="fw-semibold text-secondary-light">Collection Progress</span>
                        <span class="fw-bold text-success-600">{{ $collectionRate }}% (Rp {{ number_format($totalPaid, 0, ',', '.') }})</span>
                    </div>
                    <div class="progress radius-8" style="height: 10px; background-color: #e2e8f0;">
                        <div class="progress-bar bg-success-main radius-8" role="progressbar" style="width: {{ min(100, $collectionRate) }}%" aria-valuenow="{{ $collectionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-xs text-secondary-light mt-6">
                        <span>Awal Proyek (0%)</span>
                        <span>Outstanding: Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</span>
                        <span>Target Pelunasan (100%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
        <div class="card-header bg-transparent py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h6 class="text-lg fw-bold text-primary-light mb-1">Performance per Kategori Biaya (10 Cost Categories)</h6>
                <p class="text-xs text-secondary-light mb-0">Distribusi alokasi anggaran, komitmen terbuka, pengeluaran riil, dan variance per kategori biaya.</p>
            </div>
            <span class="badge bg-neutral-100 text-secondary-light text-xs px-10 py-4 radius-6">10 Kategori Standar</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="ps-24 py-16 text-xs fw-bold text-uppercase">Cost Category</th>
                            <th class="text-end text-nowrap py-16 text-xs fw-bold text-uppercase">Approved Budget</th>
                            <th class="text-end text-nowrap py-16 text-xs fw-bold text-uppercase">Committed</th>
                            <th class="text-end text-nowrap py-16 text-xs fw-bold text-uppercase">Actual Cost</th>
                            <th class="text-end text-nowrap py-16 text-xs fw-bold text-uppercase">Variance</th>
                            <th class="text-center text-nowrap pe-24 py-16 text-xs fw-bold text-uppercase">Status / CPI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categoryBreakdown as $cat)
                            <tr>
                                <td class="ps-24 py-14 fw-semibold text-primary-light">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="w-8-px h-8-px radius-circle bg-primary-600"></span>
                                        {{ $cat['name'] }}
                                    </div>
                                </td>
                                <td class="text-end text-nowrap py-14 fw-medium text-secondary-light">Rp {{ number_format($cat['budget'], 0, ',', '.') }}</td>
                                <td class="text-end text-nowrap py-14 text-warning-main fw-medium">Rp {{ number_format($cat['committed'], 0, ',', '.') }}</td>
                                <td class="text-end text-nowrap py-14 text-danger-main fw-semibold">Rp {{ number_format($cat['actual'], 0, ',', '.') }}</td>
                                <td class="text-end text-nowrap py-14 {{ $cat['variance'] >= 0 ? 'text-success-main' : 'text-danger-main' }} fw-bold">
                                    Rp {{ number_format($cat['variance'], 0, ',', '.') }}
                                </td>
                                <td class="text-center text-nowrap pe-24 py-14">
                                    <span class="badge {{ $cat['cpi'] >= 1.0 ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }} px-12 py-4 rounded-pill fw-medium text-xs">
                                        CPI {{ number_format($cat['cpi'], 2) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@elseif ($activeTab === 'projects')
    
    <div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
        <div class="card-header bg-transparent py-16 px-24 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h6 class="text-lg fw-bold text-primary-light mb-1">Project Cost Register &amp; EVM Breakdown</h6>
                <p class="text-xs text-secondary-light mb-0">Tabel kontrol biaya, efisiensi CPI, dan sisa anggaran untuk setiap proyek yang terdaftar.</p>
            </div>
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-10 py-4 text-xs fw-semibold">{{ $projects->count() }} Proyek Terdaftar</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="ps-24 py-16 text-xs fw-bold text-uppercase">Project / SO</th>
                            <th class="py-16 text-xs fw-bold text-uppercase">Customer &amp; Lokasi</th>
                            <th class="text-end text-nowrap py-16 text-xs fw-bold text-uppercase">Nilai Kontrak</th>
                            <th class="text-end text-nowrap py-16 text-xs fw-bold text-uppercase">Budget (BAC)</th>
                            <th class="text-end text-nowrap py-16 text-xs fw-bold text-uppercase">Actual Cost (AC)</th>
                            <th class="text-end text-nowrap py-16 text-xs fw-bold text-uppercase">Committed</th>
                            <th class="text-end text-nowrap py-16 text-xs fw-bold text-uppercase">Variance</th>
                            <th class="text-center text-nowrap py-16 text-xs fw-bold text-uppercase">CPI</th>
                            <th class="text-center text-nowrap pe-24 py-16 text-xs fw-bold text-uppercase" width="90">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            @php
                                $projBudget = (float) $project->budget;
                                $projActual = (float) $project->actual_cost;
                                $projCommitted = $project->committed_cost;
                                $projVariance = $projBudget - ($projActual + $projCommitted);
                                $projCpi = $project->cpi;
                            @endphp
                            <tr>
                                <td class="ps-24 py-14">
                                    <a href="{{ route('dashboard-admin.pmo.projects.show', $project->id) }}" class="fw-bold text-primary-600 hover-text-primary text-sm d-block mb-1">
                                        {{ $project->title }}
                                    </a>
                                    <span class="badge bg-neutral-100 text-secondary-light font-monospace text-xs">{{ $project->project_code }}</span>
                                </td>
                                <td class="py-14">
                                    <span class="fw-semibold text-primary-light d-block text-sm">{{ $project->customer->name ?? '-' }}</span>
                                    <span class="text-xs text-secondary-light d-flex align-items-center gap-1 mt-1">
                                        <iconify-icon icon="solar:map-point-bold-duotone" class="text-danger-main text-xs"></iconify-icon>
                                        {{ $project->location ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap py-14 fw-semibold text-primary-light">
                                    Rp {{ number_format($project->contract_value, 0, ',', '.') }}
                                </td>
                                <td class="text-end text-nowrap py-14 fw-medium text-secondary-light">
                                    Rp {{ number_format($projBudget, 0, ',', '.') }}
                                </td>
                                <td class="text-end text-nowrap py-14 text-danger-main fw-semibold">
                                    Rp {{ number_format($projActual, 0, ',', '.') }}
                                </td>
                                <td class="text-end text-nowrap py-14 text-warning-main fw-medium">
                                    Rp {{ number_format($projCommitted, 0, ',', '.') }}
                                </td>
                                <td class="text-end text-nowrap py-14 {{ $projVariance >= 0 ? 'text-success-main' : 'text-danger-main' }} fw-bold">
                                    Rp {{ number_format($projVariance, 0, ',', '.') }}
                                </td>
                                <td class="text-center text-nowrap py-14">
                                    <span class="badge {{ $projCpi >= 1.0 ? 'bg-success-focus text-success-main' : 'bg-danger-focus text-danger-main' }} px-12 py-4 rounded-pill fw-semibold text-xs">
                                        CPI {{ number_format($projCpi, 2) }}
                                    </span>
                                </td>
                                <td class="text-center text-nowrap pe-24 py-14">
                                    <a href="{{ route('dashboard-admin.pmo.projects.show', $project->id) }}" class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center" title="Detail Proyek">
                                        <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-40 text-secondary-light">Belum ada proyek terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    
    <div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
        <div class="card-header bg-transparent py-16 px-24 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h6 class="text-lg fw-bold text-primary-light mb-1">Transaction Ledger &amp; Cost Journal</h6>
                <p class="text-xs text-secondary-light mb-0">Catatan mutasi pengeluaran riil dan komitmen PO per proyek.</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <form method="GET" action="{{ route('dashboard-admin.pmo.cost.index') }}" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="tab" value="transactions">
                    <select name="project_id" class="form-select form-select-sm radius-8" onchange="this.form.submit()" style="min-width: 220px;">
                        <option value="all">Semua Proyek</option>
                        @foreach ($allProjects as $p)
                            <option value="{{ $p->id }}" {{ $projectIdFilter == $p->id ? 'selected' : '' }}>
                                {{ $p->project_code }} — {{ Str::limit($p->title, 25) }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <button type="button" class="btn btn-sm btn-primary px-14 py-8 radius-8 d-inline-flex align-items-center gap-1 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#newCostTransactionModal">
                    <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                    Catat Biaya
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="ps-24 py-16 text-xs fw-bold text-uppercase">Tanggal</th>
                            <th class="py-16 text-xs fw-bold text-uppercase">Project</th>
                            <th class="py-16 text-xs fw-bold text-uppercase">Kategori Biaya</th>
                            <th class="py-16 text-xs fw-bold text-uppercase">Deskripsi / Subkategori</th>
                            <th class="text-center py-16 text-xs fw-bold text-uppercase">Tipe</th>
                            <th class="text-end text-nowrap py-16 text-xs fw-bold text-uppercase">Nominal</th>
                            <th class="py-16 text-xs fw-bold text-uppercase">Vendor / Ref</th>
                            <th class="text-center pe-24 py-16 text-xs fw-bold text-uppercase" width="80">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $tx)
                            <tr>
                                <td class="ps-24 py-14 text-secondary-light text-nowrap text-sm">{{ \Carbon\Carbon::parse($tx->entry_date)->format('d M Y') }}</td>
                                <td class="py-14">
                                    <strong class="text-primary-light d-block text-sm">{{ $tx->project->title ?? '-' }}</strong>
                                    <span class="badge bg-neutral-100 text-secondary-light font-monospace text-xs">{{ $tx->project->project_code ?? '' }}</span>
                                </td>
                                <td class="py-14"><span class="badge bg-primary-50 text-primary-700 border border-primary-200 px-8 py-2 text-xs">{{ $tx->cost_category }}</span></td>
                                <td class="py-14 text-sm">{{ $tx->cost_subcategory ?: ($tx->notes ?: '-') }}</td>
                                <td class="text-center py-14">
                                    <span class="badge {{ $tx->type === 'actual' ? 'bg-danger-focus text-danger-main' : 'bg-warning-focus text-warning-main' }} px-12 py-4 rounded-pill text-xs fw-semibold text-uppercase">
                                        {{ $tx->type }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap py-14 fw-bold text-primary-light">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                <td class="py-14 text-xs">
                                    <span class="d-block fw-medium text-primary-light">{{ $tx->vendor ?: '-' }}</span>
                                    <span class="text-secondary-light font-monospace">{{ $tx->reference_no }}</span>
                                </td>
                                <td class="text-center pe-24 py-14">
                                    <form action="{{ route('dashboard-admin.pmo.cost.transactions.destroy', $tx->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center border-0 hover-bg-danger hover-text-white transition-all shadow-xs" title="Hapus">
                                            <iconify-icon icon="mingcute:delete-2-line" class="text-base"></iconify-icon>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-40 text-secondary-light">Belum ada transaksi biaya pada filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="p-16 border-top">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endif


<div class="modal fade" id="newCostTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fw-bold text-primary-light">Tambah Transaksi Biaya Proyek</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.pmo.cost.transactions.store') }}" method="POST">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Pilih Proyek <span class="text-danger">*</span></label>
                            <select name="project_id" class="form-select radius-8" required>
                                <option value="">-- Pilih Proyek Terkait --</option>
                                @foreach ($allProjects as $proj)
                                    <option value="{{ $proj->id }}">{{ $proj->project_code }} — {{ $proj->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" name="entry_date" class="form-control radius-8" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Kategori Biaya (10 Kategori) <span class="text-danger">*</span></label>
                            <select name="cost_category" class="form-select radius-8" required>
                                @foreach (\App\Models\ProjectCostEntry::CATEGORIES as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Tipe Transaksi <span class="text-danger">*</span></label>
                            <select name="type" class="form-select radius-8" required>
                                <option value="actual">Actual Expense (Pengeluaran Riil)</option>
                                <option value="commitment">Commitment (Komitmen PO / Kontrak Vendor)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Nominal Biaya (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" min="1" step="1" class="form-control radius-8" placeholder="Contoh: 15000000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Vendor / Pihak Penerima</label>
                            <input type="text" name="vendor" class="form-control radius-8" placeholder="Nama vendor atau supplier">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Nomor Referensi (PO / Invoice / Voucher)</label>
                            <input type="text" name="reference_no" class="form-control radius-8" placeholder="Contoh: PO-2026-0812">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Subkategori / Uraian Singkat</label>
                            <input type="text" name="cost_subcategory" class="form-control radius-8" placeholder="Contoh: Pengadaan sparepart UHP">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-secondary-light">Catatan / Keterangan</label>
                            <textarea name="notes" rows="2" class="form-control radius-8" placeholder="Keterangan tambahan transaksi..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-12 px-24">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-16 radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-20 radius-8">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
