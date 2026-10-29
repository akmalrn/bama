@extends('layout.layout')

@php
    $title = 'Customer Contact Register';
    $subTitle = 'Sales CRM';
    $script = '<script>
        document.addEventListener("DOMContentLoaded", function () {
            // Helper to sync industry and location based on selected company option
            function syncCompanyFields(selectEl, industryInputId, locationInputId) {
                if (!selectEl) return;
                const selectedOpt = selectEl.options[selectEl.selectedIndex];
                if (selectedOpt && selectedOpt.value) {
                    const industry = selectedOpt.getAttribute("data-industry") || "";
                    const location = selectedOpt.getAttribute("data-location") || "";
                    const indInput = document.getElementById(industryInputId);
                    const locInput = document.getElementById(locationInputId);
                    if (indInput) indInput.value = industry;
                    if (locInput) locInput.value = location;
                }
            }

            // Create Modal company select listener
            const createCompSelect = document.getElementById("create_customer_select");
            if (createCompSelect) {
                createCompSelect.addEventListener("change", function() {
                    syncCompanyFields(this, "create_industry", "create_location");
                });
            }

            // Edit Modal company select listener
            const editCompSelect = document.getElementById("edit_company_name");
            if (editCompSelect) {
                editCompSelect.addEventListener("change", function() {
                    syncCompanyFields(this, "edit_industry", "edit_location");
                });
            }

            // Edit Contact Button click listener
            document.querySelectorAll(".btn-edit-contact").forEach(btn => {
                btn.addEventListener("click", function() {
                    const id = this.getAttribute("data-id");
                    const company = this.getAttribute("data-company") || "";
                    const industry = this.getAttribute("data-industry") || "";
                    const location = this.getAttribute("data-location") || "";
                    const pic = this.getAttribute("data-pic") || "";
                    const phone = this.getAttribute("data-phone") || "";
                    const email = this.getAttribute("data-email") || "";

                    const form = document.getElementById("editContactForm");
                    if (form) form.action = `/dashboard-admin/sales/customers/${id}`;

                    const compSelect = document.getElementById("edit_company_name");
                    if (compSelect) {
                        compSelect.value = company;
                        // Trigger field sync if option exists
                        syncCompanyFields(compSelect, "edit_industry", "edit_location");
                    }
                    // Fallback to explicit values if needed
                    const indInput = document.getElementById("edit_industry");
                    if (indInput && (!indInput.value || indInput.value === "")) indInput.value = industry;

                    const locInput = document.getElementById("edit_location");
                    if (locInput && (!locInput.value || locInput.value === "")) locInput.value = location;

                    document.getElementById("edit_pic_name").value = pic;
                    document.getElementById("edit_pic_phone").value = phone;
                    document.getElementById("edit_pic_email").value = email;
                });
            });

            @if(request()->query("open_create") || (isset($errors) && $errors->any()))
                const createModalEl = document.getElementById("createContactModal");
                if (createModalEl) {
                    const createModal = new bootstrap.Modal(createModalEl);
                    createModal.show();
                }
            @endif
        });
    </script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0">Customer Contact Register</h6>
        <p class="text-neutral-500 text-sm mb-0">Kelola relasi contact customer, PIC kunci, sektor industri, dan profil engagement tim Sales.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary text-sm btn-sm px-16 py-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createContactModal">
            <iconify-icon icon="solar:user-plus-outline" class="icon text-lg"></iconify-icon>
            Tambah Contact
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Analytics / Sector Breakdown Cards --}}
<div class="row g-3 mb-24">
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-none border h-100">
            <div class="card-body p-20">
                <div class="d-flex align-items-center justify-content-between mb-16">
                    <div>
                        <h6 class="text-sm fw-semibold mb-2">Total Contacts Terdaftar</h6>
                        <span class="text-xs text-neutral-500">{{ $totalContacts }} PIC perusahaan aktif</span>
                    </div>
                    <span class="w-36-px h-36-px bg-primary-subtle text-primary-main rounded-circle d-flex align-items-center justify-content-center text-lg">
                        <iconify-icon icon="solar:users-group-rounded-outline"></iconify-icon>
                    </span>
                </div>
                <h3 class="mb-0 fw-bold">{{ $totalContacts }}</h3>
                <p class="text-xs text-neutral-400 mt-8 mb-0">Tersinkronisasi otomatis dengan register Customer</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-none border h-100">
            <div class="card-body p-20">
                <div class="d-flex align-items-center justify-content-between mb-12">
                    <div>
                        <h6 class="text-sm fw-semibold mb-2">Contact by Sector</h6>
                        <span class="text-xs text-neutral-500">Sebaran sektor industri</span>
                    </div>
                    <span class="w-36-px h-36-px bg-warning-subtle text-warning-main rounded-circle d-flex align-items-center justify-content-center text-lg">
                        <iconify-icon icon="solar:pie-chart-2-outline"></iconify-icon>
                    </span>
                </div>
                <div class="d-flex flex-column gap-2">
                    @forelse($sectors->take(3) as $sectorName => $count)
                        <div>
                            <div class="d-flex justify-content-between text-xs mb-1">
                                <span class="text-neutral-600 text-truncate max-w-200-px">{{ $sectorName ?: 'General' }}</span>
                                <span class="fw-bold">{{ $count }}</span>
                            </div>
                            <div class="progress h-4-px bg-neutral-100">
                                <div class="progress-bar bg-warning-main" style="width: {{ $totalContacts ? ($count / $totalContacts * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <span class="text-xs text-neutral-400">Belum ada data sektor</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="card shadow-none border h-100">
            <div class="card-body p-20">
                <div class="d-flex align-items-center justify-content-between mb-12">
                    <div>
                        <h6 class="text-sm fw-semibold mb-2">Aktivitas Terakhir</h6>
                        <span class="text-xs text-neutral-500">Log follow-up terbaru</span>
                    </div>
                    <span class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle d-flex align-items-center justify-content-center text-lg">
                        <iconify-icon icon="solar:chat-round-call-outline"></iconify-icon>
                    </span>
                </div>
                <div class="d-flex flex-column gap-2">
                    @forelse($recentActivities->take(3) as $ra)
                        <div class="d-flex align-items-center justify-content-between text-xs border-bottom pb-2">
                            <span class="fw-semibold text-truncate max-w-180-px">{{ $ra->title }}</span>
                            <span class="text-neutral-400">{{ $ra->activity_at ? $ra->activity_at->diffForHumans() : '—' }}</span>
                        </div>
                    @empty
                        <span class="text-xs text-neutral-400">Belum ada aktivitas</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card shadow-none border mb-24">
    <div class="card-body p-20">
        <form method="GET" action="{{ route('dashboard-admin.sales.contacts.index') }}" class="row g-3">
            <div class="col-lg-6 col-md-8">
                <label class="form-label text-sm fw-medium">Pencarian Contact</label>
                <div class="position-relative">
                    <input type="text" name="search" class="form-control text-sm pe-40" placeholder="Cari nama PIC, nama perusahaan, email, atau no HP..." value="{{ request('search') }}">
                    <iconify-icon icon="solar:magnifer-outline" class="position-absolute end-0 top-50 translate-middle-y me-12 text-neutral-400"></iconify-icon>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <label class="form-label text-sm fw-medium">Sektor Industri</label>
                <select name="industry" class="form-select text-sm">
                    <option value="">Semua Sektor</option>
                    @foreach($industries as $ind)
                        <option value="{{ $ind }}" {{ request('industry') == $ind ? 'selected' : '' }}>{{ $ind }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-12 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100 py-10">Filter</button>
                @if(request()->anyFilled(['search', 'industry']))
                    <a href="{{ route('dashboard-admin.sales.contacts.index') }}" class="btn btn-light btn-sm py-10" title="Reset">
                        <iconify-icon icon="solar:restart-outline"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Contact Table --}}
