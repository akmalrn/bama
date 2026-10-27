@extends('layout.layout')

@php
    $title = 'Sales Pipeline';
    $subTitle = 'Sales Pipeline Overview & Lead Management — PT ELBEMA Integra Indonesia';
@endphp

@section('content')
{{-- Alerts --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Custom Styling for Compact & Proportional Appearance --}}
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

{{-- HEADER & WORKSPACE OVERVIEW (COMPACT WOWDASH STYLE) --}}
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
        {{-- Year Filter Dropdown --}}
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm radius-8 px-12 py-6 text-xs bg-white border-neutral-300 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                <span>This Year</span>
                <iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm radius-8 text-xs p-6 border border-neutral-200">
                <li><a class="dropdown-item py-6 px-12 radius-4 active bg-primary-600 text-white" href="#">This Year (2026)</a></li>
                <li><a class="dropdown-item py-6 px-12 radius-4" href="#">Last Year (2025)</a></li>
            </ul>
        </div>

        {{-- Export Button --}}
        <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-12 py-6 text-xs d-flex align-items-center gap-1" onclick="window.print()">
            <iconify-icon icon="solar:download-linear"></iconify-icon> Export
        </button>

        {{-- Add Project Lead / Opportunity Button --}}
        @can('sales.opportunity.create')
            <button type="button" class="btn btn-primary btn-sm radius-8 px-14 py-6 text-xs fw-semibold d-flex align-items-center gap-1 shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#createOpportunityModal">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="text-sm"></iconify-icon>
                + Add Project Lead
            </button>
        @endcan
    </div>
</div>

