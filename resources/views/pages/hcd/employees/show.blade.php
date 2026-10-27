@extends('layout.layout')

@php
    $title = 'Profil 360 Karyawan: ' . $employee->name;
    $subTitle = 'Detail Profil Personel, Kualifikasi, Sertifikasi K3 & Riwayat Penugasan Proyek';
    $script = '<script></script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0 text-primary-light">{{ $title }}</h6>
        <p class="text-xs text-secondary-light mb-0">{{ $subTitle }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('dashboard-admin.hcd.employees.index') }}" class="btn btn-sm btn-outline-secondary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
            Kembali ke Direktori
        </a>
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


<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-body p-24">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
            <div class="d-flex align-items-center gap-4">
                <div class="w-72-px h-72-px bg-primary-100 text-primary-600 fw-bold radius-circle d-flex align-items-center justify-content-center text-2xl flex-shrink-0 shadow-sm">
                    {{ $employee->user->initials ?? strtoupper(substr($employee->name, 0, 2)) }}
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h5 class="fw-bold text-primary-light mb-0">{{ $employee->name }}</h5>
                        <span class="badge {{ $employee->status === 'active' ? 'bg-success-focus text-success-main' : 'bg-neutral-100 text-secondary-light' }} text-2xs px-8 py-2 rounded-pill">
                            {{ strtoupper($employee->status ?? 'ACTIVE') }}
                        </span>
                    </div>
                    <span class="text-xs fw-semibold text-primary-600 d-block mb-1">
                        {{ $employee->position->title ?? 'Staff' }} · {{ $employee->department->name ?? '-' }}
                    </span>
                    <div class="d-flex flex-wrap align-items-center gap-3 text-xs text-secondary-light">
                        <span><iconify-icon icon="solar:card-2-linear" class="me-1"></iconify-icon>NIP: <strong>{{ $employee->employee_number }}</strong></span>
                        @if ($employee->nik)
                            <span><iconify-icon icon="solar:user-id-linear" class="me-1"></iconify-icon>NIK: <strong>{{ $employee->nik }}</strong></span>
                        @endif
                        <span><iconify-icon icon="solar:map-point-linear" class="me-1 text-danger-main"></iconify-icon>{{ $employee->work_location ?? 'Head Office' }}</span>
                        <span><iconify-icon icon="solar:phone-linear" class="me-1"></iconify-icon>{{ $employee->phone ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 text-end">
                <div class="p-12 radius-8 border text-center" style="min-width: 120px;">
                    <span class="text-2xs fw-bold text-uppercase text-secondary-light d-block mb-1">Sertifikat K3</span>
                    <strong class="fs-5 fw-bold text-success-main d-block">{{ $employee->employeeTrainings->count() }}</strong>
                    <small class="text-3xs text-secondary-light">Kompetensi</small>
                </div>
                <div class="p-12 radius-8 border text-center" style="min-width: 120px;">
                    <span class="text-2xs fw-bold text-uppercase text-secondary-light d-block mb-1">Tugas Selesai</span>
                    <strong class="fs-5 fw-bold text-primary-light d-block">{{ $employee->tasks->where('status', 'completed')->count() }}</strong>
                    <small class="text-3xs text-secondary-light">dari {{ $employee->tasks->count() }} Tugas</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-24">
    
    <div class="col-lg-5">
        <div class="card radius-12 border-0 shadow-sm mb-24">
            <div class="card-header bg-transparent border-bottom py-16 px-20">
                <h6 class="text-sm fw-semibold mb-0 text-primary-light">Informasi Hubungan Kerja &amp; Kontrak</h6>
            </div>
            <div class="card-body p-20">
                <ul class="list-group list-group-flush text-xs">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-10 bg-transparent border-bottom">
                        <span class="text-secondary-light">Status Kepegawaian</span>
                        <span class="badge {{ $employee->employment_status === 'permanent' ? 'bg-primary-50 text-primary-700' : 'bg-warning-50 text-warning-700' }} border">
                            {{ strtoupper($employee->employment_status ?? 'CONTRACT') }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-10 bg-transparent border-bottom">
                        <span class="text-secondary-light">Tanggal Masuk (Join Date)</span>
                        <span class="fw-semibold text-primary-light">{{ $employee->join_date ? $employee->join_date->format('d F Y') : '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-10 bg-transparent border-bottom">
                        <span class="text-secondary-light">Masa Kerja</span>
                        <span class="fw-medium text-primary-light">{{ $employee->join_date ? $employee->join_date->diffForHumans(null, true) : '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-10 bg-transparent border-bottom">
                        <span class="text-secondary-light">Penempatan Lokasi Kerja</span>
                        <span class="fw-semibold text-primary-light">{{ $employee->work_location ?? 'Head Office' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-10 bg-transparent border-bottom">
                        <span class="text-secondary-light">Rate Harian Lapangan</span>
                        <span class="fw-bold text-success-main font-monospace">Rp {{ number_format($employee->daily_rate, 0, ',', '.') }} / hari</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-10 bg-transparent border-bottom">
                        <span class="text-secondary-light">Pendidikan Terakhir</span>
                        <span class="fw-medium text-primary-light">{{ $employee->education ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-10 bg-transparent">
                        <span class="text-secondary-light">Kontak Darurat</span>
                        <span class="fw-medium text-primary-light text-end">
                            {{ $employee->emergency_contact_name ?? '-' }}<br>
                            <small class="text-secondary-light font-monospace">{{ $employee->emergency_contact_phone ?? '' }}</small>
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card radius-12 border-0 shadow-sm mb-24">
            <div class="card-header bg-transparent border-bottom py-16 px-20">
                <h6 class="text-sm fw-semibold mb-0 text-primary-light">Keahlian &amp; Spesialisasi Teknis</h6>
            </div>
            <div class="card-body p-20">
                @if ($employee->skills)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (explode(',', $employee->skills) as $skill)
                            <span class="badge bg-neutral-100 text-primary-light border text-xs px-10 py-6 radius-6">
                                <iconify-icon icon="solar:check-circle-bold" class="text-success-main me-1"></iconify-icon>
                                {{ trim($skill) }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-secondary-light mb-0">Belum ada rincian keahlian khusus terinput.</p>
                @endif
            </div>
        </div>
    </div>

    
    <div class="col-lg-7">
        
        <div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
            <div class="card-header bg-transparent border-bottom py-16 px-20 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Sertifikasi &amp; Lisensi K3 Personel</h6>
                    <span class="text-2xs text-secondary-light">Kepatuhan izin kerja untuk mobilisasi site industri</span>
                </div>
                <a href="{{ route('dashboard-admin.hcd.training.index', ['employee_id' => $employee->id]) }}" class="btn btn-sm btn-outline-primary radius-6 text-xs">
                    + Catat Sertifikat
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle">
                        <thead class="bg-neutral-50 text-xs text-secondary-light">
                            <tr>
                                <th class="ps-20 py-12">Judul Sertifikasi</th>
                                <th class="py-12">No. Sertifikat</th>
                                <th class="py-12">Masa Berlaku</th>
                                <th class="pe-20 py-12 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employee->employeeTrainings as $cert)
                                <tr>
                                    <td class="ps-20 py-12 text-xs">
                                        <strong class="text-primary-light d-block">{{ $cert->training->title ?? '-' }}</strong>
                                        <small class="text-secondary-light">{{ $cert->training->institution ?? 'Institusi Resmi' }}</small>
                                    </td>
                                    <td class="py-12 text-xs font-monospace text-primary-light">
                                        {{ $cert->certificate_number ?? '-' }}
                                    </td>
                                    <td class="py-12 text-xs">
                                        <span class="text-primary-light d-block">{{ $cert->issue_date ? $cert->issue_date->format('d M Y') : '-' }}</span>
                                        <small class="text-secondary-light">s/d <strong>{{ $cert->expiry_date ? $cert->expiry_date->format('d M Y') : 'Seumur Hidup' }}</strong></small>
                                    </td>
                                    <td class="pe-20 py-12 text-center">
                                        <span class="badge {{ $cert->status_badge_class }} text-3xs px-8 py-3 rounded-pill">
                                            {{ strtoupper(str_replace('_', ' ', $cert->computed_status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-24 text-secondary-light text-xs">Belum ada data sertifikasi untuk karyawan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="card radius-12 border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-transparent border-bottom py-16 px-20 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Daftar Penugasan Kerja (Task Monitoring)</h6>
                    <span class="text-2xs text-secondary-light">Tugas yang dibebankan kepada personel ini</span>
                </div>
                <span class="badge bg-neutral-100 text-secondary-light text-xs">{{ $employee->tasks->count() }} Tugas</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle">
                        <thead class="bg-neutral-50 text-xs text-secondary-light">
                            <tr>
                                <th class="ps-20 py-12">Kode &amp; Tugas</th>
                                <th class="py-12">Proyek</th>
                                <th class="py-12 text-center">Prioritas</th>
                                <th class="pe-20 py-12 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employee->tasks as $t)
                                <tr>
                                    <td class="ps-20 py-12 text-xs">
                                        <span class="font-monospace text-3xs text-secondary-light">{{ $t->task_code }}</span>
                                        <strong class="text-primary-light d-block text-truncate" style="max-width: 240px;" title="{{ $t->title }}">{{ $t->title }}</strong>
                                    </td>
                                    <td class="py-12 text-xs">
                                        {{ $t->project->project_code ?? 'Internal Dept' }}
                                    </td>
                                    <td class="py-12 text-center">
                                        <span class="badge {{ $t->priority_badge_class }} text-3xs">
                                            {{ ucfirst($t->priority) }}
                                        </span>
                                    </td>
                                    <td class="pe-20 py-12 text-center">
                                        <span class="badge {{ $t->status_badge_class }} text-3xs rounded-pill">
                                            {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-24 text-secondary-light text-xs">Tidak ada riwayat penugasan saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
