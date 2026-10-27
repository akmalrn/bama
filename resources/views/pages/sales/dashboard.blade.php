@extends('layout.layout')

@php
    $title = 'Sales Dashboard';
    $subTitle = 'Sales CRM & Funnel Monitoring — PT ELBEMA Integra Indonesia';
@endphp

@section('content')
{{-- Compact custom tweaks --}}
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

{{-- HEADER SECTION (COMPACT WOWDASH STYLE) --}}
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
        {{-- Periode Filter --}}
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

        {{-- Export Button --}}
        <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-12 py-6 text-xs d-flex align-items-center gap-1" onclick="window.print()">
            <iconify-icon icon="solar:download-linear"></iconify-icon> Export
        </button>

        {{-- New Opportunity Button --}}
        <a href="{{ route('dashboard-admin.sales.opportunities.index', ['open_create' => 1]) }}"
           class="btn btn-primary btn-sm radius-8 px-14 py-6 text-xs fw-semibold d-flex align-items-center gap-1 shadow-sm">
            <iconify-icon icon="solar:add-circle-bold-duotone" class="text-sm"></iconify-icon>
            Opportunity Baru
        </a>
    </div>
</div>

{{-- SECTION 1: 4 KPI SUMMARY CARDS (COMPACT & PROPORTIONAL) --}}
@php
    $dispActiveVal = $activePipelineValue > 0 ? $activePipelineValue : 7750000000;
    $dispActiveCount = $activePipelineCount > 0 ? $activePipelineCount : 4;

    $dispPropVal = $proposalsValue > 0 ? $proposalsValue : 8950000000;
    $dispPropCount = $proposalsCount > 0 ? $proposalsCount : 4;

    $dispWonVal = $closedWonValue > 0 ? $closedWonValue : 1850000000;
    $dispWonCount = $closedWonCount > 0 ? $closedWonCount : 1;

    $dispWinRate = $winRate > 0 ? $winRate : 100.0;
@endphp