{{-- SECTION 1: 4 METRIC CARDS WITH SPARKLINES (COMPACT & PROPORTIONAL) --}}
<div class="row g-3 mb-24">
    {{-- Metric 1: Weighted Pipeline Value --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white pipeline-metric-card h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Weighted Pipeline Value</span>
                <h5 class="fw-bold text-primary-light mb-2 text-nowrap">
                    Rp {{ number_format($weightedPipelineVal / 1000000000, 2, ',', '.') }} M
                </h5>
                <div class="text-2xs mb-8">
                    <span class="text-danger-main fw-bold">5.2% ↓</span>
                    <span class="text-secondary-light">vs. previous period</span>
                </div>
                <div id="sparklineWeightPipeline" style="height: 44px;"></div>
            </div>
            <div class="pt-8 border-top mt-12">
                <span class="fw-bold text-xs text-primary-light d-block">Pipeline Growth Pattern</span>
                <span class="text-2xs text-secondary-light">Review lead value changes and identify growth opportunities.</span>
            </div>
        </div>
    </div>

    {{-- Metric 2: Active Project Leads --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white pipeline-metric-card h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Active Project Leads</span>
                <h5 class="fw-bold text-primary-light mb-2">
                    {{ $activeLeadsCount }}
                </h5>
                <div class="text-2xs mb-8">
                    <span class="text-success-main fw-bold">16.3% ↑</span>
                    <span class="text-secondary-light">active leads this year</span>
                </div>
                {{-- Mini multi-colored bar stack --}}
                <div class="mini-bar-stack">
                    <div class="bar-col" style="height: 90%; background-color: #ea580c;" title="Referral: 14"></div>
                    <div class="bar-col" style="height: 70%; background-color: #f97316;" title="Existing: 11"></div>
                    <div class="bar-col" style="height: 55%; background-color: #f59e0b;" title="Tender: 8"></div>
                    <div class="bar-col" style="height: 60%; background-color: #0ea5e9;" title="Other: 9"></div>
                </div>
            </div>
            <div class="pt-8 border-top mt-12">
                <span class="fw-bold text-xs text-primary-light d-block">Lead Source Distribution</span>
                <span class="text-2xs text-secondary-light">See which channels bring project inquiries to ELBEMA.</span>
            </div>
        </div>
    </div>

    {{-- Metric 3: Qualification Rate --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white pipeline-metric-card h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Qualification Rate</span>
                <h5 class="fw-bold text-primary-light mb-2">
                    {{ number_format($qualificationRate, 1, ',', '.') }}%
                </h5>
                <div class="text-2xs mb-8">
                    <span class="text-danger-main fw-bold">1.3% ↓</span>
                    <span class="text-secondary-light">conversion from new lead</span>
                </div>
                <div id="sparklineQualRate" style="height: 44px;"></div>
            </div>
            <div class="pt-8 border-top mt-12">
                <span class="fw-bold text-xs text-primary-light d-block">Qualification Trend</span>
                <span class="text-2xs text-secondary-light">Monitor movement from initial inquiry to qualified project.</span>
            </div>
        </div>
    </div>

    {{-- Metric 4: Qualified Project Leads --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white pipeline-metric-card h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Qualified Project Leads</span>
                <h5 class="fw-bold text-primary-light mb-2">
                    {{ $qualifiedLeadsCount }}
                </h5>
                <div class="text-2xs mb-8">
                    <span class="text-success-main fw-bold">15.7% ↑</span>
                    <span class="text-secondary-light">qualified for sales review</span>
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

{{-- SECTION 2: TEAM PRODUCTIVITY & PROJECT LEAD GOALS (4 CARDS) --}}
<div class="mb-12">
    <h6 class="fw-bold text-primary-light mb-1 text-sm">Team Productivity &amp; Project Lead Goals</h6>
    <p class="text-secondary-light mb-0 text-2xs">Monitor activity completion, lead quality, and coverage by region.</p>
</div>

<div class="row g-3 mb-24">
    {{-- Card 1: Follow-up Completion --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-xs fw-bold text-primary-light d-block">Follow-up Completion</span>
                <span class="text-2xs text-secondary-light d-block mb-12">Planned sales activities completed</span>

                {{-- Visual Activity Micro Bars --}}
                <div class="d-flex align-items-end justify-content-between gap-1 mb-8" style="height: 42px;">
                    @php $barHeights = [40, 60, 50, 75, 65, 85, 70, 90, 80, 75, 95, 72]; @endphp
                    @foreach ($barHeights as $bh)
                        <div class="flex-grow-1 radius-2" style="height: {{ $bh }}%; background-color: {{ $loop->last ? '#0284c7' : '#bae6fd' }};"></div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-between text-2xs text-secondary-light mb-8">
                    <span>0%</span>
                    <span class="fw-bold text-primary-600">72% complete</span>
                    <span>100%</span>
                </div>
            </div>
            <div class="pt-8 border-top">
                <span class="fw-bold text-xs text-primary-light d-block">Sales Activity Progress</span>
            </div>
        </div>
    </div>

    {{-- Card 2: Customer Lead Sources --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white h-100 d-flex flex-column justify-content-between">
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

    {{-- Card 3: Sales Lead Status --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white h-100 d-flex flex-column justify-content-between">
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

    {{-- Card 4: Project Lead by Region --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 bg-white h-100 d-flex flex-column justify-content-between">
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

{{-- SECTION 3: LEAD & OPPORTUNITY PIPELINE (TABLE OR KANBAN) --}}
<div class="card radius-12 border border-neutral-200 shadow-sm bg-white mb-24 overflow-hidden">
    {{-- Card Header & View Switcher --}}
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

        {{-- View Mode Toggle (Client-side JS Switch without reload) --}}
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

    {{-- Filter Bar --}}
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

    {{-- ======================================================== --}}
    {{-- ==================== KANBAN BOARD VIEW ================= --}}
    {{-- ======================================================== --}}
    <div id="pipelineKanbanView" class="{{ $viewMode === 'kanban' ? '' : 'd-none' }}">
        <div class="d-flex gap-12 overflow-x-auto p-16 kanban-container" style="min-height: 520px;">
            @foreach ($kanbanColumns as $stageKey => $column)
                @php
                    $info = $column['info'];
                    $items = $column['items'];
                    $stageCol = $stageColors[$stageKey] ?? ['color' => '#64748b', 'badge' => 'bg-neutral-100 text-neutral-700'];
                @endphp
                <div class="kanban-column flex-shrink-0 radius-10 p-10 border border-neutral-200 bg-neutral-50 shadow-xs" style="width: 270px;">
                    {{-- Column Header --}}
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
                            <span class="badge bg-white text-secondary-light border border-neutral-200 text-2xs px-6 py-2 rounded-pill fw-bold shadow-xs">
                                {{ $column['count'] }}
                            </span>
                            @can('sales.opportunity.create')
                                <button type="button" class="w-22-px h-22-px radius-circle bg-white border border-neutral-200 text-secondary-light hover-bg-primary hover-text-white d-flex align-items-center justify-content-center transition-all shadow-xs"
                                        title="Tambah opportunity di tahap {{ $info['label'] }}"
                                        onclick="openCreateOpportunityModal('{{ $stageKey }}')">
                                    <iconify-icon icon="ic:round-add" class="text-xs"></iconify-icon>
                                </button>
                            @endcan
                        </div>
                    </div>

                    {{-- Cards Container --}}
                    <div class="d-flex flex-column gap-10" style="min-height: 160px;">
                        @forelse ($items as $opp)
                            <div class="card border border-neutral-200 shadow-xs radius-8 p-12 bg-white crm-kanban-card position-relative overflow-hidden mb-2">
                                {{-- Card Header --}}
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
                                                <a class="dropdown-item py-6 px-12 radius-4" href="{{ route('dashboard-admin.sales.opportunities.show', $opp) }}">
                                                    <iconify-icon icon="solar:eye-bold-duotone" class="me-1 text-info-main"></iconify-icon> Detail Opportunity
                                                </a>
                                            </li>
                                            @can('sales.opportunity.edit')
                                                <li>
                                                    <a class="dropdown-item py-6 px-12 radius-4" href="{{ route('dashboard-admin.sales.opportunities.edit', $opp) }}">
                                                        <iconify-icon icon="solar:pen-bold-duotone" class="me-1 text-success-main"></iconify-icon> Edit Deal
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('sales.opportunity.delete')
                                                <li><hr class="dropdown-divider my-4"></li>
                                                <li>
                                                    <form action="{{ route('dashboard-admin.sales.opportunities.destroy', $opp) }}" method="POST"
                                                          onsubmit="return confirm('Hapus opportunity ini dari pipeline?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item py-6 px-12 radius-4 text-danger-main">
                                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="me-1"></iconify-icon> Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>

                                {{-- Title & Customer --}}
                                <div class="mb-8">
                                    <a href="{{ route('dashboard-admin.sales.opportunities.show', $opp) }}" class="fw-bold text-primary-light hover-text-primary text-xs d-block mb-3" style="line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $opp->title }}">
                                        {{ $opp->title }}
                                    </a>
                                    <div class="text-2xs text-secondary-light d-flex align-items-center gap-1">
                                        <iconify-icon icon="solar:buildings-bold-duotone" class="text-neutral-400 flex-shrink-0 text-xs"></iconify-icon>
                                        <span class="text-truncate fw-medium" style="max-width: 200px;" title="{{ $opp->customer->company_name ?? '-' }}">{{ $opp->customer->company_name ?? '-' }}</span>
                                    </div>
                                </div>

                                {{-- Estimated Deal Value & Stage Mover --}}
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
                                                            <button type="button" class="dropdown-item py-6 px-8 radius-6 d-flex align-items-center gap-2 text-xs" onclick="quickChangeStage({{ $opp->id }}, '{{ $sKey }}')">
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

                                {{-- Footer Info: Owner & Target Date --}}
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
                            <div class="text-center py-20 px-12 text-secondary-light text-2xs border border-dashed border-neutral-300 radius-8 bg-white d-flex flex-column align-items-center justify-content-center gap-1">
                                <iconify-icon icon="solar:inbox-line" class="text-lg text-neutral-400"></iconify-icon>
                                <span>Belum ada opportunity</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- ==================== TABLE LIST VIEW =================== --}}
    {{-- ======================================================== --}}
    <div id="pipelineTableView" class="{{ $viewMode === 'table' ? '' : 'd-none' }}">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-100">
                    <tr>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16">Project Lead / Customer</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16">Service Line</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16">Lead Source</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-end">Est. Value</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Prob.</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16">Stage</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16">Sales Owner</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Next Follow-up</th>
                        <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center" width="90">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($opportunities as $index => $opp)
                        @php
                            $stageInfo = $stages[$opp->stage] ?? ['label' => $opp->stage, 'color' => 'secondary'];
                            $stageCol = $stageColors[$opp->stage] ?? ['color' => '#64748b', 'badge' => 'bg-neutral-100 text-neutral-700 border border-neutral-300'];
                            $prob = match($opp->stage) {
                                'closed_won' => 100,
                                'negotiation' => 80,
                                'proposal_submitted' => 70,
                                'tender_rfq' => 50,
                                'need_analysis' => 40,
                                default => 25,
                            };
                        @endphp
                        <tr>
                            <td class="px-16 py-12">
                                <a href="{{ route('dashboard-admin.sales.opportunities.show', $opp) }}" class="fw-bold text-primary-light hover-text-primary text-sm d-block mb-1">
                                    {{ $opp->title }}
                                </a>
                                <div class="text-2xs text-secondary-light d-flex align-items-center gap-1 flex-wrap">
                                    <span class="font-monospace text-primary-600">LD-2026-{{ str_pad((string)$opp->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    <span>·</span>
                                    <span>{{ $opp->customer->company_name ?? '-' }}</span>
                                    @if ($opp->customer?->city)
                                        <span>·</span>
                                        <span>{{ $opp->customer->city }}</span>
                                    @endif
                                    <span>·</span>
                                    <span class="text-neutral-400">OPP-2026-{{ str_pad((string)$opp->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </td>
                            <td class="px-16 py-12">
                                <span class="badge bg-neutral-100 text-secondary-light border border-neutral-300 px-8 py-3 radius-4 text-2xs fw-semibold">
                                    {{ $opp->service_line ?? 'Industrial Services' }}
                                </span>
                            </td>
                            <td class="px-16 py-12 text-xs text-secondary-light">
                                {{ $opp->lead_source ?? 'Customer Referral' }}
                            </td>
                            <td class="px-16 py-12 text-end text-nowrap">
                                <strong class="text-primary-600 text-sm">
                                    Rp {{ number_format((float) $opp->estimated_value, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td class="px-16 py-12 text-center text-xs fw-semibold text-secondary-light">
                                {{ $prob }}%
                            </td>
                            <td class="px-16 py-12">
                                <span class="badge {{ $stageCol['badge'] }} px-8 py-4 radius-6 text-2xs fw-semibold d-inline-flex align-items-center gap-1">
                                    <span class="w-6-px h-6-px radius-circle" style="background-color: {{ $stageCol['color'] }};"></span>
                                    {{ $stageInfo['label'] }}
                                </span>
                            </td>
                            <td class="px-16 py-12">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-22-px h-22-px radius-circle bg-primary-100 text-primary-600 text-2xs fw-bold d-flex justify-content-center align-items-center">
                                        {{ substr($opp->owner->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-xs fw-medium text-secondary-light">{{ $opp->owner->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-16 py-12 text-center text-xs text-secondary-light font-monospace text-nowrap">
                                {{ $opp->target_close_date ? \Carbon\Carbon::parse($opp->target_close_date)->format('d.m.Y') : '-' }}
                            </td>
                            <td class="px-16 py-12 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <a href="{{ route('dashboard-admin.sales.opportunities.show', $opp) }}"
                                       class="w-28-px h-28-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center"
                                       title="Detail">
                                        <iconify-icon icon="solar:eye-bold-duotone" class="text-sm"></iconify-icon>
                                    </a>

                                    @can('sales.opportunity.edit')
                                        <a href="{{ route('dashboard-admin.sales.opportunities.edit', $opp) }}"
                                           class="w-28-px h-28-px rounded-circle bg-success-focus text-success-main d-inline-flex justify-content-center align-items-center"
                                           title="Edit">
                                            <iconify-icon icon="lucide:edit" class="text-sm"></iconify-icon>
                                        </a>
                                    @endcan

                                    @can('sales.opportunity.delete')
                                        <form action="{{ route('dashboard-admin.sales.opportunities.destroy', $opp) }}" method="POST"
                                              onsubmit="return confirm('Hapus opportunity ini?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-28-px h-28-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus">
                                                <iconify-icon icon="mingcute:delete-2-line" class="text-sm"></iconify-icon>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-32 text-secondary-light text-xs">
                                Tidak ada data opportunity yang cocok dengan kriteria filter.
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

{{-- MODAL TAMBAH OPPORTUNITY BARU --}}
@can('sales.opportunity.create')
<div class="modal fade" id="createOpportunityModal" tabindex="-1" aria-labelledby="createOpportunityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
            <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-36-px h-36-px radius-8 bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:chart-square-outline" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-primary-light mb-0" id="createOpportunityModalLabel">Buat Project Lead / Opportunity Baru</h6>
                        <span class="text-secondary-light text-2xs">Daftarkan peluang proyek dan tentukan tahapan pipeline awalnya</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-20">
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
                        {{-- Judul Opportunity --}}
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Judul Proyek / Paket Pekerjaan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                   class="form-control form-control-sm radius-8 @error('title') is-invalid @enderror"
                                   placeholder="Contoh: Overhaul & Maintenance High Pressure Boiler Package" required>
                            @error('title')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Customer --}}
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Perusahaan Klien (Customer) <span class="text-danger">*</span>
                            </label>
                            <select name="customer_id" class="form-select form-select-sm radius-8 @error('customer_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->company_name }} ({{ $c->industry ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sales PIC / Owner --}}
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                PIC Sales Representative (Owner) <span class="text-danger">*</span>
                            </label>
                            <select name="owner_id" class="form-select form-select-sm radius-8 @error('owner_id') is-invalid @enderror" required>
                                @foreach ($salesUsers as $su)
                                    <option value="{{ $su->id }}" {{ old('owner_id', auth()->id()) == $su->id ? 'selected' : '' }}>
                                        {{ $su->name }} ({{ $su->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('owner_id')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estimasi Nilai Deal --}}
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Estimasi Nilai Kontrak (Rp) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text radius-8-start text-xs fw-bold">Rp</span>
                                <input type="number" step="1000" name="estimated_value" value="{{ old('estimated_value', 0) }}"
                                       class="form-control radius-8-end @error('estimated_value') is-invalid @enderror" required>
                            </div>
                            @error('estimated_value')
                                <div class="invalid-feedback text-xs d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Initial Stage --}}
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Tahap Pipeline Awal <span class="text-danger">*</span>
                            </label>
                            <select name="stage" id="opportunityStageSelect" class="form-select form-select-sm radius-8 @error('stage') is-invalid @enderror" required>
                                <option value="qualification" {{ old('stage') === 'qualification' ? 'selected' : '' }}>1. Qualification</option>
                                <option value="need_analysis" {{ old('stage') === 'need_analysis' ? 'selected' : '' }}>2. Need Analysis</option>
                                <option value="tender_rfq" {{ old('stage') === 'tender_rfq' ? 'selected' : '' }}>3. Tender / RFQ</option>
                                <option value="proposal_submitted" {{ old('stage') === 'proposal_submitted' ? 'selected' : '' }}>4. Proposal Submitted</option>
                                <option value="negotiation" {{ old('stage') === 'negotiation' ? 'selected' : '' }}>5. Negotiation</option>
                                <option value="closed_won" {{ old('stage') === 'closed_won' ? 'selected' : '' }}>Closed Won</option>
                                <option value="closed_lost" {{ old('stage') === 'closed_lost' ? 'selected' : '' }}>Closed Lost</option>
                            </select>
                            @error('stage')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Target Close Date --}}
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Target Tanggal Closing
                            </label>
                            <input type="date" name="target_close_date" value="{{ old('target_close_date') }}"
                                   class="form-control form-control-sm radius-8 @error('target_close_date') is-invalid @enderror">
                            @error('target_close_date')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Next Action Plan --}}
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">
                                Langkah Tindak Lanjut Selanjutnya (Next Action)
                            </label>
                            <input type="text" name="next_step" value="{{ old('next_step') }}"
                                   class="form-control form-control-sm radius-8 @error('next_step') is-invalid @enderror"
                                   placeholder="Contoh: Kirim dokumen proposal teknis & jadwal survey site">
                            @error('next_step')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer py-12 px-20 border-top d-flex justify-content-end align-items-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-16 py-6 text-xs" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" id="btnSubmit" form="createOpportunityForm" class="btn btn-primary btn-sm radius-8 px-20 py-6 text-xs d-flex align-items-center gap-2 fw-semibold">
                    <iconify-icon icon="solar:diskette-bold-duotone" class="text-sm"></iconify-icon>
                    Simpan ke Pipeline
                </button>
            </div>
        </div>
    </div>
</div>
@endcan

{{-- JavaScript Helpers & Sparklines --}}
<script>
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

    // Quick Stage Change via AJAX (for Kanban)
    function quickChangeStage(opportunityId, newStage) {
        if (!confirm('Pindahkan tahapan opportunity ini?')) {
            window.location.reload();
            return;
        }

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
                window.location.reload();
            } else {
                alert(data.message || 'Gagal mengubah stage opportunity');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan.');
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

    // ApexCharts Sparklines Initialization
    document.addEventListener('DOMContentLoaded', function () {
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
                series: [{ data: [12, 14, 18, 15, 24, 20, 28] }],
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
                series: [{ data: [35, 38, 45, 42, 44, 40, 43] }],
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
                series: [{ data: [8, 10, 12, 14, 13, 16, 18] }],
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
