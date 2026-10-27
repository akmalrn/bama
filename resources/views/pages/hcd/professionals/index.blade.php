@extends('layout.layout')

@php
    $title = 'Professional Database';
    $subTitle = 'Talent Pool Tenaga Ahli, Spesialis Lapangan & Subkontraktor Teknis Terverifikasi';
    $script = '<script>
        function openEditProfessionalModal(prof) {
            document.getElementById("editProfForm").action = "/dashboard-admin/hcd/professionals/" + prof.id;
            document.getElementById("editProfName").value = prof.full_name;
            document.getElementById("editProfSpec").value = prof.specialization;
            document.getElementById("editProfCat").value = prof.category;
            document.getElementById("editProfPhone").value = prof.contact_phone;
            document.getElementById("editProfEmail").value = prof.contact_email || "";
            document.getElementById("editProfCity").value = prof.city || "";
            document.getElementById("editProfExp").value = prof.experience_years;
            document.getElementById("editProfRate").value = prof.daily_rate;
            document.getElementById("editProfStatus").value = prof.availability_status;
            document.getElementById("editProfProject").value = prof.current_project_id || "";
            document.getElementById("editProfRating").value = prof.rating || 5.0;
            document.getElementById("editProfCerts").value = prof.certifications_summary || "";
            document.getElementById("editProfNotes").value = prof.notes || "";
            new bootstrap.Modal(document.getElementById("editProfessionalModal")).show();
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
        <button type="button" class="btn btn-sm btn-primary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#newProfessionalModal">
            <iconify-icon icon="solar:user-plus-bold" class="text-lg"></iconify-icon>
            + Tambah Tenaga Ahli
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

{{-- 4 KPI METRIC CARDS --}}
<div class="row g-3 mb-24">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light">Total Talent Pool</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $counts['all'] }} <span class="fs-6 fw-normal text-secondary-light">Spesialis</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-xl flex-shrink-0">
                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Database Eksternal &amp; Freelance</span>
                    <span class="badge bg-neutral-100 text-secondary-light">Verified</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Siap Mobilisasi (Available)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-success-main fs-5">{{ $counts['available'] }} <span class="fs-6 fw-normal text-secondary-light">Pakar</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-main text-xl flex-shrink-0">
                        <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Ready for Turnaround Job</span>
                    <span class="badge bg-success-50 text-success-700">Available</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Sedang Bertugas di Site</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $counts['mobilized'] }} <span class="fs-6 fw-normal text-secondary-light">Personel</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-xl flex-shrink-0">
                        <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Alokasi Proyek Aktif</span>
                    <span class="badge bg-primary-50 text-primary-700">Mobilized</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Standby / Cadangan</span>
                        <h4 class="fw-bold mb-0 mt-2 text-warning-main fs-5">{{ $counts['standby'] }} <span class="fs-6 fw-normal text-secondary-light">Orang</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-xl flex-shrink-0">
                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Siaga Bila Ada Shortage</span>
                    <span class="badge bg-warning-50 text-warning-700">Standby</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.hcd.professionals.index') }}" class="row g-2 align-items-center">
            <div class="col-lg-3 col-md-6">
                <select name="category" class="form-select form-select-sm radius-8" onchange="this.form.submit()">
                    <option value="all">Semua Kategori Bidang</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="status" class="form-select form-select-sm radius-8" onchange="this.form.submit()">
                    <option value="all">Semua Ketersediaan (Availability)</option>
                    <option value="available" {{ $statusFilter === 'available' ? 'selected' : '' }}>Available (Siap Kerja)</option>
                    <option value="mobilized" {{ $statusFilter === 'mobilized' ? 'selected' : '' }}>Mobilized (Sedang di Site)</option>
                    <option value="standby" {{ $statusFilter === 'standby' ? 'selected' : '' }}>Standby (Siaga)</option>
                </select>
            </div>
            <div class="col-lg-5 col-md-8">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" value="{{ $search }}" class="form-control radius-8" placeholder="Cari nama, keahlian khusus, sertifikasi, kota...">
                    <button class="btn btn-outline-secondary" type="submit">
                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                    </button>
                </div>
            </div>
            <div class="col-lg-1 col-md-4 text-end">
                @if ($categoryFilter !== 'all' || $statusFilter !== 'all' || $search)
                    <a href="{{ route('dashboard-admin.hcd.professionals.index') }}" class="btn btn-sm btn-outline-danger w-100 radius-8" title="Reset Filter">
                        <iconify-icon icon="solar:restart-bold"></iconify-icon> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- PROFESSIONALS DIRECTORY CARDS / TABLE --}}
