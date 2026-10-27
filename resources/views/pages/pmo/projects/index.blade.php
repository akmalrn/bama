@extends('layout.layout')

@php
    $title = 'Project Register (PMO)';
    $subTitle = 'Daftar Proyek & Monitoring Pelaksanaan — PT ELBEMA Integra Indonesia';
@endphp

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-24">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('dashboard-admin.pmo.dashboard') }}" class="btn btn-sm btn-outline-secondary px-10 py-4 radius-6 d-inline-flex align-items-center gap-1 text-xs">
                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon> Dashboard PMO
            </a>
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">PMO Portfolio</span>
        </div>
        <h4 class="mb-4 fw-bold text-primary-light">Project Register &amp; Execution Control</h4>
        <p class="text-secondary-light mb-0 text-sm">Kelola seluruh proyek yang berjalan, performa kontrak, jadwal, dan deliverables.</p>
    </div>

    <div>
        @can('pmo.project.create')
            <button type="button" class="btn btn-primary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="text-base"></iconify-icon>
                Tambah Project Baru
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

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show text-sm radius-8 mb-20" role="alert">
        <strong>Terdapat kesalahan pengisian data:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="row g-3 mb-24">
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-primary">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Proyek</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">{{ $totalProjects }} <span class="fs-6 fw-normal text-secondary-light">Proyek</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top">
                    Total Nilai: <strong class="text-primary-600">Rp {{ number_format($totalContractValue, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-warning">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Ongoing (Berjalan)</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">{{ $ongoingProjects }} <span class="fs-6 fw-normal text-secondary-light">Aktif</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top d-flex justify-content-between">
                    <span>Sedang Eksekusi Lapangan</span>
                    <span class="badge bg-warning-50 text-warning-700">In Progress</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-success">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Completed (Selesai)</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">{{ $completedProjects }} <span class="fs-6 fw-normal text-secondary-light">Proyek</span></h4>
                <div class="text-xs text-secondary-light pt-8 border-top d-flex justify-content-between">
                    <span>Handover & BASTP terbit</span>
                    <span class="badge bg-success-50 text-success-700">100% Selesai</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-info">
            <div class="card-body p-16">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Penagihan Termin (Invoice)</span>
                <h4 class="fw-bold mb-1 mt-1 text-primary-light fs-5">Rp {{ number_format($totalSubmittedInvoice / 1000000, 1) }}M</h4>
                <div class="text-xs text-secondary-light pt-8 border-top d-flex justify-content-between">
                    <span>Terbayar: <strong>Rp {{ number_format($totalPaidInvoice / 1000000, 1) }}M</strong></span>
                    <span class="badge bg-info-50 text-info-700">Cash Flow</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-20">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.pmo.projects.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="position-relative">
                    <input type="text" name="search" class="form-control form-control-sm ps-36 radius-8" placeholder="Cari nama project, kode, lokasi, atau customer..." value="{{ request('search') }}">
                    <iconify-icon icon="solar:magnifer-linear" class="position-absolute top-50 start-0 translate-middle-y ms-12 text-secondary-light text-base"></iconify-icon>
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm radius-8">
                    <option value="">Semua Status</option>
                    @foreach (\App\Models\Project::STATUSES as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="start_date" class="form-control form-control-sm radius-8" value="{{ request('start_date') }}" title="Mulai Tanggal">
            </div>
            <div class="col-md-2">
                <input type="date" name="end_date" class="form-control form-control-sm radius-8" value="{{ request('end_date') }}" title="Sampai Tanggal">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 radius-8">Filter</button>
                @if (request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('dashboard-admin.pmo.projects.index') }}" class="btn btn-sm btn-outline-secondary radius-8" title="Reset Filter">
                        <iconify-icon icon="solar:restart-linear"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 crm-table-compact">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-16" style="width: 40px;">No</th>
                        <th>Kode &amp; Nama Project</th>
                        <th>Customer &amp; Lokasi</th>
                        <th>Kategori Scope</th>
                        <th>Project Manager</th>
                        <th>Jadwal Pelaksanaan</th>
                        <th>Progress Fisik</th>
                        <th>Status</th>
                        <th>Nilai Kontrak</th>
                        <th class="text-center pe-16" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($projects as $idx => $p)
                        <tr>
                            <td class="ps-16 text-secondary-light fw-bold">{{ $projects->firstItem() + $idx }}</td>
                            <td>
                                <a href="{{ route('dashboard-admin.pmo.projects.show', $p->id) }}" class="fw-bold text-primary-600 text-decoration-none d-block">
                                    {{ $p->title }}
                                </a>
                                <span class="badge bg-neutral-100 text-neutral-700 text-3xs">{{ $p->project_code }}</span>
                            </td>
                            <td>
                                <strong class="d-block text-neutral-800">{{ $p->customer?->company_name ?? '—' }}</strong>
                                <small class="text-secondary-light">{{ $p->location ?: 'Site belum diset' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary-50 text-primary-700 border border-primary-200">{{ $p->category ?: 'General Service' }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-24-px h-24-px rounded-circle bg-primary-100 text-primary-600 d-flex align-items-center justify-content-center fw-bold text-3xs">
                                        {{ strtoupper(substr($p->projectManager?->user?->name ?? 'PM', 0, 2)) }}
                                    </div>
                                    <span class="text-neutral-800 fw-semibold">{{ $p->projectManager?->user?->name ?? 'Unassigned' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="d-block text-neutral-800">{{ $p->start_date ? $p->start_date->format('d/m/Y') : '—' }} s/d {{ $p->end_date ? $p->end_date->format('d/m/Y') : '—' }}</span>
                                <small class="text-secondary-light">
                                    @if ($p->start_date && $p->end_date)
                                        {{ $p->start_date->diffInDays($p->end_date) }} Hari Kalender
                                    @endif
                                </small>
                            </td>
                            <td style="min-width: 120px;">
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
                                @if ($p->status === 'completed')
                                    <span class="badge bg-success-50 text-success-700 border border-success-200 text-2xs">Completed</span>
                                @elseif ($p->status === 'on_progress')
                                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200 text-2xs">Ongoing</span>
                                @elseif ($p->status === 'planning')
                                    <span class="badge bg-info-50 text-info-700 border border-info-200 text-2xs">Planning</span>
                                @else
                                    <span class="badge bg-neutral-100 text-neutral-700 text-2xs">{{ ucfirst(str_replace('_', ' ', $p->status)) }}</span>
                                @endif
                            </td>
                            <td class="fw-bold text-neutral-800">
                                Rp {{ number_format($p->contract_value, 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-16">
                                <div class="d-inline-flex align-items-center gap-1">
                                    
                                    <a href="{{ route('dashboard-admin.pmo.projects.show', $p->id) }}"
                                       class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center border-0 text-sm shadow-xs"
                                       title="Lihat Detail 360">
                                        <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                    </a>

                                    @can('pmo.project.edit')
                                        
                                        <button type="button"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center border-0 text-sm shadow-xs btn-edit-project"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editProjectModal"
                                                data-action="{{ route('dashboard-admin.pmo.projects.update', $p->id) }}"
                                                data-title="{{ $p->title }}"
                                                data-customer="{{ $p->customer_id }}"
                                                data-manager="{{ $p->project_manager_id }}"
                                                data-value="{{ $p->contract_value }}"
                                                data-budget="{{ $p->budget }}"
                                                data-actualcost="{{ $p->actual_cost }}"
                                                data-start="{{ $p->start_date ? $p->start_date->format('Y-m-d') : '' }}"
                                                data-end="{{ $p->end_date ? $p->end_date->format('Y-m-d') : '' }}"
                                                data-status="{{ $p->status }}"
                                                data-progress="{{ $p->progress_percentage }}"
                                                data-planned="{{ $p->planned_progress_percentage }}"
                                                data-location="{{ $p->location }}"
                                                data-category="{{ $p->category }}"
                                                data-sector="{{ $p->sector }}"
                                                data-procurement="{{ $p->procurement_type }}"
                                                data-risk="{{ $p->risk_level }}"
                                                data-notes="{{ $p->notes }}"
                                                title="Edit Project">
                                            <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                        </button>
                                    @endcan

                                    @can('pmo.project.delete')
                                        
                                        <form action="{{ route('dashboard-admin.pmo.projects.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus project {{ $p->project_code }} secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center border-0 text-sm shadow-xs" title="Hapus Project">
                                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-32 text-secondary-light">
                                <iconify-icon icon="solar:folder-open-line-duotone" class="text-4xl d-block mx-auto mb-2 text-neutral-400"></iconify-icon>
                                Tidak ada proyek yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-16 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-xs text-secondary-light">Menampilkan {{ $projects->firstItem() ?? 0 }} s/d {{ $projects->lastItem() ?? 0 }} dari total {{ $projects->total() }} project</span>
            {{ $projects->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>


<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom p-20">
                <div>
                    <h6 class="modal-title fw-bold text-primary-light" id="createProjectModalLabel">Tambah Project Baru (PMO)</h6>
                    <p class="text-secondary-light text-xs mb-0">Daftarkan Sales Order/Kontrak baru ke dalam sistem portfolio PMO.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.pmo.projects.store') }}" method="POST">
                @csrf
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-bold">Nama Project / Scope <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm radius-8" placeholder="Contoh: Spherical Tank Cleaning CO₂ Unit 12" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Project Code (Opsional)</label>
                            <input type="text" name="project_code" class="form-control form-control-sm radius-8" placeholder="Otomatis PRJ-2026-XXX jika kosong">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select form-select-sm radius-8" required>
                                <option value="">Pilih Customer</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->company_name }} ({{ $c->location }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Project Manager Assigned <span class="text-danger">*</span></label>
                            <select name="project_manager_id" class="form-select form-select-sm radius-8" required>
                                <option value="">Pilih Project Manager</option>
                                @foreach ($employees as $e)
                                    <option value="{{ $e->id }}">{{ $e->user?->name ?? 'EMP-' . $e->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Nilai Kontrak / PO (IDR) <span class="text-danger">*</span></label>
                            <input type="number" name="contract_value" class="form-control form-control-sm radius-8" placeholder="0" min="0" step="1000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Approved Budget / BAC (IDR)</label>
                            <input type="number" name="budget" class="form-control form-control-sm radius-8" placeholder="Otomatis 85% kontrak jika kosong" min="0" step="1000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Tanggal Mulai (Kick-off)</label>
                            <input type="date" name="start_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Tanggal Target Selesai (Due Date)</label>
                            <input type="date" name="end_date" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Kategori Layanan</label>
                            <input type="text" name="category" class="form-control form-control-sm radius-8" placeholder="Online Leak Sealing, Tank Cleaning, etc">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Sektor Industri</label>
                            <input type="text" name="sector" class="form-control form-control-sm radius-8" placeholder="Oil & Gas, Fertilizer, Mining">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Lokasi Site / Kota</label>
                            <input type="text" name="location" class="form-control form-control-sm radius-8" placeholder="Palembang, Bontang, etc">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Metode Pengadaan</label>
                            <select name="procurement_type" class="form-select form-select-sm radius-8">
                                <option value="Direct Selection">Direct Selection</option>
                                <option value="Open Tender">Open Tender</option>
                                <option value="Direct Appointment">Direct Appointment</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Status Eksekusi <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm radius-8" required>
                                <option value="planning">Planning</option>
                                <option value="on_progress" selected>Ongoing (In Progress)</option>
                                <option value="completed">Completed</option>
                                <option value="on_hold">On Hold</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Tingkat Risiko <span class="text-danger">*</span></label>
                            <select name="risk_level" class="form-select form-select-sm radius-8" required>
                                <option value="low" selected>Low Risk</option>
                                <option value="medium">Medium Risk</option>
                                <option value="high">High Risk</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Catatan / Key Deliverables</label>
                            <textarea name="notes" class="form-control form-control-sm radius-8" rows="2" placeholder="Catatan deliverable utama, milestone target, atau koordinasi teknis..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-16">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8" data-bs-dismiss="modal">Batal</button>
                    
                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-sm radius-8 px-16 fw-semibold">
                        Simpan Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom p-20">
                <div>
                    <h6 class="modal-title fw-bold text-primary-light" id="editProjectModalLabel">Edit Informasi Project</h6>
                    <p class="text-secondary-light text-xs mb-0">Perbarui parameter kontrak, progress fisik, dan status pelaksanaan.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditProject" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-bold">Nama Project / Scope <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_title" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" id="edit_customer_id" class="form-select form-select-sm radius-8" required>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Project Manager Assigned <span class="text-danger">*</span></label>
                            <select name="project_manager_id" id="edit_project_manager_id" class="form-select form-select-sm radius-8" required>
                                @foreach ($employees as $e)
                                    <option value="{{ $e->id }}">{{ $e->user?->name ?? 'EMP-' . $e->id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Nilai Kontrak (IDR) <span class="text-danger">*</span></label>
                            <input type="number" name="contract_value" id="edit_contract_value" class="form-control form-control-sm radius-8" required min="0" step="1000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Approved Budget (BAC)</label>
                            <input type="number" name="budget" id="edit_budget" class="form-control form-control-sm radius-8" min="0" step="1000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Actual Cost to Date (AC)</label>
                            <input type="number" name="actual_cost" id="edit_actual_cost" class="form-control form-control-sm radius-8" min="0" step="1000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold">Tanggal Target Selesai</label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold">Progress Aktual (%)</label>
                            <input type="number" name="progress_percentage" id="edit_progress_percentage" class="form-control form-control-sm radius-8" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold">Progress Plan (%)</label>
                            <input type="number" name="planned_progress_percentage" id="edit_planned_progress_percentage" class="form-control form-control-sm radius-8" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold">Status Eksekusi <span class="text-danger">*</span></label>
                            <select name="status" id="edit_status" class="form-select form-select-sm radius-8" required>
                                @foreach (\App\Models\Project::STATUSES as $st)
                                    <option value="{{ $st }}">{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-bold">Tingkat Risiko <span class="text-danger">*</span></label>
                            <select name="risk_level" id="edit_risk_level" class="form-select form-select-sm radius-8" required>
                                @foreach (\App\Models\Project::RISKS as $rk)
                                    <option value="{{ $rk }}">{{ ucfirst($rk) }} Risk</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Kategori Layanan</label>
                            <input type="text" name="category" id="edit_category" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Sektor Industri</label>
                            <input type="text" name="sector" id="edit_sector" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold">Lokasi Site</label>
                            <input type="text" name="location" id="edit_location" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold">Catatan Proyek</label>
                            <textarea name="notes" id="edit_notes" class="form-control form-control-sm radius-8" rows="2"></textarea>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.btn-edit-project');
        const formEdit = document.getElementById('formEditProject');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                formEdit.action = this.dataset.action;
                document.getElementById('edit_title').value = this.dataset.title || '';
                document.getElementById('edit_customer_id').value = this.dataset.customer || '';
                document.getElementById('edit_project_manager_id').value = this.dataset.manager || '';
                document.getElementById('edit_contract_value').value = this.dataset.value || 0;
                document.getElementById('edit_budget').value = this.dataset.budget || 0;
                document.getElementById('edit_actual_cost').value = this.dataset.actualcost || 0;
                document.getElementById('edit_start_date').value = this.dataset.start || '';
                document.getElementById('edit_end_date').value = this.dataset.end || '';
                document.getElementById('edit_status').value = this.dataset.status || 'planning';
                document.getElementById('edit_progress_percentage').value = this.dataset.progress || 0;
                document.getElementById('edit_planned_progress_percentage').value = this.dataset.planned || 0;
                document.getElementById('edit_location').value = this.dataset.location || '';
                document.getElementById('edit_category').value = this.dataset.category || '';
                document.getElementById('edit_sector').value = this.dataset.sector || '';
                document.getElementById('edit_risk_level').value = this.dataset.risk || 'low';
                document.getElementById('edit_notes').value = this.dataset.notes || '';
            });
        });
    });
</script>
@endpush
@endsection
