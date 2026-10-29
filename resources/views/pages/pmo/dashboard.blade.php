@extends('layout.layout')

@php
    $title = 'Dashboard PMO';
    $subTitle = 'Project Management Office — Portfolio, Delivery, EVM & Controls';
@endphp

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-24">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">Project Management Office</span>
            <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-8 py-2 text-xs fw-semibold">Live Monitoring</span>
        </div>
        <h4 class="mb-4 fw-bold text-primary-light">PMO Executive Portfolio Dashboard</h4>
        <p class="text-secondary-light mb-0 text-sm">
            Monitoring komprehensif performa proyek, Earned Value Management (EVM), delivery milestone, dan arus kas.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('dashboard-admin.pmo.projects.index') }}" class="btn btn-outline-primary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold">
            <iconify-icon icon="solar:folder-with-files-bold-duotone" class="text-base"></iconify-icon>
            Project Register
        </a>
        <a href="{{ route('dashboard-admin.pmo.milestones.index') }}" class="btn btn-primary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold shadow-sm">
            <iconify-icon icon="solar:checklist-minimalistic-bold-duotone" class="text-base"></iconify-icon>
            Milestone & WBS
        </a>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-primary">
            <div class="card-body p-16">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Portfolio</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light fs-5">
                            {{ $totalCount }} <span class="fs-6 fw-normal text-secondary-light">Proyek</span>
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-xl shadow-xs">
                        <iconify-icon icon="solar:folder-open-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Nilai Kontrak: <strong class="text-primary-600">Rp {{ number_format($totalValue, 0, ',', '.') }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-warning">
            <div class="card-body p-16">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Project Berjalan (Ongoing)</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light fs-5">
                            {{ $ongoingCount }} <span class="fs-6 fw-normal text-secondary-light">Aktif</span>
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-xl shadow-xs">
                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Komitmen: <strong class="text-warning-main">Rp {{ number_format($ongoingValue, 0, ',', '.') }}</strong></span>
                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200">Execution</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-success">
            <div class="card-body p-16">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Project Selesai (Completed)</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light fs-5">
                            {{ $completedCount }} <span class="fs-6 fw-normal text-secondary-light">Selesai</span>
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-main text-xl shadow-xs">
                        <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Realisasi: <strong class="text-success-main">Rp {{ number_format($completedValue, 0, ',', '.') }}</strong></span>
                    <span class="badge bg-success-50 text-success-700 border border-success-200">Handover 100%</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-info">
            <div class="card-body p-16">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Portfolio Actual Cost</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light fs-5">
                            Rp {{ number_format($totalActualCost, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-info-100 text-info-main text-xl shadow-xs">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Approved BAC: <strong class="text-primary-600">Rp {{ number_format($totalBudget, 0, ',', '.') }}</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-lg-8">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom p-16 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h6 class="mb-1 fw-bold text-primary-light">Project Trend by Status &amp; S-Curve Execution</h6>
                    <p class="text-secondary-light text-xs mb-0">Tren kumulatif penyelesaian proyek dan distribusi status pelaksanaan per bulan.</p>
                </div>
                <div class="d-flex align-items-center gap-3 text-xs">
                    <span class="d-flex align-items-center gap-1"><span class="w-10-px h-10-px radius-2 bg-warning-main d-inline-block"></span> Ongoing</span>
                    <span class="d-flex align-items-center gap-1"><span class="w-10-px h-10-px radius-2 bg-success-main d-inline-block"></span> Completed</span>
                    <span class="d-flex align-items-center gap-1"><span class="w-12-px h-3-px bg-primary-600 d-inline-block"></span> Kumulatif Total</span>
                </div>
            </div>
            <div class="card-body p-20">
                <div class="table-responsive">
                    <svg viewBox="0 0 740 220" class="w-100" style="min-height: 200px; overflow: visible;">
                        <defs>
                            <linearGradient id="pmoCurveGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#487fff" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="#487fff" stop-opacity="0.0"/>
                            </linearGradient>
                        </defs>
                        
                        @for ($i = 0; $i <= 4; $i++)
                            @php $y = 180 - ($i * 40); @endphp
                            <line x1="40" y1="{{ $y }}" x2="720" y2="{{ $y }}" stroke="#f1f3f5" stroke-width="1" />
                            <text x="30" y="{{ $y + 4 }}" fill="#8c97a4" font-size="9" text-anchor="end">{{ $i * 2 }}</text>
                        @endfor

                        
                        @php
                            $step = (720 - 60) / max(1, count($trendMonths) - 1);
                            $polylinePoints = [];
                        @endphp

                        @foreach ($trendMonths as $idx => $m)
                            @php
                                $x = 60 + ($idx * $step);
                                $ongoingH = min(160, $m['ongoing'] * 20);
                                $completedH = min(160, $m['completed'] * 20);
                                $yTotal = 180 - min(160, $m['total'] * 20);
                                $polylinePoints[] = "{$x},{$yTotal}";
                            @endphp

                            
                            <rect x="{{ $x - 12 }}" y="{{ 180 - $ongoingH }}" width="11" height="{{ $ongoingH }}" rx="3" fill="#ff9f43" opacity="0.85">
                                <title>{{ $m['name'] }}: {{ $m['ongoing'] }} Ongoing</title>
                            </rect>
                            
                            <rect x="{{ $x + 1 }}" y="{{ 180 - $completedH }}" width="11" height="{{ $completedH }}" rx="3" fill="#28c76f" opacity="0.85">
                                <title>{{ $m['name'] }}: {{ $m['completed'] }} Completed</title>
                            </rect>

                            
                            <text x="{{ $x }}" y="198" fill="#6c757d" font-size="9" text-anchor="middle" font-weight="600">{{ $m['name'] }}</text>
                        @endforeach

                        
                        <polyline fill="none" stroke="#487fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" points="{{ implode(' ', $polylinePoints) }}" />
                        @foreach ($polylinePoints as $pt)
                            @php [$px, $py] = explode(',', $pt); @endphp
                            <circle cx="{{ $px }}" cy="{{ $py }}" r="4" fill="#ffffff" stroke="#487fff" stroke-width="2.5" />
                        @endforeach
                    </svg>
                </div>
                <div class="mt-12 p-10 radius-8 bg-neutral-50 border text-xs text-secondary-light d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:info-circle-bold-duotone" class="text-primary-600 text-base"></iconify-icon>
                    <span>S-Curve merefleksikan laju penyelesaian deliverable proyek terhadap target baseline portfolio 2026.</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom p-16">
                <h6 class="mb-1 fw-bold text-primary-light">EVM Performance Index</h6>
                <p class="text-secondary-light text-xs mb-0">Efisiensi biaya (CPI) & deviasi jadwal (SPI) portfolio.</p>
            </div>
            <div class="card-body p-16 d-flex flex-column gap-3">
                
                <div class="border radius-10 p-12 bg-neutral-50">
                    <div class="d-flex justify-content-between align-items-center mb-8">
                        <span class="text-xs fw-bold text-uppercase text-secondary-light">Cost Performance Index (CPI)</span>
                        <span class="badge bg-primary-50 text-primary-600 border border-primary-200 text-2xs">EV ÷ AC</span>
                    </div>
                    <div class="row g-2">
                        @foreach ($cpiBuckets as $b)
                            <div class="col-4">
                                <div class="p-8 radius-8 text-center border {{ $b['class'] === 'below' ? 'bg-danger-50 border-danger-200 text-danger-main' : ($b['class'] === 'above' ? 'bg-success-50 border-success-200 text-success-main' : 'bg-white border-neutral-200 text-neutral-800') }}">
                                    <span class="d-block text-2xs fw-semibold">{{ $b['label'] }}</span>
                                    <strong class="d-block fs-6 fw-bold">{{ $b['count'] }}</strong>
                                    <span class="text-3xs text-secondary-light d-block text-truncate">{{ $b['sub'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                
                <div class="border radius-10 p-12 bg-neutral-50">
                    <div class="d-flex justify-content-between align-items-center mb-8">
                        <span class="text-xs fw-bold text-uppercase text-secondary-light">Schedule Performance Index (SPI)</span>
                        <span class="badge bg-primary-50 text-primary-600 border border-primary-200 text-2xs">EV ÷ PV</span>
                    </div>
                    <div class="row g-2">
                        @foreach ($spiBuckets as $b)
                            <div class="col-4">
                                <div class="p-8 radius-8 text-center border {{ $b['class'] === 'below' ? 'bg-warning-50 border-warning-200 text-warning-main' : ($b['class'] === 'above' ? 'bg-success-50 border-success-200 text-success-main' : 'bg-white border-neutral-200 text-neutral-800') }}">
                                    <span class="d-block text-2xs fw-semibold">{{ $b['label'] }}</span>
                                    <strong class="d-block fs-6 fw-bold">{{ $b['count'] }}</strong>
                                    <span class="text-3xs text-secondary-light d-block text-truncate">{{ $b['sub'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                
                <div class="mt-auto p-12 radius-10 bg-primary-50 border border-primary-200 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="d-block text-xs fw-semibold text-secondary-light">Total Portfolio Man-Hours</span>
                        <h5 class="mb-0 fw-bold text-primary-600">{{ number_format($totalManHours, 0, ',', '.') }} <span class="fs-6 fw-normal text-secondary-light">Jam</span></h5>
                    </div>
                    <div class="w-36-px h-36-px radius-8 bg-primary-100 text-primary-600 d-flex align-items-center justify-content-center text-lg">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-lg-6">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom p-16 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1 fw-bold text-primary-light">Project by Industry Sector</h6>
                    <p class="text-secondary-light text-xs mb-0">Komposisi sektor industri customer dan nilai kontrak.</p>
                </div>
                <span class="badge bg-neutral-100 text-neutral-700">{{ count($sectorBreakdown) }} Sektor</span>
            </div>
            <div class="card-body p-16">
                <div class="d-flex flex-column gap-3">
                    @foreach (array_slice($sectorBreakdown, 0, 5) as $sec)
                        <div>
                            <div class="d-flex justify-content-between align-items-center text-xs mb-1">
                                <span class="fw-semibold text-primary-light">{{ $sec['label'] }} <small class="text-secondary-light">({{ $sec['count'] }} Proyek)</small></span>
                                <span class="fw-bold text-neutral-800">Rp {{ number_format($sec['value'], 0, ',', '.') }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary-600" role="progressbar" style="width: {{ $sec['percentage'] }}%" aria-valuenow="{{ $sec['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom p-16 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1 fw-bold text-primary-light">Project by Service Line</h6>
                    <p class="text-secondary-light text-xs mb-0">Distribusi scope pekerjaan dan disiplin layanan.</p>
                </div>
                <span class="badge bg-neutral-100 text-neutral-700">{{ count($categoryBreakdown) }} Layanan</span>
            </div>
            <div class="card-body p-16">
                <div class="d-flex flex-column gap-3">
                    @foreach (array_slice($categoryBreakdown, 0, 5) as $cat)
                        <div>
                            <div class="d-flex justify-content-between align-items-center text-xs mb-1">
                                <span class="fw-semibold text-primary-light">{{ $cat['label'] }} <small class="text-secondary-light">({{ $cat['count'] }} Proyek)</small></span>
                                <span class="fw-bold text-neutral-800">Rp {{ number_format($cat['value'], 0, ',', '.') }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning-main" role="progressbar" style="width: {{ $cat['percentage'] }}%" aria-valuenow="{{ $cat['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom p-16 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1 fw-bold text-primary-light">Project by Site Location</h6>
                    <p class="text-secondary-light text-xs mb-0">Lokasi geografis eksekusi proyek di Indonesia.</p>
                </div>
                <span class="badge bg-neutral-100 text-neutral-700">{{ count($locationBreakdown) }} Site</span>
            </div>
            <div class="card-body p-16">
                <div class="d-flex flex-column gap-3">
                    @foreach (array_slice($locationBreakdown, 0, 5) as $loc)
                        <div>
                            <div class="d-flex justify-content-between align-items-center text-xs mb-1">
                                <span class="fw-semibold text-primary-light">{{ $loc['label'] }} <small class="text-secondary-light">({{ $loc['count'] }} Proyek)</small></span>
                                <span class="fw-bold text-neutral-800">Rp {{ number_format($loc['value'], 0, ',', '.') }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info-main" role="progressbar" style="width: {{ $loc['percentage'] }}%" aria-valuenow="{{ $loc['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom p-16 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1 fw-bold text-primary-light">Invoice &amp; Cash Collection</h6>
                    <p class="text-secondary-light text-xs mb-0">Realisasi penagihan termin proyek dan kas diterima.</p>
                </div>
                <span class="badge bg-success-50 text-success-700 border border-success-200">{{ $collectionRate }}% Terkoleksi</span>
            </div>
            <div class="card-body p-16">
                <div class="row g-2 mb-16">
                    <div class="col-4">
                        <div class="p-10 radius-8 border bg-neutral-50 text-center">
                            <span class="text-2xs text-secondary-light fw-semibold d-block">SUBMITTED</span>
                            <strong class="text-xs fw-bold text-primary-light d-block mt-1">Rp {{ number_format($invoiceSubmitted / 1000000, 1) }}M</strong>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-10 radius-8 border bg-success-50 border-success-200 text-center">
                            <span class="text-2xs text-success-700 fw-semibold d-block">PAID CASH</span>
                            <strong class="text-xs fw-bold text-success-700 d-block mt-1">Rp {{ number_format($invoicePaid / 1000000, 1) }}M</strong>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-10 radius-8 border bg-warning-50 border-warning-200 text-center">
                            <span class="text-2xs text-warning-700 fw-semibold d-block">OUTSTANDING</span>
                            <strong class="text-xs fw-bold text-warning-700 d-block mt-1">Rp {{ number_format($outstanding / 1000000, 1) }}M</strong>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between text-xs mb-1">
                        <span class="text-secondary-light">Collection Progress</span>
                        <span class="fw-bold text-success-main">{{ $collectionRate }}% Selesai Dibayar</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success-main" role="progressbar" style="width: {{ $collectionRate }}%" aria-valuenow="{{ $collectionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-header bg-transparent border-bottom p-16 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="mb-1 fw-bold text-primary-light">Project Portfolio Overview</h6>
            <p class="text-secondary-light text-xs mb-0">Daftar proyek aktif dan metrik kontrol delivery PMO.</p>
        </div>
        <a href="{{ route('dashboard-admin.pmo.projects.index') }}" class="btn btn-sm btn-outline-primary px-12 py-6 radius-8 d-inline-flex align-items-center gap-1">
            Lihat Semua Proyek <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 crm-table-compact">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-16">Kode &amp; Proyek</th>
                        <th>Customer &amp; Lokasi</th>
                        <th>Disiplin / Scope</th>
                        <th>Project Manager</th>
                        <th>Progress Fisik</th>
                        <th>EVM CPI/SPI</th>
                        <th>Status</th>
                        <th>Nilai Kontrak</th>
                        <th class="text-center pe-16">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($projects->take(6) as $p)
                        <tr>
                            <td class="ps-16">
                                <a href="{{ route('dashboard-admin.pmo.projects.show', $p->id) }}" class="fw-bold text-primary-600 d-block text-decoration-none">
                                    {{ $p->title }}
                                </a>
                                <span class="badge bg-neutral-100 text-neutral-700 text-3xs">{{ $p->project_code }}</span>
                            </td>
                            <td>
                                <strong class="d-block text-neutral-800">{{ $p->customer?->company_name ?? '—' }}</strong>
                                <small class="text-secondary-light">{{ $p->location ?: '—' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary-50 text-primary-700 border border-primary-200">{{ $p->category ?: 'General' }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-24-px h-24-px rounded-circle bg-primary-100 text-primary-600 d-flex align-items-center justify-content-center fw-bold text-3xs">
                                        {{ strtoupper(substr($p->projectManager?->user?->name ?? 'PM', 0, 2)) }}
                                    </div>
                                    <span class="text-neutral-800 fw-semibold">{{ $p->projectManager?->user?->name ?? 'Unassigned' }}</span>
                                </div>
                            </td>
                            <td style="min-width: 130px;">
                                <div class="d-flex justify-content-between text-2xs mb-1">
                                    <span class="text-secondary-light">Aktual: <strong>{{ $p->progress_percentage }}%</strong></span>
                                    <span class="text-secondary-light">Plan: {{ $p->planned_progress_percentage }}%</span>
                                </div>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar {{ $p->progress_percentage >= $p->planned_progress_percentage ? 'bg-success-main' : 'bg-warning-main' }}"
                                         style="width: {{ $p->progress_percentage }}%"></div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <span class="badge {{ $p->cpi >= 1 ? 'bg-success-50 text-success-700 border border-success-200' : 'bg-danger-50 text-danger-700 border border-danger-200' }} text-3xs">
                                        CPI {{ number_format($p->cpi, 2) }}
                                    </span>
                                    <span class="badge {{ $p->spi >= 1 ? 'bg-success-50 text-success-700 border border-success-200' : 'bg-warning-50 text-warning-700 border border-warning-200' }} text-3xs">
                                        SPI {{ number_format($p->spi, 2) }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if ($p->status === 'completed')
                                    <span class="badge bg-success-50 text-success-700 border border-success-200 text-2xs">Completed</span>
                                @elseif ($p->status === 'on_progress')
                                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200 text-2xs">Ongoing</span>
                                @else
                                    <span class="badge bg-neutral-100 text-neutral-700 text-2xs">{{ ucfirst($p->status) }}</span>
                                @endif
                            </td>
                            <td class="fw-bold text-neutral-800">
                                Rp {{ number_format($p->contract_value, 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-16">
                                <a href="{{ route('dashboard-admin.pmo.projects.show', $p->id) }}"
                                   class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center"
                                   title="Detail Project 360">
                                    <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-32 text-secondary-light">
                                <iconify-icon icon="solar:folder-open-line-duotone" class="text-4xl d-block mx-auto mb-2 text-neutral-400"></iconify-icon>
                                Belum ada proyek terdaftar di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
