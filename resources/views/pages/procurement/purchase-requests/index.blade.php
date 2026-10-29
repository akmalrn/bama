@extends('layout.layout')

@php
    $title = 'Purchase Request (PR) Register';
    $subTitle = 'Permintaan Pengadaan Material, Alat & Jasa Lapangan Proyek PMO';
    $script = '<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Dynamic PR Items Row in Create Modal
            let itemIndex = 1;
            const itemsContainer = document.getElementById("prItemsContainer");
            const btnAddItem = document.getElementById("btnAddPRItem");

            function updatePRTotal() {
                let total = 0;
                document.querySelectorAll(".pr-item-row").forEach(function(row) {
                    const qty = parseFloat(row.querySelector(".item-qty").value) || 0;
                    const price = parseFloat(row.querySelector(".item-price").value) || 0;
                    const subtotal = qty * price;
                    row.querySelector(".item-subtotal").textContent = "Rp " + new Intl.NumberFormat("id-ID").format(subtotal);
                    total += subtotal;
                });
                document.getElementById("prGrandTotal").textContent = "Rp " + new Intl.NumberFormat("id-ID").format(total);
            }

            if (btnAddItem && itemsContainer) {
                btnAddItem.addEventListener("click", function() {
                    const row = document.createElement("tr");
                    row.className = "pr-item-row align-middle";
                    row.innerHTML = `
                        <td>
                            <input type="text" name="items[${itemIndex}][item_name]" class="form-control form-control-sm radius-6 text-xs" placeholder="Nama barang/jasa" required>
                            <input type="text" name="items[${itemIndex}][specifications]" class="form-control form-control-sm radius-6 text-xxs mt-1" placeholder="Spek/standar (ASTM/ASME)">
                        </td>
                        <td style="width: 100px;">
                            <input type="number" step="0.01" name="items[${itemIndex}][qty]" class="form-control form-control-sm radius-6 text-xs item-qty" value="1" required>
                        </td>
                        <td style="width: 90px;">
                            <input type="text" name="items[${itemIndex}][unit]" class="form-control form-control-sm radius-6 text-xs" value="Pcs" required>
                        </td>
                        <td style="width: 160px;">
                            <input type="number" step="1000" name="items[${itemIndex}][estimated_unit_price]" class="form-control form-control-sm radius-6 text-xs item-price" value="0" required>
                        </td>
                        <td style="width: 140px;" class="text-end fw-semibold text-xs item-subtotal">Rp 0</td>
                        <td style="width: 40px;" class="text-center">
                            <button type="button" class="w-28-px h-28-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center btn-remove-item" title="Hapus Item"><iconify-icon icon="mingcute:delete-2-line"></iconify-icon></button>
                        </td>
                    `;
                    itemsContainer.appendChild(row);
                    itemIndex++;

                    row.querySelector(".btn-remove-item").addEventListener("click", function() {
                        row.remove();
                        updatePRTotal();
                    });

                    row.querySelectorAll(".item-qty, .item-price").forEach(el => el.addEventListener("input", updatePRTotal));
                });

                document.querySelectorAll(".pr-item-row .item-qty, .pr-item-row .item-price").forEach(el => el.addEventListener("input", updatePRTotal));
                document.querySelectorAll(".pr-item-row .btn-remove-item").forEach(btn => {
                    btn.addEventListener("click", function() {
                        btn.closest("tr").remove();
                        updatePRTotal();
                    });
                });
            }

            // Modal Review / Approval PR
            const reviewPRModal = document.getElementById("reviewPRModal");
            if (reviewPRModal) {
                reviewPRModal.addEventListener("show.bs.modal", function(event) {
                    const button = event.relatedTarget;
                    const pr = JSON.parse(button.getAttribute("data-pr"));
                    const form = reviewPRModal.querySelector("form");
                    form.action = "/dashboard-admin/procurement/purchase-requests/" + pr.id + "/status";

                    reviewPRModal.querySelector("#review_pr_number").textContent = pr.pr_number;
                    reviewPRModal.querySelector("#review_pr_title").textContent = pr.title;
                    reviewPRModal.querySelector("#review_status").value = pr.status;
                    reviewPRModal.querySelector("#review_approval_notes").value = pr.approval_notes || "";
                });
            }

            // Modal Detail PR
            const viewPRModal = document.getElementById("viewPRModal");
            if (viewPRModal) {
                viewPRModal.addEventListener("show.bs.modal", function(event) {
                    const button = event.relatedTarget;
                    const pr = JSON.parse(button.getAttribute("data-pr"));

                    viewPRModal.querySelector("#detail_pr_number").textContent = pr.pr_number;
                    viewPRModal.querySelector("#detail_pr_title").textContent = pr.title;
                    viewPRModal.querySelector("#detail_pr_category").textContent = pr.category;
                    viewPRModal.querySelector("#detail_project_title").textContent = pr.project ? pr.project.title : "Kebutuhan Umum / Workshop";
                    viewPRModal.querySelector("#detail_request_date").textContent = pr.request_date || "-";
                    viewPRModal.querySelector("#detail_required_date").textContent = pr.required_date || "-";
                    viewPRModal.querySelector("#detail_justification").textContent = pr.justification || "-";
                    viewPRModal.querySelector("#detail_approval_notes").textContent = pr.approval_notes || "Belum ada catatan persetujuan.";
                    viewPRModal.querySelector("#detail_total").textContent = "Rp " + new Intl.NumberFormat("id-ID").format(pr.estimated_total);

                    const tbody = viewPRModal.querySelector("#detail_items_tbody");
                    tbody.innerHTML = "";
                    if (pr.items && pr.items.length > 0) {
                        pr.items.forEach(function(item, idx) {
                            const tr = document.createElement("tr");
                            tr.innerHTML = `
                                <td>${idx + 1}</td>
                                <td>
                                    <div class="fw-semibold text-primary-light">${item.item_name}</div>
                                    <span class="text-secondary-light text-xxs">${item.specifications || "-"}</span>
                                </td>
                                <td>${item.qty} ${item.unit}</td>
                                <td class="text-end">Rp ${new Intl.NumberFormat("id-ID").format(item.estimated_unit_price)}</td>
                                <td class="text-end fw-bold text-primary-600">Rp ${new Intl.NumberFormat("id-ID").format(item.estimated_total_price)}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML = "<tr><td colspan=\"5\" class=\"text-center text-secondary-light py-12\">Tidak ada item rincian.</td></tr>";
                    }

                    const poLink = viewPRModal.querySelector("#detail_convert_po_btn");
                    if (pr.status === "approved" && !pr.purchase_order) {
                        poLink.classList.remove("d-none");
                        poLink.href = "/dashboard-admin/procurement/purchase-orders?convert_pr_id=" + pr.id;
                    } else {
                        poLink.classList.add("d-none");
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
        <button type="button" class="btn btn-sm btn-primary-600 radius-8 px-14 py-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#createPRModal">
            <iconify-icon icon="solar:document-add-bold-duotone"></iconify-icon>
            Ajukan Purchase Request (PR)
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
                    <span class="text-xs text-secondary-light d-block mb-1">Total PR Diajukan</span>
                    <h5 class="fw-bold mb-0 text-primary-light">{{ $counts['all'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-xl">
                    <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Menunggu Review / Approval</span>
                    <h5 class="fw-bold mb-0 text-warning-main">{{ $counts['pending_approval'] }}</h5>
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
                    <span class="text-xs text-secondary-light d-block mb-1">Disetujui (Siap Diterbitkan PO)</span>
                    <h5 class="fw-bold mb-0 text-success-main">{{ $counts['approved'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-main text-xl">
                    <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Telah Dikonversi ke PO</span>
                    <h5 class="fw-bold mb-0 text-info-main">{{ $counts['converted_to_po'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-info-50 text-info-main text-xl">
                    <iconify-icon icon="solar:cart-check-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER ROW --}}
<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.procurement.purchase-requests.index') }}" class="d-flex flex-wrap align-items-end justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Status PR</label>
                    <select name="status" class="form-select form-select-sm radius-8 text-xs pe-28">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending_approval" {{ $statusFilter === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="converted_to_po" {{ $statusFilter === 'converted_to_po' ? 'selected' : '' }}>Converted to PO</option>
                        <option value="draft" {{ $statusFilter === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Prioritas</label>
                    <select name="priority" class="form-select form-select-sm radius-8 text-xs pe-28">
                        <option value="all" {{ $priorityFilter === 'all' ? 'selected' : '' }}>Semua Prioritas</option>
                        <option value="urgent" {{ $priorityFilter === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="high" {{ $priorityFilter === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ $priorityFilter === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ $priorityFilter === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Proyek Terkait</label>
                    <select name="project_id" class="form-select form-select-sm radius-8 text-xs pe-28">
                        <option value="all">Semua Proyek</option>
                        @foreach ($projects as $proj)
                            <option value="{{ $proj->id }}" {{ $projectFilter == $proj->id ? 'selected' : '' }}>{{ Str::limit($proj->title, 25) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Pencarian</label>
                    <div class="position-relative">
                        <input type="text" name="search" class="form-control form-control-sm radius-8 text-xs pe-32" placeholder="Nomor PR, judul, justifikasi..." value="{{ $search }}">
                        <iconify-icon icon="solar:magnifer-outline" class="position-absolute top-50 end-0 translate-middle-y me-10 text-secondary-light"></iconify-icon>
                    </div>
                </div>
                <div class="pt-18">
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-14 text-xs">Filter</button>
                    @if($statusFilter !== 'all' || $priorityFilter !== 'all' || $projectFilter !== 'all' || $search)
                        <a href="{{ route('dashboard-admin.procurement.purchase-requests.index') }}" class="btn btn-sm btn-outline-secondary radius-8 px-12 text-xs">Reset</a>
                    @endif
                </div>
            </div>
            <div class="text-xs text-secondary-light">
                Menampilkan <span class="fw-semibold text-primary-light">{{ $purchaseRequests->count() }}</span> dari <span class="fw-semibold text-primary-light">{{ $purchaseRequests->total() }}</span> PR
            </div>
        </form>
    </div>
</div>

{{-- TABLE PURCHASE REQUESTS --}}
<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-header bg-transparent py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Daftar Pengajuan Purchase Request (PR)</h6>
            <span class="text-xs text-secondary-light">Alur Pengadaan Material & Jasa untuk Proyek Turnaround Migas</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th>No. PR & Tanggal</th>
                        <th>Judul Kebutuhan</th>
                        <th>Proyek PMO</th>
                        <th>Pemohon & Approver</th>
                        <th>Estimasi Nilai</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($purchaseRequests as $pr)
                        <tr>
                            <td>
                                <span class="fw-bold text-primary-600 d-block">{{ $pr->pr_number }}</span>
                                <span class="text-secondary-light text-xxs">Tgl: {{ $pr->request_date?->format('d M Y') }}</span>
                                <span class="d-block text-xxs text-secondary-light">Butuh: {{ $pr->required_date?->format('d M Y') }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-primary-light">{{ $pr->title }}</div>
                                <span class="badge bg-neutral-100 text-neutral-700 border border-neutral-200 mt-1">{{ $pr->category }}</span>
                                <span class="text-secondary-light text-xxs ms-1">&bull; {{ $pr->items->count() }} Item Rincian</span>
                            </td>
                            <td>
                                @if($pr->project)
                                    <span class="fw-medium text-primary-light d-block">{{ Str::limit($pr->project->title, 35) }}</span>
                                    <span class="text-secondary-light text-xxs">{{ $pr->project->project_code }}</span>
                                @else
                                    <span class="text-secondary-light">Umum / Workshop</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium text-primary-light">{{ $pr->requestedBy?->user?->name ?? 'Staf Operasional' }}</div>
                                <span class="text-secondary-light text-xxs d-block">Appr: {{ $pr->approvedBy?->user?->name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-neutral-800 d-block">Rp {{ number_format($pr->estimated_total, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($pr->priority === 'urgent')
                                    <span class="badge bg-danger-100 text-danger-700 border border-danger-200">Urgent</span>
                                @elseif($pr->priority === 'high')
                                    <span class="badge bg-warning-100 text-warning-800 border border-warning-200">High</span>
                                @elseif($pr->priority === 'medium')
                                    <span class="badge bg-info-100 text-info-700 border border-info-200">Medium</span>
                                @else
                                    <span class="badge bg-neutral-100 text-neutral-600 border border-neutral-200">Low</span>
                                @endif
                            </td>
                            <td>
                                @if($pr->status === 'approved')
                                    <span class="badge bg-success-50 text-success-700 border border-success-200">Disetujui</span>
                                @elseif($pr->status === 'converted_to_po')
                                    <span class="badge bg-info-50 text-info-700 border border-info-200">PO Terbit</span>
                                @elseif($pr->status === 'pending_approval')
                                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200">Pending Review</span>
                                @elseif($pr->status === 'draft')
                                    <span class="badge bg-neutral-50 text-neutral-600 border border-neutral-200">Draft</span>
                                @else
                                    <span class="badge bg-danger-50 text-danger-700 border border-danger-200">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <button type="button" class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center border-0" data-bs-toggle="modal" data-bs-target="#viewPRModal" data-pr="{{ json_encode($pr->load(['items', 'project'])) }}" title="Lihat Detail & Item">
                                        <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                    </button>
                                    @can('procurement.pr.approve')
                                        <button type="button" class="w-32-px h-32-px rounded-circle bg-warning-focus text-warning-main d-inline-flex justify-content-center align-items-center border-0" data-bs-toggle="modal" data-bs-target="#reviewPRModal" data-pr="{{ json_encode($pr) }}" title="Review / Approval">
                                            <iconify-icon icon="solar:check-square-outline"></iconify-icon>
                                        </button>
                                    @endcan
                                    @can('procurement.pr.create')
                                        @if($pr->status !== 'converted_to_po')
                                            <form action="{{ route('dashboard-admin.procurement.purchase-requests.destroy', $pr->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus PR ini?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus PR">
                                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-24 text-secondary-light">
                                Tidak ada data Purchase Request yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-16 border-top">
            {{ $purchaseRequests->links() }}
        </div>
    </div>
</div>

{{-- MODAL TAMBAH PR DENGAN DYNAMIC ITEM ROW --}}
<div class="modal fade" id="createPRModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light">Buat Pengajuan Purchase Request (PR) Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.procurement.purchase-requests.store') }}" method="POST">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3 mb-20">
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nomor PR <span class="text-danger">*</span></label>
                            <input type="text" name="pr_number" class="form-control form-control-sm radius-8" value="{{ $nextPRNumber }}" required>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label text-xs fw-semibold text-primary-light">Judul Kebutuhan Pengadaan <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm radius-8" placeholder="Contoh: Pengadaan Stud Bolt B7 & Spiral Wound Gasket Turnaround" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Proyek PMO Terkait</label>
                            <select name="project_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Kebutuhan Umum / Workshop --</option>
                                @foreach ($projects as $proj)
                                    <option value="{{ $proj->id }}">{{ $proj->title }} ({{ $proj->project_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Pemohon (Requester Personel)</label>
                            <select name="requested_by" class="form-select form-select-sm radius-8">
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->user?->name ?? 'Employee #' . $emp->id }} - {{ $emp->employee_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Kategori Kebutuhan <span class="text-danger">*</span></label>
                            <select name="category" class="form-select form-select-sm radius-8" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Tanggal Pengajuan <span class="text-danger">*</span></label>
                            <input type="date" name="request_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Batas Waktu Dibutuhkan (Deadline) <span class="text-danger">*</span></label>
                            <input type="date" name="required_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Tingkat Prioritas <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select form-select-sm radius-8" required>
                                <option value="urgent">Urgent (Kritis / Shutdown)</option>
                                <option value="high">High (Tinggi)</option>
                                <option value="medium" selected>Medium (Normal)</option>
                                <option value="low">Low (Rendah / Rutin)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Status Pengajuan</label>
                            <select name="status" class="form-select form-select-sm radius-8" required>
                                <option value="pending_approval" selected>Kirim untuk Review (Pending)</option>
                                <option value="draft">Simpan sebagai Draft</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Justifikasi & Tujuan Pengadaan <span class="text-danger">*</span></label>
                            <textarea name="justification" class="form-control form-control-sm radius-8" rows="2" placeholder="Jelaskan urgensi, area pemasangan, dan alasan kebutuhan material ini..." required></textarea>
                        </div>
                    </div>

                    {{-- Dynamic Line Items --}}
                    <div class="border radius-12 p-16 bg-neutral-50">
                        <div class="d-flex align-items-center justify-content-between mb-12">
                            <div>
                                <h6 class="fs-6 fw-semibold text-primary-light mb-0">Rincian Barang / Jasa yang Diminta</h6>
                                <span class="text-xxs text-secondary-light">Tentukan nama spesifikasi material, jumlah unit, dan estimasi harga satuan</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary radius-8 px-12 py-6 text-xs d-flex align-items-center gap-1" id="btnAddPRItem">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Tambah Baris Item
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table bordered-table mb-0">
                                <thead class="text-xxs text-secondary-light text-uppercase bg-light">
                                    <tr>
                                        <th>Nama Barang & Spesifikasi Teknis</th>
                                        <th style="width: 100px;">Qty</th>
                                        <th style="width: 90px;">Satuan</th>
                                        <th style="width: 160px;">Est. Harga Satuan</th>
                                        <th style="width: 140px;" class="text-end">Subtotal Est.</th>
                                        <th style="width: 40px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="prItemsContainer">
                                    <tr class="pr-item-row align-middle">
                                        <td>
                                            <input type="text" name="items[0][item_name]" class="form-control form-control-sm radius-6 text-xs" placeholder="Contoh: Stud Bolt B7 1x7 inch" required>
                                            <input type="text" name="items[0][specifications]" class="form-control form-control-sm radius-6 text-xxs mt-1" placeholder="Spek/standar ASTM/ASME/Material Certificate">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[0][qty]" class="form-control form-control-sm radius-6 text-xs item-qty" value="1" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][unit]" class="form-control form-control-sm radius-6 text-xs" value="Pcs" required>
                                        </td>
                                        <td>
                                            <input type="number" step="1000" name="items[0][estimated_unit_price]" class="form-control form-control-sm radius-6 text-xs item-price" value="0" required>
                                        </td>
                                        <td class="text-end fw-semibold text-xs item-subtotal">Rp 0</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-secondary radius-6" disabled><iconify-icon icon="solar:lock-outline"></iconify-icon></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-neutral-50">
                                        <td colspan="4" class="text-end fw-bold text-xs text-primary-light">Total Estimasi Anggaran PR:</td>
                                        <td class="text-end fw-bold text-sm text-primary-600" id="prGrandTotal">Rp 0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-16 px-24 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8 px-14" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-16">Kirim Purchase Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL REVIEW / APPROVAL PR --}}
<div class="modal fade" id="reviewPRModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light">Review & Persetujuan Purchase Request</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-24">
                    <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200 mb-16">
                        <span class="text-xxs text-secondary-light d-block mb-1">Nomor & Judul PR</span>
                        <div class="fw-bold text-primary-600 text-xs" id="review_pr_number">PR-xxx</div>
                        <div class="text-xs text-primary-light" id="review_pr_title">Title</div>
                    </div>
                    <div class="mb-16">
                        <label class="form-label text-xs fw-semibold text-primary-light">Keputusan Review / Status</label>
                        <select name="status" id="review_status" class="form-select form-select-sm radius-8">
                            <option value="approved">Setujui (Approved untuk Penerbitan PO)</option>
                            <option value="pending_approval">Tinjau Ulang (Pending Approval)</option>
                            <option value="rejected">Tolak Permintaan (Rejected)</option>
                            <option value="draft">Kembalikan ke Draft Pemohon</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-xs fw-semibold text-primary-light">Catatan Evaluasi / Catatan Persetujuan</label>
                        <textarea name="approval_notes" id="review_approval_notes" class="form-control form-control-sm radius-8" rows="3" placeholder="Instruksi pemilihan vendor atau catatan evaluasi anggaran..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-16 px-24 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8 px-14" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-16">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL 360 PR --}}
<div class="modal fade" id="viewPRModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <div>
                    <h6 class="modal-title fs-6 fw-semibold text-primary-light mb-0" id="detail_pr_number">PR-xxx</h6>
                    <span class="text-xs text-secondary-light" id="detail_pr_title">Judul</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <div class="row g-3 mb-20">
                    <div class="col-md-6">
                        <span class="text-xxs text-secondary-light d-block mb-1">Proyek Terkait</span>
                        <div class="fw-semibold text-primary-light text-xs" id="detail_project_title">-</div>
                        <span class="text-xxs text-secondary-light mt-1 d-block">Kategori: <span id="detail_pr_category" class="fw-medium text-primary-light">-</span></span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-xxs text-secondary-light d-block mb-1">Jadwal Permintaan</span>
                        <div class="text-xs text-primary-light">Tgl Pengajuan: <span id="detail_request_date" class="fw-medium">-</span></div>
                        <div class="text-xs text-primary-light">Deadline Lapangan: <span id="detail_required_date" class="fw-semibold text-danger-main">-</span></div>
                    </div>
                    <div class="col-12">
                        <span class="text-xxs text-secondary-light d-block mb-1">Justifikasi Kebutuhan Lapangan</span>
                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200 text-xs text-primary-light" id="detail_justification">-</div>
                    </div>
                    <div class="col-12">
                        <span class="text-xxs text-secondary-light d-block mb-1">Catatan Persetujuan (Approval Notes)</span>
                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200 text-xs text-secondary-light" id="detail_approval_notes">-</div>
                    </div>
                </div>

                <h6 class="fs-6 fw-semibold text-primary-light mb-12">Daftar Item Barang / Jasa</h6>
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 text-xs">
                        <thead class="bg-neutral-50 text-secondary-light text-uppercase text-xxs">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang / Spek</th>
                                <th>Jumlah</th>
                                <th class="text-end">Est. Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detail_items_tbody"></tbody>
                        <tfoot>
                            <tr class="bg-neutral-50">
                                <td colspan="4" class="text-end fw-bold text-xs text-primary-light">Total Estimasi:</td>
                                <td class="text-end fw-bold text-sm text-primary-600" id="detail_total">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-16 px-24 border-top d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-sm btn-outline-secondary radius-8 px-16" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="btn btn-sm btn-success radius-8 px-16 d-none" id="detail_convert_po_btn">
                    <iconify-icon icon="solar:cart-check-bold-duotone"></iconify-icon>
                    Konversi ke Purchase Order (PO)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
