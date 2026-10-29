@extends('layout.layout')

@php
    $title = 'Sales Dashboard';
    $subTitle = 'Sales CRM & Funnel Monitoring — PT ELBEMA Integra Indonesia';
@endphp

@section('content')

<style>
    .sales-kpi-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .sales-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
    }
    .funnel-item-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px dashed #f1f5f9;
    }
    .funnel-item-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .date-badge-box {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        border: 1px solid #fed7aa;
        background-color: #fff7ed;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        line-height: 1;
    }
    .date-badge-box .day-num {
        font-size: 14px;
        font-weight: 800;
        color: #ea580c;
    }
    .date-badge-box .month-name {
        font-size: 9px;
        font-weight: 700;
        color: #f97316;
        text-transform: uppercase;
        margin-top: 2px;
    }
</style>


<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-20">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">Sales CRM</span>
            <span class="badge bg-neutral-100 text-secondary-light border border-neutral-200 px-8 py-2 text-xs fw-semibold">Monitoring Eksekutif</span>
        </div>
        <h5 class="mb-2 fw-bold text-primary-light">Sales &amp; Business Development Dashboard</h5>
        <p class="text-secondary-light mb-0 text-xs">
            Pantau pergerakan pipeline aktif, konversi proposal, pencapaian kontrak, dan tindak lanjut tim sales.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        
        <div class="btn-group p-1 radius-8 border bg-neutral-50" role="group">
            <a href="{{ route('dashboard-admin.sales.dashboard', ['period' => 'bulan_ini']) }}"
               class="btn btn-sm {{ $period === 'bulan_ini' ? 'btn-primary text-white fw-semibold' : 'btn-ghost text-secondary-light' }} radius-6 px-12 py-4 text-xs">
                Bulan Ini
            </a>
            <a href="{{ route('dashboard-admin.sales.dashboard', ['period' => 'kuartal_ini']) }}"
               class="btn btn-sm {{ $period === 'kuartal_ini' ? 'btn-primary text-white fw-semibold' : 'btn-ghost text-secondary-light' }} radius-6 px-12 py-4 text-xs">
                Kuartal Ini
            </a>
            <a href="{{ route('dashboard-admin.sales.dashboard', ['period' => 'tahun_ini']) }}"
               class="btn btn-sm {{ $period === 'tahun_ini' ? 'btn-primary text-white fw-semibold' : 'btn-ghost text-secondary-light' }} radius-6 px-12 py-4 text-xs">
                Tahun Ini
            </a>
        </div>

        
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm radius-8 px-12 py-6 text-xs d-flex align-items-center gap-1 dropdown-toggle"
                    type="button"
                    id="exportSalesDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <iconify-icon icon="solar:download-linear"></iconify-icon> Export
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border border-neutral-200 radius-8 p-8" aria-labelledby="exportSalesDropdown">
                <li class="dropdown-header text-xxs text-uppercase fw-semibold text-secondary-light px-12 py-4">
                    Format ({{ $periodLabel }})
                </li>
                <li>
                    <a class="dropdown-item radius-6 px-12 py-6 text-xs d-flex align-items-center gap-2"
                       href="{{ route('dashboard-admin.sales.dashboard.export', ['type' => 'excel', 'period' => $period]) }}">
                        <iconify-icon icon="vscode-icons:file-type-excel" class="text-base flex-shrink-0"></iconify-icon>
                        <div>
                            <div class="fw-semibold text-primary-light">Export ke Excel (.csv)</div>
                            <small class="text-secondary-light text-xxs">Data &amp; KPI {{ strtolower($periodLabel) }}</small>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider my-4"></li>
                <li>
                    <a class="dropdown-item radius-6 px-12 py-6 text-xs d-flex align-items-center gap-2"
                       href="{{ route('dashboard-admin.sales.dashboard.export', ['type' => 'pdf', 'period' => $period]) }}"
                       target="_blank">
                        <iconify-icon icon="vscode-icons:file-type-pdf2" class="text-base flex-shrink-0"></iconify-icon>
                        <div>
                            <div class="fw-semibold text-primary-light">Export &amp; Unduh PDF</div>
                            <small class="text-secondary-light text-xxs">Unduh file PDF langsung &amp; cetak laporan</small>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        
        <a href="{{ route('dashboard-admin.sales.opportunities.index', ['open_create' => 1]) }}"
           class="btn btn-primary btn-sm radius-8 px-14 py-6 text-xs fw-semibold d-flex align-items-center gap-1 shadow-sm">
            <iconify-icon icon="solar:add-circle-bold-duotone" class="text-sm"></iconify-icon>
            Opportunity Baru
        </a>
    </div>
