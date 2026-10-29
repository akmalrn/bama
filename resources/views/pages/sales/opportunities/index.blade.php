@extends('layout.layout')

@php
    $title = 'Sales Pipeline';
    $subTitle = 'Sales Pipeline Overview & Lead Management — PT ELBEMA Integra Indonesia';
@endphp

@section('content')


<style>
    .pipeline-metric-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .pipeline-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
    }
    .mini-breakdown-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }
    .mini-breakdown-row:last-child {
        margin-bottom: 0;
    }
    .mini-bar-stack {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        height: 48px;
        padding-bottom: 4px;
    }
    .mini-bar-stack .bar-col {
        flex: 1;
        border-radius: 4px;
        transition: opacity 0.2s;
    }
    .kanban-container::-webkit-scrollbar {
        height: 8px;
    }
    .kanban-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .kanban-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .kanban-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    .crm-kanban-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .crm-kanban-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.08) !important;
    }
</style>


<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-20">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">Sales Workspace</span>
            <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-8 py-2 text-xs fw-semibold d-inline-flex align-items-center gap-1">
                <span class="w-6-px h-6-px radius-circle bg-warning-main d-inline-block"></span>
                Data Pipeline
            </span>
        </div>
        <h5 class="mb-2 fw-bold text-primary-light">Sales Pipeline Overview</h5>
        <p class="text-secondary-light mb-0 text-xs">
            Track project opportunities, sales performance, and follow-up progress across the ELBEMA team.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm radius-8 px-12 py-6 text-xs d-flex align-items-center gap-2 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <span>{{ $selectedYear === 'all' ? 'All Time (Semua)' : ($selectedYear == '2026' ? 'This Year (2026)' : 'Year (' . $selectedYear . ')') }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm radius-8 text-xs p-6 border border-neutral-200">
                <li>
                    <a class="dropdown-item py-6 px-12 radius-4 {{ $selectedYear == '2026' ? 'active bg-primary-600 text-white' : '' }}" 
                       href="{{ request()->fullUrlWithQuery(['year' => 2026]) }}">This Year (2026)</a>
                </li>
                <li>
                    <a class="dropdown-item py-6 px-12 radius-4 {{ $selectedYear == '2025' ? 'active bg-primary-600 text-white' : '' }}" 
                       href="{{ request()->fullUrlWithQuery(['year' => 2025]) }}">Last Year (2025)</a>
                </li>
                <li><hr class="dropdown-divider my-4"></li>
                <li>
                    <a class="dropdown-item py-6 px-12 radius-4 {{ $selectedYear === 'all' ? 'active bg-primary-600 text-white' : '' }}" 
                       href="{{ request()->fullUrlWithQuery(['year' => 'all']) }}">All Time (Semua)</a>
                </li>
            </ul>
        </div>

        
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm radius-8 px-12 py-6 text-xs d-flex align-items-center gap-1 dropdown-toggle"
                    type="button"
                    id="exportPipelineDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <iconify-icon icon="solar:download-linear"></iconify-icon> Export
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border border-neutral-200 radius-8 p-8" aria-labelledby="exportPipelineDropdown">
                <li class="dropdown-header text-xxs text-uppercase fw-semibold text-secondary-light px-12 py-4">
                    Format ({{ $selectedYear === 'all' ? 'All Time' : 'Tahun ' . $selectedYear }})
                </li>
                <li>
                    <a class="dropdown-item radius-6 px-12 py-6 text-xs d-flex align-items-center gap-2"
                       href="{{ route('dashboard-admin.sales.opportunities.export', ['type' => 'excel', 'year' => $selectedYear]) }}">
                        <iconify-icon icon="vscode-icons:file-type-excel" class="text-base flex-shrink-0"></iconify-icon>
                        <div>
                            <div class="fw-semibold text-primary-light">Export ke Excel (.csv)</div>
                            <small class="text-secondary-light text-xxs">Data pipeline &amp; KPI {{ $selectedYear === 'all' ? 'semua tahun' : 'tahun ' . $selectedYear }}</small>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider my-4"></li>
                <li>
                    <a class="dropdown-item radius-6 px-12 py-6 text-xs d-flex align-items-center gap-2"
                       href="{{ route('dashboard-admin.sales.opportunities.export', ['type' => 'pdf', 'year' => $selectedYear]) }}"
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

        
        @can('sales.opportunity.create')
            <button type="button" class="btn btn-primary btn-sm radius-8 px-14 py-6 text-xs fw-semibold d-flex align-items-center gap-1 shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#createOpportunityModal">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="text-sm"></iconify-icon>
                + Add Project Lead
            </button>
        @endcan
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 pipeline-metric-card h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Weighted Pipeline Value</span>
                <h5 class="fw-bold text-primary-light mb-2 text-nowrap">
                    @if ($weightedPipelineVal >= 1000000000)
                        Rp {{ number_format($weightedPipelineVal / 1000000000, 2, ',', '.') }} M
                    @elseif ($weightedPipelineVal >= 1000000)
                        Rp {{ number_format($weightedPipelineVal / 1000000, 1, ',', '.') }} Jt
                    @else
                        Rp {{ number_format($weightedPipelineVal, 0, ',', '.') }}
                    @endif
                </h5>
                <div class="text-2xs mb-8">
                    <span class="badge bg-primary-50 text-primary-600 border border-primary-100 px-6 py-2 radius-4 fw-semibold">Probability Weighted</span>
                    <span class="text-secondary-light ms-1">dari Rp {{ number_format($totalActiveValue / 1000000000, 2, ',', '.') }} M</span>
                </div>
                <div id="sparklineWeightPipeline" style="height: 44px;"></div>
            </div>
            <div class="pt-8 border-top mt-12">
                <span class="fw-bold text-xs text-primary-light d-block">Pipeline Growth Pattern</span>
                <span class="text-2xs text-secondary-light">Review lead value changes and identify growth opportunities.</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 pipeline-metric-card h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Active Project Leads</span>
                <h5 class="fw-bold text-primary-light mb-2">
                    {{ $activeLeadsCount }}
                </h5>
                <div class="text-2xs mb-8">
                    <span class="badge bg-success-50 text-success-main border border-success-100 px-6 py-2 radius-4 fw-semibold">Active In Pipeline</span>
                    <span class="text-secondary-light ms-1">leads aktif</span>
                </div>
                
                <div class="mini-bar-stack">
                    @foreach ($leadSources as $src)
                        @php
                            $barH = $activeLeadsCount > 0 ? max(25, round(($src['count'] / max(1, $activeLeadsCount)) * 100)) : 30;
                        @endphp
                        <div class="bar-col" style="height: {{ $barH }}%; background-color: {{ $src['color'] }};" title="{{ $src['name'] }}: {{ $src['count'] }}"></div>
                    @endforeach
                </div>
            </div>
            <div class="pt-8 border-top mt-12">
                <span class="fw-bold text-xs text-primary-light d-block">Lead Source Distribution</span>
                <span class="text-2xs text-secondary-light">See which channels bring project inquiries to ELBEMA.</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 pipeline-metric-card h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Qualification Rate</span>
                <h5 class="fw-bold text-primary-light mb-2">
                    {{ number_format($qualificationRate, 1, ',', '.') }}%
                </h5>
                <div class="text-2xs mb-8">
                    <span class="badge bg-warning-50 text-warning-main border border-warning-100 px-6 py-2 radius-4 fw-semibold">{{ $qualifiedLeadsCount }} dari {{ $totalOpportunitiesCount }}</span>
                    <span class="text-secondary-light ms-1">qualified deals</span>
                </div>
                <div id="sparklineQualRate" style="height: 44px;"></div>
            </div>
            <div class="pt-8 border-top mt-12">
                <span class="fw-bold text-xs text-primary-light d-block">Qualification Trend</span>
                <span class="text-2xs text-secondary-light">Monitor movement from initial inquiry to qualified project.</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 pipeline-metric-card h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Qualified Project Leads</span>
                <h5 class="fw-bold text-primary-light mb-2">
                    {{ $qualifiedLeadsCount }}
                </h5>
                <div class="text-2xs mb-8">
                    <span class="badge bg-info-50 text-info-main border border-info-100 px-6 py-2 radius-4 fw-semibold">Tender / Proposal / Negot.</span>
                </div>
                <div id="sparklineQualified" style="height: 44px;"></div>
            </div>
            <div class="pt-8 border-top mt-12">
                <span class="fw-bold text-xs text-primary-light d-block">Sales Qualification Results</span>
                <span class="text-2xs text-secondary-light">Qualified leads are ready for opportunity assessment.</span>
            </div>
        </div>
    </div>
</div>


<div class="mb-12">
    <h6 class="fw-bold text-primary-light mb-1 text-sm">Team Productivity &amp; Project Lead Goals</h6>
    <p class="text-secondary-light mb-0 text-2xs">Monitor activity completion, lead quality, and coverage by region.</p>
</div>

<div class="row g-3 mb-24">
    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-bold text-primary-light d-block">Follow-up Completion</span>
                <span class="text-2xs text-secondary-light d-block mb-12">Planned sales activities completed</span>

                
                <div class="d-flex align-items-end justify-content-between gap-1 mb-8" style="height: 42px;">
                    @php
                        $barHeights = [
                            max(20, round($followUpCompletion * 0.4)),
                            max(25, round($followUpCompletion * 0.6)),
                            max(30, round($followUpCompletion * 0.5)),
                            max(35, round($followUpCompletion * 0.7)),
                            max(40, round($followUpCompletion * 0.85)),
                            max(45, round($followUpCompletion * 0.9)),
                            max(30, round($followUpCompletion * 0.75)),
                            max(50, round($followUpCompletion * 0.95)),
                            max(40, round($followUpCompletion * 0.8)),
                            max(35, round($followUpCompletion * 0.7)),
                            max(45, round($followUpCompletion * 0.9)),
                            max(20, $followUpCompletion)
                        ];
                    @endphp
                    @foreach ($barHeights as $bh)
                        <div class="flex-grow-1 radius-2" style="height: {{ $bh }}%; background-color: {{ $loop->last ? '#0284c7' : '#bae6fd' }};"></div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-between text-2xs text-secondary-light mb-8">
                    <span>0%</span>
                    <span class="fw-bold text-primary-600">{{ $followUpCompletion }}% complete</span>
                    <span>100%</span>
                </div>
            </div>
            <div class="pt-8 border-top">
                <span class="fw-bold text-xs text-primary-light d-block">Sales Activity Progress</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-bold text-primary-light d-block">Customer Lead Sources</span>
                <span class="text-2xs text-secondary-light d-block mb-12">Origin of registered project leads</span>

                @foreach ($leadSources as $src)
                    <div class="mini-breakdown-row text-2xs">
                        <span class="text-secondary-light text-truncate" style="width: 75px;">{{ $src['name'] }}</span>
                        <div class="progress flex-grow-1" style="height: 6px; border-radius: 3px; background-color: #f1f5f9;">
                            <div class="progress-bar radius-3" style="width: {{ $src['pct'] }}%; background-color: {{ $src['color'] }};"></div>
                        </div>
                        <span class="fw-bold text-primary-light text-end" style="width: 20px;">{{ $src['count'] }}</span>
                    </div>
                @endforeach
            </div>
            <div class="pt-8 border-top mt-8">
                <span class="fw-bold text-xs text-primary-light d-block">Customer Acquisition Mix</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-bold text-primary-light d-block">Sales Lead Status</span>
                <span class="text-2xs text-secondary-light d-block mb-12">Track movement across the lead pipeline</span>

                @foreach ($leadStatuses as $lst)
                    <div class="mini-breakdown-row text-2xs">
                        <span class="text-secondary-light text-truncate" style="width: 75px;">{{ $lst['name'] }}</span>
                        <div class="progress flex-grow-1" style="height: 6px; border-radius: 3px; background-color: #f1f5f9;">
                            <div class="progress-bar radius-3" style="width: {{ $lst['pct'] }}%; background-color: {{ $lst['color'] }};"></div>
                        </div>
                        <span class="fw-bold text-primary-light text-end" style="width: 20px;">{{ $lst['count'] }}</span>
                    </div>
                @endforeach
            </div>
            <div class="pt-8 border-top mt-8">
                <span class="fw-bold text-xs text-primary-light d-block">Lead Pipeline Overview</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-bold text-primary-light d-block">Project Lead by Region</span>
                <span class="text-2xs text-secondary-light d-block mb-12">Coverage of customer projects in Indonesia</span>

                @foreach ($regionalBreakdown as $reg)
                    <div class="mini-breakdown-row text-2xs">
                        <span class="text-secondary-light text-truncate" style="width: 90px;" title="{{ $reg['region'] }}">{{ $reg['region'] }}</span>
                        <div class="progress flex-grow-1" style="height: 6px; border-radius: 3px; background-color: #f1f5f9;">
                            <div class="progress-bar radius-3" style="width: {{ $reg['pct'] }}%; background-color: {{ $reg['color'] }};"></div>
                        </div>
                        <span class="fw-bold text-primary-light text-end" style="width: 25px;">{{ $reg['count'] }}</span>
                    </div>
                @endforeach
            </div>
            <div class="pt-8 border-top mt-8">
                <span class="fw-bold text-xs text-primary-light d-block">Regional Project Coverage</span>
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border border-neutral-200 shadow-sm mb-24 overflow-hidden">
    
    <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold text-primary-light mb-0 text-sm">Lead &amp; Opportunity Pipeline</h6>
                <span class="badge bg-neutral-100 text-secondary-light border border-neutral-200 text-2xs px-6 py-2 radius-4">
                    {{ $totalOpportunitiesCount }} Total Records
                </span>
            </div>
            <span class="text-2xs text-secondary-light">Lead converts when an RFQ, proposal request, or bidding invitation is confirmed.</span>
        </div>

        
        <div class="btn-group p-1 radius-8 border bg-neutral-50" role="group">
            <button type="button" id="btnViewTable" onclick="switchPipelineView('table')"
               class="btn btn-sm {{ $viewMode === 'table' ? 'btn-primary text-white fw-semibold shadow-xs' : 'btn-ghost text-secondary-light' }} radius-6 px-12 py-4 text-xs d-flex align-items-center gap-1">
                <iconify-icon icon="solar:list-check-bold-duotone"></iconify-icon>
                Tabel Pipeline
            </button>
            <button type="button" id="btnViewKanban" onclick="switchPipelineView('kanban')"
               class="btn btn-sm {{ $viewMode === 'kanban' ? 'btn-primary text-white fw-semibold shadow-xs' : 'btn-ghost text-secondary-light' }} radius-6 px-12 py-4 text-xs d-flex align-items-center gap-1">
                <iconify-icon icon="solar:kanban-bold-duotone"></iconify-icon>
                Kanban Board
            </button>
        </div>
    </div>

    
    <div class="p-16 border-bottom bg-neutral-50">
        <form action="{{ route('dashboard-admin.sales.opportunities.index') }}" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="view" value="{{ $viewMode }}" id="filterViewInput">

            <div class="col-md-4 col-sm-6">
                <div class="position-relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search project, customer, or location..."
                           class="form-control form-control-sm radius-8 pe-40 text-xs">
                    <iconify-icon icon="ion:search-outline" class="position-absolute end-0 top-50 translate-middle-y me-12 text-secondary-light text-sm"></iconify-icon>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <select name="customer_id" class="form-select form-select-sm radius-8 text-xs" onchange="this.form.submit()">
                    <option value="">All Customers</option>
                    @foreach ($customers as $cust)
                        <option value="{{ $cust->id }}" {{ request('customer_id') == $cust->id ? 'selected' : '' }}>
                            {{ $cust->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <select name="owner_id" class="form-select form-select-sm radius-8 text-xs" onchange="this.form.submit()">
                    <option value="">All Sales Owners</option>
                    @foreach ($salesUsers as $su)
                        <option value="{{ $su->id }}" {{ request('owner_id') == $su->id ? 'selected' : '' }}>
                            {{ $su->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 col-sm-6 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary btn-sm radius-8 px-12 py-6 w-100 d-flex align-items-center justify-content-center gap-1 text-xs">
                    <iconify-icon icon="solar:filter-bold-duotone"></iconify-icon> Filter
                </button>
                @if (request()->hasAny(['search', 'customer_id', 'owner_id', 'stage']))
                    <a href="{{ route('dashboard-admin.sales.opportunities.index', ['view' => $viewMode]) }}" class="btn btn-outline-secondary btn-sm radius-8 px-10 py-6" title="Reset filter">
                        <iconify-icon icon="solar:restart-bold"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>

    @php
        $stageColors = [
            'qualification' => ['color' => '#64748b', 'badge' => 'bg-neutral-100 text-neutral-700 border border-neutral-300'],
            'need_analysis' => ['color' => '#0284c7', 'badge' => 'bg-info-50 text-info-700 border border-info-200'],
            'tender_rfq' => ['color' => '#d97706', 'badge' => 'bg-warning-50 text-warning-700 border border-warning-200'],
            'proposal_submitted' => ['color' => '#487fff', 'badge' => 'bg-primary-50 text-primary-700 border border-primary-200'],
            'negotiation' => ['color' => '#7c3aed', 'badge' => 'bg-purple-50 text-purple-700 border border-purple-200'],
            'closed_won' => ['color' => '#16a34a', 'badge' => 'bg-success-50 text-success-700 border border-success-200'],
            'closed_lost' => ['color' => '#dc2626', 'badge' => 'bg-danger-50 text-danger-700 border border-danger-200'],
        ];
    @endphp

    
    
    
    <div id="pipelineKanbanView" class="{{ $viewMode === 'kanban' ? '' : 'd-none' }}">
        <div class="d-flex gap-12 overflow-x-auto p-16 kanban-container" style="min-height: 520px;">
            @foreach ($kanbanColumns as $stageKey => $column)
                @php
                    $info = $column['info'];
                    $items = $column['items'];
                    $stageCol = $stageColors[$stageKey] ?? ['color' => '#64748b', 'badge' => 'bg-neutral-100 text-neutral-700'];
                @endphp
                <div class="kanban-column flex-shrink-0 radius-10 p-10 border border-neutral-200 bg-neutral-50 shadow-xs" style="width: 270px;">
                    
                    <div class="d-flex justify-content-between align-items-center mb-10 px-2 pb-8 border-bottom">
                        <div style="min-width: 0;">
                            <h6 class="fw-bold mb-0 text-xs text-primary-light text-truncate" title="{{ $info['label'] }}" style="line-height: 1.2;">
                                {{ $info['label'] }}
                            </h6>
                            <span class="text-2xs text-secondary-light font-monospace fw-medium d-block mt-1">
                                Rp {{ number_format($column['total_value'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-1 flex-shrink-0 ms-2">
                            <span class="badge bg-neutral-200 text-secondary-light border border-neutral-300 text-2xs px-6 py-2 rounded-pill fw-bold shadow-xs">
                                {{ $column['count'] }}
                            </span>
                            @can('sales.opportunity.create')
                                <button type="button" class="w-22-px h-22-px radius-circle bg-neutral-100 border border-neutral-300 text-secondary-light hover-bg-primary hover-text-white d-flex align-items-center justify-content-center transition-all shadow-xs"
                                        title="Tambah opportunity di tahap {{ $info['label'] }}"
                                        onclick="openCreateOpportunityModal('{{ $stageKey }}')">
                                    <iconify-icon icon="ic:round-add" class="text-xs"></iconify-icon>
                                </button>
                            @endcan
                        </div>
                    </div>

                    
                    <div class="d-flex flex-column gap-10" style="min-height: 160px;">
                        @forelse ($items as $opp)
                            <div class="card border border-neutral-200 shadow-xs radius-8 p-12 crm-kanban-card position-relative overflow-hidden mb-2">
                                
                                <div class="d-flex justify-content-between align-items-center mb-6">
                                    <span class="badge bg-neutral-100 text-secondary-light font-monospace text-2xs px-6 py-2 radius-4 fw-semibold">
                                        #OPP-{{ str_pad((string)$opp->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div class="dropdown">
                                        <button class="btn btn-xs btn-ghost p-0 text-secondary-light hover-text-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <iconify-icon icon="solar:menu-dots-bold" class="text-sm"></iconify-icon>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg radius-8 text-xs p-6 border border-neutral-200">
                                            <li>
                                                <button type="button" class="dropdown-item py-6 px-12 radius-4 d-flex align-items-center"
                                                        onclick='openOpportunityDetailModal(@json($opp->loadMissing(["customer", "owner"])))'>
                                                    <iconify-icon icon="solar:eye-bold-duotone" class="me-1 text-info-main"></iconify-icon> Detail Project Lead
                                                </button>
                                            </li>
                                            @can('sales.opportunity.edit')
                                                <li>
                                                    <button type="button" class="dropdown-item py-6 px-12 radius-4 d-flex align-items-center"
                                                            onclick='openOpportunityEditModal(@json($opp->loadMissing(["customer", "owner"])))'>
                                                        <iconify-icon icon="solar:pen-bold-duotone" class="me-1 text-success-main"></iconify-icon> Edit Lead
                                                    </button>
                                                </li>
                                            @endcan
                                            @can('sales.opportunity.delete')
                                                <li><hr class="dropdown-divider my-4"></li>
                                                <li>
                                                    <form id="delete-form-k-{{ $opp->id }}" action="{{ route('dashboard-admin.sales.opportunities.destroy', $opp) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn-delete-id dropdown-item py-6 px-12 radius-4 text-danger-main d-flex align-items-center" data-id="k-{{ $opp->id }}">
                                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="me-1"></iconify-icon> Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>

                                
                                <div class="mb-8">
                                    <button type="button" class="btn p-0 text-start border-0 bg-transparent fw-bold text-primary-light hover-text-primary text-xs d-block mb-3"
                                            style="line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                                            title="{{ $opp->title }}"
                                            onclick='openOpportunityDetailModal(@json($opp->loadMissing(["customer", "owner"])))'>
                                        {{ $opp->title }}
                                    </button>
                                    <div class="text-2xs text-secondary-light d-flex align-items-center gap-1">
                                        <iconify-icon icon="solar:buildings-bold-duotone" class="text-neutral-400 flex-shrink-0 text-xs"></iconify-icon>
                                        <span class="text-truncate fw-medium" style="max-width: 200px;" title="{{ $opp->customer->company_name ?? '-' }}">{{ $opp->customer->company_name ?? '-' }}</span>
                                    </div>
                                </div>

                                
                                <div class="pt-8 border-top mt-8 d-flex justify-content-between align-items-center gap-2">
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <span class="text-2xs text-secondary-light text-uppercase fw-semibold d-block mb-1" style="letter-spacing: 0.3px;">Est. Deal Value</span>
                                        <strong class="text-primary-600 text-xs text-nowrap d-block">
                                            Rp {{ number_format((float)$opp->estimated_value, 0, ',', '.') }}
                                        </strong>
                                    </div>

                                    @can('sales.opportunity.edit')
                                        <div class="dropdown flex-shrink-0">
                                            <button class="btn btn-xs btn-outline-secondary py-2 px-8 radius-6 text-2xs d-inline-flex align-items-center gap-1 text-secondary-light hover-text-primary" type="button" data-bs-toggle="dropdown" title="Pindahkan Tahap Pipeline">
                                                <span>Pindah</span>
                                                <iconify-icon icon="solar:alt-arrow-right-outline" class="text-2xs"></iconify-icon>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg radius-8 text-xs p-6 border border-neutral-200" style="min-width: 195px;">
                                                <li class="dropdown-header text-2xs text-uppercase text-secondary-light px-8 py-4 fw-bold">Pindahkan ke:</li>
                                                @foreach ($stages as $sKey => $sVal)
                                                    @if ($sKey !== $opp->stage)
                                                        @php $targetStageCol = $stageColors[$sKey] ?? ['color' => '#64748b']; @endphp
                                                        <li>
                                                            <button type="button" class="dropdown-item py-6 px-8 radius-6 d-flex align-items-center gap-2 text-xs" onclick="quickChangeStage({{ $opp->id }}, '{{ $sKey }}', '{{ addslashes($sVal['label']) }}')">
                                                                <span class="w-8-px h-8-px radius-circle flex-shrink-0" style="background-color: {{ $targetStageCol['color'] }};"></span>
                                                                <span class="fw-medium">{{ $sVal['label'] }}</span>
                                                            </button>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endcan
                                </div>

                                
                                <div class="pt-6 mt-6 border-top d-flex justify-content-between align-items-center text-2xs text-secondary-light">
                                    <div class="d-flex align-items-center gap-1" title="Sales PIC: {{ $opp->owner->name ?? '-' }}">
                                        <iconify-icon icon="solar:user-circle-bold" class="text-neutral-400 text-sm"></iconify-icon>
                                        <span class="text-truncate fw-medium" style="max-width: 95px;">{{ $opp->owner->name ?? '-' }}</span>
                                    </div>

                                    @if($opp->target_close_date)
                                        <span class="d-flex align-items-center gap-1 font-monospace" title="Target Closing">
                                            <iconify-icon icon="solar:calendar-date-outline" class="text-xs"></iconify-icon>
                                            {{ \Carbon\Carbon::parse($opp->target_close_date)->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-20 px-12 text-secondary-light text-2xs border border-dashed border-neutral-300 radius-8 d-flex flex-column align-items-center justify-content-center gap-1">
                                <iconify-icon icon="solar:inbox-line" class="text-lg text-neutral-400"></iconify-icon>
                                <span>Belum ada opportunity</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    
    
    
    <div id="pipelineTableView" class="{{ $viewMode === 'table' ? '' : 'd-none' }}">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-100">
                    <tr>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-20">Project Lead / Customer</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16">Service Line</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16">Lead Source</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16">Sales Owner</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($opportunities as $index => $opp)
                        @php
                            $leadId = 'LD-' . ($opp->created_at ? $opp->created_at->format('Y') : '2026') . '-' . str_pad((string)$opp->id, 3, '0', STR_PAD_LEFT);
                        @endphp
                        <tr>
                            <td class="px-20 py-14">
                                <button type="button" class="btn p-0 text-start border-0 bg-transparent fw-bold text-primary-light hover-text-primary text-sm d-block mb-1"
                                        onclick='openOpportunityDetailModal(@json($opp->loadMissing(["customer", "owner"])))'>
                                    {{ $opp->title }}
                                </button>
                                <div class="text-2xs text-secondary-light d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-6 py-2 radius-4 font-monospace fw-semibold">{{ $leadId }}</span>
                                    <span class="text-neutral-300">•</span>
                                    <span class="fw-semibold text-neutral-700">{{ $opp->customer->company_name ?? '-' }}</span>
                                    @if ($opp->project_location || $opp->customer?->location)
                                        <span class="text-neutral-300">•</span>
                                        <span class="text-secondary-light">
                                            <iconify-icon icon="solar:map-point-linear" class="text-2xs"></iconify-icon>
                                            {{ $opp->project_location ?: $opp->customer->location }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-16 py-14">
                                <span class="badge bg-neutral-100 text-neutral-800 border border-neutral-300 px-10 py-5 radius-6 text-xs fw-semibold">
                                    {{ $opp->service_line ?: 'Industrial Services' }}
                                </span>
                            </td>
                            <td class="px-16 py-14">
                                <span class="text-xs text-secondary-light fw-medium">
                                    {{ $opp->lead_source ?: 'Existing Customer' }}
                                </span>
                            </td>
                            <td class="px-16 py-14">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-28-px h-28-px radius-circle bg-primary-50 text-primary-600 text-xs fw-bold d-flex justify-content-center align-items-center border border-primary-100 flex-shrink-0">
                                        {{ strtoupper(substr($opp->owner->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-xs fw-semibold text-primary-light">{{ $opp->owner->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-16 py-14 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <button type="button"
                                            class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center border-0 transition-all hover-scale"
                                            title="Lihat Detail"
                                            onclick='openOpportunityDetailModal(@json($opp->loadMissing(["customer", "owner"])))'>
                                        <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                    </button>

                                    @can('sales.opportunity.edit')
                                        <button type="button"
                                                class="w-32-px h-32-px rounded-circle bg-success-focus text-success-main d-inline-flex justify-content-center align-items-center border-0 transition-all hover-scale"
                                                title="Edit Lead"
                                                onclick='openOpportunityEditModal(@json($opp->loadMissing(["customer", "owner"])))'>
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </button>
                                    @endcan

                                    @can('sales.opportunity.delete')
                                        <form action="{{ route('dashboard-admin.sales.opportunities.destroy', $opp) }}" method="POST"
                                              id="delete-form-{{ $opp->id }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0 transition-all hover-scale btn-delete-id" data-id="{{ $opp->id }}" title="Hapus">
                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-32 text-secondary-light text-xs">
                                Tidak ada data project lead yang cocok dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($opportunities->hasPages())
            <div class="p-16 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-secondary-light text-xs">
                    Menampilkan {{ $opportunities->firstItem() }} sampai {{ $opportunities->lastItem() }} dari total {{ $opportunities->total() }} opportunity
                </span>
                <div>
                    {{ $opportunities->links() }}
                </div>
            </div>
        @endif
    </div>
</div>


@can('sales.opportunity.create')
<div class="modal fade" id="createOpportunityModal" tabindex="-1" aria-labelledby="createOpportunityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
            
            <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title fw-bold text-primary-light mb-1 text-lg" id="createOpportunityModalLabel">Tambah Project Lead</h5>
                    <p class="text-secondary-light text-xs mb-0">Masukkan informasi awal proyek yang akan dipantau tim Sales.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-24">
                @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 radius-8 mb-16" role="alert">
                        <div class="fw-semibold mb-2 text-xs">Terdapat kesalahan pengisian data:</div>
                        <ul class="mb-0 text-2xs ps-16">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('dashboard-admin.sales.opportunities.store') }}" method="POST" id="createOpportunityForm">
                    @csrf

                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Nama Project / Scope <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                   class="form-control form-control-sm radius-8 @error('title') is-invalid @enderror"
                                   placeholder="Contoh: Tank Cleaning Campaign" required>
                            @error('title')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Customer <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="customer" list="customerSuggestions" value="{{ old('customer') }}"
                                   class="form-control form-control-sm radius-8 @error('customer') is-invalid @enderror"
                                   placeholder="Nama perusahaan customer" required>
                            <datalist id="customerSuggestions">
                                @foreach ($customers as $c)
                                    <option value="{{ $c->company_name }}">{{ $c->company_name }} ({{ $c->industry ?? 'General' }})</option>
                                @endforeach
                            </datalist>
                            @error('customer')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Service Line <span class="text-danger">*</span>
                            </label>
                            <select name="service_line" class="form-select form-select-sm radius-8 @error('service_line') is-invalid @enderror" required>
                                <option value="UHP Cleaning" {{ old('service_line', 'UHP Cleaning') === 'UHP Cleaning' ? 'selected' : '' }}>UHP Cleaning</option>
                                <option value="Hydrojetting" {{ old('service_line') === 'Hydrojetting' ? 'selected' : '' }}>Hydrojetting</option>
                                <option value="Chemical Cleaning" {{ old('service_line') === 'Chemical Cleaning' ? 'selected' : '' }}>Chemical Cleaning</option>
                                <option value="Tank Cleaning" {{ old('service_line') === 'Tank Cleaning' ? 'selected' : '' }}>Tank Cleaning</option>
                                <option value="Cold Cutting & Pipe Bevelling" {{ old('service_line') === 'Cold Cutting & Pipe Bevelling' ? 'selected' : '' }}>Cold Cutting &amp; Pipe Bevelling</option>
                                <option value="General Maintenance" {{ old('service_line') === 'General Maintenance' ? 'selected' : '' }}>General Maintenance</option>
                                <option value="Civil & Construction" {{ old('service_line') === 'Civil & Construction' ? 'selected' : '' }}>Civil &amp; Construction</option>
                                <option value="Other" {{ old('service_line') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('service_line')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Lokasi Proyek
                            </label>
                            <input type="text" name="project_location" value="{{ old('project_location') }}"
                                   class="form-control form-control-sm radius-8 @error('project_location') is-invalid @enderror"
                                   placeholder="Kota / site">
                            @error('project_location')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Lead Source
                            </label>
                            <select name="lead_source" class="form-select form-select-sm radius-8 @error('lead_source') is-invalid @enderror">
                                <option value="Existing Customer" {{ old('lead_source', 'Existing Customer') === 'Existing Customer' ? 'selected' : '' }}>Existing Customer</option>
                                <option value="Referral" {{ old('lead_source') === 'Referral' ? 'selected' : '' }}>Referral</option>
                                <option value="Tender / RFQ" {{ old('lead_source') === 'Tender / RFQ' ? 'selected' : '' }}>Tender / RFQ</option>
                                <option value="Website / Inbound" {{ old('lead_source') === 'Website / Inbound' ? 'selected' : '' }}>Website / Inbound</option>
                                <option value="Direct Outreach" {{ old('lead_source') === 'Direct Outreach' ? 'selected' : '' }}>Direct Outreach</option>
                                <option value="Other" {{ old('lead_source') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('lead_source')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Estimasi Nilai Proyek (Rp)
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text radius-8-start text-xs fw-bold px-12">Rp</span>
                                <input type="text" inputmode="numeric" name="estimated_value" id="createEstimatedValue"
                                       value="{{ old('estimated_value') ? number_format((float) preg_replace('/[^0-9]/', '', old('estimated_value')), 0, ',', '.') : '' }}"
                                       class="form-control form-control-sm radius-8-end rupiah-input @error('estimated_value') is-invalid @enderror"
                                       placeholder="10.000" autocomplete="off">
                            </div>
                            @error('estimated_value')
                                <div class="invalid-feedback text-xs d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Sales Owner
                            </label>
                            <select name="owner_id" class="form-select form-select-sm radius-8 @error('owner_id') is-invalid @enderror" required>
                                @foreach ($salesUsers as $su)
                                    <option value="{{ $su->id }}" {{ old('owner_id', auth()->id()) == $su->id ? 'selected' : '' }}>
                                        {{ $su->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('owner_id')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Next Follow-up
                            </label>
                            <input type="date" name="next_follow_up_date" value="{{ old('next_follow_up_date') }}"
                                   class="form-control form-control-sm radius-8 @error('next_follow_up_date') is-invalid @enderror">
                            @error('next_follow_up_date')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Catatan Awal
                            </label>
                            <textarea name="notes" rows="3"
                                      class="form-control form-control-sm radius-8 @error('notes') is-invalid @enderror"
                                      placeholder="Kebutuhan customer, sumber informasi, atau langkah berikutnya">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            
            <div class="modal-footer py-14 px-24 border-top d-flex justify-content-end align-items-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-20 py-8 text-xs" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" id="btnSubmit" form="createOpportunityForm" class="btn btn-primary btn-sm radius-8 px-20 py-8 text-xs fw-semibold" style="background-color: #5551ff; border-color: #5551ff;">
                    Simpan Project Lead
                </button>
            </div>
        </div>
    </div>
</div>
@endcan




<div class="modal fade" id="detailOpportunityModal" tabindex="-1" aria-labelledby="detailOpportunityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
            <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title fw-bold text-primary-light mb-1 text-lg" id="detailOpportunityModalLabel">Detail Project Lead</h5>
                    <p class="text-secondary-light text-xs mb-0">Informasi lengkap peluang proyek dan progress tim Sales.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-24">
                
                <div class="p-16 radius-12 bg-neutral-50 border border-neutral-200 mb-20 d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div style="min-width: 0; max-width: 75%;">
                        <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-3 radius-4 text-xs font-monospace fw-bold mb-2 d-inline-block" id="detailCode">
                            LD-2026-000
                        </span>
                        <h5 class="fw-bold text-primary-light mb-1 text-base text-break" id="detailTitle">
                            -
                        </h5>
                        <div class="text-xs text-secondary-light d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-semibold text-neutral-800" id="detailCustomer">-</span>
                            <span class="text-neutral-300">•</span>
                            <span id="detailCustomerLocation">-</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-neutral-100 text-neutral-800 border px-10 py-5 radius-6 text-xs fw-bold d-inline-block mb-1" id="detailStageBadge">
                            Qualification
                        </span>
                        <div class="text-2xs text-secondary-light" id="detailProbText">Probabilitas: 20%</div>
                    </div>
                </div>

                
                <div class="row g-3 mb-20">
                    <div class="col-md-6">
                        <div class="p-12 radius-8 border border-neutral-200 bg-white">
                            <span class="text-2xs text-uppercase fw-semibold text-secondary-light d-block mb-1">Service Line</span>
                            <span class="text-xs fw-bold text-primary-light" id="detailServiceLine">-</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-12 radius-8 border border-neutral-200 bg-white">
                            <span class="text-2xs text-uppercase fw-semibold text-secondary-light d-block mb-1">Lokasi Proyek</span>
                            <span class="text-xs fw-bold text-primary-light" id="detailLocation">-</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-12 radius-8 border border-neutral-200 bg-white">
                            <span class="text-2xs text-uppercase fw-semibold text-secondary-light d-block mb-1">Lead Source</span>
                            <span class="text-xs fw-bold text-primary-light" id="detailLeadSource">-</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-12 radius-8 border border-neutral-200 bg-white">
                            <span class="text-2xs text-uppercase fw-semibold text-secondary-light d-block mb-1">Estimasi Nilai Proyek</span>
                            <span class="text-xs fw-bold text-primary-600" id="detailEstimatedValue">Rp 0</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-12 radius-8 border border-neutral-200 bg-white">
                            <span class="text-2xs text-uppercase fw-semibold text-secondary-light d-block mb-1">Sales Owner (PIC)</span>
                            <span class="text-xs fw-bold text-primary-light" id="detailOwner">-</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-12 radius-8 border border-neutral-200 bg-white">
                            <span class="text-2xs text-uppercase fw-semibold text-secondary-light d-block mb-1">Next Follow-up / Target Closing</span>
                            <span class="text-xs fw-bold text-primary-light font-monospace" id="detailFollowUpDate">-</span>
                        </div>
                    </div>
                </div>

                
                <div class="p-14 radius-8 border border-neutral-200 bg-white">
                    <span class="text-2xs text-uppercase fw-semibold text-secondary-light d-block mb-2">Catatan Awal / Langkah Selanjutnya</span>
                    <p class="text-xs text-neutral-700 mb-0" id="detailNotes" style="white-space: pre-line;">-</p>
                </div>
            </div>

            <div class="modal-footer py-14 px-24 border-top d-flex justify-content-end align-items-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-20 py-8 text-xs" data-bs-dismiss="modal">
                    Tutup
                </button>
                @can('sales.opportunity.edit')
                    <button type="button" class="btn btn-primary btn-sm radius-8 px-20 py-8 text-xs fw-semibold" id="btnSwitchToEdit">
                        <iconify-icon icon="lucide:edit" class="me-1"></iconify-icon> Edit Project Lead
                    </button>
                @endcan
            </div>
        </div>
    </div>
</div>




@can('sales.opportunity.edit')
<div class="modal fade" id="editOpportunityModal" tabindex="-1" aria-labelledby="editOpportunityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
            <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="modal-title fw-bold text-primary-light mb-1 text-lg" id="editOpportunityModalLabel">Edit Project Lead</h5>
                    <p class="text-secondary-light text-xs mb-0">Perbarui data informasi proyek dan status pipeline.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-24">
                <form action="" method="POST" id="editOpportunityForm">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Nama Project / Scope <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="editTitle"
                                   class="form-control form-control-sm radius-8"
                                   placeholder="Contoh: Tank Cleaning Campaign" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Customer <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="customer" id="editCustomer" list="customerSuggestionsEdit"
                                   class="form-control form-control-sm radius-8"
                                   placeholder="Nama perusahaan customer" required>
                            <datalist id="customerSuggestionsEdit">
                                @foreach ($customers as $c)
                                    <option value="{{ $c->company_name }}">{{ $c->company_name }} ({{ $c->industry ?? 'General' }})</option>
                                @endforeach
                            </datalist>
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Service Line <span class="text-danger">*</span>
                            </label>
                            <select name="service_line" id="editServiceLine" class="form-select form-select-sm radius-8" required>
                                <option value="UHP Cleaning">UHP Cleaning</option>
                                <option value="Hydrojetting">Hydrojetting</option>
                                <option value="Chemical Cleaning">Chemical Cleaning</option>
                                <option value="Tank Cleaning">Tank Cleaning</option>
                                <option value="Cold Cutting & Pipe Bevelling">Cold Cutting &amp; Pipe Bevelling</option>
                                <option value="General Maintenance">General Maintenance</option>
                                <option value="Civil & Construction">Civil &amp; Construction</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Lokasi Proyek
                            </label>
                            <input type="text" name="project_location" id="editLocation"
                                   class="form-control form-control-sm radius-8"
                                   placeholder="Kota / site">
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Lead Source
                            </label>
                            <select name="lead_source" id="editLeadSource" class="form-select form-select-sm radius-8">
                                <option value="Existing Customer">Existing Customer</option>
                                <option value="Referral">Referral</option>
                                <option value="Tender / RFQ">Tender / RFQ</option>
                                <option value="Website / Inbound">Website / Inbound</option>
                                <option value="Direct Outreach">Direct Outreach</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Estimasi Nilai Proyek (Rp)
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text radius-8-start text-xs fw-bold px-12">Rp</span>
                                <input type="text" inputmode="numeric" name="estimated_value" id="editEstimatedValue"
                                       class="form-control form-control-sm radius-8-end rupiah-input"
                                       placeholder="10.000" autocomplete="off">
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Tahap Pipeline (Stage) <span class="text-danger">*</span>
                            </label>
                            <select name="stage" id="editStage" class="form-select form-select-sm radius-8" required>
                                <option value="qualification">1. Qualification</option>
                                <option value="need_analysis">2. Need Analysis</option>
                                <option value="tender_rfq">3. Tender / RFQ</option>
                                <option value="proposal_submitted">4. Proposal Submitted</option>
                                <option value="negotiation">5. Negotiation</option>
                                <option value="closed_won">Closed Won</option>
                                <option value="closed_lost">Closed Lost</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Sales Owner <span class="text-danger">*</span>
                            </label>
                            <select name="owner_id" id="editOwnerId" class="form-select form-select-sm radius-8" required>
                                @foreach ($salesUsers as $su)
                                    <option value="{{ $su->id }}">{{ $su->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Next Follow-up
                            </label>
                            <input type="date" name="next_follow_up_date" id="editNextFollowUp"
                                   class="form-control form-control-sm radius-8">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Target Tanggal Closing
                            </label>
                            <input type="date" name="target_close_date" id="editTargetCloseDate"
                                   class="form-control form-control-sm radius-8">
                        </div>

                        
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Catatan Awal
                            </label>
                            <textarea name="notes" id="editNotes" rows="3"
                                      class="form-control form-control-sm radius-8"
                                      placeholder="Kebutuhan customer, sumber informasi, atau langkah berikutnya"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer py-14 px-24 border-top d-flex justify-content-end align-items-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-20 py-8 text-xs" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" id="btnUpdate" form="editOpportunityForm" class="btn btn-primary btn-sm radius-8 px-20 py-8 text-xs fw-semibold" style="background-color: #5551ff; border-color: #5551ff;">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
@endcan


<script>
    let currentSelectedOpp = null;

    function formatRupiah(val) {
        if (!val && val !== 0) return '';
        let str = val.toString().replace(/[^0-9]/g, '');
        if (!str) return '';
        return new Intl.NumberFormat('id-ID').format(str);
    }

    function formatIDR(val) {
        return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
    }

    function openOpportunityDetailModal(opp) {
        currentSelectedOpp = opp;
        const year = opp.created_at ? new Date(opp.created_at).getFullYear() : '2026';
        const code = 'LD-' + year + '-' + String(opp.id).padStart(3, '0');

        document.getElementById('detailCode').textContent = code;
        document.getElementById('detailTitle').textContent = opp.title || '-';
        document.getElementById('detailCustomer').textContent = opp.customer?.company_name || opp.customer?.name || '-';
        document.getElementById('detailCustomerLocation').textContent = opp.project_location || opp.customer?.location || 'Indonesia';

        const stageNames = {
            'qualification': 'Qualification',
            'need_analysis': 'Need Analysis',
            'tender_rfq': 'Tender / RFQ',
            'proposal_submitted': 'Proposal Submitted',
            'negotiation': 'Negotiation',
            'closed_won': 'Closed Won',
            'closed_lost': 'Closed Lost'
        };
        const stageBadge = document.getElementById('detailStageBadge');
        stageBadge.textContent = stageNames[opp.stage] || opp.stage || 'Qualification';

        const probs = {
            'closed_won': '100%',
            'negotiation': '80%',
            'proposal_submitted': '70%',
            'tender_rfq': '50%',
            'need_analysis': '40%',
            'qualification': '20%',
            'closed_lost': '0%'
        };
        document.getElementById('detailProbText').textContent = 'Probabilitas: ' + (probs[opp.stage] || '25%');

        document.getElementById('detailServiceLine').textContent = opp.service_line || 'Industrial Services';
        document.getElementById('detailLocation').textContent = opp.project_location || opp.customer?.location || '-';
        document.getElementById('detailLeadSource').textContent = opp.lead_source || 'Existing Customer';
        document.getElementById('detailEstimatedValue').textContent = formatIDR(opp.estimated_value);
        document.getElementById('detailOwner').textContent = opp.owner?.name || '-';

        const followUp = opp.next_follow_up_date || opp.target_close_date;
        if (followUp) {
            const d = new Date(followUp);
            document.getElementById('detailFollowUpDate').textContent = d.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
        } else {
            document.getElementById('detailFollowUpDate').textContent = '-';
        }

        document.getElementById('detailNotes').textContent = opp.notes || opp.next_step || 'Tidak ada catatan tambahan.';

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailOpportunityModal'));
        modal.show();
    }

    function openOpportunityEditModal(opp) {
        currentSelectedOpp = opp;
        const form = document.getElementById('editOpportunityForm');
        form.action = '/dashboard-admin/sales/opportunities/' + opp.id;

        document.getElementById('editTitle').value = opp.title || '';
        document.getElementById('editCustomer').value = opp.customer?.company_name || '';
        document.getElementById('editServiceLine').value = opp.service_line || 'UHP Cleaning';
        document.getElementById('editLocation').value = opp.project_location || '';
        document.getElementById('editLeadSource').value = opp.lead_source || 'Existing Customer';
        document.getElementById('editEstimatedValue').value = opp.estimated_value ? formatRupiah(Math.round(opp.estimated_value)) : '';
        document.getElementById('editStage').value = opp.stage || 'qualification';
        document.getElementById('editOwnerId').value = opp.owner_id || '';

        if (opp.next_follow_up_date) {
            document.getElementById('editNextFollowUp').value = opp.next_follow_up_date.split('T')[0];
        } else {
            document.getElementById('editNextFollowUp').value = '';
        }

        if (opp.target_close_date) {
            document.getElementById('editTargetCloseDate').value = opp.target_close_date.split('T')[0];
        } else {
            document.getElementById('editTargetCloseDate').value = '';
        }

        document.getElementById('editNotes').value = opp.notes || opp.next_step || '';

        const detailModalEl = document.getElementById('detailOpportunityModal');
        if (detailModalEl) {
            const detailModal = bootstrap.Modal.getInstance(detailModalEl);
            if (detailModal) detailModal.hide();
        }

        const editModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editOpportunityModal'));
        editModal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btnSwitch = document.getElementById('btnSwitchToEdit');
        if (btnSwitch) {
            btnSwitch.addEventListener('click', function() {
                if (currentSelectedOpp) {
                    openOpportunityEditModal(currentSelectedOpp);
                }
            });
        }
    });
    // Toggle View (Kanban / Table) instantly with JS without page refresh
    function switchPipelineView(mode) {
        const tableView = document.getElementById('pipelineTableView');
        const kanbanView = document.getElementById('pipelineKanbanView');
        const btnTable = document.getElementById('btnViewTable');
        const btnKanban = document.getElementById('btnViewKanban');
        const filterViewInput = document.getElementById('filterViewInput');

        if (!tableView || !kanbanView || !btnTable || !btnKanban) return;

        if (mode === 'kanban') {
            tableView.classList.add('d-none');
            kanbanView.classList.remove('d-none');
            btnKanban.className = 'btn btn-sm btn-primary text-white fw-semibold shadow-xs radius-6 px-12 py-4 text-xs d-flex align-items-center gap-1';
            btnTable.className = 'btn btn-sm btn-ghost text-secondary-light radius-6 px-12 py-4 text-xs d-flex align-items-center gap-1';
        } else {
            kanbanView.classList.add('d-none');
            tableView.classList.remove('d-none');
            btnTable.className = 'btn btn-sm btn-primary text-white fw-semibold shadow-xs radius-6 px-12 py-4 text-xs d-flex align-items-center gap-1';
            btnKanban.className = 'btn btn-sm btn-ghost text-secondary-light radius-6 px-12 py-4 text-xs d-flex align-items-center gap-1';
        }

        if (filterViewInput) {
            filterViewInput.value = mode;
        }

        try {
            const url = new URL(window.location);
            url.searchParams.set('view', mode);
            window.history.replaceState({}, '', url);
        } catch (e) {
            console.warn(e);
        }
    }

    // Quick Stage Change via AJAX with SweetAlert2
    function quickChangeStage(opportunityId, newStage, stageLabel = '') {
        const stageNames = {
            'qualification': '1. Qualification',
            'need_analysis': '2. Need Analysis',
            'tender_rfq': '3. Tender / RFQ',
            'proposal_submitted': '4. Proposal Submitted',
            'negotiation': '5. Negotiation',
            'closed_won': 'Closed Won (Deal)',
            'closed_lost': 'Closed Lost'
        };
        const targetName = stageLabel || stageNames[newStage] || newStage;

        Swal.fire({
            title: 'Pindahkan Tahap Pipeline?',
            html: `Pindahkan opportunity ini ke tahap <b>${targetName}</b>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#5551ff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Pindahkan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading indicator
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang memperbarui tahapan opportunity...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`{{ url('dashboard-admin/sales/opportunities') }}/${opportunityId}/stage`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ stage: newStage })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message || 'Tahapan opportunity berhasil dipindahkan.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memindahkan',
                            text: data.message || 'Gagal mengubah stage opportunity.'
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Jaringan',
                        text: 'Terjadi kesalahan jaringan saat memindahkan opportunity.'
                    });
                });
            }
        });
    }

    // Open Create Opportunity Modal
    function openCreateOpportunityModal(stageKey = null) {
        if (stageKey) {
            const stageSelect = document.getElementById('opportunityStageSelect');
            if (stageSelect) {
                stageSelect.value = stageKey;
            }
        }
        const modalEl = document.getElementById('createOpportunityModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }

    @if (request('open_create') || (isset($errors) && $errors->any()))
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('createOpportunityModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });
    @endif

    // ApexCharts Sparklines & Rupiah Mask Initialization
    document.addEventListener('DOMContentLoaded', function () {
        // Automatic Indonesian thousand dot formatting for Rupiah inputs
        document.querySelectorAll('.rupiah-input').forEach(function(input) {
            input.addEventListener('input', function() {
                let cursor = this.selectionStart;
                let prevLen = this.value.length;
                let raw = this.value.replace(/[^0-9]/g, '');
                if (raw === '') {
                    this.value = '';
                    return;
                }
                let formatted = new Intl.NumberFormat('id-ID').format(raw);
                this.value = formatted;
                let diff = formatted.length - prevLen;
                this.setSelectionRange(cursor + diff, cursor + diff);
            });
        });



        const sparklineDefaults = {
            chart: {
                type: 'area',
                height: 44,
                sparkline: { enabled: true },
                animations: { enabled: true }
            },
            stroke: { curve: 'smooth', width: 2 },
            tooltip: { enabled: false }
        };

        // 1. Weighted Pipeline Sparkline
        const el1 = document.querySelector("#sparklineWeightPipeline");
        if (el1) {
            new ApexCharts(el1, {
                ...sparklineDefaults,
                series: [{ data: @json($sparklinePipeline) }],
                colors: ['#ea580c'],
                fill: {
                    type: 'gradient',
                    gradient: { opacityFrom: 0.35, opacityTo: 0.05 }
                }
            }).render();
        }

        // 2. Qualification Rate Sparkline
        const el2 = document.querySelector("#sparklineQualRate");
        if (el2) {
            new ApexCharts(el2, {
                ...sparklineDefaults,
                series: [{ data: @json($sparklineQualRate) }],
                colors: ['#ea580c'],
                fill: {
                    type: 'gradient',
                    gradient: { opacityFrom: 0.35, opacityTo: 0.05 }
                }
            }).render();
        }

        // 3. Qualified Leads Sparkline
        const el3 = document.querySelector("#sparklineQualified");
        if (el3) {
            new ApexCharts(el3, {
                ...sparklineDefaults,
                series: [{ data: @json($sparklineQualified) }],
                colors: ['#ea580c'],
                fill: {
                    type: 'gradient',
                    gradient: { opacityFrom: 0.35, opacityTo: 0.05 }
                }
            }).render();
        }
    });
</script>
@endsection
