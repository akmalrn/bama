@extends('layout.layout')

@php
    $title = 'My Workspace';
    $subTitle = 'Workbench Personal Karyawan & Timesheet Harian — PT ELBEMA Integra Indonesia';
@endphp

@section('content')
{{-- Alert Notification --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 radius-8 mb-20" role="alert">
        <div class="d-flex align-items-center gap-2 mb-1">
            <iconify-icon icon="solar:danger-triangle-bold-duotone" class="text-xl"></iconify-icon>
            <strong>Terdapat kesalahan pada input formulir:</strong>
        </div>
        <ul class="mb-0 ps-3 text-xs">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Header Banner & Quick Action --}}
<div class="card radius-12 border border-neutral-200 shadow-sm p-20 mb-24 bg-base">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="w-56-px h-56-px radius-circle bg-primary-100 text-primary-600 d-flex justify-content-center align-items-center text-2xl flex-shrink-0">
                <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-2xs fw-bold text-uppercase">Personal Workspace</span>
                    <span class="badge bg-success-50 text-success-700 border border-success-200 px-8 py-2 text-2xs fw-semibold d-inline-flex align-items-center gap-1">
                        <span class="w-6-px h-6-px radius-circle bg-success-main d-inline-block"></span>
                        Aktif
                    </span>
                </div>
                <h5 class="mb-1 fw-bold text-primary-light">Selamat Datang, {{ $user->name }}!</h5>
                <p class="text-secondary-light mb-0 text-xs">
                    {{ $employee?->designation ?? 'Staf Operasional' }} {{ $employee ? '— NIK: ' . $employee->employee_number : '' }} | {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-primary btn-sm radius-8 px-14 py-8 text-xs fw-semibold d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddTimesheet">
                <iconify-icon icon="solar:clock-circle-bold-duotone" class="text-sm"></iconify-icon>
                + Catat Timesheet / Jam Kerja
            </button>
        </div>
    </div>
</div>

{{-- SECTION 1: 4 KPI CARDS --}}
<div class="row g-3 mb-24">
    {{-- Card 1: Total Tugas --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between bg-base">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-semibold text-secondary-light">Tugas Ditugaskan</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-lg">
                        <iconify-icon icon="solar:checklist-minimalistic-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <h4 class="mb-2 fw-bold text-primary-light">{{ $totalMyTasks }}</h4>
                <p class="text-secondary-light text-2xs mb-0">Total akumulasi tugas yang didelegasikan</p>
            </div>
            <div class="pt-8 border-top border-neutral-200 mt-12 d-flex align-items-center justify-content-between">
                <span class="text-2xs text-secondary-light">Selesai: <strong class="text-success-main">{{ $completedTasks }}</strong></span>
                <span class="text-2xs text-secondary-light">Tertunda: <strong class="text-warning-main">{{ $pendingTasks }}</strong></span>
            </div>
        </div>
    </div>

    {{-- Card 2: Tugas Menunggu/On Progress --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between bg-base">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-semibold text-secondary-light">Tugas Aktif (Pending/In Progress)</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-warning-50 text-warning-600 text-lg">
                        <iconify-icon icon="solar:hourglass-line-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <h4 class="mb-2 fw-bold text-primary-light">{{ $pendingTasks }}</h4>
                <p class="text-secondary-light text-2xs mb-0">Perlu tindak lanjut atau penyelesaian</p>
            </div>
            <div class="pt-8 border-top border-neutral-200 mt-12">
                <span class="text-2xs text-secondary-light">Fokus hari ini &amp; target deadline terdekat</span>
            </div>
        </div>
    </div>

    {{-- Card 3: Jam Kerja Minggu Ini --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between bg-base">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-semibold text-secondary-light">Timesheet Minggu Ini</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-info-50 text-info-600 text-lg">
                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <h4 class="mb-2 fw-bold text-primary-light">{{ number_format($weeklyTotalHours, 1) }} <span class="text-xs fw-normal text-secondary-light">Jam</span></h4>
                <div class="progress h-4-px bg-neutral-100 radius-4 mb-2">
                    @php $weeklyPercent = min(100, round(($weeklyTotalHours / 40) * 100)); @endphp
                    <div class="progress-bar bg-info-main" style="width: {{ $weeklyPercent }}%"></div>
                </div>
                <p class="text-secondary-light text-2xs mb-0">{{ $weeklyPercent }}% dari target standar 40 jam/minggu</p>
            </div>
            <div class="pt-8 border-top border-neutral-200 mt-12">
                <span class="text-2xs text-secondary-light">Senin s/d Minggu saat ini</span>
            </div>
        </div>
    </div>

    {{-- Card 4: Jam Kerja Bulan Ini --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between bg-base">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-semibold text-secondary-light">Akumulasi Bulan Ini</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-600 text-lg">
                        <iconify-icon icon="solar:calendar-date-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <h4 class="mb-2 fw-bold text-primary-light">{{ number_format($monthlyTotalHours, 1) }} <span class="text-xs fw-normal text-secondary-light">Jam</span></h4>
                <p class="text-secondary-light text-2xs mb-0">Total jam kerja tervalidasi bulan {{ now()->translatedFormat('F') }}</p>
            </div>
            <div class="pt-8 border-top border-neutral-200 mt-12">
                <span class="text-2xs text-secondary-light">Basis perhitungan man-hours &amp; payroll</span>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 2: TUGAS SAYA & QUICK STATUS UPDATE --}}
<div class="card radius-12 border border-neutral-200 shadow-sm p-20 mb-24 bg-base">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-16 pb-12 border-bottom border-neutral-200">
        <div>
            <h6 class="mb-1 fw-bold text-primary-light">Daftar Tugas &amp; Tanggung Jawab Saya</h6>
            <p class="text-secondary-light text-xs mb-0">Kelola dan perbarui progress pekerjaan langsung dari workspace personal</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary-50 text-primary-600 text-xs px-10 py-4 radius-6">{{ $recentTasks->count() }} Tugas Terbaru</span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table bordered-table sm-table mb-0">
            <thead>
                <tr>
                    <th class="text-xs">Kode &amp; Judul Tugas</th>
                    <th class="text-xs">Proyek Terkait</th>
                    <th class="text-xs">Prioritas</th>
                    <th class="text-xs">Batas Waktu (Due Date)</th>
                    <th class="text-xs">Status</th>
                    <th class="text-xs text-center">Aksi / Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentTasks as $task)
                    <tr>
                        <td>
                            <div class="fw-bold text-primary-light text-xs">{{ $task->task_code }}</div>
                            <div class="text-secondary-light text-2xs">{{ $task->title }}</div>
                        </td>
                        <td>
                            @if ($task->project)
                                <span class="badge bg-neutral-100 text-neutral-700 text-2xs">{{ $task->project->title }}</span>
                            @else
                                <span class="text-secondary-light text-2xs">- Non-Project -</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $priorityClass = match(strtolower($task->priority ?? 'medium')) {
                                    'high' => 'bg-danger-50 text-danger-600 border border-danger-200',
                                    'urgent' => 'bg-danger-100 text-danger-700 border border-danger-300',
                                    'low' => 'bg-neutral-100 text-neutral-600',
                                    default => 'bg-warning-50 text-warning-600 border border-warning-200'
                                };
                            @endphp
                            <span class="badge {{ $priorityClass }} text-2xs text-uppercase px-6 py-2 radius-4">
                                {{ $task->priority ?? 'Medium' }}
                            </span>
                        </td>
                        <td>
                            <div class="text-xs text-secondary-light">
                                {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td>
                            @php
                                $statusBadge = match($task->status) {
                                    'completed' => 'bg-success-50 text-success-600 border border-success-200',
                                    'in_progress' => 'bg-info-50 text-info-600 border border-info-200',
                                    'review' => 'bg-purple-50 text-purple-600 border border-purple-200',
                                    default => 'bg-warning-50 text-warning-600 border border-warning-200'
                                };
                                $statusLabel = match($task->status) {
                                    'completed' => 'Selesai',
                                    'in_progress' => 'Sedang Berjalan',
                                    'review' => 'Dalam Review',
                                    default => 'Menunggu'
                                };
                            @endphp
                            <span class="badge {{ $statusBadge }} text-2xs px-8 py-3 radius-4">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="w-32-px h-32-px rounded-circle bg-primary-focus text-primary-600 border-0 d-inline-flex justify-content-center align-items-center" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalUpdateTask-{{ $task->id }}" 
                                title="Perbarui Status Tugas">
                                <iconify-icon icon="solar:pen-new-square-linear" class="text-sm"></iconify-icon>
                            </button>
                        </td>
                    </tr>

                    {{-- Modal Update Task Status --}}
                    <div class="modal fade" id="modalUpdateTask-{{ $task->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content radius-12 border-0 shadow">
                                <form action="{{ route('dashboard-admin.workspace.task.status', $task->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-header border-bottom border-neutral-200 py-16 px-20">
                                        <h6 class="modal-title fw-bold text-primary-light">Update Status Tugas</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-20 text-start">
                                        <div class="mb-12">
                                            <div class="text-2xs text-secondary-light">Kode &amp; Judul Tugas:</div>
                                            <div class="fw-bold text-xs text-primary-light">{{ $task->task_code }} - {{ $task->title }}</div>
                                        </div>

                                        <div class="mb-16">
                                            <label class="form-label text-xs fw-semibold text-primary-light">Status Pengerjaan <span class="text-danger-main">*</span></label>
                                            <select name="status" class="form-select form-select-sm text-xs" required>
                                                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>Sedang Berjalan (In Progress)</option>
                                                <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Menunggu Review Tim</option>
                                                <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                                            </select>
                                        </div>

                                        <div class="mb-16">
                                            <label class="form-label text-xs fw-semibold text-primary-light">Jam Aktual Dihabiskan (Jam)</label>
                                            <input type="number" step="0.5" min="0" name="actual_hours" class="form-control form-control-sm text-xs" value="{{ $task->actual_hours ?? 0 }}">
                                            <span class="text-2xs text-secondary-light">Opsional, masukkan estimasi total jam pengerjaan aktual</span>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top border-neutral-200 py-12 px-20">
                                        <button type="button" class="btn btn-neutral-200 btn-sm radius-8 px-14 py-6 text-xs" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm radius-8 px-14 py-6 text-xs">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-24 text-secondary-light text-xs">
                            <iconify-icon icon="solar:folder-open-outline" class="text-3xl text-neutral-400 d-block mb-1 mx-auto"></iconify-icon>
                            Belum ada tugas yang didelegasikan untuk akun Anda saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- SECTION 3: TIMESHEET MINGGU INI --}}
<div class="card radius-12 border border-neutral-200 shadow-sm p-20 mb-24 bg-base">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-16 pb-12 border-bottom border-neutral-200">
        <div>
            <h6 class="mb-1 fw-bold text-primary-light">Timesheet &amp; Log Jam Kerja Mingguan</h6>
            <p class="text-secondary-light text-xs mb-0">Catatan aktivitas harian Anda pada periode minggu ini</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info-50 text-info-600 border border-info-200 text-xs px-10 py-4 radius-6">
                Total: {{ number_format($weeklyTotalHours, 1) }} Jam Terdaftar
            </span>
            <button type="button" class="btn btn-outline-primary btn-sm radius-8 px-12 py-6 text-xs fw-semibold d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalAddTimesheet">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="text-sm"></iconify-icon>
                Input Log Hari Ini
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table bordered-table sm-table mb-0">
            <thead>
                <tr>
                    <th class="text-xs">Tanggal</th>
                    <th class="text-xs">Jenis Pekerjaan</th>
                    <th class="text-xs">Proyek Terkait</th>
                    <th class="text-xs">Uraian Ringkas Pekerjaan</th>
                    <th class="text-xs">Lokasi</th>
                    <th class="text-xs text-center">Durasi (Jam)</th>
                    <th class="text-xs text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($weeklyTimesheets as $timesheet)
                    <tr>
                        <td>
                            <div class="fw-bold text-primary-light text-xs">{{ $timesheet->date->translatedFormat('d M Y') }}</div>
                            <div class="text-secondary-light text-2xs">{{ $timesheet->date->translatedFormat('l') }}</div>
                        </td>
                        <td>
                            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 text-2xs px-6 py-2 radius-4">
                                {{ $timesheet->work_type }}
                            </span>
                        </td>
                        <td>
                            @if ($timesheet->project)
                                <span class="text-xs fw-semibold text-primary-light">{{ $timesheet->project->title }}</span>
                            @else
                                <span class="text-secondary-light text-2xs">- Internal / Non-Project -</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-xs text-primary-light">{{ $timesheet->activity_summary }}</div>
                            @if ($timesheet->notes)
                                <div class="text-secondary-light text-2xs fst-italic">{{ $timesheet->notes }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="text-xs text-secondary-light d-flex align-items-center gap-1">
                                <iconify-icon icon="solar:map-point-bold-duotone" class="text-primary-600 text-xs"></iconify-icon>
                                {{ $timesheet->location }}
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold text-xs text-info-main">{{ number_format($timesheet->hours, 1) }} Jam</span>
                        </td>
                        <td class="text-center">
                            @php
                                $tsStatusBadge = match($timesheet->status) {
                                    'approved' => 'bg-success-50 text-success-600 border border-success-200',
                                    'revised' => 'bg-danger-50 text-danger-600 border border-danger-200',
                                    default => 'bg-warning-50 text-warning-600 border border-warning-200'
                                };
                            @endphp
                            <span class="badge {{ $tsStatusBadge }} text-2xs px-8 py-3 radius-4 text-capitalize">
                                {{ $timesheet->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-24 text-secondary-light text-xs">
                            <iconify-icon icon="solar:clock-circle-outline" class="text-3xl text-neutral-400 d-block mb-1 mx-auto"></iconify-icon>
                            Belum ada catatan log timesheet untuk minggu ini. Klik "Input Log Hari Ini" untuk mencatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- SECTION 4: AKSES CEPAT DOKUMEN & PROYEK AKTIF --}}
<div class="row g-3">
    {{-- Proyek Yang Diikuti --}}
    <div class="col-xl-7">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-20 h-100 bg-base">
            <div class="d-flex justify-content-between align-items-center mb-16 pb-8 border-bottom border-neutral-200">
                <h6 class="mb-0 fw-bold text-primary-light">Proyek Aktif Yang Terkait</h6>
                <a href="{{ route('dashboard-admin.pmo.projects.index') }}" class="text-xs text-primary-600 fw-semibold text-decoration-none">Lihat Semua Proyek &rarr;</a>
            </div>
            <div class="row g-2">
                @forelse ($activeProjects as $proj)
                    <div class="col-md-6">
                        <div class="p-12 radius-8 border border-neutral-200 bg-neutral-50 h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <span class="badge bg-primary-100 text-primary-700 text-2xs">{{ $proj->code ?? 'PROJ' }}</span>
                                    <span class="text-2xs text-secondary-light">{{ $proj->status }}</span>
                                </div>
                                <div class="fw-bold text-xs text-primary-light mb-1">{{ Str::limit($proj->title, 40) }}</div>
                                <div class="text-2xs text-secondary-light mb-2">{{ $proj->client_name ?? 'Klien Industri' }}</div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('dashboard-admin.pmo.projects.show', $proj->id) }}" class="btn btn-outline-primary btn-xs radius-4 px-8 py-2 text-2xs">Detail Proyek</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-16 text-center text-secondary-light text-xs">
                        Tidak ada data proyek aktif saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Shortcut Operasional Cepat --}}
    <div class="col-xl-5">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-20 h-100 bg-base">
            <h6 class="mb-16 pb-8 border-bottom border-neutral-200 fw-bold text-primary-light">Pintasan Formulir Cepat</h6>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('dashboard-admin.procurement.purchase-requests.index') }}" class="p-12 radius-8 border border-neutral-200 text-decoration-none d-flex align-items-center justify-content-between hover-bg-neutral-50">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-32-px h-32-px radius-circle bg-warning-50 text-warning-600 d-flex justify-content-center align-items-center text-sm">
                            <iconify-icon icon="solar:cart-large-minimalistic-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <div class="fw-semibold text-xs text-primary-light">Buat Purchase Request (PR)</div>
                            <div class="text-2xs text-secondary-light">Permintaan pengadaan material / sparepart</div>
                        </div>
                    </div>
                    <iconify-icon icon="solar:arrow-right-linear" class="text-secondary-light text-sm"></iconify-icon>
                </a>

                <a href="{{ route('dashboard-admin.assets.allocations.index') }}" class="p-12 radius-8 border border-neutral-200 text-decoration-none d-flex align-items-center justify-content-between hover-bg-neutral-50">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-32-px h-32-px radius-circle bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center text-sm">
                            <iconify-icon icon="solar:routing-2-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <div class="fw-semibold text-xs text-primary-light">Permohonan Alokasi Asset</div>
                            <div class="text-2xs text-secondary-light">Pinjam mesin UHP / Hydrotest / Tools ke site</div>
                        </div>
                    </div>
                    <iconify-icon icon="solar:arrow-right-linear" class="text-secondary-light text-sm"></iconify-icon>
                </a>

                <a href="{{ route('dashboard-admin.hsse.incidents.index') }}" class="p-12 radius-8 border border-neutral-200 text-decoration-none d-flex align-items-center justify-content-between hover-bg-neutral-50">
                    <div class="d-flex align-items-center gap-2">
                        <div class="w-32-px h-32-px radius-circle bg-danger-50 text-danger-600 d-flex justify-content-center align-items-center text-sm">
                            <iconify-icon icon="solar:shield-warning-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <div class="fw-semibold text-xs text-primary-light">Lapor Insiden HSSE / Nearmiss</div>
                            <div class="text-2xs text-secondary-light">Pelaporan keselamatan kerja lapangan</div>
                        </div>
                    </div>
                    <iconify-icon icon="solar:arrow-right-linear" class="text-secondary-light text-sm"></iconify-icon>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH TIMESHEET --}}