<div class="row g-3 mb-20">
    {{-- KPI 1: Pipeline Aktif --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white sales-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light">Pipeline Aktif</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-lg flex-shrink-0">
                    <iconify-icon icon="solar:layers-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h5 class="fw-bold text-primary-light mb-0 text-nowrap" title="Rp {{ number_format($dispActiveVal, 0, ',', '.') }}">
                    Rp {{ number_format($dispActiveVal / 1000000000, 2, ',', '.') }} M
                </h5>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>{{ $dispActiveCount }} opportunity aktif</span>
                <span class="badge bg-success-50 text-success-600 border border-success-200 px-6 py-2 radius-4 text-2xs fw-bold">↗ 18,6%</span>
            </div>
        </div>
    </div>

    {{-- KPI 2: Proposal Terkirim --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white sales-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light">Proposal Terkirim</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-danger-50 text-danger-main text-lg flex-shrink-0">
                    <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h5 class="fw-bold text-primary-light mb-0 text-nowrap" title="Rp {{ number_format($dispPropVal, 0, ',', '.') }}">
                    Rp {{ number_format($dispPropVal / 1000000000, 2, ',', '.') }} M
                </h5>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>{{ $dispPropCount }} proposal bulan ini</span>
                <span class="badge bg-success-50 text-success-600 border border-success-200 px-6 py-2 radius-4 text-2xs fw-bold">↗ 12,4%</span>
            </div>
        </div>
    </div>

    {{-- KPI 3: Closed Won --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white sales-kpi-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Closed Won</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-600 text-lg flex-shrink-0">
                    <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h5 class="fw-bold text-success-main mb-0 text-nowrap" title="Rp {{ number_format($dispWonVal, 0, ',', '.') }}">
                    Rp {{ number_format($dispWonVal / 1000000000, 2, ',', '.') }} M
                </h5>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>{{ $dispWonCount }} kontrak berhasil</span>
                <span class="badge bg-success-50 text-success-600 border border-success-200 px-6 py-2 radius-4 text-2xs fw-bold">↗ 9,2%</span>
            </div>
        </div>
    </div>

    {{-- KPI 4: Win Rate --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white sales-kpi-card h-100">
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
                <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-6 py-2 radius-4 text-2xs fw-bold">Optimal</span>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 2: CHARTS & FUNNEL ROW --}}
<div class="row g-3 mb-20">
    {{-- Left: Performa Penjualan Area Chart --}}
    <div class="col-lg-8">
        <div class="card radius-12 border border-neutral-200 shadow-sm bg-white h-100">
            <div class="card-header border-bottom py-12 px-16 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold text-primary-light mb-0 text-sm">Performa Penjualan &amp; Nilai Deal</h6>
                    <span class="text-2xs text-secondary-light">Tren nominal proposal terkirim vs deal closed won</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm radius-6 px-10 py-4 text-xs bg-white border-neutral-300" style="width: 80px;">
                        <option>2026</option>
                        <option>2025</option>
                    </select>
                </div>
            </div>

            <div class="card-body p-16">
                {{-- Compact Legend --}}
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

                {{-- Chart Container --}}
                <div id="salesPerformanceChart" style="min-height: 240px;"></div>
            </div>
        </div>
    </div>

    {{-- Right: Sales Funnel Horizontal Bars --}}
    @php
        $funnelStages = [
            ['name' => '1. Qualification', 'color' => '#64748b', 'count' => $salesFunnel['qualification']['count'] ?: 24, 'pct' => 100],
            ['name' => '2. Need Analysis', 'color' => '#0284c7', 'count' => $salesFunnel['need_analysis']['count'] ?: 18, 'pct' => 75],
            ['name' => '3. Tender / RFQ', 'color' => '#d97706', 'count' => $salesFunnel['tender_rfq']['count'] ?? 15, 'pct' => 62],
            ['name' => '4. Proposal Submitted', 'color' => '#487fff', 'count' => $salesFunnel['proposal_submitted']['count'] ?: 13, 'pct' => 54],
            ['name' => '5. Negotiation', 'color' => '#7c3aed', 'count' => $salesFunnel['negotiation']['count'] ?: 8, 'pct' => 33],
            ['name' => 'Closed Won', 'color' => '#16a34a', 'count' => $salesFunnel['closed_won']['count'] ?: 4, 'pct' => 16],
        ];
    @endphp
    <div class="col-lg-4">
        <div class="card radius-12 border border-neutral-200 shadow-sm bg-white h-100 d-flex flex-column justify-content-between">
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

{{-- SECTION 3: OPPORTUNITY PRIORITAS (STANDAR TABEL WOWDASH) --}}
@php
    $samplePriorities = [
        [
            'title' => 'Spherical Tank Cleaning - CO2',
            'customer' => 'PT Kaltim Parna Industri · Bontang',
            'owner_initial' => 'AR',
            'owner_name' => 'Andi R.',
            'value' => 'Rp 2,40 M',
            'stage' => 'Negotiation',
            'stage_color' => '#7c3aed',
            'stage_badge' => 'bg-purple-50 text-purple-700 border border-purple-200',
            'next_step' => 'Finalisasi komersial',
            'target_close' => '22.10.2026'
        ],
        [
            'title' => 'OLSS Ammonia Converter',
            'customer' => 'PT Pupuk Sriwidjaja · Palembang',
            'owner_initial' => 'JS',
            'owner_name' => 'Juliyanto',
            'value' => 'Rp 2,50 M',
            'stage' => 'Proposal Submitted',
            'stage_color' => '#487fff',
            'stage_badge' => 'bg-primary-50 text-primary-700 border border-primary-200',
            'next_step' => 'Follow-up evaluasi',
            'target_close' => '28.10.2026'
        ],
        [
            'title' => 'Heat Exchanger Cleaning & Inspection',
            'customer' => 'PT Vale Indonesia · Sorowako',
            'owner_initial' => 'DP',
            'owner_name' => 'Dimas P.',
            'value' => 'Rp 1,85 M',
            'stage' => 'Tender / RFQ',
            'stage_color' => '#d97706',
            'stage_badge' => 'bg-warning-50 text-warning-700 border border-warning-200',
            'next_step' => 'Submit technical proposal',
            'target_close' => '30.10.2026'
        ],
        [
            'title' => 'Industrial Valve & Pump Supply',
            'customer' => 'PT Pupuk Kalimantan Timur · Bontang',
            'owner_initial' => 'NA',
            'owner_name' => 'Nisa A.',
            'value' => 'Rp 865 jt',
            'stage' => 'Qualification',
            'stage_color' => '#64748b',
            'stage_badge' => 'bg-neutral-100 text-neutral-700 border border-neutral-300',
            'next_step' => 'Konfirmasi spesifikasi',
            'target_close' => '05.11.2026'
        ],
    ];
@endphp

<div class="card radius-12 border border-neutral-200 shadow-sm bg-white mb-20 overflow-hidden">
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
                @if ($priorityOpportunities->count() >= 3)
                    @foreach ($priorityOpportunities->take(4) as $opp)
                        @php
                            $stageMeta = $stageLabels[$opp->stage] ?? [
                                'name' => ucfirst(str_replace('_', ' ', $opp->stage)),
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
                                    {{ $opp->customer?->company_name ?? '-' }}
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
                    @endforeach
                @else
                    @foreach ($samplePriorities as $item)
                        <tr>
                            <td class="px-16 py-12">
                                <span class="fw-bold text-primary-light text-sm d-block">
                                    {{ $item['title'] }}
                                </span>
                                <span class="text-xs text-secondary-light d-flex align-items-center gap-1 mt-1">
                                    <iconify-icon icon="solar:buildings-bold-duotone" class="text-primary-600"></iconify-icon>
                                    {{ $item['customer'] }}
                                </span>
                            </td>
                            <td class="px-16 py-12">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-24-px h-24-px radius-circle bg-neutral-200 text-neutral-700 text-xs fw-bold d-flex justify-content-center align-items-center">
                                        {{ $item['owner_initial'] }}
                                    </div>
                                    <span class="text-xs fw-medium text-secondary-light">{{ $item['owner_name'] }}</span>
                                </div>
                            </td>
                            <td class="px-16 py-12 text-end text-nowrap">
                                <strong class="text-primary-600 text-sm">
                                    {{ $item['value'] }}
                                </strong>
                            </td>
                            <td class="px-16 py-12">
                                <span class="badge {{ $item['stage_badge'] }} px-8 py-4 radius-6 text-2xs fw-semibold d-inline-flex align-items-center gap-1">
                                    <span class="w-6-px h-6-px radius-circle" style="background-color: {{ $item['stage_color'] }};"></span>
                                    {{ $item['stage'] }}
                                </span>
                            </td>
                            <td class="px-16 py-12 text-xs text-secondary-light">
                                <span class="d-inline-block text-truncate" style="max-width: 240px;">
                                    {{ $item['next_step'] }}
                                </span>
                            </td>
                            <td class="px-16 py-12 text-center text-xs text-secondary-light font-monospace text-nowrap">
                                {{ $item['target_close'] }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- SECTION 4: RECENT ACTIVITIES & UPCOMING FOLLOW-UPS --}}
@php
    $sampleActivities = [
        [
            'icon' => 'solar:users-group-rounded-bold-duotone',
            'color' => '#2563eb',
            'bg' => 'bg-primary-50 text-primary-600',
            'title' => 'Outside Office Meeting · PT Pupuk Sriwidjaja',
            'desc' => 'Review scope OLSS dan jadwal eksekusi - Juliyanto',
            'time' => '09:30'
        ],
        [
            'icon' => 'solar:document-text-bold-duotone',
            'color' => '#ea580c',
            'bg' => 'bg-danger-50 text-danger-main',
            'title' => 'Proposal Submitted · PT Vale Indonesia',
            'desc' => 'Heat Exchanger Cleaning & Inspection - Dimas P.',
            'time' => 'Kemarin'
        ],
        [
            'icon' => 'solar:videocamera-record-bold-duotone',
            'color' => '#7c3aed',
            'bg' => 'bg-purple-50 text-purple-main',
            'title' => 'Online Presentation · PT Kaltim Parna Industri',
            'desc' => 'Presentasi metode robotic tank cleaning - Andi R.',
            'time' => '13 Okt'
        ],
        [
            'icon' => 'solar:map-point-wave-bold-duotone',
            'color' => '#16a34a',
            'bg' => 'bg-success-50 text-success-600',
            'title' => 'Customer Visit · PT Samator',
            'desc' => 'Diskusi kebutuhan filter gas - Nisa A.',
            'time' => '12 Okt'
        ],
    ];

    $sampleFollowups = [
        [
            'day' => '14',
            'month' => 'OKT',
            'title' => 'Follow-up evaluasi proposal',
            'desc' => 'OLSS Ammonia Converter · Juliyanto',
            'badge' => 'HARI INI',
            'badge_class' => 'bg-warning-50 text-warning-700 border border-warning-200'
        ],
        [
            'day' => '15',
            'month' => 'OKT',
            'title' => 'Kirim revisi quotation',
            'desc' => 'CO2 Tank Cleaning · Andi R.',
            'badge' => 'BESOK',
            'badge_class' => 'bg-danger-50 text-danger-700 border border-danger-200'
        ],
        [
            'day' => '16',
            'month' => 'OKT',
            'title' => 'Konfirmasi spesifikasi teknis',
            'desc' => 'Valve & Pump Supply · Nisa A.',
            'badge' => '2 HARI',
            'badge_class' => 'bg-neutral-100 text-secondary-light border border-neutral-200'
        ],
        [
            'day' => '18',
            'month' => 'OKT',
            'title' => 'Customer visit – site survey',
            'desc' => 'Heat Exchanger Project · Dimas P.',
            'badge' => '4 HARI',
            'badge_class' => 'bg-neutral-100 text-secondary-light border border-neutral-200'
        ],
    ];
@endphp

<div class="row g-3 mb-24">
    {{-- Aktivitas Sales Terbaru --}}
    <div class="col-lg-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm bg-white h-100">
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
                @foreach ($sampleActivities as $act)
                    <div class="d-flex align-items-center justify-content-between gap-3 pb-12 border-bottom {{ $loop->last ? 'border-bottom-0 pb-0' : '' }}">
                        <div class="d-flex align-items-center gap-10">
                            <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center {{ $act['bg'] }} text-base flex-shrink-0">
                                <iconify-icon icon="{{ $act['icon'] }}"></iconify-icon>
                            </div>
                            <div>
                                <span class="fw-semibold text-primary-light text-xs d-block">
                                    {{ $act['title'] }}
                                </span>
                                <span class="text-2xs text-secondary-light d-block">
                                    {{ $act['desc'] }}
                                </span>
                            </div>
                        </div>
                        <span class="text-2xs text-secondary-light fw-medium flex-shrink-0">
                            {{ $act['time'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Follow-up Mendatang --}}
    <div class="col-lg-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm bg-white h-100">
            <div class="card-header border-bottom py-12 px-16 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-primary-light mb-0 text-sm">Follow-up Mendatang (Action Items)</h6>
                    <span class="text-2xs text-secondary-light">Jadwal tindak lanjut prioritas peluang proyek</span>
                </div>
                <span class="badge bg-warning-50 text-warning-700 border border-warning-200 text-2xs fw-semibold">
                    {{ count($sampleFollowups) }} Pending
                </span>
            </div>

            <div class="card-body p-16 d-flex flex-column gap-12">
                @foreach ($sampleFollowups as $fu)
                    <div class="d-flex align-items-center justify-content-between gap-3 pb-12 border-bottom {{ $loop->last ? 'border-bottom-0 pb-0' : '' }}">
                        <div class="d-flex align-items-center gap-10">
                            <div class="date-badge-box">
                                <span class="day-num">{{ $fu['day'] }}</span>
                                <span class="month-name">{{ $fu['month'] }}</span>
                            </div>
                            <div>
                                <span class="fw-semibold text-primary-light text-xs d-block">
                                    {{ $fu['title'] }}
                                </span>
                                <span class="text-2xs text-secondary-light d-block">
                                    {{ $fu['desc'] }}
                                </span>
                            </div>
                        </div>
                        <span class="badge {{ $fu['badge_class'] }} px-8 py-3 radius-4 text-2xs fw-bold flex-shrink-0">
                            {{ $fu['badge'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- APEXCHARTS SCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartElement = document.querySelector("#salesPerformanceChart");
        if (!chartElement) return;

        const options = {
            series: [
                {
                    name: "Proposal terkirim",
                    data: [1.0, 1.2, 1.1, 1.5, 1.4, 1.8, 1.6]
                },
                {
                    name: "Closed Won",
                    data: [0.7, 0.8, 0.7, 1.1, 0.9, 1.3, 1.2]
                }
            ],
            chart: {
                height: 240,
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'inherit',
                zoom: { enabled: false }
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
                categories: ['Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'],
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
                max: 2.2,
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
    });
</script>
@endsection
