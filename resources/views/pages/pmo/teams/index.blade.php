@extends('layout.layout')

@php
    $title = 'Penugasan Tim (PMO)';
    $subTitle = 'Alokasi Sumber Daya Manusia & Evaluasi Kinerja — PT ELBEMA Integra Indonesia';
@endphp

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-24">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('dashboard-admin.pmo.dashboard') }}" class="btn btn-sm btn-outline-secondary px-10 py-4 radius-6 d-inline-flex align-items-center gap-1 text-xs">
                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon> Dashboard PMO
            </a>
            <span class="badge bg-success-50 text-success-700 border border-success-200 px-8 py-2 text-xs fw-bold text-uppercase">PMO Resource</span>
        </div>
        <h4 class="mb-4 fw-bold text-primary-light">Penugasan Tim &amp; Evaluasi Kinerja Personel</h4>
        <p class="text-secondary-light mb-0 text-sm">Alokasi SDM proyek, pengawasan peran di lapangan, dan riwayat evaluasi performa kerja.</p>
    </div>

    <div>
        @can('pmo.team.assign')
            <button type="button" class="btn btn-primary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#createTeamModal">
                <iconify-icon icon="solar:user-plus-bold-duotone" class="text-base"></iconify-icon>
                Tugaskan Personel Baru
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

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-sm radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- 4 KPI CARDS (Compact) --}}
<div class="row g-3 mb-24">
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-primary">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Penugasan</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">{{ $totalAssignments }} <span class="fs-6 fw-normal text-secondary-light">Alokasi</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top">Alokasi personel di seluruh portfolio</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-info">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Karyawan Bertugas</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">{{ $uniqueEmployees }} <span class="fs-6 fw-normal text-secondary-light">Personel</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top d-flex justify-content-between">
                    <span>Active Manpower</span>
                    <span class="badge bg-info-50 text-info-700">Site Team</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-success">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Proyek Ter-Cover</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">{{ $activeProjectsWithTeam }} <span class="fs-6 fw-normal text-secondary-light">Proyek</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top d-flex justify-content-between">
                    <span>Coverage Tim Lengkap</span>
                    <span class="badge bg-success-50 text-success-700">Covered</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-warning">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Rata-Rata PM Rating</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">★ {{ number_format($averageRating, 1) }} <span class="fs-6 fw-normal text-secondary-light">/ 5.0</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top d-flex justify-content-between">
                    <span>Evaluasi Manajer</span>
                    <span class="badge bg-warning-50 text-warning-700">Performance</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTERS CARD --}}
<div class="card radius-12 border-0 shadow-sm mb-20">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.pmo.teams.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="position-relative">
                    <input type="text" name="role" class="form-control form-control-sm ps-36 radius-8" placeholder="Cari posisi / role..." value="{{ request('role') }}">
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
                <select name="employee_id" class="form-select form-select-sm radius-8">
                    <option value="">Semua Personel</option>
                    @foreach ($employees as $em)
                        <option value="{{ $em->id }}" {{ request('employee_id') == $em->id ? 'selected' : '' }}>
                            {{ $em->user?->name ?? 'EMP-' . $em->id }} ({{ $em->employee_number }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 radius-8">Filter</button>
                @if (request()->hasAny(['role', 'project_id', 'employee_id']))
                    <a href="{{ route('dashboard-admin.pmo.teams.index') }}" class="btn btn-sm btn-outline-secondary radius-8" title="Reset Filter">
                        <iconify-icon icon="solar:restart-linear"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- TEAM ASSIGNMENT TABLE CARD --}}
