@extends('layout.layout')

@php
    $title = 'Planning & Scheduling';
    $subTitle = 'Baseline, Progress Aktual, Forecast & Work Breakdown Structure (WBS) — PT ELBEMA Integra Indonesia';
@endphp

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-24">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('dashboard-admin.pmo.dashboard') }}" class="btn btn-sm btn-outline-secondary px-10 py-4 radius-6 d-inline-flex align-items-center gap-1 text-xs">
                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon> Dashboard PMO
            </a>
            <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-8 py-2 text-xs fw-bold text-uppercase">PMO Controls</span>
        </div>
        <h4 class="mb-4 fw-bold text-primary-light">Planning &amp; Scheduling</h4>
        <p class="text-secondary-light mb-0 text-sm">Baseline, progress aktual, forecast, milestone, dan deviasi jadwal setiap project.</p>
    </div>

    <div>
        @can('pmo.milestone.manage')
            <button type="button" class="btn btn-primary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#createMilestoneModal">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="text-base"></iconify-icon>
                Tambah Milestone
            </button>
        @endcan
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show text-sm radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- 4 KPI CARDS (Compact) --}}
<div class="row g-3 mb-24">
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-primary">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Milestones</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">{{ $totalMilestones }} <span class="fs-6 fw-normal text-secondary-light">Deliverable</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top">Across active portfolio projects</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-success">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Milestone Selesai</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">{{ $completedMilestones }} <span class="fs-6 fw-normal text-secondary-light">Tahapan</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top d-flex justify-content-between">
                    <span>100% Verified</span>
                    <span class="badge bg-success-50 text-success-700">Completed</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-warning">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Sedang Berjalan</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">{{ $inProgressMilestones }} <span class="fs-6 fw-normal text-secondary-light">Tahapan</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top d-flex justify-content-between">
                    <span>Progres Eksekusi</span>
                    <span class="badge bg-warning-50 text-warning-700">In Progress</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-danger">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-danger-main">Overdue / Tertunda</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">{{ $overdueMilestones }} <span class="fs-6 fw-normal text-secondary-light">Item</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top d-flex justify-content-between">
                    <span>Butuh Mitigasi & Escalation</span>
                    <span class="badge bg-danger-50 text-danger-700">Critical Path</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER CARD --}}
<div class="card radius-12 border-0 shadow-sm mb-20">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.pmo.milestones.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="position-relative">
                    <input type="text" name="search" class="form-control form-control-sm ps-36 radius-8" placeholder="Cari milestone, kode WBS, deliverable..." value="{{ request('search') }}">
                    <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-12 text-secondary-light text-base"></iconify-icon>
                </div>
            </div>
            <div class="col-md-3">
                <select name="project_id" class="form-select form-select-sm radius-8">
                    <option value="">Semua Project</option>
                    @foreach ($projects as $pr)
                        <option value="{{ $pr->id }}" {{ request('project_id') == $pr->id ? 'selected' : '' }}>
                            {{ $pr->project_code }} — {{ Str::limit($pr->title, 32) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm radius-8">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 radius-8">Filter</button>
                @if (request()->hasAny(['search', 'project_id', 'status']))
                    <a href="{{ route('dashboard-admin.pmo.milestones.index') }}" class="btn btn-sm btn-outline-secondary radius-8" title="Reset Filter">
                        <iconify-icon icon="solar:restart-linear"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- MILESTONE TABLE CARD --}}
