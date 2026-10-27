@extends('layout.layout')

@php
    $title = 'HCD Task Monitoring';
    $subTitle = 'Pusat Pemantauan Penugasan Personel, Produktivitas Man-hours & Progres Kerja';
    $script = '<script>
        function openStatusModal(taskId, taskCode, currentStatus, actualHours) {
            document.getElementById("statusTaskForm").action = "/dashboard-admin/hcd/tasks/" + taskId + "/status";
            document.getElementById("statusTaskCode").innerText = taskCode;
            document.getElementById("taskStatusSelect").value = currentStatus;
            document.getElementById("taskActualHours").value = actualHours || 0;
            new bootstrap.Modal(document.getElementById("statusModal")).show();
        }
    </script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0 text-primary-light">{{ $title }}</h6>
        <p class="text-xs text-secondary-light mb-0">{{ $subTitle }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-sm btn-primary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#newTaskModal">
            <iconify-icon icon="solar:document-add-bold" class="text-lg"></iconify-icon>
            + Buat Tugas Baru
        </button>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show radius-8 mb-20 d-flex align-items-center justify-content-between" role="alert">
        <div class="d-flex align-items-center gap-2">
            <iconify-icon icon="solar:check-circle-bold" class="text-xl flex-shrink-0"></iconify-icon>
            <span class="text-xs">{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- 4 METRIC STATUS CARDS --}}
<div class="row g-3 mb-24">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light">Total Tugas</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $counts['all'] }} <span class="fs-6 fw-normal text-secondary-light">Item</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-xl flex-shrink-0">
                        <iconify-icon icon="solar:checklist-minimalistic-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Semua Penugasan</span>
                    <span class="badge bg-neutral-100 text-secondary-light">All Scope</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Sedang Dikerjakan</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $counts['in_progress'] }} <span class="fs-6 fw-normal text-secondary-light">Tugas</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-xl flex-shrink-0">
                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Aktif Berjalan</span>
                    <span class="badge bg-primary-50 text-primary-700">In Progress</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Menunggu &amp; Review</span>
                        <h4 class="fw-bold mb-0 mt-2 text-warning-main fs-5">{{ $counts['pending'] + $counts['review'] }} <span class="fs-6 fw-normal text-secondary-light">Item</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-xl flex-shrink-0">
                        <iconify-icon icon="solar:danger-triangle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>{{ $counts['pending'] }} Pending · {{ $counts['review'] }} Review</span>
                    <span class="badge bg-warning-50 text-warning-700">Action Needed</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Selesai (Completed)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-success-main fs-5">{{ $counts['completed'] }} <span class="fs-6 fw-normal text-secondary-light">Tugas</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-main text-xl flex-shrink-0">
                        <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Selesai Terverifikasi</span>
                    <span class="badge bg-success-50 text-success-700">Done</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- STATUS TABS & FILTER BAR --}}
