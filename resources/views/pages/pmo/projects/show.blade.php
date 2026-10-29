@extends('layout.layout')

@php
    $title = 'Project Detail 360';
    $subTitle = $project->project_code . ' — ' . $project->title;
    $pv = $project->planned_value;
    $ev = $project->earned_value;
    $ac = (float) $project->actual_cost;
    $cpi = $project->cpi;
    $spi = $project->spi;
    $eac = $project->eac;
    $vac = $project->vac;
    $cv = $project->cost_variance;
    $sv = $project->schedule_variance;
    $margin = (float) $project->contract_value - $eac;
@endphp

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-24">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('dashboard-admin.pmo.projects.index') }}" class="btn btn-sm btn-outline-secondary px-10 py-4 radius-6 d-inline-flex align-items-center gap-1 text-xs">
                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon> Project Register
            </a>
            <span class="badge bg-neutral-100 text-neutral-700 text-xs fw-bold">{{ $project->project_code }}</span>
            <span class="badge {{ $project->status === 'completed' ? 'bg-success-50 text-success-700 border border-success-200' : ($project->status === 'on_progress' ? 'bg-warning-50 text-warning-700 border border-warning-200' : 'bg-primary-50 text-primary-700 border border-primary-200') }} text-xs">
                {{ strtoupper(str_replace('_', ' ', $project->status)) }}
            </span>
        </div>
        <h4 class="mb-2 fw-bold text-primary-light">{{ $project->title }}</h4>
        <p class="text-secondary-light mb-0 text-sm">
            <strong>{{ $project->customer?->company_name ?? '—' }}</strong> · {{ $project->location ?: 'Site belum ditentukan' }} · {{ $project->category ?: 'General Scope' }} ·
            PM: <span class="text-primary-600 fw-semibold">{{ $project->projectManager?->user?->name ?? 'Unassigned' }}</span>
        </p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-outline-primary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#editProjectModal">
            <iconify-icon icon="solar:pen-bold-duotone" class="text-base"></iconify-icon>
            Edit Project
        </button>
        <button type="button" class="btn btn-primary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addMilestoneModal">
            <iconify-icon icon="solar:add-circle-bold-duotone" class="text-base"></iconify-icon>
            ＋ Tambah Milestone
        </button>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show text-sm radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-sm radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="row g-3 mb-24">
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 p-12">
            <span class="text-2xs fw-bold text-uppercase text-secondary-light d-block mb-1">SALES ORDER VALUE</span>
            <strong class="fs-6 fw-bold text-primary-light d-block">Rp {{ number_format($project->contract_value / 1000000, 1) }}M</strong>
            <small class="text-3xs text-secondary-light mt-1 d-block">{{ $project->project_code }}</small>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 p-12">
            <span class="text-2xs fw-bold text-uppercase text-secondary-light d-block mb-1">PROJECT BUDGET (BAC)</span>
            <strong class="fs-6 fw-bold text-primary-light d-block">Rp {{ number_format($project->budget / 1000000, 1) }}M</strong>
            <small class="text-3xs text-secondary-light mt-1 d-block">Approved cost baseline</small>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 p-12">
            <span class="text-2xs fw-bold text-uppercase text-secondary-light d-block mb-1">ACTUAL COST (AC)</span>
            <strong class="fs-6 fw-bold text-primary-light d-block">Rp {{ number_format($ac / 1000000, 1) }}M</strong>
            <small class="text-3xs text-secondary-light mt-1 d-block">{{ $project->budget > 0 ? round(($ac / $project->budget) * 100, 1) : 0 }}% budget terpakai</small>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 p-12">
            <span class="text-2xs fw-bold text-uppercase text-secondary-light d-block mb-1">COST FORECAST (EAC)</span>
            <strong class="fs-6 fw-bold text-primary-light d-block">Rp {{ number_format($eac / 1000000, 1) }}M</strong>
            <small class="text-3xs {{ $vac < 0 ? 'text-danger-main fw-bold' : 'text-success-main' }} mt-1 d-block">
                VAC: {{ $vac >= 0 ? '+' : '' }}Rp {{ number_format($vac / 1000000, 1) }}M
            </small>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 p-12">
            <span class="text-2xs fw-bold text-uppercase text-secondary-light d-block mb-1">SCHEDULE SPI</span>
            <strong class="fs-6 fw-bold {{ $spi < 1 ? 'text-warning-main' : 'text-success-main' }} d-block">{{ number_format($spi, 2) }} SPI</strong>
            <small class="text-3xs text-secondary-light mt-1 d-block">Aktual {{ $project->progress_percentage }}% / Plan {{ $project->planned_progress_percentage }}%</small>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 p-12">
            <span class="text-2xs fw-bold text-uppercase text-secondary-light d-block mb-1">FORECAST MARGIN</span>
            <strong class="fs-6 fw-bold text-success-main d-block">Rp {{ number_format($margin / 1000000, 1) }}M</strong>
            <small class="text-3xs text-secondary-light mt-1 d-block">SO Value − EAC</small>
        </div>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-lg-8">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom p-16 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 fw-bold text-primary-light">Earned Value Management (EVM) Performance</h6>
                    <p class="text-secondary-light text-xs mb-0">Analisis baseline, nilai perolehan, efisiensi biaya, dan forecast penyelesaian.</p>
                </div>
                <span class="text-xs text-secondary-light">
                    {{ $project->start_date ? $project->start_date->format('d M Y') : '—' }} s/d {{ $project->end_date ? $project->end_date->format('d M Y') : '—' }}
                </span>
            </div>
            <div class="card-body p-16">
                <div class="row g-2 mb-16">
                    <div class="col-md-3 col-6">
                        <div class="p-10 radius-8 border bg-neutral-50">
                            <span class="text-3xs fw-bold text-secondary-light d-block text-uppercase">PV · PLANNED VALUE</span>
                            <strong class="text-sm fw-bold text-primary-light d-block mt-1">Rp {{ number_format($pv / 1000000, 1) }}M</strong>
                            <span class="text-3xs text-secondary-light">{{ $project->planned_progress_percentage }}% planned progress</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-10 radius-8 border bg-neutral-50">
                            <span class="text-3xs fw-bold text-secondary-light d-block text-uppercase">EV · EARNED VALUE</span>
                            <strong class="text-sm fw-bold text-success-main d-block mt-1">Rp {{ number_format($ev / 1000000, 1) }}M</strong>
                            <span class="text-3xs text-secondary-light">{{ $project->progress_percentage }}% actual progress</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-10 radius-8 border bg-neutral-50">
                            <span class="text-3xs fw-bold text-secondary-light d-block text-uppercase">AC · ACTUAL COST</span>
                            <strong class="text-sm fw-bold text-warning-main d-block mt-1">Rp {{ number_format($ac / 1000000, 1) }}M</strong>
                            <span class="text-3xs text-secondary-light">Cost to date</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-10 radius-8 border {{ $cpi < 1 ? 'bg-danger-50 border-danger-200' : 'bg-success-50 border-success-200' }}">
                            <span class="text-3xs fw-bold text-secondary-light d-block text-uppercase">CPI · EFFICIENCY</span>
                            <strong class="text-sm fw-bold {{ $cpi < 1 ? 'text-danger-main' : 'text-success-main' }} d-block mt-1">{{ number_format($cpi, 2) }}</strong>
                            <span class="text-3xs {{ $cpi < 1 ? 'text-danger-main' : 'text-success-main' }}">{{ $cpi < 1 ? 'Cost overrun' : 'Cost efficient' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-10 radius-8 border {{ $spi < 1 ? 'bg-warning-50 border-warning-200' : 'bg-success-50 border-success-200' }}">
                            <span class="text-3xs fw-bold text-secondary-light d-block text-uppercase">SPI · SCHEDULE</span>
                            <strong class="text-sm fw-bold {{ $spi < 1 ? 'text-warning-main' : 'text-success-main' }} d-block mt-1">{{ number_format($spi, 2) }}</strong>
                            <span class="text-3xs {{ $spi < 1 ? 'text-warning-main' : 'text-success-main' }}">{{ $spi < 1 ? 'Behind plan' : 'On / Ahead' }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-10 radius-8 border bg-neutral-50">
                            <span class="text-3xs fw-bold text-secondary-light d-block text-uppercase">CV · COST VARIANCE</span>
                            <strong class="text-sm fw-bold {{ $cv < 0 ? 'text-danger-main' : 'text-success-main' }} d-block mt-1">
                                {{ $cv >= 0 ? '+' : '' }}Rp {{ number_format($cv / 1000000, 1) }}M
                            </strong>
                            <span class="text-3xs text-secondary-light">EV − AC</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-10 radius-8 border bg-neutral-50">
                            <span class="text-3xs fw-bold text-secondary-light d-block text-uppercase">SV · SCHEDULE VAR</span>
                            <strong class="text-sm fw-bold {{ $sv < 0 ? 'text-warning-main' : 'text-success-main' }} d-block mt-1">
                                {{ $sv >= 0 ? '+' : '' }}Rp {{ number_format($sv / 1000000, 1) }}M
                            </strong>
                            <span class="text-3xs text-secondary-light">EV − PV</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="p-10 radius-8 border bg-neutral-50">
                            <span class="text-3xs fw-bold text-secondary-light d-block text-uppercase">ETC · EST. TO COMPLETE</span>
                            <strong class="text-sm fw-bold text-primary-light d-block mt-1">Rp {{ number_format(max(0, $eac - $ac) / 1000000, 1) }}M</strong>
                            <span class="text-3xs text-secondary-light">EAC − AC</span>
                        </div>
                    </div>
                </div>

                
                <div class="d-flex flex-column gap-2 border-top pt-12">
                    <div class="d-flex align-items-center gap-2 text-xs">
                        <span class="fw-bold text-secondary-light" style="width: 25px;">PV</span>
                        <div class="progress flex-grow-1" style="height: 7px;">
                            <div class="progress-bar bg-primary-600" style="width: {{ $project->planned_progress_percentage }}%"></div>
                        </div>
                        <span class="fw-bold text-neutral-800" style="width: 80px; text-align: right;">Rp {{ number_format($pv / 1000000, 1) }}M</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-xs">
                        <span class="fw-bold text-secondary-light" style="width: 25px;">EV</span>
                        <div class="progress flex-grow-1" style="height: 7px;">
                            <div class="progress-bar bg-success-main" style="width: {{ $project->progress_percentage }}%"></div>
                        </div>
                        <span class="fw-bold text-neutral-800" style="width: 80px; text-align: right;">Rp {{ number_format($ev / 1000000, 1) }}M</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-xs">
                        <span class="fw-bold text-secondary-light" style="width: 25px;">AC</span>
                        <div class="progress flex-grow-1" style="height: 7px;">
                            <div class="progress-bar bg-warning-main" style="width: {{ $project->budget > 0 ? min(100, ($ac / $project->budget) * 100) : 0 }}%"></div>
                        </div>
                        <span class="fw-bold text-neutral-800" style="width: 80px; text-align: right;">Rp {{ number_format($ac / 1000000, 1) }}M</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom p-16 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 fw-bold text-primary-light">Project Delivery Controls</h6>
                    <p class="text-secondary-light text-xs mb-0">Indikator kesiapan dan kesehatan eksekusi.</p>
                </div>
                <span class="badge {{ $project->risk_level === 'high' ? 'bg-danger-50 text-danger-700 border border-danger-200' : ($project->risk_level === 'medium' ? 'bg-warning-50 text-warning-700 border border-warning-200' : 'bg-success-50 text-success-700 border border-success-200') }}">
                    {{ strtoupper($project->risk_level) }} RISK
                </span>
            </div>
            <div class="card-body p-16 d-flex flex-column gap-3">
                <div>
                    <div class="d-flex justify-content-between text-xs mb-1">
                        <span class="fw-semibold text-primary-light">Physical Progress</span>
                        <strong>{{ $project->progress_percentage }}% Aktual / {{ $project->planned_progress_percentage }}% Plan</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar {{ $project->progress_percentage >= $project->planned_progress_percentage ? 'bg-success-main' : 'bg-warning-main' }}" style="width: {{ $project->progress_percentage }}%"></div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 text-xs pt-8 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary-light">Schedule Adherence</span>
                        <div class="progress flex-grow-1 mx-2" style="height: 6px;">
                            <div class="progress-bar bg-primary-600" style="width: {{ min(100, $spi * 100) }}%"></div>
                        </div>
                        <strong>{{ number_format($spi, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary-light">Cost Efficiency</span>
                        <div class="progress flex-grow-1 mx-2" style="height: 6px;">
                            <div class="progress-bar {{ $cpi < 1 ? 'bg-warning-main' : 'bg-success-main' }}" style="width: {{ min(100, $cpi * 100) }}%"></div>
                        </div>
                        <strong>{{ number_format($cpi, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary-light">Readiness / Mobilization</span>
                        <div class="progress flex-grow-1 mx-2" style="height: 6px;">
                            <div class="progress-bar bg-info-main" style="width: {{ $project->readiness_percentage }}%"></div>
                        </div>
                        <strong>{{ $project->readiness_percentage }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary-light">Quality / Inspection Dossier</span>
                        <div class="progress flex-grow-1 mx-2" style="height: 6px;">
                            <div class="progress-bar bg-purple-main" style="width: {{ $project->quality_percentage }}%"></div>
                        </div>
                        <strong>{{ $project->quality_percentage }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary-light">HSSE Compliance</span>
                        <div class="progress flex-grow-1 mx-2" style="height: 6px;">
                            <div class="progress-bar bg-success-main" style="width: {{ $project->hsse_percentage }}%"></div>
                        </div>
                        <strong>{{ $project->hsse_percentage }}%</strong>
                    </div>
                </div>

                @if ($project->notes)
                    <div class="p-10 radius-8 bg-neutral-50 border text-xs text-secondary-light mt-auto">
                        <strong>Catatan Project:</strong> {{ $project->notes }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-header bg-transparent border-bottom p-16 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="mb-1 fw-bold text-primary-light">Milestone &amp; Work Breakdown Structure (WBS)</h6>
            <p class="text-secondary-light text-xs mb-0">Tahapan target pekerjaan dan bobot progress deliverable.</p>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary px-12 py-6 radius-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addMilestoneModal">
            <iconify-icon icon="solar:add-circle-linear"></iconify-icon> Tambah Milestone
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 crm-table-compact">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-16" style="width: 80px;">WBS Code</th>
                        <th>Milestone / Activity Title</th>
                        <th>Target Selesai</th>
                        <th>Tanggal Aktual</th>
                        <th>Bobot (%)</th>
                        <th>Progress (%)</th>
                        <th>Status</th>
                        <th>Deliverable</th>
                        <th class="text-center pe-16" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($project->milestones as $m)
                        <tr>
                            <td class="ps-16 fw-bold text-primary-600">{{ $m->milestone_code ?: 'WBS-' . $m->id }}</td>
                            <td><strong class="text-neutral-800">{{ $m->title }}</strong></td>
                            <td>{{ $m->target_date ? $m->target_date->format('d/m/Y') : '—' }}</td>
                            <td>{{ $m->actual_date ? $m->actual_date->format('d/m/Y') : '—' }}</td>
                            <td class="fw-semibold">{{ $m->weight_percentage }}%</td>
                            <td style="min-width: 100px;">
                                <div class="d-flex justify-content-between text-2xs mb-1">
                                    <span>{{ $m->progress_percentage }}%</span>
                                </div>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar {{ $m->progress_percentage == 100 ? 'bg-success-main' : 'bg-primary-600' }}" style="width: {{ $m->progress_percentage }}%"></div>
                                </div>
                            </td>
                            <td>
                                @if ($m->status === 'completed')
                                    <span class="badge bg-success-50 text-success-700 border border-success-200 text-2xs">Completed</span>
                                @elseif ($m->status === 'in_progress')
                                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200 text-2xs">In Progress</span>
                                @elseif ($m->status === 'overdue')
                                    <span class="badge bg-danger-50 text-danger-700 border border-danger-200 text-2xs">Overdue</span>
                                @else
                                    <span class="badge bg-neutral-100 text-neutral-700 text-2xs">Pending</span>
                                @endif
                            </td>
                            <td><span class="text-secondary-light">{{ $m->deliverable ?: '—' }}</span></td>
                            <td class="text-center pe-16">
                                <form action="{{ route('dashboard-admin.pmo.milestones.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus milestone ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus Milestone">
                                        <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-24 text-secondary-light">
                                Belum ada milestone WBS yang dibuat untuk proyek ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-header bg-transparent border-bottom p-16 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="mb-1 fw-bold text-primary-light">Project Team &amp; Performance Evaluation</h6>
            <p class="text-secondary-light text-xs mb-0">Personel yang ditugaskan dan catatan evaluasi kerja dari Project Manager.</p>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary px-12 py-6 radius-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#assignMemberModal">
            <iconify-icon icon="solar:user-plus-linear"></iconify-icon> Tugaskan Personel
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 crm-table-compact">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-16">Nama Personel</th>
                        <th>Posisi di Project</th>
                        <th>Disiplin Keahlian</th>
                        <th>PM Rating Evaluasi</th>
                        <th>Catatan Evaluasi Lapangan</th>
                        <th class="text-center pe-16" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($project->members as $mem)
                        <tr>
                            <td class="ps-16">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-32-px h-32-px rounded-circle bg-primary-100 text-primary-600 d-flex align-items-center justify-content-center fw-bold text-xs">
                                        {{ strtoupper(substr($mem->employee?->user?->name ?? 'EM', 0, 2)) }}
                                    </div>
                                    <div>
                                        <strong class="text-neutral-800 d-block">{{ $mem->employee?->user?->name ?? '—' }}</strong>
                                        <small class="text-secondary-light">{{ $mem->employee?->employee_number ?? '—' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-neutral-100 text-neutral-800">{{ $mem->role }}</span></td>
                            <td><span class="text-secondary-light">{{ $mem->discipline ?: '—' }}</span></td>
                            <td>
                                @if ($mem->rating)
                                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200">
                                        ★ {{ number_format($mem->rating, 1) }} / 5.0
                                    </span>
                                @else
                                    <span class="text-secondary-light">Belum dievaluasi</span>
                                @endif
                            </td>
                            <td><span class="text-secondary-light">{{ $mem->performance_notes ?: 'Menunggu evaluasi PM' }}</span></td>
                            <td class="text-center pe-16">
                                <form action="{{ route('dashboard-admin.pmo.teams.destroy', $mem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus penugasan personel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus Penugasan">
                                        <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-24 text-secondary-light">
                                Belum ada personel yang dialokasikan ke proyek ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-header bg-transparent border-bottom p-16 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="mb-1 fw-bold text-primary-light">Project Documents &amp; Handover Dossier</h6>
            <p class="text-secondary-light text-xs mb-0">Kontrak, BASTP, laporan teknis, dan dokumen CSMS/HSSE terkait.</p>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary px-12 py-6 radius-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
            <iconify-icon icon="solar:upload-linear"></iconify-icon> Upload Dokumen
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 crm-table-compact">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-16">Nama Dokumen</th>
                        <th>Kategori</th>
                        <th>Tanggal Unggah</th>
                        <th>Ukuran File</th>
                        <th>Akses File</th>
                        <th class="text-center pe-16" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($project->documents as $doc)
                        <tr>
                            <td class="ps-16 fw-bold text-neutral-800">{{ $doc->name }}</td>
                            <td><span class="badge bg-primary-50 text-primary-700 border border-primary-200">{{ $doc->category }}</span></td>
                            <td>{{ $doc->uploaded_date ? $doc->uploaded_date->format('d/m/Y') : '—' }}</td>
                            <td>{{ $doc->file_size ?: '—' }}</td>
                            <td>
                                <a href="{{ asset($doc->file_path) }}" target="_blank" class="text-primary-600 text-decoration-none fw-semibold d-inline-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:file-download-bold-duotone" class="text-base"></iconify-icon> Download
                                </a>
                            </td>
                            <td class="text-center pe-16">
                                <form action="{{ route('dashboard-admin.pmo.documents.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus Dokumen">
                                        <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-24 text-secondary-light">
                                Belum ada dokumen pendukung yang diunggah untuk proyek ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="addMilestoneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title fw-bold text-primary-light">Tambah Milestone &amp; WBS</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.pmo.milestones.store') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label text-xs fw-bold">Judul Milestone / Scope <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm radius-8" placeholder="Contoh: Site Mobilization & Inspection" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-xs fw-bold">Kode WBS</label>
                            <input type="text" name="milestone_code" class="form-control form-control-sm radius-8" placeholder="WBS-01">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Target Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="target_date" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Bobot (%)</label>
                            <input type="number" name="weight_percentage" class="form-control form-control-sm radius-8" min="0" max="100" step="0.1" placeholder="20">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Progress (%)</label>
                            <input type="number" name="progress_percentage" class="form-control form-control-sm radius-8" min="0" max="100" value="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm radius-8" required>
                                <option value="pending" selected>Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Deliverable Target</label>
                            <input type="text" name="deliverable" class="form-control form-control-sm radius-8" placeholder="Contoh: Laporan Uji Tekan, BASTP, Mill Certificate">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-16">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8" data-bs-dismiss="modal">Batal</button>
                    
                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-sm radius-8 px-16 fw-semibold">
                        Simpan Milestone
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="assignMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title fw-bold text-primary-light">Tugaskan Personel ke Project</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.pmo.teams.store') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Pilih Karyawan / Personel <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select form-select-sm radius-8" required>
                                <option value="">Pilih Personel</option>
                                @foreach ($employees as $e)
                                    <option value="{{ $e->id }}">{{ $e->user?->name ?? 'EMP-' . $e->id }} — {{ $e->position?->title ?? 'Staff' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Role di Project <span class="text-danger">*</span></label>
                            <input type="text" name="role" class="form-control form-control-sm radius-8" placeholder="Site Supervisor, HSSE Officer, etc" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Disiplin Keahlian</label>
                            <input type="text" name="discipline" class="form-control form-control-sm radius-8" placeholder="Mechanical, HSSE, Electrical, etc">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Status Penugasan <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm radius-8" required>
                                <option value="active" selected>Active</option>
                                <option value="completed">Completed</option>
                                <option value="released">Released</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">PM Rating Awal (1 - 5)</label>
                            <input type="number" name="rating" class="form-control form-control-sm radius-8" min="1" max="5" step="0.1" placeholder="4.5">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Catatan Evaluasi / Scope Tanggung Jawab</label>
                            <textarea name="performance_notes" class="form-control form-control-sm radius-8" rows="2" placeholder="Tanggung jawab khusus atau catatan kinerja..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-16">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8" data-bs-dismiss="modal">Batal</button>
                    
                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-sm radius-8 px-16 fw-semibold">
                        Tugaskan Personel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="uploadDocModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title fw-bold text-primary-light">Unggah Dokumen Project</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.pmo.documents.store', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Nama Dokumen <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm radius-8" placeholder="Contoh: Kontrak BASTP Tahap 1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Kategori Dokumen <span class="text-danger">*</span></label>
                            <select name="category" class="form-select form-select-sm radius-8" required>
                                <option value="Contract">Contract / PO</option>
                                <option value="BASTP">BASTP / Handover</option>
                                <option value="Technical Report">Technical Report</option>
                                <option value="HSSE Documents">HSSE Documents</option>
                                <option value="Timesheet">Timesheet</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Tanggal Upload <span class="text-danger">*</span></label>
                            <input type="date" name="uploaded_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Pilih File (PDF, DOCX, XLSX, JPG - Max 5MB) <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control form-control-sm radius-8" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-16">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8" data-bs-dismiss="modal">Batal</button>
                    
                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-sm radius-8 px-16 fw-semibold">
                        Unggah Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title fw-bold text-primary-light">Perbarui Data Project ({{ $project->project_code }})</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.pmo.projects.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-bold">Nama Project / Scope <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm radius-8" value="{{ $project->title }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select form-select-sm radius-8" required>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}" {{ $project->customer_id === $c->id ? 'selected' : '' }}>{{ $c->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Project Manager <span class="text-danger">*</span></label>
                            <select name="project_manager_id" class="form-select form-select-sm radius-8" required>
                                @foreach ($employees as $e)
                                    <option value="{{ $e->id }}" {{ $project->project_manager_id === $e->id ? 'selected' : '' }}>{{ $e->user?->name ?? 'EMP-' . $e->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Nilai Kontrak (IDR) <span class="text-danger">*</span></label>
                            <input type="number" name="contract_value" class="form-control form-control-sm radius-8" value="{{ $project->contract_value }}" required min="0" step="1000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Approved Budget (BAC)</label>
                            <input type="number" name="budget" class="form-control form-control-sm radius-8" value="{{ $project->budget }}" min="0" step="1000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Actual Cost to Date (AC)</label>
                            <input type="number" name="actual_cost" class="form-control form-control-sm radius-8" value="{{ $project->actual_cost }}" min="0" step="1000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control form-control-sm radius-8" value="{{ $project->start_date ? $project->start_date->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control form-control-sm radius-8" value="{{ $project->end_date ? $project->end_date->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold">Progress Aktual (%)</label>
                            <input type="number" name="progress_percentage" class="form-control form-control-sm radius-8" min="0" max="100" value="{{ $project->progress_percentage }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold">Progress Plan (%)</label>
                            <input type="number" name="planned_progress_percentage" class="form-control form-control-sm radius-8" min="0" max="100" value="{{ $project->planned_progress_percentage }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold">Status Eksekusi <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm radius-8" required>
                                @foreach (\App\Models\Project::STATUSES as $st)
                                    <option value="{{ $st }}" {{ $project->status === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold">Tingkat Risiko <span class="text-danger">*</span></label>
                            <select name="risk_level" class="form-select form-select-sm radius-8" required>
                                @foreach (\App\Models\Project::RISKS as $rk)
                                    <option value="{{ $rk }}" {{ $project->risk_level === $rk ? 'selected' : '' }}>{{ ucfirst($rk) }} Risk</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Readiness (%)</label>
                            <input type="number" name="readiness_percentage" class="form-control form-control-sm radius-8" min="0" max="100" value="{{ $project->readiness_percentage }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Quality Completion (%)</label>
                            <input type="number" name="quality_percentage" class="form-control form-control-sm radius-8" min="0" max="100" value="{{ $project->quality_percentage }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">HSSE Action (%)</label>
                            <input type="number" name="hsse_percentage" class="form-control form-control-sm radius-8" min="0" max="100" value="{{ $project->hsse_percentage }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Catatan Proyek</label>
                            <textarea name="notes" class="form-control form-control-sm radius-8" rows="2">{{ $project->notes }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-16">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8" data-bs-dismiss="modal">Batal</button>
                    
                    <button type="submit" id="btnUpdate" class="btn btn-primary btn-sm radius-8 px-16 fw-semibold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
