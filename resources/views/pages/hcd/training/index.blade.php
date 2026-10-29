@extends('layout.layout')

@php
    $title = 'Training & Development';
    $subTitle = 'Manajemen Kompetensi, Sertifikasi K3 & Pelacak Masa Berlaku Izin Kerja Personel';
    $script = '<script></script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0 text-primary-light">{{ $title }}</h6>
        <p class="text-xs text-secondary-light mb-0">{{ $subTitle }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-sm btn-outline-primary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#newCatalogModal">
            <iconify-icon icon="solar:folder-with-files-bold-duotone"></iconify-icon>
            + Program Baru (Katalog)
        </button>
        <button type="button" class="btn btn-sm btn-primary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#newCertificateModal">
            <iconify-icon icon="solar:diploma-verified-bold-duotone"></iconify-icon>
            + Catat Sertifikat Karyawan
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


<div class="row g-3 mb-24">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light">Total Sertifikat</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $counts['all'] }} <span class="fs-6 fw-normal text-secondary-light">Lisensi</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-xl flex-shrink-0">
                        <iconify-icon icon="solar:diploma-verified-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Semua Catatan Personel</span>
                    <span class="badge bg-neutral-100 text-secondary-light">{{ $trainings->count() }} Program</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Sertifikasi Aktif &amp; Valid</span>
                        <h4 class="fw-bold mb-0 mt-2 text-success-main fs-5">{{ $counts['valid'] }} <span class="fs-6 fw-normal text-secondary-light">Personel</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-main text-xl flex-shrink-0">
                        <iconify-icon icon="solar:shield-check-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Siap Mobilisasi Site</span>
                    <span class="badge bg-success-50 text-success-700">Valid</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Mendekati Expired (&lt;30 Hari)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-warning-main fs-5">{{ $counts['expiring_soon'] }} <span class="fs-6 fw-normal text-secondary-light">Perlu Refresher</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-xl flex-shrink-0">
                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Segera Jadwalkan Ulang</span>
                    <span class="badge bg-warning-50 text-warning-700">Expiring Soon</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-danger-main">Expired / Kadaluarsa</span>
                        <h4 class="fw-bold mb-0 mt-2 text-danger-main fs-5">{{ $counts['expired'] }} <span class="fs-6 fw-normal text-secondary-light">Tidak Boleh Site</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-danger-100 text-danger-main text-xl flex-shrink-0">
                        <iconify-icon icon="solar:danger-triangle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Wajib Re-sertifikasi BNSP</span>
                    <span class="badge bg-danger-50 text-danger-700">Action Required</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-body p-16">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <ul class="nav nav-pills gap-2">
                <li class="nav-item">
                    <a class="nav-link py-8 px-20 text-xs radius-8 {{ $activeTab === 'certificates' ? 'active' : '' }}" href="{{ route('dashboard-admin.hcd.training.index', ['tab' => 'certificates']) }}">
                        <iconify-icon icon="solar:diploma-verified-bold-duotone" class="me-1"></iconify-icon>
                        Pelacak Sertifikasi Personel ({{ $counts['all'] }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-8 px-20 text-xs radius-8 {{ $activeTab === 'catalog' ? 'active' : '' }}" href="{{ route('dashboard-admin.hcd.training.index', ['tab' => 'catalog']) }}">
                        <iconify-icon icon="solar:folder-with-files-bold-duotone" class="me-1"></iconify-icon>
                        Katalog Program Pelatihan ({{ $trainings->count() }})
                    </a>
                </li>
            </ul>

            @if ($activeTab === 'certificates')
                <form method="GET" action="{{ route('dashboard-admin.hcd.training.index') }}" class="d-flex flex-wrap align-items-center gap-2">
                    <input type="hidden" name="tab" value="certificates">
                    <select name="status" class="form-select form-select-sm radius-8" onchange="this.form.submit()" style="min-width: 140px;">
                        <option value="all">Semua Status</option>
                        <option value="valid" {{ $statusFilter === 'valid' ? 'selected' : '' }}>Valid Aktif</option>
                        <option value="expiring_soon" {{ $statusFilter === 'expiring_soon' ? 'selected' : '' }}>Mendekati Expired (&lt;30hr)</option>
                        <option value="expired" {{ $statusFilter === 'expired' ? 'selected' : '' }}>Expired (Kadaluarsa)</option>
                        <option value="in_training" {{ $statusFilter === 'in_training' ? 'selected' : '' }}>Sedang Pelatihan</option>
                    </select>

                    <div class="input-group input-group-sm" style="min-width: 200px;">
                        <input type="text" name="search" value="{{ $search }}" class="form-control radius-8" placeholder="Cari nama, no. sertifikat...">
                        <button class="btn btn-outline-secondary" type="submit">
                            <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

@if ($activeTab === 'certificates')
    
    <div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                        <tr>
                            <th class="ps-24 py-16">Nama Karyawan</th>
                            <th class="py-16">Judul Program Sertifikasi</th>
                            <th class="py-16">Nomor Sertifikat</th>
                            <th class="py-16">Tanggal Terbit</th>
                            <th class="py-16">Masa Berlaku (Expired)</th>
                            <th class="py-16 text-center">Status Masa Berlaku</th>
                            <th class="pe-24 py-16 text-center" width="90">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($certificates as $cert)
                            <tr>
                                <td class="ps-24 py-14">
                                    <a href="{{ route('dashboard-admin.hcd.employees.show', $cert->employee_id) }}" class="fw-bold text-primary-600 hover-text-primary text-xs d-block mb-1">
                                        {{ $cert->employee->name ?? '-' }}
                                    </a>
                                    <small class="text-secondary-light text-3xs font-monospace">
                                        {{ $cert->employee->employee_number ?? '' }} · {{ $cert->employee->department->name ?? '-' }}
                                    </small>
                                </td>
                                <td class="py-14 text-xs">
                                    <strong class="text-primary-light d-block">{{ $cert->training->title ?? '-' }}</strong>
                                    <small class="text-secondary-light">{{ $cert->training->institution ?? '-' }} ({{ $cert->training->category ?? '-' }})</small>
                                </td>
                                <td class="py-14 text-xs font-monospace text-primary-light">
                                    {{ $cert->certificate_number ?? '-' }}
                                </td>
                                <td class="py-14 text-xs text-secondary-light">
                                    {{ $cert->issue_date ? $cert->issue_date->format('d M Y') : '-' }}
                                </td>
                                <td class="py-14 text-xs">
                                    @if ($cert->expiry_date)
                                        <strong class="{{ $cert->computed_status === 'expired' ? 'text-danger-main' : ($cert->computed_status === 'expiring_soon' ? 'text-warning-main' : 'text-primary-light') }}">
                                            {{ $cert->expiry_date->format('d M Y') }}
                                        </strong>
                                        <small class="text-3xs d-block text-secondary-light">{{ $cert->expiry_date->diffForHumans() }}</small>
                                    @else
                                        <span class="badge bg-neutral-100 text-secondary-light">Seumur Hidup</span>
                                    @endif
                                </td>
                                <td class="py-14 text-center">
                                    <span class="badge {{ $cert->status_badge_class }} text-xs px-10 py-4 rounded-pill">
                                        {{ strtoupper(str_replace('_', ' ', $cert->computed_status)) }}
                                    </span>
                                </td>
                                <td class="pe-24 py-14 text-center">
                                    <form method="POST" action="{{ route('dashboard-admin.hcd.training.certificates.destroy', $cert->id) }}" onsubmit="return confirm('Hapus catatan sertifikat ini?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-40 text-secondary-light">
                                    <iconify-icon icon="solar:diploma-verified-bold-duotone" class="text-4xl text-neutral-400 mb-2"></iconify-icon>
                                    <p class="text-xs mb-0">Belum ada sertifikasi yang tercatat sesuai filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($certificates->hasPages())
                <div class="p-16 border-top">
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>
    </div>
@else
    
    <div class="row g-3 mb-24">
        @forelse ($trainings as $tr)
            <div class="col-xl-4 col-md-6">
                <div class="card radius-12 border-0 shadow-sm h-100 p-20 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-12">
                            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 text-2xs">{{ $tr->category }}</span>
                            <span class="badge bg-neutral-100 text-secondary-light text-2xs">{{ $tr->validity_years }} Tahun Berlaku</span>
                        </div>
                        <h6 class="fw-bold text-primary-light fs-6 mb-2">{{ $tr->title }}</h6>
                        <p class="text-xs text-secondary-light mb-16">{{ $tr->description ?? 'Deskripsi kurikulum standar industri.' }}</p>
                    </div>
                    <div class="pt-12 border-top d-flex justify-content-between align-items-center text-xs">
                        <span class="text-secondary-light">Lembaga: <strong>{{ $tr->institution ?? 'Institusi Resmi' }}</strong></span>
                        <span class="badge bg-success-50 text-success-700">{{ $tr->employee_trainings_count }} Lulusan</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-40 text-secondary-light">Belum ada program pelatihan di katalog.</div>
        @endforelse
    </div>
@endif


<div class="modal fade" id="newCertificateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-12 border-0 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-bold text-primary-light">Catat Sertifikat / Pelatihan Karyawan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('dashboard-admin.hcd.training.certificates.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Pilih Karyawan <span class="text-danger-main">*</span></label>
                            <select name="employee_id" class="form-select form-select-sm radius-8" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->employee_number }} — {{ $emp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Program Sertifikasi <span class="text-danger-main">*</span></label>
                            <select name="training_id" class="form-select form-select-sm radius-8" required>
                                <option value="">-- Pilih Program Pelatihan --</option>
                                @foreach ($trainings as $tr)
                                    <option value="{{ $tr->id }}">{{ $tr->title }} ({{ $tr->institution ?? 'Umum' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Nomor Sertifikat / Lisensi</label>
                            <input type="text" name="certificate_number" class="form-control form-control-sm radius-8" placeholder="Contoh: CERT-K3-2026-0091">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Status</label>
                            <select name="status" class="form-select form-select-sm radius-8" required>
                                <option value="valid" selected>Lulus &amp; Berlaku (Valid)</option>
                                <option value="in_training">Sedang Mengikuti Pelatihan (In Training)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Tanggal Terbit <span class="text-danger-main">*</span></label>
                            <input type="date" name="issue_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Tanggal Kadaluarsa (Expiry Date)</label>
                            <input type="date" name="expiry_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d', strtotime('+3 years')) }}">
                            <small class="text-3xs text-secondary-light">Kosongkan bila sertifikat berlaku seumur hidup</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Biaya Pelatihan (IDR)</label>
                            <input type="number" name="cost" class="form-control form-control-sm radius-8" placeholder="Contoh: 4500000" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">File Scan Sertifikat (PDF/Gambar)</label>
                            <input type="file" name="certificate_file" class="form-control form-control-sm radius-8" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-secondary-light">Catatan / Nilai Evaluasi</label>
                            <textarea name="notes" rows="2" class="form-control form-control-sm radius-8" placeholder="Catatan hasil asesmen atau rekomendasi instruktur..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary radius-8 px-20">Simpan Sertifikat</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="newCatalogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-12 border-0 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-bold text-primary-light">Tambah Program Pelatihan ke Katalog</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('dashboard-admin.hcd.training.catalog.store') }}">
                @csrf
                <div class="modal-body p-24">
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary-light">Judul Program Sertifikasi <span class="text-danger-main">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm radius-8" placeholder="Contoh: CSMS Lead Auditor Training" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Kategori <span class="text-danger-main">*</span></label>
                            <select name="category" class="form-select form-select-sm radius-8" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Masa Berlaku Standar (Tahun)</label>
                            <input type="number" name="validity_years" class="form-control form-control-sm radius-8" value="3" min="1" max="10" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-xs fw-bold text-secondary-light">Lembaga Penyelenggara</label>
                        <input type="text" name="institution" class="form-control form-control-sm radius-8" placeholder="Contoh: Kemenaker RI / BNSP / Internal BAMA">
                    </div>
                    <div>
                        <label class="form-label text-xs fw-bold text-secondary-light">Deskripsi Kompetensi</label>
                        <textarea name="description" rows="3" class="form-control form-control-sm radius-8" placeholder="Cakupan materi silabus pelatihan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary radius-8 px-20">Tambahkan ke Katalog</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