<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-24 py-16">Tenaga Ahli &amp; Spesialisasi</th>
                        <th class="py-16">Bidang &amp; Pengalaman</th>
                        <th class="py-16">Kota Asal &amp; Kontak</th>
                        <th class="py-16 text-end">Rate Harian</th>
                        <th class="py-16 text-center">Status / Proyek Saat Ini</th>
                        <th class="py-16 text-center">Rating</th>
                        <th class="pe-24 py-16 text-center" width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($professionals as $prof)
                        <tr>
                            <td class="ps-24 py-14">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="w-40-px h-40-px bg-primary-50 text-primary-600 fw-bold radius-circle d-flex align-items-center justify-content-center flex-shrink-0 text-sm">
                                        {{ strtoupper(substr($prof->full_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <strong class="text-xs text-primary-light d-block mb-1">{{ $prof->full_name }}</strong>
                                        <span class="text-xs text-primary-600 fw-medium d-block mb-1">
                                            {{ $prof->specialization }}
                                        </span>
                                        @if ($prof->certifications_summary)
                                            <small class="text-3xs text-secondary-light text-truncate d-block" style="max-width: 280px;" title="{{ $prof->certifications_summary }}">
                                                <iconify-icon icon="solar:diploma-verified-bold-duotone" class="text-success-main me-1"></iconify-icon>
                                                {{ $prof->certifications_summary }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-14 text-xs">
                                <span class="badge bg-neutral-100 text-secondary-light mb-1">{{ $prof->category }}</span>
                                <small class="text-secondary-light d-block">
                                    Pengalaman: <strong>{{ $prof->experience_years }} Tahun</strong>
                                </small>
                            </td>
                            <td class="py-14 text-xs">
                                <span class="text-primary-light d-flex align-items-center gap-1 mb-1">
                                    <iconify-icon icon="solar:map-point-bold-duotone" class="text-danger-main text-xs"></iconify-icon>
                                    {{ $prof->city ?? 'Indonesia' }}
                                </span>
                                <span class="font-monospace text-secondary-light d-block">{{ $prof->contact_phone }}</span>
                            </td>
                            <td class="py-14 text-end text-xs font-monospace">
                                <span class="fw-bold text-primary-light">Rp {{ number_format($prof->daily_rate, 0, ',', '.') }}</span>
                                <small class="text-3xs text-secondary-light d-block">/hari</small>
                            </td>
                            <td class="py-14 text-center">
                                <span class="badge {{ $prof->status_badge_class }} text-xs px-10 py-4 rounded-pill mb-1">
                                    {{ strtoupper($prof->availability_status) }}
                                </span>
                                @if ($prof->currentProject)
                                    <small class="text-3xs text-primary-600 d-block font-monospace">
                                        {{ $prof->currentProject->project_code }}
                                    </small>
                                @endif
                            </td>
                            <td class="py-14 text-center text-xs">
                                <span class="badge bg-warning-50 text-warning-main border border-warning-200 px-8 py-3 rounded-pill d-inline-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:star-bold" class="text-xs"></iconify-icon>
                                    {{ number_format($prof->rating, 2) }}
                                </span>
                            </td>
                            <td class="pe-24 py-14 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary p-6 radius-6" onclick="openEditProfessionalModal({{ json_encode($prof) }})" title="Edit Profil & Penugasan">
                                        <iconify-icon icon="solar:pen-linear" class="text-sm"></iconify-icon>
                                    </button>
                                    <form method="POST" action="{{ route('dashboard-admin.hcd.professionals.destroy', $prof->id) }}" onsubmit="return confirm('Hapus data tenaga ahli {{ $prof->full_name }}?');" class="d-inline">
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
                            <td colspan="7" class="text-center py-40 text-secondary-light">
                                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="text-4xl text-neutral-400 mb-2"></iconify-icon>
                                <p class="text-xs mb-0">Belum ada tenaga ahli terdaftar sesuai filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($professionals->hasPages())
            <div class="p-16 border-top">
                {{ $professionals->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH TENAGA AHLI --}}
<div class="modal fade" id="newProfessionalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-12 border-0 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-bold text-primary-light">Tambah Tenaga Ahli ke Talent Pool</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('dashboard-admin.hcd.professionals.store') }}">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Nama Lengkap &amp; Gelar <span class="text-danger-main">*</span></label>
                            <input type="text" name="full_name" class="form-control form-control-sm radius-8" placeholder="Contoh: Ir. Muhammad Danang, IPM" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Spesialisasi Lapangan <span class="text-danger-main">*</span></label>
                            <input type="text" name="specialization" class="form-control form-control-sm radius-8" placeholder="Contoh: Hot Tapping & Line Stopping Lead" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Kategori Bidang <span class="text-danger-main">*</span></label>
                            <select name="category" class="form-select form-select-sm radius-8" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Kota Asal / Homebase</label>
                            <input type="text" name="city" class="form-control form-control-sm radius-8" placeholder="Contoh: Balikpapan / Cilegon / Palembang">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">No. Handphone / WhatsApp <span class="text-danger-main">*</span></label>
                            <input type="text" name="contact_phone" class="form-control form-control-sm radius-8" placeholder="0812xxxxxxxx" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Alamat Email</label>
                            <input type="email" name="contact_email" class="form-control form-control-sm radius-8" placeholder="pakar@email.com">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Pengalaman Kerja (Tahun) <span class="text-danger-main">*</span></label>
                            <input type="number" name="experience_years" class="form-control form-control-sm radius-8" value="5" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Rate Harian (IDR/Day)</label>
                            <input type="number" name="daily_rate" class="form-control form-control-sm radius-8" placeholder="Contoh: 1500000" value="1200000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Status Ketersediaan <span class="text-danger-main">*</span></label>
                            <select name="availability_status" class="form-select form-select-sm radius-8" required>
                                <option value="available" selected>Available (Siap Kerja)</option>
                                <option value="standby">Standby (Siaga)</option>
                                <option value="mobilized">Mobilized (Sedang di Site)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-secondary-light">Ringkasan Sertifikasi &amp; Lisensi K3</label>
                            <textarea name="certifications_summary" rows="2" class="form-control form-control-sm radius-8" placeholder="Contoh: API 2201 Hot Tapping, CSMS Pertamina, AK3 Migas..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-secondary-light">Catatan Kinerja &amp; Portofolio Ringkas</label>
                            <textarea name="notes" rows="2" class="form-control form-control-sm radius-8" placeholder="Portofolio pekerjaan shutdown/turnaround yang pernah ditangani..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary radius-8 px-20">Simpan Tenaga Ahli</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT TENAGA AHLI --}}
<div class="modal fade" id="editProfessionalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-12 border-0 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-bold text-primary-light">Edit Data Tenaga Ahli</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProfForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Nama Lengkap <span class="text-danger-main">*</span></label>
                            <input type="text" id="editProfName" name="full_name" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Spesialisasi <span class="text-danger-main">*</span></label>
                            <input type="text" id="editProfSpec" name="specialization" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Kategori <span class="text-danger-main">*</span></label>
                            <select id="editProfCat" name="category" class="form-select form-select-sm radius-8" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Kota Asal</label>
                            <input type="text" id="editProfCity" name="city" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Telepon <span class="text-danger-main">*</span></label>
                            <input type="text" id="editProfPhone" name="contact_phone" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Email</label>
                            <input type="email" id="editProfEmail" name="contact_email" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Pengalaman (Tahun)</label>
                            <input type="number" id="editProfExp" name="experience_years" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Rate Harian (IDR)</label>
                            <input type="number" id="editProfRate" name="daily_rate" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Rating (1-5)</label>
                            <input type="number" step="0.05" id="editProfRating" name="rating" class="form-control form-control-sm radius-8" min="1" max="5">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Status Ketersediaan</label>
                            <select id="editProfStatus" name="availability_status" class="form-select form-select-sm radius-8" required>
                                <option value="available">Available (Siap Kerja)</option>
                                <option value="mobilized">Mobilized (Sedang di Site)</option>
                                <option value="standby">Standby (Siaga)</option>
                                <option value="blacklisted">Blacklisted</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Tugaskan ke Proyek (Jika Mobilized)</label>
                            <select id="editProfProject" name="current_project_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Tidak Ditugaskan --</option>
                                @foreach ($projects as $pr)
                                    <option value="{{ $pr->id }}">{{ $pr->project_code }} — {{ Str::limit($pr->title, 30) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-secondary-light">Ringkasan Sertifikasi</label>
                            <textarea id="editProfCerts" name="certifications_summary" rows="2" class="form-control form-control-sm radius-8"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-secondary-light">Catatan Khusus</label>
                            <textarea id="editProfNotes" name="notes" rows="2" class="form-control form-control-sm radius-8"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary radius-8 px-20">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