<div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
    <div class="card-header border-bottom py-16 px-24 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="fw-bold text-primary-light mb-0 text-sm">Daftar Customer Contact</h6>
            <span class="text-2xs text-secondary-light">Daftar PIC, perusahaan, dan kontak komunikasi komersial.</span>
        </div>
        <span class="badge bg-neutral-100 text-secondary-light border border-neutral-200 text-2xs px-8 py-3 radius-4 fw-medium">
            • {{ $customers->total() }} contacts
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-100">
                    <tr>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">NAMA CONTACT (PIC)</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">PERUSAHAAN</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">SEKTOR INDUSTRI</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">LOKASI / KANTOR</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">NO. TELEPON / HP</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">EMAIL</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-center text-primary-light" width="110">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                        <tr>
                            <td class="px-16 py-12">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="w-32-px h-32-px rounded-circle bg-primary-50 text-primary-600 fw-bold d-flex align-items-center justify-content-center text-xs flex-shrink-0">
                                        {{ strtoupper(substr($c->pic_name ?? 'C', 0, 2)) }}
                                    </span>
                                    <div>
                                        <span class="fw-bold text-sm d-block text-primary-light">{{ $c->pic_name ?: '—' }}</span>
                                        <span class="text-secondary-light text-2xs">PIC Utama</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-16 py-12">
                                <a href="{{ route('dashboard-admin.sales.customers.show', $c) }}" class="fw-semibold text-sm d-block text-primary-light hover-text-primary">
                                    {{ $c->company_name }}
                                </a>
                            </td>
                            <td class="px-16 py-12">
                                <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-8 py-4 radius-6 text-2xs fw-semibold text-nowrap">
                                    {{ $c->industry ?: 'General' }}
                                </span>
                            </td>
                            <td class="px-16 py-12">
                                <span class="text-xs text-secondary-light">{{ Str::limit($c->location, 30) ?: '—' }}</span>
                            </td>
                            <td class="px-16 py-12">
                                @if($c->pic_phone)
                                    <a href="tel:{{ $c->pic_phone }}" class="text-primary-600 text-xs font-monospace d-inline-flex align-items-center gap-1 hover-text-primary text-nowrap">
                                        <iconify-icon icon="solar:phone-outline" class="text-sm"></iconify-icon>
                                        {{ $c->pic_phone }}
                                    </a>
                                @else
                                    <span class="text-secondary-light text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-16 py-12">
                                @if($c->pic_email)
                                    <a href="mailto:{{ $c->pic_email }}" class="text-primary-light text-xs d-inline-flex align-items-center gap-1 hover-text-primary text-nowrap">
                                        <iconify-icon icon="solar:letter-outline" class="text-sm text-secondary-light"></iconify-icon>
                                        {{ $c->pic_email }}
                                    </a>
                                @else
                                    <span class="text-secondary-light text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-16 py-12 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('dashboard-admin.sales.customers.show', $c) }}"
                                       class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center"
                                       title="Detail Customer & PIC">
                                        <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                    </a>
                                    <button type="button" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center btn-edit-contact"
                                        data-bs-toggle="modal" data-bs-target="#editContactModal"
                                        data-id="{{ $c->id }}"
                                        data-company="{{ $c->company_name }}"
                                        data-industry="{{ $c->industry }}"
                                        data-location="{{ $c->location }}"
                                        data-pic="{{ $c->pic_name }}"
                                        data-phone="{{ $c->pic_phone }}"
                                        data-email="{{ $c->pic_email }}"
                                        title="Edit Contact">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-40 text-secondary-light text-xs">
                                <iconify-icon icon="solar:user-cross-outline" class="text-3xl mb-8 d-block mx-auto text-neutral-400"></iconify-icon>
                                Belum ada contact yang sesuai pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="p-16 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-2xs text-secondary-light">
                    Menampilkan {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} dari {{ $customers->total() }} contact
                </span>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Modal Create Contact --}}