<div class="modal fade" id="modalAddTimesheet" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-12 border-0 shadow">
            <form action="{{ route('dashboard-admin.workspace.timesheet.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom border-neutral-200 py-16 px-20">
                    <h6 class="modal-title fw-bold text-primary-light d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="text-primary-600"></iconify-icon>
                        Catat Log Jam Kerja / Timesheet Harian
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-20 text-start">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Tanggal Kerja <span class="text-danger-main">*</span></label>
                            <input type="date" name="date" class="form-control form-control-sm text-xs" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Durasi (Jam) <span class="text-danger-main">*</span></label>
                            <input type="number" step="0.5" min="0.5" max="24" name="hours" class="form-control form-control-sm text-xs" value="8.0" required>
                            <span class="text-2xs text-secondary-light">Standar jam kerja harian normal adalah 8.0 jam</span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Tipe Aktivitas Pekerjaan <span class="text-danger-main">*</span></label>
                            <select name="work_type" class="form-select form-select-sm text-xs" required>
                                <option value="On-Site Project">On-Site Project Execution</option>
                                <option value="Workshop Prep">Workshop Prep &amp; Maintenance</option>
                                <option value="Engineering Design">Engineering &amp; Technical Calculation</option>
                                <option value="Customer Meeting">Customer / Client Meeting</option>
                                <option value="Administration">Administration &amp; Reporting</option>
                                <option value="Overtime">Lembur Operasional (Overtime)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Lokasi Pekerjaan <span class="text-danger-main">*</span></label>
                            <input type="text" name="location" class="form-control form-control-sm text-xs" placeholder="Contoh: Site Bontang / Workshop Balikpapan" value="Head Office / Workshop" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Proyek Terkait (Opsional)</label>
                            <select name="project_id" class="form-select form-select-sm text-xs">
                                <option value="">-- Non-Project / Aktivitas Rutin Internal --</option>
                                @foreach ($allProjects as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Uraian Ringkas Pekerjaan (Activity Summary) <span class="text-danger-main">*</span></label>
                            <textarea name="activity_summary" rows="3" class="form-control form-control-sm text-xs" placeholder="Uraikan apa yang Anda selesaikan hari ini..." required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Catatan Tambahan / Kendala Lapangan (Opsional)</label>
                            <textarea name="notes" rows="2" class="form-control form-control-sm text-xs" placeholder="Tuliskan kendala cuaca, ketersediaan alat, atau catatan lainnya jika ada..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-neutral-200 py-12 px-20">
                    <button type="button" class="btn btn-neutral-200 btn-sm radius-8 px-14 py-6 text-xs" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm radius-8 px-14 py-6 text-xs">Simpan Timesheet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
