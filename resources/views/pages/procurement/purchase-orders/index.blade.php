@extends('layout.layout')

@php
    $title = 'Purchase Order (PO) Register';
    $subTitle = 'Penerbitan Kontrak Pembelian Material & Jasa ke Rekanan Terverifikasi';
    $script = '<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Dynamic PO Items Row in Create Modal
            let itemIndex = 1;
            const itemsContainer = document.getElementById("poItemsContainer");
            const btnAddItem = document.getElementById("btnAddPOItem");

            function calculatePOTotals() {
                let subtotal = 0;
                document.querySelectorAll(".po-item-row").forEach(function(row) {
                    const qty = parseFloat(row.querySelector(".po-qty").value) || 0;
                    const price = parseFloat(row.querySelector(".po-price").value) || 0;
                    const itemTotal = qty * price;
                    row.querySelector(".po-item-total").textContent = "Rp " + new Intl.NumberFormat("id-ID").format(itemTotal);
                    subtotal += itemTotal;
                });

                const discount = parseFloat(document.getElementById("poDiscountInput").value) || 0;
                const taxRate = parseFloat(document.getElementById("poTaxRateInput").value) || 11;
                const taxable = Math.max(0, subtotal - discount);
                const taxAmount = Math.round(taxable * (taxRate / 100));
                const grandTotal = taxable + taxAmount;

                document.getElementById("poSubtotalDisplay").textContent = "Rp " + new Intl.NumberFormat("id-ID").format(subtotal);
                document.getElementById("poTaxDisplay").textContent = "Rp " + new Intl.NumberFormat("id-ID").format(taxAmount);
                document.getElementById("poGrandTotalDisplay").textContent = "Rp " + new Intl.NumberFormat("id-ID").format(grandTotal);
            }

            if (btnAddItem && itemsContainer) {
                btnAddItem.addEventListener("click", function() {
                    const row = document.createElement("tr");
                    row.className = "po-item-row align-middle";
                    row.innerHTML = `
                        <td>
                            <input type="text" name="items[${itemIndex}][item_name]" class="form-control form-control-sm radius-6 text-xs" placeholder="Nama barang/jasa" required>
                            <input type="text" name="items[${itemIndex}][specifications]" class="form-control form-control-sm radius-6 text-xxs mt-1" placeholder="Spek material/sertifikat">
                        </td>
                        <td style="width: 100px;">
                            <input type="number" step="0.01" name="items[${itemIndex}][qty]" class="form-control form-control-sm radius-6 text-xs po-qty" value="1" required>
                        </td>
                        <td style="width: 90px;">
                            <input type="text" name="items[${itemIndex}][unit]" class="form-control form-control-sm radius-6 text-xs" value="Pcs" required>
                        </td>
                        <td style="width: 160px;">
                            <input type="number" step="1000" name="items[${itemIndex}][unit_price]" class="form-control form-control-sm radius-6 text-xs po-price" value="0" required>
                        </td>
                        <td style="width: 140px;" class="text-end fw-semibold text-xs po-item-total">Rp 0</td>
                        <td style="width: 40px;" class="text-center">
                            <button type="button" class="w-28-px h-28-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center btn-remove-po-item" title="Hapus Item"><iconify-icon icon="mingcute:delete-2-line"></iconify-icon></button>
                        </td>
                    `;
                    itemsContainer.appendChild(row);
                    itemIndex++;

                    row.querySelector(".btn-remove-po-item").addEventListener("click", function() {
                        row.remove();
                        calculatePOTotals();
                    });

                    row.querySelectorAll(".po-qty, .po-price").forEach(el => el.addEventListener("input", calculatePOTotals));
                });

                document.querySelectorAll(".po-item-row .po-qty, .po-item-row .po-price").forEach(el => el.addEventListener("input", calculatePOTotals));
                document.querySelectorAll(".po-item-row .btn-remove-po-item").forEach(btn => {
                    btn.addEventListener("click", function() {
                        btn.closest("tr").remove();
                        calculatePOTotals();
                    });
                });

                document.getElementById("poDiscountInput")?.addEventListener("input", calculatePOTotals);
                document.getElementById("poTaxRateInput")?.addEventListener("input", calculatePOTotals);
            }

            // Auto fill items from approved PR
            const selectPR = document.getElementById("selectSourcePR");
            if (selectPR) {
                selectPR.addEventListener("change", function() {
                    const prData = this.options[this.selectedIndex].getAttribute("data-pr-json");
                    if (prData) {
                        const pr = JSON.parse(prData);
                        if (pr.project_id) {
                            document.getElementById("po_project_id").value = pr.project_id;
                        }
                        if (pr.items && pr.items.length > 0) {
                            itemsContainer.innerHTML = "";
                            pr.items.forEach(function(item, idx) {
                                const row = document.createElement("tr");
                                row.className = "po-item-row align-middle";
                                row.innerHTML = `
                                    <td>
                                        <input type="text" name="items[${idx}][item_name]" class="form-control form-control-sm radius-6 text-xs" value="${item.item_name}" required>
                                        <input type="text" name="items[${idx}][specifications]" class="form-control form-control-sm radius-6 text-xxs mt-1" value="${item.specifications || ""}">
                                    </td>
                                    <td style="width: 100px;">
                                        <input type="number" step="0.01" name="items[${idx}][qty]" class="form-control form-control-sm radius-6 text-xs po-qty" value="${item.qty}" required>
                                    </td>
                                    <td style="width: 90px;">
                                        <input type="text" name="items[${idx}][unit]" class="form-control form-control-sm radius-6 text-xs" value="${item.unit}" required>
                                    </td>
                                    <td style="width: 160px;">
                                        <input type="number" step="1000" name="items[${idx}][unit_price]" class="form-control form-control-sm radius-6 text-xs po-price" value="${item.estimated_unit_price}" required>
                                    </td>
                                    <td style="width: 140px;" class="text-end fw-semibold text-xs po-item-total">Rp 0</td>
                                    <td style="width: 40px;" class="text-center">
                                        <button type="button" class="w-28-px h-28-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center btn-remove-po-item" title="Hapus Item"><iconify-icon icon="mingcute:delete-2-line"></iconify-icon></button>
                                    </td>
                                `;
                                itemsContainer.appendChild(row);

                                row.querySelector(".btn-remove-po-item").addEventListener("click", function() {
                                    row.remove();
                                    calculatePOTotals();
                                });
                                row.querySelectorAll(".po-qty, .po-price").forEach(el => el.addEventListener("input", calculatePOTotals));
                            });
                            itemIndex = pr.items.length;
                            calculatePOTotals();
                        }
                    }
                });

                // Auto select if URL has query parameter convert_pr_id
                const urlParams = new URLSearchParams(window.location.search);
                const convertPrId = urlParams.get("convert_pr_id");
                if (convertPrId) {
                    selectPR.value = convertPrId;
                    selectPR.dispatchEvent(new Event("change"));
                    const modalEl = document.getElementById("createPOModal");
                    if (modalEl) {
                        const bsModal = new bootstrap.Modal(modalEl);
                        bsModal.show();
                    }
                }
            }

            // Modal Update PO Status
            const updatePOStatusModal = document.getElementById("updatePOStatusModal");
            if (updatePOStatusModal) {
                updatePOStatusModal.addEventListener("show.bs.modal", function(event) {
                    const button = event.relatedTarget;
                    const po = JSON.parse(button.getAttribute("data-po"));
                    const form = updatePOStatusModal.querySelector("form");
                    form.action = "/dashboard-admin/procurement/purchase-orders/" + po.id + "/status";

                    updatePOStatusModal.querySelector("#po_status_number").textContent = po.po_number;
                    updatePOStatusModal.querySelector("#po_status_vendor").textContent = po.vendor ? po.vendor.name : "-";
                    updatePOStatusModal.querySelector("#po_status_select").value = po.status;
                    updatePOStatusModal.querySelector("#po_status_notes").value = po.notes || "";
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
        <button type="button" class="btn btn-sm btn-primary-600 radius-8 px-14 py-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#createPOModal">
            <iconify-icon icon="solar:cart-check-bold-duotone"></iconify-icon>
            Terbitkan Purchase Order (PO)
        </button>
    </div>
</div>


<div class="row g-3 mb-24">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Total PO Terbit</span>
                    <h5 class="fw-bold mb-0 text-primary-light">{{ $counts['all'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-xl">
                    <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">PO Aktif Berjalan</span>
                    <h5 class="fw-bold mb-0 text-info-main">{{ $counts['issued'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-info-50 text-info-main text-xl">
                    <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Dalam Ekspedisi (In Delivery)</span>
                    <h5 class="fw-bold mb-0 text-warning-main">{{ $counts['in_delivery'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-50 text-warning-main text-xl">
                    <iconify-icon icon="solar:delivery-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Pesanan Selesai di Site</span>
                    <h5 class="fw-bold mb-0 text-success-main">{{ $counts['completed'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-main text-xl">
                    <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.procurement.purchase-orders.index') }}" class="d-flex flex-wrap align-items-end justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Status PO</label>
                    <select name="status" class="form-select form-select-sm radius-8 text-xs pe-28">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="issued" {{ $statusFilter === 'issued' ? 'selected' : '' }}>Issued (Terkirim)</option>
                        <option value="in_delivery" {{ $statusFilter === 'in_delivery' ? 'selected' : '' }}>In Delivery</option>
                        <option value="completed" {{ $statusFilter === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                        <option value="draft" {{ $statusFilter === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="cancelled" {{ $statusFilter === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Mitra Vendor</label>
                    <select name="vendor_id" class="form-select form-select-sm radius-8 text-xs pe-28">
                        <option value="all">Semua Vendor</option>
                        @foreach ($vendors as $ven)
                            <option value="{{ $ven->id }}" {{ $vendorFilter == $ven->id ? 'selected' : '' }}>{{ $ven->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Proyek PMO</label>
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
                        <input type="text" name="search" class="form-control form-control-sm radius-8 text-xs pe-32" placeholder="Nomor PO, vendor, syarat bayar..." value="{{ $search }}">
                        <iconify-icon icon="solar:magnifer-outline" class="position-absolute top-50 end-0 translate-middle-y me-10 text-secondary-light"></iconify-icon>
                    </div>
                </div>
                <div class="pt-18">
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-14 text-xs">Filter</button>
                    @if($statusFilter !== 'all' || $vendorFilter !== 'all' || $projectFilter !== 'all' || $search)
                        <a href="{{ route('dashboard-admin.procurement.purchase-orders.index') }}" class="btn btn-sm btn-outline-secondary radius-8 px-12 text-xs">Reset</a>
                    @endif
                </div>
            </div>
            <div class="text-xs text-secondary-light">
                Menampilkan <span class="fw-semibold text-primary-light">{{ $purchaseOrders->count() }}</span> dari <span class="fw-semibold text-primary-light">{{ $purchaseOrders->total() }}</span> PO
            </div>
        </form>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-header bg-transparent py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Daftar Purchase Order (PO)</h6>
            <span class="text-xs text-secondary-light">Terintegrasi Komitmen Biaya Proyek (PMO) & Jurnal Beban AP (Finance)</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th>No. PO & Tanggal</th>
                        <th>Mitra Vendor</th>
                        <th>Proyek PMO</th>
                        <th>Rincian Finansial (DPP / PPN / Total)</th>
                        <th>Term of Payment</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($purchaseOrders as $po)
                        <tr>
                            <td>
                                <a href="{{ route('dashboard-admin.procurement.purchase-orders.show', $po->id) }}" class="fw-bold text-primary-600 text-decoration-none d-block">
                                    {{ $po->po_number }}
                                </a>
                                <span class="text-secondary-light text-xxs">Tgl PO: {{ $po->po_date?->format('d M Y') }}</span>
                                <span class="d-block text-xxs text-secondary-light">Deadline: {{ $po->delivery_deadline?->format('d M Y') }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-primary-light d-block">{{ $po->vendor?->name }}</span>
                                <span class="text-secondary-light text-xxs">{{ $po->vendor?->category }}</span>
                                @if($po->purchaseRequest)
                                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200 text-xxs d-inline-block mt-1">Ref: {{ $po->purchaseRequest->pr_number }}</span>
                                @endif
                            </td>
                            <td>
                                @if($po->project)
                                    <span class="fw-medium text-primary-light d-block">{{ Str::limit($po->project->title, 35) }}</span>
                                    <span class="text-secondary-light text-xxs">{{ $po->project->project_code }}</span>
                                @else
                                    <span class="text-secondary-light">Umum / Workshop</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-secondary-light text-xxs">DPP: Rp {{ number_format($po->subtotal, 0, ',', '.') }}</div>
                                <div class="text-secondary-light text-xxs">PPN (11%): Rp {{ number_format($po->tax_amount, 0, ',', '.') }}</div>
                                <span class="fw-bold text-neutral-800 d-block mt-1">Total: Rp {{ number_format($po->grand_total, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="fw-medium text-neutral-800">{{ $po->payment_terms }}</span>
                                <span class="d-block text-secondary-light text-xxs mt-1">{{ $po->items->count() }} Item Dipesan</span>
                            </td>
                            <td>
                                @if($po->status === 'completed')
                                    <span class="badge bg-success-50 text-success-700 border border-success-200">Selesai di Site</span>
                                @elseif($po->status === 'in_delivery')
                                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200">In Delivery</span>
                                @elseif($po->status === 'issued')
                                    <span class="badge bg-primary-50 text-primary-700 border border-primary-200">Issued</span>
                                @elseif($po->status === 'draft')
                                    <span class="badge bg-neutral-50 text-neutral-600 border border-neutral-200">Draft</span>
                                @else
                                    <span class="badge bg-danger-50 text-danger-700 border border-danger-200">{{ ucfirst($po->status) }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('dashboard-admin.procurement.purchase-orders.show', $po->id) }}" class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center border-0" title="Preview / Cetak PO Resmi">
                                        <iconify-icon icon="solar:printer-minimalistic-outline"></iconify-icon>
                                    </a>
                                    <button type="button" class="w-32-px h-32-px rounded-circle bg-warning-focus text-warning-main d-inline-flex justify-content-center align-items-center border-0" data-bs-toggle="modal" data-bs-target="#updatePOStatusModal" data-po="{{ json_encode($po) }}" title="Update Status PO">
                                        <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                    </button>
                                    <form action="{{ route('dashboard-admin.procurement.purchase-orders.destroy', $po->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus PO ini?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus PO">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-24 text-secondary-light">
                                Tidak ada Purchase Order yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-16 border-top">
            {{ $purchaseOrders->links() }}
        </div>
    </div>
</div>


<div class="modal fade" id="createPOModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light">Terbitkan Purchase Order (PO) Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.procurement.purchase-orders.store') }}" method="POST">
                @csrf
                <div class="modal-body p-24">
                    
                    <div class="p-16 radius-12 bg-primary-50 border border-primary-200 mb-20">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label text-xs fw-bold text-primary-700 mb-0">Impor Otomatis dari Purchase Request (PR):</label>
                            </div>
                            <div class="col-md-8">
                                <select name="purchase_request_id" id="selectSourcePR" class="form-select form-select-sm radius-8">
                                    <option value="">-- Buat Mandiri / Tanpa PR --</option>
                                    @foreach ($approvedPRs as $apr)
                                        <option value="{{ $apr->id }}" data-pr-json="{{ json_encode($apr) }}">
                                            {{ $apr->pr_number }} - {{ $apr->title }} (Rp {{ number_format($apr->estimated_total, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-20">
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nomor PO <span class="text-danger">*</span></label>
                            <input type="text" name="po_number" class="form-control form-control-sm radius-8" value="{{ $nextPONumber }}" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label text-xs fw-semibold text-primary-light">Mitra Rekanan Vendor <span class="text-danger">*</span></label>
                            <select name="vendor_id" class="form-select form-select-sm radius-8" required>
                                <option value="">-- Pilih Vendor --</option>
                                @foreach ($vendors as $ven)
                                    <option value="{{ $ven->id }}">{{ $ven->name }} ({{ $ven->category }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Proyek PMO Terkait</label>
                            <select name="project_id" id="po_project_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Kebutuhan Umum / Workshop --</option>
                                @foreach ($projects as $proj)
                                    <option value="{{ $proj->id }}">{{ $proj->title }} ({{ $proj->project_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Tanggal PO <span class="text-danger">*</span></label>
                            <input type="date" name="po_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Batas Pengiriman (Deadline) <span class="text-danger">*</span></label>
                            <input type="date" name="delivery_deadline" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d', strtotime('+14 days')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Term of Payment <span class="text-danger">*</span></label>
                            <select name="payment_terms" class="form-select form-select-sm radius-8" required>
                                @foreach ($paymentTermsList as $pt)
                                    <option value="{{ $pt }}">{{ $pt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Status Awal</label>
                            <select name="status" class="form-select form-select-sm radius-8">
                                <option value="issued" selected>Issued (Terkirim)</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Alamat Pengiriman (Delivery Destination) <span class="text-danger">*</span></label>
                            <textarea name="delivery_address" class="form-control form-control-sm radius-8" rows="2" placeholder="Gudang tujuan / Site Pertamina / Workshop..." required>Site Project PT ELBEMA Integra Indonesia, Bontang, Kalimantan Timur</textarea>
                        </div>
                    </div>

                    
                    <div class="border radius-12 p-16 bg-neutral-50 mb-20">
                        <div class="d-flex align-items-center justify-content-between mb-12">
                            <div>
                                <h6 class="fs-6 fw-semibold text-primary-light mb-0">Rincian Barang / Jasa yang Dipesan</h6>
                                <span class="text-xxs text-secondary-light">Tentukan harga satuan final hasil negosiasi dengan vendor</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary radius-8 px-12 py-6 text-xs d-flex align-items-center gap-1" id="btnAddPOItem">
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
                                        <th style="width: 160px;">Harga Satuan Final</th>
                                        <th style="width: 140px;" class="text-end">Total Harga</th>
                                        <th style="width: 40px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="poItemsContainer">
                                    <tr class="po-item-row align-middle">
                                        <td>
                                            <input type="text" name="items[0][item_name]" class="form-control form-control-sm radius-6 text-xs" placeholder="Contoh: Gate Valve 4 inch Class 300" required>
                                            <input type="text" name="items[0][specifications]" class="form-control form-control-sm radius-6 text-xxs mt-1" placeholder="Spek material/sertifikat">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[0][qty]" class="form-control form-control-sm radius-6 text-xs po-qty" value="1" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][unit]" class="form-control form-control-sm radius-6 text-xs" value="Pcs" required>
                                        </td>
                                        <td>
                                            <input type="number" step="1000" name="items[0][unit_price]" class="form-control form-control-sm radius-6 text-xs po-price" value="0" required>
                                        </td>
                                        <td class="text-end fw-semibold text-xs po-item-total">Rp 0</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-secondary radius-6" disabled><iconify-icon icon="solar:lock-outline"></iconify-icon></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                    <div class="row g-3 justify-content-end">
                        <div class="col-md-5">
                            <div class="p-16 radius-12 bg-neutral-50 border border-neutral-200">
                                <div class="d-flex justify-content-between align-items-center mb-8 text-xs">
                                    <span class="text-secondary-light">Subtotal (DPP):</span>
                                    <span class="fw-semibold text-primary-light" id="poSubtotalDisplay">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-8 text-xs">
                                    <span class="text-secondary-light">Diskon / Potongan:</span>
                                    <div class="w-120-px">
                                        <input type="number" step="1000" name="discount_amount" id="poDiscountInput" class="form-control form-control-sm radius-6 text-end text-xs" value="0">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-8 text-xs">
                                    <span class="text-secondary-light">Tarif PPN (%):</span>
                                    <div class="w-80-px">
                                        <input type="number" step="0.5" name="tax_rate" id="poTaxRateInput" class="form-control form-control-sm radius-6 text-end text-xs" value="11">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-8 text-xs">
                                    <span class="text-secondary-light">PPN Terhitung:</span>
                                    <span class="fw-semibold text-primary-light" id="poTaxDisplay">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-8 border-top text-sm">
                                    <span class="fw-bold text-primary-light">Grand Total PO:</span>
                                    <span class="fw-bold text-primary-600 fs-6" id="poGrandTotalDisplay">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-16 px-24 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8 px-14" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-16">Terbitkan PO Resmi</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="updatePOStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light">Perbarui Status Purchase Order</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-24">
                    <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200 mb-16">
                        <span class="text-xxs text-secondary-light d-block mb-1">Nomor PO & Vendor</span>
                        <div class="fw-bold text-primary-600 text-xs" id="po_status_number">PO-xxx</div>
                        <div class="text-xs text-primary-light" id="po_status_vendor">Vendor Name</div>
                    </div>
                    <div class="mb-16">
                        <label class="form-label text-xs fw-semibold text-primary-light">Status Terkini</label>
                        <select name="status" id="po_status_select" class="form-select form-select-sm radius-8">
                            <option value="issued">Issued (PO Terkirim ke Vendor)</option>
                            <option value="acknowledged">Acknowledged (Dikonfirmasi Vendor)</option>
                            <option value="in_delivery">In Delivery (Dalam Ekspedisi/Pengiriman)</option>
                            <option value="partially_received">Partially Received (Diterima Sebagian)</option>
                            <option value="completed">Completed (Selesai Diterima Utuh)</option>
                            <option value="cancelled">Cancelled (Dibatalkan)</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-xs fw-semibold text-primary-light">Catatan Operasional / Progres Pengiriman</label>
                        <textarea name="notes" id="po_status_notes" class="form-control form-control-sm radius-8" rows="3" placeholder="Informasi estimasi waktu kirim, kendala, atau no tanda terima..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-16 px-24 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8 px-14" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-16">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