<div class="modal fade" id="createContactModal" tabindex="-1" aria-labelledby="createContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 radius-12 shadow">
            <div class="modal-header border-bottom p-20">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-32-px h-32-px radius-8 bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:user-plus-bold-duotone" class="text-base"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title mb-0 fw-bold" id="createContactModalLabel">Tambah Customer Contact Baru</h6>
                        <span class="text-2xs text-secondary-light">Pilih perusahaan dari Customer Register & tentukan PIC</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.sales.contacts.store') }}" method="POST">
                @csrf
                <div class="modal-body p-20">
                    {{-- Dropdown Pilih Perusahaan dari Customer Register --}}
                    <div class="mb-16">
                        <label for="create_customer_select" class="form-label text-sm fw-medium">
                            Pilih Perusahaan / Client (Customer Register) <span class="text-danger-main">*</span>
                        </label>
                        <select name="company_name" id="create_customer_select" class="form-select text-sm" required>
                            <option value="">-- Pilih Perusahaan Terdaftar --</option>
                            @foreach($registeredCompanies as $company)
                                <option value="{{ $company->company_name }}"
                                        data-industry="{{ $company->industry }}"
                                        data-location="{{ $company->location }}"
                                        {{ old('company_name') === $company->company_name ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-secondary-light text-2xs mt-1 d-block">
                            * Memilih dari Customer Register mencegah typo dan duplikasi data klien.
                        </small>
                    </div>

                    <div class="row g-3 mb-16">
                        <div class="col-md-6">
                            <label for="create_industry" class="form-label text-sm fw-medium">Sektor Industri</label>
                            <input type="text" name="industry" id="create_industry" class="form-control text-sm bg-neutral-50" placeholder="Terisi otomatis..." value="{{ old('industry') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="create_location" class="form-label text-sm fw-medium">Lokasi / Kota</label>
                            <input type="text" name="location" id="create_location" class="form-control text-sm bg-neutral-50" placeholder="Terisi otomatis..." value="{{ old('location') }}" readonly>
                        </div>
                    </div>

                    <div class="mb-16">
                        <label for="pic_name" class="form-label text-sm fw-medium">Nama PIC / Contact Person <span class="text-danger-main">*</span></label>
                        <input type="text" name="pic_name" id="pic_name" class="form-control text-sm" placeholder="Contoh: Bpk. Bambang Irawan" value="{{ old('pic_name') }}" required>
                    </div>

                    <div class="row g-3 mb-0">
                        <div class="col-md-6">
                            <label for="pic_phone" class="form-label text-sm fw-medium">No. Telepon / WhatsApp</label>
                            <input type="text" name="pic_phone" id="pic_phone" class="form-control text-sm font-monospace" placeholder="08xxxxxxxxxx" value="{{ old('pic_phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="pic_email" class="form-label text-sm fw-medium">Alamat Email</label>
                            <input type="email" name="pic_email" id="pic_email" class="form-control text-sm" placeholder="pic@perusahaan.com" value="{{ old('pic_email') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-20">
                    <button type="button" class="btn btn-secondary text-sm btn-sm px-16 py-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary text-sm btn-sm px-16 py-8">Simpan Contact</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Contact --}}