<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 crm-table-compact">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-16" style="width: 40px;">No</th>
                        <th>Karyawan &amp; Identitas</th>
                        <th>Project &amp; Customer</th>
                        <th>Role di Proyek</th>
                        <th>Disiplin</th>
                        <th>Mulai Tugas</th>
                        <th>PM Evaluasi Rating</th>
                        <th>Catatan Kinerja Lapangan</th>
                        <th>Status</th>
                        <th class="text-center pe-16" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($members as $idx => $mem)
                        <tr>
                            <td class="ps-16 text-secondary-light fw-bold">{{ $members->firstItem() + $idx }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-32-px h-32-px rounded-circle bg-primary-100 text-primary-600 d-flex align-items-center justify-content-center fw-bold text-xs">
                                        {{ strtoupper(substr($mem->employee?->user?->name ?? 'EM', 0, 2)) }}
                                    </div>
                                    <div>
                                        <strong class="text-neutral-800 d-block">{{ $mem->employee?->user?->name ?? '—' }}</strong>
                                        <small class="text-secondary-light">{{ $mem->employee?->employee_number ?? '—' }} · {{ $mem->employee?->position?->title ?? 'Staff' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('dashboard-admin.pmo.projects.show', $mem->project_id) }}" class="fw-bold text-primary-600 text-decoration-none d-block">
                                    {{ Str::limit($mem->project?->title, 30) }}
                                </a>
                                <small class="text-secondary-light">{{ $mem->project?->customer?->company_name ?? '—' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-neutral-100 text-neutral-800">{{ $mem->role }}</span>
                            </td>
                            <td>
                                <span class="text-secondary-light">{{ $mem->discipline ?: '—' }}</span>
                            </td>
                            <td>
                                {{ $mem->assigned_date ? $mem->assigned_date->format('d/m/Y') : '—' }}
                            </td>
                            <td>
                                @if ($mem->rating)
                                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200">
                                        ★ {{ number_format($mem->rating, 1) }} / 5.0
                                    </span>
                                @else
                                    <span class="text-secondary-light">Belum dievaluasi</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-secondary-light">{{ Str::limit($mem->performance_notes, 36) ?: '—' }}</span>
                            </td>
                            <td>
                                @if ($mem->status === 'active')
                                    <span class="badge bg-success-50 text-success-700 border border-success-200 text-2xs">Active</span>
                                @elseif ($mem->status === 'completed')
                                    <span class="badge bg-neutral-100 text-neutral-700 text-2xs">Completed</span>
                                @else
                                    <span class="badge bg-warning-50 text-warning-700 text-2xs">Released</span>
                                @endif
                            </td>
                            <td class="text-center pe-16">
                                <div class="d-inline-flex align-items-center gap-1">
                                    {{-- Edit Button (Green) --}}
                                    <button type="button"
                                            class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center border-0 text-sm shadow-xs btn-edit-team"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editTeamModal"
                                            data-action="{{ route('dashboard-admin.pmo.teams.update', $mem->id) }}"
                                            data-role="{{ $mem->role }}"
                                            data-discipline="{{ $mem->discipline }}"
                                            data-date="{{ $mem->assigned_date ? $mem->assigned_date->format('Y-m-d') : '' }}"
                                            data-rating="{{ $mem->rating }}"
                                            data-notes="{{ $mem->performance_notes }}"
                                            data-status="{{ $mem->status }}"
                                            title="Edit Evaluasi / Role">
                                        <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                    </button>

                                    {{-- Delete Button (Red) --}}
                                    <form action="{{ route('dashboard-admin.pmo.teams.destroy', $mem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus penugasan personel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center border-0 text-sm shadow-xs" title="Hapus Penugasan">
                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-32 text-secondary-light">
                                Tidak ada penugasan tim yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-16 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-xs text-secondary-light">Menampilkan {{ $members->firstItem() ?? 0 }} s/d {{ $members->lastItem() ?? 0 }} dari total {{ $members->total() }} penugasan</span>
            {{ $members->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- MODAL TUGASKAN PERSONEL (id="btnSubmit") --}}
<div class="modal fade" id="createTeamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title fw-bold text-primary-light">Tugaskan Personel ke Project</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.pmo.teams.store') }}" method="POST">
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
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Pilih Karyawan <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select form-select-sm radius-8" required>
                                <option value="">Pilih Karyawan</option>
                                @foreach ($employees as $em)
                                    <option value="{{ $em->id }}">{{ $em->user?->name ?? 'EMP-' . $em->id }} ({{ $em->employee_number }}) — {{ $em->position?->title ?? 'Staff' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Role di Project <span class="text-danger">*</span></label>
                            <input type="text" name="role" class="form-control form-control-sm radius-8" placeholder="Lead Engineer, Site Supervisor, etc" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Disiplin Keahlian</label>
                            <input type="text" name="discipline" class="form-control form-control-sm radius-8" placeholder="Mechanical, HSSE, etc">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Tanggal Mulai Tugas</label>
                            <input type="date" name="assigned_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">PM Rating Awal (1 - 5)</label>
                            <input type="number" name="rating" class="form-control form-control-sm radius-8" min="1" max="5" step="0.1" placeholder="4.5">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Status Penugasan <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm radius-8" required>
                                <option value="active" selected>Active</option>
                                <option value="completed">Completed</option>
                                <option value="released">Released</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Catatan Evaluasi / Scope Tanggung Jawab</label>
                            <textarea name="performance_notes" class="form-control form-control-sm radius-8" rows="2" placeholder="Catatan tugas lapangan atau evaluasi awal..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-16">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8" data-bs-dismiss="modal">Batal</button>
                    {{-- User rule: id="btnSubmit" for create button --}}
                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-sm radius-8 px-16 fw-semibold">
                        Tugaskan Personel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT EVALUASI PENUGASAN (id="btnUpdate") --}}
<div class="modal fade" id="editTeamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title fw-bold text-primary-light">Edit Penugasan &amp; Evaluasi PM</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditTeam" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Role di Project <span class="text-danger">*</span></label>
                            <input type="text" name="role" id="edit_t_role" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Disiplin Keahlian</label>
                            <input type="text" name="discipline" id="edit_t_discipline" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">Tanggal Mulai Tugas</label>
                            <input type="date" name="assigned_date" id="edit_t_date" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs fw-bold">PM Evaluasi Rating (1 - 5)</label>
                            <input type="number" name="rating" id="edit_t_rating" class="form-control form-control-sm radius-8" min="1" max="5" step="0.1">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Status Penugasan <span class="text-danger">*</span></label>
                            <select name="status" id="edit_t_status" class="form-select form-select-sm radius-8" required>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="released">Released</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Catatan Kinerja &amp; Ulasan Project Manager</label>
                            <textarea name="performance_notes" id="edit_t_notes" class="form-control form-control-sm radius-8" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-16">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8" data-bs-dismiss="modal">Batal</button>
                    {{-- User rule: id="btnUpdate" for update button --}}
                    <button type="submit" id="btnUpdate" class="btn btn-primary btn-sm radius-8 px-16 fw-semibold">
                        Simpan Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.btn-edit-team');
        const form = document.getElementById('formEditTeam');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                form.action = this.dataset.action;
                document.getElementById('edit_t_role').value = this.dataset.role || '';
                document.getElementById('edit_t_discipline').value = this.dataset.discipline || '';
                document.getElementById('edit_t_date').value = this.dataset.date || '';
                document.getElementById('edit_t_rating').value = this.dataset.rating || '';
                document.getElementById('edit_t_status').value = this.dataset.status || 'active';
                document.getElementById('edit_t_notes').value = this.dataset.notes || '';
            });
        });
    });
</script>
@endpush
@endsection