<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 crm-table-compact">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-16" style="width: 70px;">WBS Code</th>
                        <th>Project &amp; Customer</th>
                        <th>Milestone / Activity Title</th>
                        <th>Target Selesai</th>
                        <th>Bobot (%)</th>
                        <th>Progress (%)</th>
                        <th>Status</th>
                        <th>Deliverable Document</th>
                        <th class="text-center pe-16" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($milestones as $m)
                        <tr>
                            <td class="ps-16 fw-bold text-primary-600">{{ $m->milestone_code ?: 'WBS-' . $m->id }}</td>
                            <td>
                                <a href="{{ route('dashboard-admin.pmo.projects.show', $m->project_id) }}" class="fw-bold text-primary-600 text-decoration-none d-block">
                                    {{ Str::limit($m->project?->title, 32) }}
                                </a>
                                <small class="text-secondary-light">{{ $m->project?->customer?->company_name ?? '—' }}</small>
                            </td>
                            <td>
                                <strong class="text-neutral-800 d-block">{{ $m->title }}</strong>
                                @if ($m->notes)
                                    <small class="text-secondary-light">{{ Str::limit($m->notes, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="d-block text-neutral-800">{{ $m->target_date ? $m->target_date->format('d/m/Y') : '—' }}</span>
                                @if ($m->actual_date)
                                    <small class="text-success-main">Selesai: {{ $m->actual_date->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $m->weight_percentage }}%</td>
                            <td style="min-width: 100px;">
                                <div class="d-flex justify-content-between text-2xs mb-1">
                                    <span class="fw-bold">{{ $m->progress_percentage }}%</span>
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
                                <div class="d-inline-flex align-items-center gap-1">
                                    {{-- Edit Button (Green) --}}
                                    <button type="button"
                                            class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center border-0 text-sm shadow-xs btn-edit-milestone"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editMilestoneModal"
                                            data-action="{{ route('dashboard-admin.pmo.milestones.update', $m->id) }}"
                                            data-code="{{ $m->milestone_code }}"
                                            data-title="{{ $m->title }}"
                                            data-target="{{ $m->target_date ? $m->target_date->format('Y-m-d') : '' }}"
                                            data-actual="{{ $m->actual_date ? $m->actual_date->format('Y-m-d') : '' }}"
                                            data-weight="{{ $m->weight_percentage }}"
                                            data-progress="{{ $m->progress_percentage }}"
                                            data-status="{{ $m->status }}"
                                            data-deliverable="{{ $m->deliverable }}"
                                            data-notes="{{ $m->notes }}"
                                            title="Edit Milestone">
                                        <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                    </button>

                                    {{-- Delete Button (Red) --}}
                                    <form action="{{ route('dashboard-admin.pmo.milestones.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus milestone ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center border-0 text-sm shadow-xs" title="Hapus Milestone">
                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-32 text-secondary-light">
                                Tidak ada milestone yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-16 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-xs text-secondary-light">Menampilkan {{ $milestones->firstItem() ?? 0 }} s/d {{ $milestones->lastItem() ?? 0 }} dari total {{ $milestones->total() }} milestone</span>
            {{ $milestones->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- MODAL TAMBAH MILESTONE (id="btnSubmit") --}}
<div class="modal fade" id="createMilestoneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title fw-bold text-primary-light">Tambah Milestone Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.pmo.milestones.store') }}" method="POST">
                @csrf
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Pilih Project <span class="text-danger">*</span></label>
                            <select name="project_id" class="form-select form-select-sm radius-8" required>
                                <option value="">Pilih Project</option>
                                @foreach ($projects as $pr)
                                    <option value="{{ $pr->id }}">{{ $pr->project_code }} — {{ $pr->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-8">
                            <label class="form-label text-xs fw-bold">Judul Milestone / Scope <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm radius-8" placeholder="Contoh: Equipment Readiness & Site Mob" required>
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
                            <input type="number" name="weight_percentage" class="form-control form-control-sm radius-8" min="0" max="100" step="0.1" placeholder="25">
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
                            <input type="text" name="deliverable" class="form-control form-control-sm radius-8" placeholder="Contoh: Mill Certificate, BASTP, Laporan Teknis">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-16">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8" data-bs-dismiss="modal">Batal</button>
                    {{-- User rule: id="btnSubmit" for create button --}}
                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-sm radius-8 px-16 fw-semibold">
                        Simpan Milestone
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT MILESTONE (id="btnUpdate") --}}
<div class="modal fade" id="editMilestoneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title fw-bold text-primary-light">Edit Milestone &amp; WBS</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditMilestone" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label text-xs fw-bold">Judul Milestone <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_m_title" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label text-xs fw-bold">Kode WBS</label>
                            <input type="text" name="milestone_code" id="edit_m_code" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Target Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="target_date" id="edit_m_target" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Tanggal Aktual Selesai</label>
                            <input type="date" name="actual_date" id="edit_m_actual" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Bobot (%)</label>
                            <input type="number" name="weight_percentage" id="edit_m_weight" class="form-control form-control-sm radius-8" min="0" max="100" step="0.1">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Progress (%)</label>
                            <input type="number" name="progress_percentage" id="edit_m_progress" class="form-control form-control-sm radius-8" min="0" max="100">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="edit_m_status" class="form-select form-select-sm radius-8" required>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Deliverable Target</label>
                            <input type="text" name="deliverable" id="edit_m_deliverable" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Catatan Perkembangan</label>
                            <textarea name="notes" id="edit_m_notes" class="form-control form-control-sm radius-8" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-16">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8" data-bs-dismiss="modal">Batal</button>
                    {{-- User rule: id="btnUpdate" for update button --}}
                    <button type="submit" id="btnUpdate" class="btn btn-primary btn-sm radius-8 px-16 fw-semibold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.btn-edit-milestone');
        const form = document.getElementById('formEditMilestone');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                form.action = this.dataset.action;
                document.getElementById('edit_m_code').value = this.dataset.code || '';
                document.getElementById('edit_m_title').value = this.dataset.title || '';
                document.getElementById('edit_m_target').value = this.dataset.target || '';
                document.getElementById('edit_m_actual').value = this.dataset.actual || '';
                document.getElementById('edit_m_weight').value = this.dataset.weight || 0;
                document.getElementById('edit_m_progress').value = this.dataset.progress || 0;
                document.getElementById('edit_m_status').value = this.dataset.status || 'pending';
                document.getElementById('edit_m_deliverable').value = this.dataset.deliverable || '';
                document.getElementById('edit_m_notes').value = this.dataset.notes || '';
            });
        });
    });
</script>
@endpush
@endsection