<div class="modal fade" id="editContactModal" tabindex="-1" aria-labelledby="editContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 radius-12 shadow">
            <div class="modal-header border-bottom p-20">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-32-px h-32-px radius-8 bg-success-50 text-success-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:pen-bold-duotone" class="text-base"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title mb-0 fw-bold" id="editContactModalLabel">Edit Customer Contact</h6>
                        <span class="text-2xs text-secondary-light">Perbarui data PIC dan informasi kontak</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editContactForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-20">
                    <div class="mb-16">
                        <label for="edit_company_name" class="form-label text-sm fw-medium">Nama Perusahaan / Client</label>
                        <select name="company_name" id="edit_company_name" class="form-select text-sm" required>
                            @foreach($registeredCompanies as $company)
                                <option value="{{ $company->company_name }}"
                                        data-industry="{{ $company->industry }}"
                                        data-location="{{ $company->location }}">
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-16">
                        <div class="col-md-6">
                            <label for="edit_industry" class="form-label text-sm fw-medium">Sektor Industri</label>
                            <input type="text" name="industry" id="edit_industry" class="form-control text-sm bg-neutral-50" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_location" class="form-label text-sm fw-medium">Lokasi / Kota</label>
                            <input type="text" name="location" id="edit_location" class="form-control text-sm bg-neutral-50" readonly>
                        </div>
                    </div>

                    <div class="mb-16">
                        <label for="edit_pic_name" class="form-label text-sm fw-medium">Nama PIC / Contact Person <span class="text-danger-main">*</span></label>
                        <input type="text" name="pic_name" id="edit_pic_name" class="form-control text-sm" required>
                    </div>

                    <div class="row g-3 mb-0">
                        <div class="col-md-6">
                            <label for="edit_pic_phone" class="form-label text-sm fw-medium">No. Telepon / WhatsApp</label>
                            <input type="text" name="pic_phone" id="edit_pic_phone" class="form-control text-sm font-monospace">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_pic_email" class="form-label text-sm fw-medium">Alamat Email</label>
                            <input type="email" name="pic_email" id="edit_pic_email" class="form-control text-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-20">
                    <button type="button" class="btn btn-secondary text-sm btn-sm px-16 py-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnUpdate" class="btn btn-primary text-sm btn-sm px-16 py-8">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