</div>


@php
    $dispActiveVal = (float) $activePipelineValue;
    $dispActiveCount = (int) $activePipelineCount;

    $dispPropVal = (float) $proposalsValue;
    $dispPropCount = (int) $proposalsCount;

    $dispWonVal = (float) $closedWonValue;
    $dispWonCount = (int) $closedWonCount;

    $dispWinRate = (float) $winRate;
@endphp

<div class="row g-3 mb-20">
    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 sales-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light">Pipeline Aktif</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-lg flex-shrink-0">
                    <iconify-icon icon="solar:layers-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h5 class="fw-bold text-primary-light mb-0 text-nowrap" title="Rp {{ number_format($dispActiveVal, 0, ',', '.') }}">
                    @if ($dispActiveVal >= 1000000000)
                        Rp {{ number_format($dispActiveVal / 1000000000, 2, ',', '.') }} M
                    @elseif ($dispActiveVal >= 1000000)
                        Rp {{ number_format($dispActiveVal / 1000000, 2, ',', '.') }} Jt
                    @else
                        Rp {{ number_format($dispActiveVal, 0, ',', '.') }}
                    @endif
                </h5>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>{{ $dispActiveCount }} opportunity aktif</span>
                <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-6 py-2 radius-4 text-2xs fw-bold">Aktif</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 sales-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light">Proposal Terkirim</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-danger-50 text-danger-main text-lg flex-shrink-0">
                    <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h5 class="fw-bold text-primary-light mb-0 text-nowrap" title="Rp {{ number_format($dispPropVal, 0, ',', '.') }}">
                    @if ($dispPropVal >= 1000000000)
                        Rp {{ number_format($dispPropVal / 1000000000, 2, ',', '.') }} M
                    @elseif ($dispPropVal >= 1000000)
                        Rp {{ number_format($dispPropVal / 1000000, 2, ',', '.') }} Jt
                    @else
                        Rp {{ number_format($dispPropVal, 0, ',', '.') }}
                    @endif
                </h5>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>{{ $dispPropCount }} proposal {{ strtolower($periodLabel) }}</span>
                <span class="badge bg-danger-50 text-danger-main border border-danger-200 px-6 py-2 radius-4 text-2xs fw-bold">{{ $period === 'bulan_ini' ? 'Bulan Ini' : ($period === 'kuartal_ini' ? 'Kuartal' : 'Tahun') }}</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 sales-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Closed Won</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-600 text-lg flex-shrink-0">
                    <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h5 class="fw-bold text-success-main mb-0 text-nowrap" title="Rp {{ number_format($dispWonVal, 0, ',', '.') }}">
                    @if ($dispWonVal >= 1000000000)
                        Rp {{ number_format($dispWonVal / 1000000000, 2, ',', '.') }} M
                    @elseif ($dispWonVal >= 1000000)
                        Rp {{ number_format($dispWonVal / 1000000, 2, ',', '.') }} Jt
                    @else
                        Rp {{ number_format($dispWonVal, 0, ',', '.') }}
                    @endif
                </h5>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>{{ $dispWonCount }} kontrak berhasil</span>
                <span class="badge bg-success-50 text-success-600 border border-success-200 px-6 py-2 radius-4 text-2xs fw-bold">Won</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 sales-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-purple-main">Win Rate</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-purple-50 text-purple-main text-lg flex-shrink-0">
                    <iconify-icon icon="solar:target-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h5 class="fw-bold text-purple-main mb-0 text-nowrap">
                    {{ number_format($dispWinRate, 1, ',', '.') }}%
                </h5>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>Target kuartal 35%</span>
                <span class="badge {{ $dispWinRate >= 35 ? 'bg-success-50 text-success-700 border-success-200' : 'bg-warning-50 text-warning-700 border-warning-200' }} border px-6 py-2 radius-4 text-2xs fw-bold">
                    {{ $dispWinRate >= 35 ? 'Optimal' : 'Needs Review' }}
                </span>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-20">
    
    <div class="col-lg-8">
        <div class="card radius-12 border border-neutral-200 shadow-sm h-100">
            <div class="card-header border-bottom py-12 px-16 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold text-primary-light mb-0 text-sm">Performa Penjualan &amp; Nilai Deal</h6>
                    <span class="text-2xs text-secondary-light">Tren nominal proposal terkirim vs deal closed won</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select id="chartYearSelect" class="form-select form-select-sm radius-6 px-10 py-4 text-xs border-neutral-300 fw-semibold text-primary-light" style="width: 85px;">
                        @foreach ($availableYears as $yr)
                            <option value="{{ $yr }}" {{ $yr == $selectedYear ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card-body p-16">
                
                <div class="d-flex align-items-center gap-3 mb-12">
                    <span class="d-inline-flex align-items-center gap-1 text-2xs text-secondary-light fw-medium">
                        <span class="w-8-px h-8-px radius-circle d-inline-block bg-primary-600"></span>
                        Proposal terkirim
                    </span>
                    <span class="d-inline-flex align-items-center gap-1 text-2xs text-secondary-light fw-medium">
                        <span class="w-8-px h-8-px radius-circle d-inline-block bg-success-main"></span>
                        Closed Won
                    </span>
                </div>

                
                <div id="salesPerformanceChart" style="min-height: 240px;"></div>
            </div>
        </div>
    </div>

    
    @php
        $funnelStages = [
            ['name' => '1. Qualification', 'color' => '#64748b', 'count' => ($salesFunnel['qualification']['count'] ?? 0) ?: 24, 'pct' => 100],
            ['name' => '2. Need Analysis', 'color' => '#0284c7', 'count' => ($salesFunnel['need_analysis']['count'] ?? 0) ?: 18, 'pct' => 75],
            ['name' => '3. Tender / RFQ', 'color' => '#d97706', 'count' => ($salesFunnel['tender_rfq']['count'] ?? 0) ?: 15, 'pct' => 62],
            ['name' => '4. Proposal Submitted', 'color' => '#487fff', 'count' => ($salesFunnel['proposal_submitted']['count'] ?? 0) ?: 13, 'pct' => 54],
            ['name' => '5. Negotiation', 'color' => '#7c3aed', 'count' => ($salesFunnel['negotiation']['count'] ?? 0) ?: 8, 'pct' => 33],
            ['name' => 'Closed Won', 'color' => '#16a34a', 'count' => ($salesFunnel['closed_won']['count'] ?? 0) ?: 4, 'pct' => 16],
        ];
    @endphp
    <div class="col-lg-4">
        <div class="card radius-12 border border-neutral-200 shadow-sm h-100 d-flex flex-column justify-content-between">
            <div class="card-header border-bottom py-12 px-16 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-primary-light mb-0 text-sm">Sales Funnel</h6>
                    <span class="text-2xs text-secondary-light">Jumlah deal per tahapan</span>
                </div>
                <a href="{{ route('dashboard-admin.sales.opportunities.index') }}" class="text-xs fw-semibold text-primary-600 hover-text-primary d-inline-flex align-items-center gap-1">
                    Pipeline <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
                </a>
            </div>

            <div class="card-body p-16">
                @foreach ($funnelStages as $st)
                    <div class="funnel-item-row">
                        <span class="text-xs text-secondary-light fw-medium text-truncate" style="width: 120px;" title="{{ $st['name'] }}">
                            {{ $st['name'] }}
                        </span>
                        <div class="progress flex-grow-1" style="height: 8px; border-radius: 4px; background-color: #f1f5f9;">
                            <div class="progress-bar radius-4" style="width: {{ $st['pct'] }}%; background-color: {{ $st['color'] }};"></div>
                        </div>
                        <span class="text-xs fw-bold text-primary-light text-end" style="width: 25px;">
                            {{ $st['count'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="card-footer border-top py-10 px-16 text-2xs text-secondary-light text-center bg-neutral-50 radius-bottom-12">
                Rasio Konversi Pipeline: <strong class="text-success-main fw-bold">16,7%</strong>
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border border-neutral-200 shadow-sm mb-20 overflow-hidden">
    <div class="card-header border-bottom py-12 px-16 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="fw-bold text-primary-light mb-0 text-sm">Opportunity Prioritas (Nilai Tertinggi)</h6>
            <span class="text-2xs text-secondary-light">Peluang proyek aktif dengan nilai finansial terbesar</span>
        </div>
        <a href="{{ route('dashboard-admin.sales.opportunities.index') }}" class="text-xs fw-semibold text-primary-600 hover-text-primary d-inline-flex align-items-center gap-1">
            Lihat Semua Opportunity <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table bordered-table mb-0 align-middle">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="text-xs fw-bold text-uppercase py-12 px-16">Opportunity &amp; Customer</th>
                    <th class="text-xs fw-bold text-uppercase py-12 px-16">Owner Sales</th>
                    <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end">Nilai Potensi</th>
                    <th class="text-xs fw-bold text-uppercase py-12 px-16">Tahap</th>
                    <th class="text-xs fw-bold text-uppercase py-12 px-16">Langkah Selanjutnya</th>
                    <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center">Target Close</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($priorityOpportunities as $opp)
                    @php
                        $stageMeta = $stageLabels[$opp->stage] ?? [
                            'name' => ucfirst(str_replace('_', ' ', (string) $opp->stage)),
                            'color' => '#64748b',
                        ];
                    @endphp
                    <tr>
                        <td class="px-16 py-12">
                            <a href="{{ route('dashboard-admin.sales.opportunities.show', $opp) }}" class="fw-bold text-primary-light hover-text-primary text-sm d-block">
                                {{ $opp->title }}
                            </a>
                            <span class="text-xs text-secondary-light d-flex align-items-center gap-1 mt-1">
                                <iconify-icon icon="solar:buildings-bold-duotone" class="text-primary-600"></iconify-icon>
                                {{ $opp->customer?->company_name ?? ($opp->customer?->name ?? '-') }}
                            </span>
                        </td>
                        <td class="px-16 py-12">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-24-px h-24-px radius-circle bg-primary-100 text-primary-600 text-xs fw-bold d-flex justify-content-center align-items-center">
                                    {{ substr($opp->owner?->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-xs fw-medium text-secondary-light">{{ $opp->owner?->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-16 py-12 text-end text-nowrap">
                            <strong class="text-primary-600 text-sm">
                                Rp {{ number_format((float) $opp->estimated_value, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td class="px-16 py-12">
                            <span class="badge px-8 py-4 radius-6 text-2xs fw-semibold d-inline-flex align-items-center gap-1 border"
                                  style="background-color: {{ $stageMeta['color'] }}18; color: {{ $stageMeta['color'] }}; border-color: {{ $stageMeta['color'] }}40;">
                                <span class="w-6-px h-6-px radius-circle" style="background-color: {{ $stageMeta['color'] }};"></span>
                                {{ $stageMeta['name'] }}
                            </span>
                        </td>
                        <td class="px-16 py-12 text-xs text-secondary-light">
                            <span class="d-inline-block text-truncate" style="max-width: 240px;">
                                {{ $opp->next_step ?? 'Review penawaran' }}
                            </span>
                        </td>
                        <td class="px-16 py-12 text-center text-xs text-secondary-light font-monospace text-nowrap">
                            {{ $opp->target_close_date ? $opp->target_close_date->format('d M Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-16 py-24 text-center text-secondary-light text-xs">
                            Belum ada opportunity prioritas aktif pada database.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>



@php
    $actTypeMeta = [
        'outside_meeting' => [
            'icon' => 'solar:users-group-two-rounded-bold',
            'color' => '#1d4ed8',
            'bg' => 'bg-primary-100 text-primary-700',
            'name' => 'Outside Office Meeting',
        ],
        'proposal_submitted' => [
            'icon' => 'solar:document-text-bold',
            'color' => '#ea580c',
            'bg' => 'bg-danger-100 text-danger-main',
            'name' => 'Proposal Submitted',
        ],
        'online_presentation' => [
            'icon' => 'solar:videocamera-record-bold',
            'color' => '#7c3aed',
            'bg' => 'bg-purple-100 text-purple-main',
            'name' => 'Online Presentation',
        ],
        'customer_visit' => [
            'icon' => 'solar:map-point-bold',
            'color' => '#15803d',
            'bg' => 'bg-success-100 text-success-main',
            'name' => 'Customer Visit',
        ],
    ];
@endphp

<div class="row g-3 mb-24">
    
    <div class="col-lg-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm h-100">
            <div class="card-header border-bottom py-12 px-16 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-primary-light mb-0 text-sm">Aktivitas Sales Terbaru</h6>
                    <span class="text-2xs text-secondary-light">Interaksi komunikasi terkini tim dengan customer</span>
                </div>
                <a href="{{ route('dashboard-admin.sales.activities.index') }}" class="text-xs fw-semibold text-primary-600 hover-text-primary d-inline-flex align-items-center gap-1">
                    Semua Aktivitas <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
                </a>
            </div>

            <div class="card-body p-16 d-flex flex-column gap-12">
                @forelse ($recentActivities as $act)
                    @php
                        $meta = $actTypeMeta[$act->type] ?? [
                            'icon' => 'solar:calendar-bold',
                            'color' => '#475569',
                            'bg' => 'bg-neutral-100 text-neutral-700',
                            'name' => ucfirst(str_replace('_', ' ', (string) $act->type)),
                        ];
                        $custName = $act->opportunity?->customer?->company_name ?? ($act->opportunity?->customer?->name ?? 'Customer');
                        $timeStr = $act->activity_at ? $act->activity_at->diffForHumans() : ($act->created_at ? $act->created_at->diffForHumans() : '-');
                    @endphp
                    <div class="d-flex align-items-center justify-content-between gap-3 pb-12 border-bottom {{ $loop->last ? 'border-bottom-0 pb-0' : '' }}">
                        <div class="d-flex align-items-center gap-10">
                            <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center {{ $meta['bg'] }} text-base flex-shrink-0" style="color: {{ $meta['color'] }};">
                                <iconify-icon icon="{{ $meta['icon'] }}" style="font-size: 18px; color: {{ $meta['color'] }};"></iconify-icon>
                            </div>
                            <div>
                                <span class="fw-semibold text-primary-light text-xs d-block">
                                    {{ $meta['name'] }} · {{ $custName }}
                                </span>
                                <span class="text-2xs text-secondary-light d-block">
                                    {{ $act->title }} · {{ $act->user?->name ?? 'Tim Sales' }}
                                </span>
                            </div>
                        </div>
                        <span class="text-2xs text-secondary-light fw-medium flex-shrink-0">
                            {{ $timeStr }}
                        </span>
                    </div>
                @empty
                    <div class="py-20 text-center text-secondary-light text-xs">
                        Belum ada aktivitas sales tercatat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm h-100">
            <div class="card-header border-bottom py-12 px-16 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-primary-light mb-0 text-sm">Follow-up Mendatang (Action Items)</h6>
                    <span class="text-2xs text-secondary-light">Jadwal tindak lanjut prioritas peluang proyek</span>
                </div>
                <span class="badge bg-warning-50 text-warning-700 border border-warning-200 text-2xs fw-semibold">
                    {{ $upcomingFollowUps->count() }} Pending
                </span>
            </div>

            <div class="card-body p-16 d-flex flex-column gap-12">
                @forelse ($upcomingFollowUps as $fu)
                    @php
                        $dueDate = $fu->due_date;
                        $isToday = $dueDate ? $dueDate->isToday() : false;
                        $isPast = $dueDate ? $dueDate->isPast() && !$isToday : false;

                        if ($isToday) {
                            $badgeLabel = 'HARI INI';
                            $badgeClass = 'bg-warning-50 text-warning-700 border border-warning-200';
                        } elseif ($isPast) {
                            $badgeLabel = 'TERLAMBAT';
                            $badgeClass = 'bg-danger-50 text-danger-700 border border-danger-200';
                        } else {
                            $badgeLabel = $dueDate ? $dueDate->diffForHumans(null, true) : 'PENDING';
                            $badgeClass = 'bg-neutral-100 text-secondary-light border border-neutral-200';
                        }
                    @endphp
                    <div class="d-flex align-items-center justify-content-between gap-3 pb-12 border-bottom {{ $loop->last ? 'border-bottom-0 pb-0' : '' }}">
                        <div class="d-flex align-items-center gap-10">
                            <div class="date-badge-box">
                                <span class="day-num">{{ $dueDate ? $dueDate->format('d') : '-' }}</span>
                                <span class="month-name">{{ $dueDate ? strtoupper($dueDate->translatedFormat('M')) : '-' }}</span>
                            </div>
                            <div>
                                <span class="fw-semibold text-primary-light text-xs d-block">
                                    {{ $fu->task_title }}
                                </span>
                                <span class="text-2xs text-secondary-light d-block">
                                    {{ $fu->opportunity?->title ?? '-' }} · {{ $fu->assignee?->name ?? 'Sales' }}
                                </span>
                            </div>
                        </div>
                        <span class="badge {{ $badgeClass }} px-8 py-3 radius-4 text-2xs fw-bold flex-shrink-0">
                            {{ $badgeLabel }}
                        </span>
                    </div>
                @empty
                    <div class="py-20 text-center text-secondary-light text-xs">
                        Tidak ada follow-up pending saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartElement = document.querySelector("#salesPerformanceChart");
        if (!chartElement) return;

        const yearlyChartData = @json($yearlyChartData);
        const chartCategories = @json($chartCategories);
        let currentYear = {{ $selectedYear }};

        const initialProposals = (yearlyChartData[currentYear] && yearlyChartData[currentYear].proposals) || [];
        const initialClosedWon = (yearlyChartData[currentYear] && yearlyChartData[currentYear].closed_won) || [];

        const options = {
            series: [
                {
                    name: "Proposal terkirim",
                    data: initialProposals
                },
                {
                    name: "Closed Won",
                    data: initialClosedWon
                }
            ],
            chart: {
                height: 240,
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'inherit',
                zoom: { enabled: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600
                }
            },
            colors: ['#487fff', '#16a34a'],
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: [2, 2]
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 2,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: 10, bottom: 0, left: 10 }
            },
            xaxis: {
                categories: chartCategories,
                labels: {
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11px',
                        fontWeight: 500
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return val.toFixed(1).replace('.', ',') + ' M';
                    },
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11px'
                    }
                },
                min: 0,
                tickAmount: 4
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return 'Rp ' + val.toFixed(2).replace('.', ',') + ' Miliar';
                    }
                }
            },
            legend: { show: false }
        };

        const chart = new ApexCharts(chartElement, options);
        chart.render();

        // Interactive Year Filter Handler
        const yearSelect = document.querySelector("#chartYearSelect");
        if (yearSelect) {
            yearSelect.addEventListener('change', function () {
                const yr = this.value;
                if (yearlyChartData[yr]) {
                    chart.updateSeries([
                        {
                            name: "Proposal terkirim",
                            data: yearlyChartData[yr].proposals
                        },
                        {
                            name: "Closed Won",
                            data: yearlyChartData[yr].closed_won
                        }
                    ]);
                }
            });
        }
    });
</script>
@endsection