<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-body p-16">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            {{-- Tabs --}}
            <ul class="nav nav-pills gap-2">
                <li class="nav-item">
                    <a class="nav-link py-8 px-16 text-xs radius-8 {{ $statusFilter === 'all' ? 'active' : '' }}" href="{{ route('dashboard-admin.hcd.tasks.index', ['status' => 'all', 'priority' => $priorityFilter, 'project_id' => $projectFilter, 'search' => $search]) }}">
                        Semua ({{ $counts['all'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-8 px-16 text-xs radius-8 {{ $statusFilter === 'pending' ? 'active' : '' }}" href="{{ route('dashboard-admin.hcd.tasks.index', ['status' => 'pending', 'priority' => $priorityFilter, 'project_id' => $projectFilter, 'search' => $search]) }}">
                        Pending ({{ $counts['pending'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-8 px-16 text-xs radius-8 {{ $statusFilter === 'in_progress' ? 'active' : '' }}" href="{{ route('dashboard-admin.hcd.tasks.index', ['status' => 'in_progress', 'priority' => $priorityFilter, 'project_id' => $projectFilter, 'search' => $search]) }}">
                        In Progress ({{ $counts['in_progress'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-8 px-16 text-xs radius-8 {{ $statusFilter === 'review' ? 'active' : '' }}" href="{{ route('dashboard-admin.hcd.tasks.index', ['status' => 'review', 'priority' => $priorityFilter, 'project_id' => $projectFilter, 'search' => $search]) }}">
                        Review ({{ $counts['review'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-8 px-16 text-xs radius-8 {{ $statusFilter === 'completed' ? 'active' : '' }}" href="{{ route('dashboard-admin.hcd.tasks.index', ['status' => 'completed', 'priority' => $priorityFilter, 'project_id' => $projectFilter, 'search' => $search]) }}">
                        Completed ({{ $counts['completed'] }})
                    </a>
                </li>
            </ul>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('dashboard-admin.hcd.tasks.index') }}" class="d-flex flex-wrap align-items-center gap-2">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                <select name="priority" class="form-select form-select-sm radius-8" onchange="this.form.submit()" style="min-width: 130px;">
                    <option value="all">Semua Prioritas</option>
                    <option value="low" {{ $priorityFilter === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ $priorityFilter === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ $priorityFilter === 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ $priorityFilter === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>

                <select name="project_id" class="form-select form-select-sm radius-8" onchange="this.form.submit()" style="min-width: 170px;">
                    <option value="all">Semua Proyek</option>
                    @foreach ($projects as $proj)
                        <option value="{{ $proj->id }}" {{ $projectFilter == $proj->id ? 'selected' : '' }}>
                            {{ $proj->project_code }}
                        </option>
                    @endforeach
                </select>

                <div class="input-group input-group-sm" style="min-width: 180px;">
                    <input type="text" name="search" value="{{ $search }}" class="form-control radius-8" placeholder="Cari judul/kode...">
                    <button class="btn btn-outline-secondary" type="submit">
                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                    </button>
                </div>
                @if ($statusFilter !== 'all' || $priorityFilter !== 'all' || $projectFilter !== 'all' || $search)
                    <a href="{{ route('dashboard-admin.hcd.tasks.index') }}" class="btn btn-sm btn-outline-danger radius-8 px-10" title="Reset Filter">
                        <iconify-icon icon="solar:restart-bold"></iconify-icon>
                    </a>
                @endif
            </form>
        </div>
    </div>
</div>

{{-- TASKS REGISTER TABLE --}}
<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-24 py-16">Kode &amp; Judul Tugas</th>
                        <th class="py-16">Penanggung Jawab (PIC)</th>
                        <th class="py-16">Proyek / Unit</th>
                        <th class="py-16 text-center">Prioritas</th>
                        <th class="py-16 text-end">Est / Real (Jam)</th>
                        <th class="py-16">Jatuh Tempo</th>
                        <th class="py-16 text-center">Status</th>
                        <th class="pe-24 py-16 text-center" width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td class="ps-24 py-14">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-neutral-100 text-secondary-light font-monospace text-xs">{{ $task->task_code }}</span>
                                    @if ($task->is_overdue)
                                        <span class="badge bg-danger-50 text-danger-600 border border-danger-200 text-3xs">TERLAMBAT</span>
                                    @endif
                                </div>
                                <strong class="text-xs text-primary-light d-block mb-1">{{ $task->title }}</strong>
                                @if ($task->description)
                                    <p class="text-2xs text-secondary-light mb-0 text-truncate" style="max-width: 320px;" title="{{ $task->description }}">
                                        {{ $task->description }}
                                    </p>
                                @endif
                            </td>
                            <td class="py-14 text-xs">
                                <span class="fw-semibold text-primary-light d-block">{{ $task->assignee->name ?? '-' }}</span>
                                <small class="text-secondary-light">{{ $task->assignee->position->title ?? $task->assignee->department->name ?? '-' }}</small>
                            </td>
                            <td class="py-14 text-xs">
                                @if ($task->project)
                                    <a href="{{ route('dashboard-admin.pmo.projects.show', $task->project->id) }}" class="fw-medium text-primary-600 d-block">
                                        {{ $task->project->project_code }}
                                    </a>
                                    <small class="text-secondary-light text-truncate d-block" style="max-width: 160px;" title="{{ $task->project->title }}">
                                        {{ $task->project->title }}
                                    </small>
                                @else
                                    <span class="badge bg-neutral-100 text-secondary-light">{{ $task->department->name ?? 'Internal Departemen' }}</span>
                                @endif
                            </td>
                            <td class="py-14 text-center">
                                <span class="badge {{ $task->priority_badge_class }} px-8 py-3 text-xs rounded-pill">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td class="py-14 text-end text-xs font-monospace">
                                <span class="fw-semibold text-primary-light">{{ number_format($task->estimated_hours, 1) }}</span> /
                                <span class="{{ $task->actual_hours > $task->estimated_hours && $task->estimated_hours > 0 ? 'text-danger-main fw-bold' : 'text-success-main' }}">
                                    {{ number_format($task->actual_hours, 1) }}
                                </span>
                            </td>
                            <td class="py-14 text-xs">
                                @if ($task->due_date)
                                    <span class="{{ $task->is_overdue ? 'text-danger-main fw-bold' : 'text-secondary-light' }}">
                                        {{ $task->due_date->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-secondary-light">-</span>
                                @endif
                            </td>
                            <td class="py-14 text-center">
                                <button type="button" class="badge {{ $task->status_badge_class }} px-10 py-4 text-xs rounded-pill border-0 cursor-pointer d-inline-flex align-items-center gap-1" onclick="openStatusModal({{ $task->id }}, '{{ $task->task_code }}', '{{ $task->status }}', {{ $task->actual_hours }})" title="Klik untuk ubah status">
                                    <span>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                    <iconify-icon icon="solar:pen-linear" class="text-2xs"></iconify-icon>
                                </button>
                            </td>
                            <td class="pe-24 py-14 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary p-6 radius-6" onclick="openStatusModal({{ $task->id }}, '{{ $task->task_code }}', '{{ $task->status }}', {{ $task->actual_hours }})" title="Ubah Status & Jam Kerja">
                                        <iconify-icon icon="solar:pen-linear" class="text-sm"></iconify-icon>
                                    </button>
                                    <form method="POST" action="{{ route('dashboard-admin.hcd.tasks.destroy', $task->id) }}" onsubmit="return confirm('Hapus tugas {{ $task->task_code }}?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger p-6 radius-6" title="Hapus">
                                            <iconify-icon icon="solar:trash-bin-trash-outline" class="text-sm"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-40 text-secondary-light">
                                <iconify-icon icon="solar:checklist-minimalistic-bold-duotone" class="text-4xl text-neutral-400 mb-2"></iconify-icon>
                                <p class="text-xs mb-0">Tidak ada tugas yang sesuai filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tasks->hasPages())
            <div class="p-16 border-top">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH TUGAS BARU --}}
<div class="modal fade" id="newTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-12 border-0 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-bold text-primary-light">Buat Tugas Baru (HCD Task)</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('dashboard-admin.hcd.tasks.store') }}">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-secondary-light">Judul Tugas <span class="text-danger-main">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm radius-8" placeholder="Contoh: Verifikasi MCU kru Turnaround Pabrik 1B" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Penanggung Jawab (PIC Karyawan) <span class="text-danger-main">*</span></label>
                            <select name="assigned_to" class="form-select form-select-sm radius-8" required>
                                <option value="">-- Pilih Karyawan Pelaksana --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->employee_number }} — {{ $emp->name }} ({{ $emp->department->name ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Prioritas <span class="text-danger-main">*</span></label>
                            <select name="priority" class="form-select form-select-sm radius-8" required>
                                <option value="low">Low (Biasa)</option>
                                <option value="medium" selected>Medium (Standard)</option>
                                <option value="high">High (Penting)</option>
                                <option value="urgent">Urgent (Kritis / Segera)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Proyek Terkait (Opsional)</label>
                            <select name="project_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Tidak Terikat Proyek Spesifik --</option>
                                @foreach ($projects as $proj)
                                    <option value="{{ $proj->id }}">
                                        {{ $proj->project_code }} — {{ Str::limit($proj->title, 35) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Departemen Terkait</label>
                            <select name="department_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach ($departments as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Tenggat Waktu (Due Date)</label>
                            <input type="date" name="due_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d', strtotime('+3 days')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Estimasi Man-Hours (Jam)</label>
                            <input type="number" step="0.5" name="estimated_hours" class="form-control form-control-sm radius-8" placeholder="Contoh: 8.0" value="8">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-secondary-light">Deskripsi &amp; Instruksi Kerja</label>
                            <textarea name="description" rows="3" class="form-control form-control-sm radius-8" placeholder="Instruksi terperinci lingkup pekerjaan..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary radius-8 px-20">Simpan &amp; Tugaskan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL UBAH STATUS TUGAS --}}
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content radius-12 border-0 shadow">
            <div class="modal-header border-bottom py-14 px-20">
                <h6 class="modal-title fs-6 fw-bold text-primary-light">Update Status: <span id="statusTaskCode" class="text-primary-600"></span></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusTaskForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-body p-20">
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary-light">Status Pengerjaan</label>
                        <select id="taskStatusSelect" name="status" class="form-select form-select-sm radius-8" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">Review</option>
                            <option value="completed">Completed (Selesai)</option>
                            <option value="blocked">Blocked (Kendala)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label text-xs fw-bold text-secondary-light">Realisasi Jam Kerja (Actual Hours)</label>
                        <input type="number" step="0.5" id="taskActualHours" name="actual_hours" class="form-control form-control-sm radius-8" placeholder="Jam kerja...">
                    </div>
                </div>
                <div class="modal-footer border-top py-12 px-20">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary radius-8 px-16">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
