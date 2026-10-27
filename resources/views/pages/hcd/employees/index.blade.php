@extends('layout.layout')

@php
    $title = 'Employee Register';
    $subTitle = 'Direktori & Database Seluruh Personel Internal, Tenaga Ahli, dan Kru Lapangan BAMA+';
    $script = '<script>
        function openEditModal(emp) {
            document.getElementById("editEmployeeForm").action = "/dashboard-admin/hcd/employees/" + emp.id;
            document.getElementById("editName").value = emp.user ? emp.user.name : "";
            document.getElementById("editDept").value = emp.department_id;
            document.getElementById("editPos").value = emp.position_id;
            document.getElementById("editNik").value = emp.nik || "";
            document.getElementById("editEmploymentStatus").value = emp.employment_status || "contract";
            document.getElementById("editWorkLocation").value = emp.work_location || "Head Office";
            document.getElementById("editStatus").value = emp.status || "active";
            document.getElementById("editDailyRate").value = emp.daily_rate || 0;
            document.getElementById("editPhone").value = emp.phone || "";
            document.getElementById("editSkills").value = emp.skills || "";
            document.getElementById("editEducation").value = emp.education || "";
            document.getElementById("editEmergencyName").value = emp.emergency_contact_name || "";
            document.getElementById("editEmergencyPhone").value = emp.emergency_contact_phone || "";
            new bootstrap.Modal(document.getElementById("editEmployeeModal")).show();
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
        <button type="button" class="btn btn-sm btn-primary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#newEmployeeModal">
            <iconify-icon icon="solar:user-plus-bold" class="text-lg"></iconify-icon>
            + Daftarkan Karyawan Baru
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
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light">Total Karyawan</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $counts['all'] }} <span class="fs-6 fw-normal text-secondary-light">Orang</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-xl flex-shrink-0">
                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>{{ $counts['active'] }} Status Aktif Bekerja</span>
                    <span class="badge bg-primary-50 text-primary-600">Terdaftar</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Karyawan Tetap (PKWTT)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $counts['permanent'] }} <span class="fs-6 fw-normal text-secondary-light">Personel</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-xl flex-shrink-0">
                        <iconify-icon icon="solar:shield-check-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Permanent Headcount</span>
                    <span class="badge bg-primary-50 text-primary-700">Permanent</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Karyawan Kontrak (PKWT)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-warning-main fs-5">{{ $counts['contract'] }} <span class="fs-6 fw-normal text-secondary-light">Personel</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-xl flex-shrink-0">
                        <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Project &amp; Turnaround Basis</span>
                    <span class="badge bg-warning-50 text-warning-700">Contract</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Kru Mobilisasi Site</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $counts['site'] }} <span class="fs-6 fw-normal text-secondary-light">Personel</span></h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-info-100 text-info-main text-xl flex-shrink-0">
                        <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Penugasan Lapangan</span>
                    <span class="badge bg-info-50 text-info-700">On-Site</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.hcd.employees.index') }}" class="row g-2 align-items-center">
            <div class="col-lg-3 col-md-6">
                <select name="department_id" class="form-select form-select-sm radius-8" onchange="this.form.submit()">
                    <option value="all">Semua Departemen</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $deptFilter == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <select name="employment_status" class="form-select form-select-sm radius-8" onchange="this.form.submit()">
                    <option value="all">Semua Status Kerja</option>
                    <option value="permanent" {{ $employmentFilter === 'permanent' ? 'selected' : '' }}>Permanent (Tetap)</option>
                    <option value="contract" {{ $employmentFilter === 'contract' ? 'selected' : '' }}>Contract (PKWT)</option>
                    <option value="freelance" {{ $employmentFilter === 'freelance' ? 'selected' : '' }}>Freelance / Harian</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <select name="location" class="form-select form-select-sm radius-8" onchange="this.form.submit()">
                    <option value="all">Semua Lokasi Kerja</option>
                    @foreach ($locations as $loc)
                        <option value="{{ $loc }}" {{ $locationFilter === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-8">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" value="{{ $search }}" class="form-control radius-8" placeholder="Cari nama, NIK, NIP, telepon...">
                    <button class="btn btn-outline-secondary" type="submit">
                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                    </button>
                </div>
            </div>
            <div class="col-lg-1 col-md-4 text-end">
                @if ($deptFilter !== 'all' || $employmentFilter !== 'all' || $locationFilter !== 'all' || $search)
                    <a href="{{ route('dashboard-admin.hcd.employees.index') }}" class="btn btn-sm btn-outline-danger w-100 radius-8" title="Reset Filter">
                        <iconify-icon icon="solar:restart-bold"></iconify-icon> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th class="ps-24 py-16">Karyawan &amp; NIP</th>
                        <th class="py-16">Departemen &amp; Jabatan</th>
                        <th class="py-16">Status &amp; Penempatan</th>
                        <th class="py-16">Kontak &amp; Telepon</th>
                        <th class="py-16 text-center">Sertifikat K3/Teknis</th>
                        <th class="py-16 text-end">Rate Harian</th>
                        <th class="pe-24 py-16 text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $emp)
                        <tr>
                            <td class="ps-24 py-14">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="w-40-px h-40-px bg-primary-50 text-primary-600 fw-bold radius-circle d-flex align-items-center justify-content-center flex-shrink-0 text-sm">
                                        {{ $emp->user->initials ?? strtoupper(substr($emp->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('dashboard-admin.hcd.employees.show', $emp->id) }}" class="fw-bold text-primary-600 hover-text-primary text-xs d-block mb-1">
                                            {{ $emp->name }}
                                        </a>
                                        <div class="d-flex align-items-center gap-1 text-3xs text-secondary-light">
                                            <span class="font-monospace text-primary-light">{{ $emp->employee_number }}</span>
                                            @if ($emp->nik)
                                                <span>· NIK: {{ $emp->nik }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-14 text-xs">
                                <span class="fw-semibold text-primary-light d-block">{{ $emp->position->title ?? '-' }}</span>
                                <span class="badge bg-neutral-100 text-secondary-light mt-1">{{ $emp->department->name ?? '-' }}</span>
                            </td>
                            <td class="py-14 text-xs">
                                <span class="badge {{ $emp->employment_status === 'permanent' ? 'bg-primary-50 text-primary-700 border border-primary-200' : 'bg-warning-50 text-warning-700 border border-warning-200' }} text-3xs mb-1">
                                    {{ strtoupper($emp->employment_status ?? 'CONTRACT') }}
                                </span>
                                <span class="text-secondary-light d-flex align-items-center gap-1 mt-1">
                                    <iconify-icon icon="solar:map-point-bold-duotone" class="text-danger-main text-xs"></iconify-icon>
                                    {{ $emp->work_location ?? 'Head Office' }}
                                </span>
                            </td>
                            <td class="py-14 text-xs">
                                <span class="text-primary-light d-block font-monospace">{{ $emp->phone ?? '-' }}</span>
                                <small class="text-secondary-light">{{ $emp->email }}</small>
                            </td>
                            <td class="py-14 text-center">
                                @php
                                    $certCount = $emp->employeeTrainings->count();
                                @endphp
                                @if ($certCount > 0)
                                    <a href="{{ route('dashboard-admin.hcd.training.index', ['employee_id' => $emp->id]) }}" class="badge bg-success-50 text-success-700 border border-success-200 px-8 py-3 radius-4 text-xs d-inline-flex align-items-center gap-1">
                                        <iconify-icon icon="solar:diploma-verified-bold-duotone"></iconify-icon>
                                        {{ $certCount }} Sertifikat
                                    </a>
                                @else
                                    <span class="text-secondary-light text-2xs">-</span>
                                @endif
                            </td>
                            <td class="py-14 text-end text-xs font-monospace">
                                @if ($emp->daily_rate > 0)
                                    <span class="fw-semibold text-primary-light">Rp {{ number_format($emp->daily_rate, 0, ',', '.') }}</span>
                                    <small class="text-3xs text-secondary-light d-block">/hari</small>
                                @else
                                    <span class="text-secondary-light">Standar HO</span>
                                @endif
                            </td>
                            <td class="pe-24 py-14 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('dashboard-admin.hcd.employees.show', $emp->id) }}" class="btn btn-sm btn-outline-primary p-6 radius-6" title="Profil 360">
                                        <iconify-icon icon="iconamoon:eye-light" class="text-sm"></iconify-icon>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary p-6 radius-6" onclick="openEditModal({{ json_encode($emp) }})" title="Edit Karyawan">
                                        <iconify-icon icon="solar:pen-linear" class="text-sm"></iconify-icon>
                                    </button>
                                    <form method="POST" action="{{ route('dashboard-admin.hcd.employees.destroy', $emp->id) }}" onsubmit="return confirm('Hapus data karyawan {{ $emp->name }}?');" class="d-inline">
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
                                <p class="text-xs mb-0">Tidak ada data karyawan yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($employees->hasPages())
            <div class="p-16 border-top">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</div>


<div class="modal fade" id="newEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-12 border-0 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-bold text-primary-light">Daftarkan Karyawan Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('dashboard-admin.hcd.employees.store') }}">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Nama Lengkap &amp; Gelar <span class="text-danger-main">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm radius-8" placeholder="Contoh: Budi Santoso, S.T." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Email Login ERP <span class="text-danger-main">*</span></label>
                            <input type="email" name="email" class="form-control form-control-sm radius-8" placeholder="budi.s@elbema.co.id" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Nomor Induk Kependudukan (NIK KTP)</label>
                            <input type="text" name="nik" class="form-control form-control-sm radius-8" placeholder="16 digit NIK...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">No. Handphone / WhatsApp</label>
                            <input type="text" name="phone" class="form-control form-control-sm radius-8" placeholder="0812xxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Departemen <span class="text-danger-main">*</span></label>
                            <select name="department_id" class="form-select form-select-sm radius-8" required>
                                <option value="">-- Pilih Departemen --</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Jabatan / Posisi <span class="text-danger-main">*</span></label>
                            <select name="position_id" class="form-select form-select-sm radius-8" required>
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach ($positions as $pos)
                                    <option value="{{ $pos->id }}">{{ $pos->title }} ({{ $pos->department->code ?? '-' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Status Kerja <span class="text-danger-main">*</span></label>
                            <select name="employment_status" class="form-select form-select-sm radius-8" required>
                                <option value="contract" selected>Kontrak (PKWT)</option>
                                <option value="permanent">Tetap (PKWTT)</option>
                                <option value="freelance">Freelance / Proyek</option>
                                <option value="internship">Magang / Internship</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Penempatan Lokasi <span class="text-danger-main">*</span></label>
                            <input type="text" name="work_location" class="form-control form-control-sm radius-8" value="Head Office" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Tanggal Mulai Bergabung <span class="text-danger-main">*</span></label>
                            <input type="date" name="join_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Rate Harian Lapangan (IDR)</label>
                            <input type="number" name="daily_rate" class="form-control form-control-sm radius-8" placeholder="Contoh: 550000" value="500000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Pendidikan Terakhir</label>
                            <input type="text" name="education" class="form-control form-control-sm radius-8" placeholder="Contoh: S1 Teknik Mesin / SMK">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-secondary-light">Keahlian Khusus &amp; Spesialisasi Teknis</label>
                            <textarea name="skills" rows="2" class="form-control form-control-sm radius-8" placeholder="Contoh: UHP Water Jetting 40.000 PSI, Enerpac Torque Wrench, AK3 Umum..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Kontak Darurat (Nama &amp; Hubungan)</label>
                            <input type="text" name="emergency_contact_name" class="form-control form-control-sm radius-8" placeholder="Nama wali/keluarga...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Nomor Telepon Kontak Darurat</label>
                            <input type="text" name="emergency_contact_phone" class="form-control form-control-sm radius-8" placeholder="08xxxxxxxx">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary radius-8 px-20">Simpan Karyawan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-12 border-0 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-bold text-primary-light">Edit Data Karyawan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editEmployeeForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Nama Lengkap <span class="text-danger-main">*</span></label>
                            <input type="text" id="editName" name="name" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">NIK KTP</label>
                            <input type="text" id="editNik" name="nik" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Departemen <span class="text-danger-main">*</span></label>
                            <select id="editDept" name="department_id" class="form-select form-select-sm radius-8" required>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Jabatan <span class="text-danger-main">*</span></label>
                            <select id="editPos" name="position_id" class="form-select form-select-sm radius-8" required>
                                @foreach ($positions as $pos)
                                    <option value="{{ $pos->id }}">{{ $pos->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Status Kepegawaian</label>
                            <select id="editEmploymentStatus" name="employment_status" class="form-select form-select-sm radius-8" required>
                                <option value="permanent">Permanent (Tetap)</option>
                                <option value="contract">Contract (PKWT)</option>
                                <option value="freelance">Freelance</option>
                                <option value="internship">Internship</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Penempatan</label>
                            <input type="text" id="editWorkLocation" name="work_location" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-bold text-secondary-light">Status Aktif</label>
                            <select id="editStatus" name="status" class="form-select form-select-sm radius-8" required>
                                <option value="active">Aktif</option>
                                <option value="mobilized">Mobilized (Di Site)</option>
                                <option value="on_leave">Cuti (On Leave)</option>
                                <option value="inactive">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">No. Telepon</label>
                            <input type="text" id="editPhone" name="phone" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Rate Harian (IDR)</label>
                            <input type="number" id="editDailyRate" name="daily_rate" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-bold text-secondary-light">Keahlian &amp; Sertifikasi</label>
                            <textarea id="editSkills" name="skills" rows="2" class="form-control form-control-sm radius-8"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Kontak Darurat (Nama)</label>
                            <input type="text" id="editEmergencyName" name="emergency_contact_name" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-bold text-secondary-light">Telepon Darurat</label>
                            <input type="text" id="editEmergencyPhone" name="emergency_contact_phone" class="form-control form-control-sm radius-8">
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
