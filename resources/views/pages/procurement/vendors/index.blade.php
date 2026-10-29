@extends('layout.layout')

@php
    $title = 'Vendor Management & Rekanan Pengadaan';
    $subTitle = 'Database Supplier, Fabrikator, Subkontraktor & Mitra Kerja Terverifikasi';
    $script = '<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Edit Vendor Modal Populate
            const editVendorModal = document.getElementById("editVendorModal");
            if (editVendorModal) {
                editVendorModal.addEventListener("show.bs.modal", function(event) {
                    const button = event.relatedTarget;
                    const vendor = JSON.parse(button.getAttribute("data-vendor"));
                    const form = editVendorModal.querySelector("form");
                    form.action = "/dashboard-admin/procurement/vendors/" + vendor.id;

                    editVendorModal.querySelector("#edit_name").value = vendor.name || "";
                    editVendorModal.querySelector("#edit_category").value = vendor.category || "";
                    editVendorModal.querySelector("#edit_contact_person").value = vendor.contact_person || "";
                    editVendorModal.querySelector("#edit_phone").value = vendor.phone || "";
                    editVendorModal.querySelector("#edit_email").value = vendor.email || "";
                    editVendorModal.querySelector("#edit_city").value = vendor.city || "";
                    editVendorModal.querySelector("#edit_address").value = vendor.address || "";
                    editVendorModal.querySelector("#edit_npwp").value = vendor.npwp || "";
                    editVendorModal.querySelector("#edit_bank_name").value = vendor.bank_name || "";
                    editVendorModal.querySelector("#edit_bank_account_number").value = vendor.bank_account_number || "";
                    editVendorModal.querySelector("#edit_bank_account_holder").value = vendor.bank_account_holder || "";
                    editVendorModal.querySelector("#edit_payment_terms").value = vendor.payment_terms || "Net 30 Days";
                    editVendorModal.querySelector("#edit_rating").value = vendor.rating || 5;
                    editVendorModal.querySelector("#edit_status").value = vendor.status || "active";
                    editVendorModal.querySelector("#edit_notes").value = vendor.notes || "";
                });
            }

            // View Detail Vendor Modal Populate
            const viewVendorModal = document.getElementById("viewVendorModal");
            if (viewVendorModal) {
                viewVendorModal.addEventListener("show.bs.modal", function(event) {
                    const button = event.relatedTarget;
                    const vendor = JSON.parse(button.getAttribute("data-vendor"));

                    viewVendorModal.querySelector("#detail_vendor_title").textContent = vendor.name + " (" + vendor.vendor_code + ")";
                    viewVendorModal.querySelector("#detail_vendor_category").textContent = vendor.category;
                    viewVendorModal.querySelector("#detail_contact_person").textContent = vendor.contact_person || "-";
                    viewVendorModal.querySelector("#detail_phone").textContent = vendor.phone || "-";
                    viewVendorModal.querySelector("#detail_email").textContent = vendor.email || "-";
                    viewVendorModal.querySelector("#detail_city").textContent = vendor.city || "-";
                    viewVendorModal.querySelector("#detail_address").textContent = vendor.address || "-";
                    viewVendorModal.querySelector("#detail_npwp").textContent = vendor.npwp || "-";
                    viewVendorModal.querySelector("#detail_bank").textContent = (vendor.bank_name || "-") + " - " + (vendor.bank_account_number || "-") + " a/n " + (vendor.bank_account_holder || "-");
                    viewVendorModal.querySelector("#detail_payment_terms").textContent = vendor.payment_terms;
                    viewVendorModal.querySelector("#detail_notes").textContent = vendor.notes || "Tidak ada catatan khusus.";

                    let stars = "";
                    for(let i=0; i<vendor.rating; i++) { stars += "★"; }
                    viewVendorModal.querySelector("#detail_rating").textContent = stars;

                    const statusBadge = viewVendorModal.querySelector("#detail_status_badge");
                    if (vendor.status === "active") {
                        statusBadge.className = "badge bg-success-50 text-success-700 border border-success-200";
                        statusBadge.textContent = "Aktif Terverifikasi";
                    } else if (vendor.status === "under_review") {
                        statusBadge.className = "badge bg-warning-50 text-warning-700 border border-warning-200";
                        statusBadge.textContent = "Under Review";
                    } else {
                        statusBadge.className = "badge bg-danger-50 text-danger-700 border border-danger-200";
                        statusBadge.textContent = "Blacklisted";
                    }
                });
            }
        });
    </script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0 text-primary-light">{{ $title }}</h6>
        <p class="text-xs text-secondary-light mb-0">{{ $subTitle }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-sm btn-primary-600 radius-8 px-14 py-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#createVendorModal">
            <iconify-icon icon="solar:user-plus-bold-duotone"></iconify-icon>
            Tambah Rekanan Baru
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:danger-circle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- 4 METRIC CARDS --}}
<div class="row g-3 mb-24">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Total Rekanan Vendor</span>
                    <h5 class="fw-bold mb-0 text-primary-light">{{ $counts['all'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-xl">
                    <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Aktif & Terverifikasi</span>
                    <h5 class="fw-bold mb-0 text-success-main">{{ $counts['active'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-main text-xl">
                    <iconify-icon icon="solar:check-read-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Dalam Penilaian / Review</span>
                    <h5 class="fw-bold mb-0 text-warning-main">{{ $counts['under_review'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-50 text-warning-main text-xl">
                    <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Blacklisted / Ditangguhkan</span>
                    <h5 class="fw-bold mb-0 text-danger-main">{{ $counts['blacklisted'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-danger-50 text-danger-main text-xl">
                    <iconify-icon icon="solar:shield-warning-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER & SEARCH ROW --}}
<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.procurement.vendors.index') }}" class="d-flex flex-wrap align-items-end justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Kategori Spesialisasi</label>
                    <select name="category" class="form-select form-select-sm radius-8 text-xs pe-28">
                        <option value="all">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Status Kualifikasi</label>
                    <select name="status" class="form-select form-select-sm radius-8 text-xs pe-28">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="under_review" {{ $statusFilter === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="blacklisted" {{ $statusFilter === 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Cari Vendor / Rekanan</label>
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control form-control-sm radius-8 text-xs pe-32" placeholder="Nama, kode, PIC, kota..." value="{{ $search }}">
                        <iconify-icon icon="solar:magnifer-outline" class="position-absolute top-50 end-0 translate-middle-y me-10 text-secondary-light"></iconify-icon>
                    </div>
                </div>
                <div class="pt-18">
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-14 text-xs">Filter</button>
                    @if($statusFilter !== 'all' || $categoryFilter !== 'all' || $search)
                        <a href="{{ route('dashboard-admin.procurement.vendors.index') }}" class="btn btn-sm btn-outline-secondary radius-8 px-12 text-xs">Reset</a>
                    @endif
                </div>
            </div>
            <div class="text-xs text-secondary-light">
                Menampilkan <span class="fw-semibold text-primary-light">{{ $vendors->count() }}</span> dari <span class="fw-semibold text-primary-light">{{ $vendors->total() }}</span> vendor
            </div>
        </form>
    </div>
</div>

{{-- TABLE VENDORS --}}
<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-header bg-transparent py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Daftar Rekanan Vendor Resmi</h6>
            <span class="text-xs text-secondary-light">Data Terintegrasi Pengadaan PR, PO, dan Penagihan AP Finance</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th>Kode & Nama Perusahaan</th>
                        <th>Kategori</th>
                        <th>PIC & Kontak</th>
                        <th>Rekening Bank (AP)</th>
                        <th>Terms & Rating</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($vendors as $v)
                        <tr>
                            <td>
                                <span class="fw-bold text-primary-600 d-block">{{ $v->vendor_code }}</span>
                                <span class="fw-semibold text-primary-light">{{ $v->name }}</span>
                                @if($v->city)
                                    <span class="d-block text-secondary-light text-xxs"><iconify-icon icon="solar:map-point-outline"></iconify-icon> {{ $v->city }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-neutral-100 text-neutral-700 border border-neutral-200">{{ $v->category }}</span>
                                <span class="d-block text-xxs text-secondary-light mt-1">{{ $v->purchase_orders_count }} Transaksi PO</span>
                            </td>
                            <td>
                                <div class="fw-medium text-primary-light">{{ $v->contact_person ?? '-' }}</div>
                                <span class="text-secondary-light d-block">{{ $v->phone ?? '-' }}</span>
                                <span class="text-secondary-light d-block text-xxs">{{ $v->email ?? '-' }}</span>
                            </td>
                            <td>
                                @if($v->bank_name)
                                    <span class="fw-medium text-primary-light d-block">{{ $v->bank_name }}</span>
                                    <span class="text-secondary-light font-monospace">{{ $v->bank_account_number }}</span>
                                    <span class="d-block text-xxs text-secondary-light">a/n {{ $v->bank_account_holder }}</span>
                                @else
                                    <span class="text-secondary-light">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-medium text-neutral-800 d-block">{{ $v->payment_terms }}</span>
                                <div class="text-warning-main text-xs mt-1">
                                    @for ($i = 1; $i <= $v->rating; $i++) ★ @endfor
                                </div>
                            </td>
                            <td>
                                @if($v->status === 'active')
                                    <span class="badge bg-success-50 text-success-700 border border-success-200">Aktif</span>
                                @elseif($v->status === 'under_review')
                                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200">Under Review</span>
                                @else
                                    <span class="badge bg-danger-50 text-danger-700 border border-danger-200">Blacklisted</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <button type="button" class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center border-0" data-bs-toggle="modal" data-bs-target="#viewVendorModal" data-vendor="{{ json_encode($v) }}" title="Lihat Profil">
                                        <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                    </button>
                                    <button type="button" class="w-32-px h-32-px rounded-circle bg-success-focus text-success-main d-inline-flex justify-content-center align-items-center border-0" data-bs-toggle="modal" data-bs-target="#editVendorModal" data-vendor="{{ json_encode($v) }}" title="Edit Vendor">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </button>
                                    <form action="{{ route('dashboard-admin.procurement.vendors.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data rekanan ini?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus Vendor">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-24 text-secondary-light">
                                Tidak ada data vendor yang cocok dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-16 border-top">
            {{ $vendors->links() }}
        </div>
    </div>
</div>

{{-- MODAL TAMBAH VENDOR --}}
<div class="modal fade" id="createVendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light">Daftarkan Rekanan Vendor Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.procurement.vendors.store') }}" method="POST">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Kode Vendor <span class="text-danger">*</span></label>
                            <input type="text" name="vendor_code" class="form-control form-control-sm radius-8" value="{{ $nextCode }}" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nama Perusahaan Vendor <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm radius-8" placeholder="Contoh: PT Valve & Piping Nusantara" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Kategori Spesialisasi <span class="text-danger">*</span></label>
                            <select name="category" class="form-select form-select-sm radius-8" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nama PIC (Contact Person)</label>
                            <input type="text" name="contact_person" class="form-control form-control-sm radius-8" placeholder="Nama sales/marketing PIC">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" class="form-control form-control-sm radius-8" placeholder="0812-xxxx-xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Alamat Email Resmi</label>
                            <input type="email" name="email" class="form-control form-control-sm radius-8" placeholder="sales@perusahaan.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Kota Operasional</label>
                            <input type="text" name="city" class="form-control form-control-sm radius-8" placeholder="Jakarta / Balikpapan / Bontang">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">NPWP Perusahaan</label>
                            <input type="text" name="npwp" class="form-control form-control-sm radius-8" placeholder="xx.xxx.xxx.x-xxx.xxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Alamat Kantor / Workshop Lengkap</label>
                            <textarea name="address" class="form-control form-control-sm radius-8" rows="2" placeholder="Alamat pabrik / gudang utama..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                                <h6 class="fs-6 fw-semibold text-primary-light mb-2">Informasi Pembayaran (Finance AP)</h6>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label text-xxs text-secondary-light mb-1">Nama Bank</label>
                                        <input type="text" name="bank_name" class="form-control form-control-sm radius-8" placeholder="Bank Mandiri / BCA / BNI">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-xxs text-secondary-light mb-1">Nomor Rekening</label>
                                        <input type="text" name="bank_account_number" class="form-control form-control-sm radius-8" placeholder="123-xx-xxxxxx-x">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-xxs text-secondary-light mb-1">Nama Pemilik Rekening</label>
                                        <input type="text" name="bank_account_holder" class="form-control form-control-sm radius-8" placeholder="PT xxxxx">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Term of Payment</label>
                            <select name="payment_terms" class="form-select form-select-sm radius-8">
                                <option value="Net 30 Days">Net 30 Days</option>
                                <option value="Net 14 Days">Net 14 Days</option>
                                <option value="Net 60 Days">Net 60 Days</option>
                                <option value="COD (Cash on Delivery)">COD (Cash on Delivery)</option>
                                <option value="50% DP, 50% On Delivery">50% DP, 50% On Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Rating Kinerja (1-5)</label>
                            <select name="rating" class="form-select form-select-sm radius-8">
                                <option value="5">★★★★★ (5 - Sangat Baik)</option>
                                <option value="4">★★★★☆ (4 - Baik)</option>
                                <option value="3">★★★☆☆ (3 - Cukup)</option>
                                <option value="2">★★☆☆☆ (2 - Kurang)</option>
                                <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Status Kualifikasi</label>
                            <select name="status" class="form-select form-select-sm radius-8">
                                <option value="active">Aktif Terverifikasi</option>
                                <option value="under_review">Dalam Penilaian</option>
                                <option value="blacklisted">Blacklisted</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Catatan Kualifikasi & Sertifikasi</label>
                            <textarea name="notes" class="form-control form-control-sm radius-8" rows="2" placeholder="Catatan sertifikat ASME, ISO, atau cakupan layanan..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-16 px-24 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8 px-14" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-16">Simpan Rekanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT VENDOR --}}
<div class="modal fade" id="editVendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light">Edit Data Rekanan Vendor</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nama Perusahaan Vendor <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control form-control-sm radius-8" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Kategori Spesialisasi <span class="text-danger">*</span></label>
                            <select name="category" id="edit_category" class="form-select form-select-sm radius-8" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nama PIC (Contact Person)</label>
                            <input type="text" name="contact_person" id="edit_contact_person" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Alamat Email Resmi</label>
                            <input type="email" name="email" id="edit_email" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Kota Operasional</label>
                            <input type="text" name="city" id="edit_city" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">NPWP Perusahaan</label>
                            <input type="text" name="npwp" id="edit_npwp" class="form-control form-control-sm radius-8">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Alamat Kantor / Workshop Lengkap</label>
                            <textarea name="address" id="edit_address" class="form-control form-control-sm radius-8" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                                <h6 class="fs-6 fw-semibold text-primary-light mb-2">Informasi Pembayaran (Finance AP)</h6>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label text-xxs text-secondary-light mb-1">Nama Bank</label>
                                        <input type="text" name="bank_name" id="edit_bank_name" class="form-control form-control-sm radius-8">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-xxs text-secondary-light mb-1">Nomor Rekening</label>
                                        <input type="text" name="bank_account_number" id="edit_bank_account_number" class="form-control form-control-sm radius-8">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-xxs text-secondary-light mb-1">Nama Pemilik Rekening</label>
                                        <input type="text" name="bank_account_holder" id="edit_bank_account_holder" class="form-control form-control-sm radius-8">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Term of Payment</label>
                            <select name="payment_terms" id="edit_payment_terms" class="form-select form-select-sm radius-8">
                                <option value="Net 30 Days">Net 30 Days</option>
                                <option value="Net 14 Days">Net 14 Days</option>
                                <option value="Net 60 Days">Net 60 Days</option>
                                <option value="COD (Cash on Delivery)">COD (Cash on Delivery)</option>
                                <option value="50% DP, 50% On Delivery">50% DP, 50% On Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Rating Kinerja (1-5)</label>
                            <select name="rating" id="edit_rating" class="form-select form-select-sm radius-8">
                                <option value="5">★★★★★ (5)</option>
                                <option value="4">★★★★☆ (4)</option>
                                <option value="3">★★★☆☆ (3)</option>
                                <option value="2">★★☆☆☆ (2)</option>
                                <option value="1">★☆☆☆☆ (1)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Status Kualifikasi</label>
                            <select name="status" id="edit_status" class="form-select form-select-sm radius-8">
                                <option value="active">Aktif Terverifikasi</option>
                                <option value="under_review">Dalam Penilaian</option>
                                <option value="blacklisted">Blacklisted</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Catatan Kualifikasi</label>
                            <textarea name="notes" id="edit_notes" class="form-control form-control-sm radius-8" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-16 px-24 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8 px-14" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-16">Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL 360 VENDOR --}}
<div class="modal fade" id="viewVendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <div>
                    <h6 class="modal-title fs-6 fw-semibold text-primary-light mb-0" id="detail_vendor_title">Detail Vendor</h6>
                    <span class="text-xs text-secondary-light" id="detail_vendor_category">Kategori</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                            <span class="text-xxs text-secondary-light d-block mb-1">Status Rekanan</span>
                            <span id="detail_status_badge" class="badge">Status</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                            <span class="text-xxs text-secondary-light d-block mb-1">Rating Evaluasi Kinerja</span>
                            <span id="detail_rating" class="text-warning-main fs-6 fw-bold">★★★★★</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-xxs text-secondary-light d-block mb-1">PIC (Kontak Utama)</span>
                        <div class="fw-semibold text-primary-light text-xs" id="detail_contact_person">-</div>
                        <div class="text-xs text-secondary-light" id="detail_phone">-</div>
                        <div class="text-xs text-secondary-light" id="detail_email">-</div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-xxs text-secondary-light d-block mb-1">NPWP & Term of Payment</span>
                        <div class="fw-medium text-primary-light text-xs">NPWP: <span id="detail_npwp" class="font-monospace">-</span></div>
                        <div class="text-xs text-secondary-light mt-1">Syarat Bayar: <span id="detail_payment_terms" class="fw-semibold text-primary-light">-</span></div>
                    </div>
                    <div class="col-12">
                        <span class="text-xxs text-secondary-light d-block mb-1">Alamat Kantor & Fasilitas Workshop</span>
                        <div class="text-xs text-primary-light" id="detail_address">-</div>
                        <div class="text-xxs text-secondary-light mt-1">Kota: <span id="detail_city">-</span></div>
                    </div>
                    <div class="col-12">
                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                            <span class="text-xxs text-secondary-light d-block mb-1">Rekening Pembayaran Finance AP</span>
                            <div class="fw-semibold text-primary-light text-xs" id="detail_bank">-</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <span class="text-xxs text-secondary-light d-block mb-1">Catatan Kualifikasi & Sertifikat</span>
                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200 text-xs text-secondary-light" id="detail_notes">-</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-16 px-24 border-top">
                <button type="button" class="btn btn-sm btn-outline-secondary radius-8 px-16" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
