@extends('layout.layout')

@php
    $title = 'Ekspedisi & Logistik Lapangan';
    $subTitle = 'Pelacakan Pengiriman Material, Suku Cadang & Peralatan Kerja ke Site Proyek';
    $script = '<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Modal Update Delivery Status
            const updateDeliveryModal = document.getElementById("updateDeliveryModal");
            if (updateDeliveryModal) {
                updateDeliveryModal.addEventListener("show.bs.modal", function(event) {
                    const button = event.relatedTarget;
                    const del = JSON.parse(button.getAttribute("data-delivery"));
                    const form = updateDeliveryModal.querySelector("form");
                    form.action = "/dashboard-admin/procurement/logistics/" + del.id + "/status";

                    updateDeliveryModal.querySelector("#modal_del_number").textContent = del.delivery_number;
                    updateDeliveryModal.querySelector("#modal_del_expedition").textContent = del.expedition_name + (del.tracking_number ? " (" + del.tracking_number + ")" : "");
                    updateDeliveryModal.querySelector("#modal_del_status").value = del.status;
                    updateDeliveryModal.querySelector("#modal_del_notes").value = del.notes || "";
                    updateDeliveryModal.querySelector("#modal_del_arrival").value = del.actual_arrival_date || "' . date('Y-m-d') . '";
                });
            }

            // Modal Detail Delivery
            const viewDeliveryModal = document.getElementById("viewDeliveryModal");
            if (viewDeliveryModal) {
                viewDeliveryModal.addEventListener("show.bs.modal", function(event) {
                    const button = event.relatedTarget;
                    const del = JSON.parse(button.getAttribute("data-delivery"));

                    viewDeliveryModal.querySelector("#detail_del_number").textContent = del.delivery_number;
                    viewDeliveryModal.querySelector("#detail_del_po").textContent = del.purchase_order ? del.purchase_order.po_number + " (" + (del.purchase_order.vendor ? del.purchase_order.vendor.name : "") + ")" : "Internal Dispatch";
                    viewDeliveryModal.querySelector("#detail_del_project").textContent = del.project ? del.project.title : "Workshop / Site";
                    viewDeliveryModal.querySelector("#detail_del_expedition").textContent = del.expedition_name;
                    viewDeliveryModal.querySelector("#detail_del_tracking").textContent = del.tracking_number || "Tanpa Resi";
                    viewDeliveryModal.querySelector("#detail_del_route").textContent = del.origin + " ➔ " + del.destination;
                    viewDeliveryModal.querySelector("#detail_del_shipment_date").textContent = del.shipment_date || "-";
                    viewDeliveryModal.querySelector("#detail_del_est_arrival").textContent = del.estimated_arrival_date || "-";
                    viewDeliveryModal.querySelector("#detail_del_act_arrival").textContent = del.actual_arrival_date || "Belum tiba";
                    viewDeliveryModal.querySelector("#detail_del_driver").textContent = (del.driver_name || "-") + " (" + (del.driver_phone || "-") + ") - Plat: " + (del.vehicle_plate || "-");
                    viewDeliveryModal.querySelector("#detail_del_recipient").textContent = (del.recipient_name || "-") + " (" + (del.recipient_phone || "-") + ")";
                    viewDeliveryModal.querySelector("#detail_del_notes").textContent = del.notes || "Tidak ada catatan pengiriman.";
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
        <button type="button" class="btn btn-sm btn-primary-600 radius-8 px-14 py-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#createDeliveryModal">
            <iconify-icon icon="solar:delivery-bold-duotone"></iconify-icon>
            Catat Pengiriman / Surat Jalan
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
                    <span class="text-xs text-secondary-light d-block mb-1">Total Ekspedisi Tercatat</span>
                    <h5 class="fw-bold mb-0 text-primary-light">{{ $counts['all'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-xl">
                    <iconify-icon icon="solar:box-minimalistic-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Dalam Perjalanan (In Transit)</span>
                    <h5 class="fw-bold mb-0 text-primary-600">{{ $counts['in_transit'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-xl">
                    <iconify-icon icon="solar:delivery-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm p-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-xs text-secondary-light d-block mb-1">Tiba di Site (Menunggu QC)</span>
                    <h5 class="fw-bold mb-0 text-info-main">{{ $counts['arrived_at_site'] }}</h5>
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
                    <span class="text-xs text-secondary-light d-block mb-1">Diterima & BAP Lengkap</span>
                    <h5 class="fw-bold mb-0 text-success-main">{{ $counts['inspected_and_received'] }}</h5>
                </div>
                <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-main text-xl">
                    <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER ROW --}}
<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.procurement.logistics.index') }}" class="d-flex flex-wrap align-items-end justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Status Pengiriman</label>
                    <select name="status" class="form-select form-select-sm radius-8 text-xs pe-28">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="in_transit" {{ $statusFilter === 'in_transit' ? 'selected' : '' }}>In Transit (Di Jalan)</option>
                        <option value="arrived_at_site" {{ $statusFilter === 'arrived_at_site' ? 'selected' : '' }}>Tiba di Site</option>
                        <option value="inspected_and_received" {{ $statusFilter === 'inspected_and_received' ? 'selected' : '' }}>Diterima Lengkap</option>
                        <option value="preparing" {{ $statusFilter === 'preparing' ? 'selected' : '' }}>Preparing (Persiapan)</option>
                        <option value="issue_damaged" {{ $statusFilter === 'issue_damaged' ? 'selected' : '' }}>Kendala / Rusak</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xxs text-secondary-light mb-1">Proyek Tujuan</label>
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
                        <input type="text" name="search" class="form-control form-control-sm radius-8 text-xs pe-32" placeholder="No surat jalan, resi, ekspedisi..." value="{{ $search }}">
                        <iconify-icon icon="solar:magnifer-outline" class="position-absolute top-50 end-0 translate-middle-y me-10 text-secondary-light"></iconify-icon>
                    </div>
                </div>
                <div class="pt-18">
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-14 text-xs">Filter</button>
                    @if($statusFilter !== 'all' || $projectFilter !== 'all' || $search)
                        <a href="{{ route('dashboard-admin.procurement.logistics.index') }}" class="btn btn-sm btn-outline-secondary radius-8 px-12 text-xs">Reset</a>
                    @endif
                </div>
            </div>
            <div class="text-xs text-secondary-light">
                Menampilkan <span class="fw-semibold text-primary-light">{{ $deliveries->count() }}</span> dari <span class="fw-semibold text-primary-light">{{ $deliveries->total() }}</span> pengiriman
            </div>
        </form>
    </div>
</div>

{{-- TABLE LOGISTICS --}}
<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-header bg-transparent py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Daftar Ekspedisi & Surat Jalan Material</h6>
            <span class="text-xs text-secondary-light">Monitoring Real-time Pergerakan Barang Dari Vendor ke Site Proyek</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th>No. Surat Jalan</th>
                        <th>PO & Mitra Vendor</th>
                        <th>Rute Pengiriman (Asal ➔ Tujuan)</th>
                        <th>Ekspedisi & No. Resi (AWB)</th>
                        <th>Jadwal (Kirim & Tiba)</th>
                        <th>Penerima Site</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($deliveries as $del)
                        <tr>
                            <td>
                                <span class="fw-bold text-primary-600 d-block">{{ $del->delivery_number }}</span>
                                <span class="text-secondary-light text-xxs">ID #{{ $del->id }}</span>
                            </td>
                            <td>
                                @if($del->purchaseOrder)
                                    <a href="{{ route('dashboard-admin.procurement.purchase-orders.show', $del->purchaseOrder->id) }}" class="fw-semibold text-primary-light text-decoration-none d-block">
                                        {{ $del->purchaseOrder->po_number }}
                                    </a>
                                    <span class="text-secondary-light text-xxs">{{ $del->purchaseOrder->vendor?->name }}</span>
                                @else
                                    <span class="fw-medium text-secondary-light">Internal Dispatch</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium text-primary-light">{{ Str::limit($del->destination, 35) }}</div>
                                <span class="text-secondary-light text-xxs">Dari: {{ Str::limit($del->origin, 30) }}</span>
                            </td>
                            <td>
                                <span class="fw-medium text-primary-light d-block">{{ $del->expedition_name }}</span>
                                <span class="text-secondary-light font-monospace text-xxs">{{ $del->tracking_number ?? 'Tanpa Resi' }}</span>
                                @if($del->vehicle_plate)
                                    <span class="badge bg-neutral-100 text-neutral-700 text-xxs d-inline-block">{{ $del->vehicle_plate }}</span>
                                @endif
                            </td>
                            <td>
                                <div>Kirim: <span class="fw-medium">{{ $del->shipment_date?->format('d M Y') }}</span></div>
                                <div class="text-secondary-light text-xxs">Est: {{ $del->estimated_arrival_date?->format('d M Y') }}</div>
                                @if($del->actual_arrival_date)
                                    <div class="text-success-main fw-semibold text-xxs">Tiba: {{ $del->actual_arrival_date?->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium text-primary-light">{{ $del->recipient_name ?? '-' }}</div>
                                <span class="text-secondary-light text-xxs">{{ $del->recipient_phone ?? '-' }}</span>
                            </td>
                            <td>
                                @if($del->status === 'inspected_and_received')
                                    <span class="badge bg-success-50 text-success-700 border border-success-200">Diterima Lengkap</span>
                                @elseif($del->status === 'arrived_at_site')
                                    <span class="badge bg-info-50 text-info-700 border border-info-200">Tiba di Site</span>
                                @elseif($del->status === 'in_transit')
                                    <span class="badge bg-primary-50 text-primary-700 border border-primary-200">In Transit</span>
                                @elseif($del->status === 'preparing')
                                    <span class="badge bg-neutral-50 text-neutral-600 border border-neutral-200">Preparing</span>
                                @else
                                    <span class="badge bg-danger-50 text-danger-700 border border-danger-200">Kendala/Rusak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <button type="button" class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center border-0" data-bs-toggle="modal" data-bs-target="#viewDeliveryModal" data-delivery="{{ json_encode($del->load(['purchaseOrder.vendor', 'project'])) }}" title="Lihat Detail">
                                        <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                    </button>
                                    <button type="button" class="w-32-px h-32-px rounded-circle bg-warning-focus text-warning-main d-inline-flex justify-content-center align-items-center border-0" data-bs-toggle="modal" data-bs-target="#updateDeliveryModal" data-delivery="{{ json_encode($del) }}" title="Update Status Pengiriman">
                                        <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                    </button>
                                    <form action="{{ route('dashboard-admin.procurement.logistics.destroy', $del->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data logistik ini?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-24 text-secondary-light">
                                Tidak ada data pengiriman logistik yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-16 border-top">
            {{ $deliveries->links() }}
        </div>
    </div>
</div>

{{-- MODAL TAMBAH PENGIRIMAN LOGISTIK --}}
<div class="modal fade" id="createDeliveryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light">Catat Pengiriman / Surat Jalan Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.procurement.logistics.store') }}" method="POST">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">No. Surat Jalan / Delivery <span class="text-danger">*</span></label>
                            <input type="text" name="delivery_number" class="form-control form-control-sm radius-8" value="{{ $nextDeliveryNumber }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Referensi Purchase Order (PO)</label>
                            <select name="purchase_order_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Tanpa PO (Internal Mobilization) --</option>
                                @foreach ($purchaseOrders as $po)
                                    <option value="{{ $po->id }}">{{ $po->po_number }} - {{ $po->vendor?->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-primary-light">Proyek Tujuan</label>
                            <select name="project_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Workshop / Gudang Utama --</option>
                                @foreach ($projects as $proj)
                                    <option value="{{ $proj->id }}">{{ $proj->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nama Ekspedisi / Kurir / Vendor Transport <span class="text-danger">*</span></label>
                            <input type="text" name="expedition_name" class="form-control form-control-sm radius-8" placeholder="Contoh: Baraka Sarana Tama / Karyati / Internal Armada" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nomor Resi / AWB / No. Polisi</label>
                            <input type="text" name="tracking_number" class="form-control form-control-sm radius-8" placeholder="AWB-xxxx-xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Asal Keberangkatan <span class="text-danger">*</span></label>
                            <input type="text" name="origin" class="form-control form-control-sm radius-8" placeholder="Gudang Jakarta / Workshop Cikarang" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Tujuan Site Lapangan <span class="text-danger">*</span></label>
                            <input type="text" name="destination" class="form-control form-control-sm radius-8" placeholder="Site Pertamina Plaju / Site KPI Bontang" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Tanggal Kirim <span class="text-danger">*</span></label>
                            <input type="date" name="shipment_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Estimasi Tiba (ETA) <span class="text-danger">*</span></label>
                            <input type="date" name="estimated_arrival_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">Status Pengiriman</label>
                            <select name="status" class="form-select form-select-sm radius-8">
                                <option value="in_transit" selected>In Transit (Sedang Dikirim)</option>
                                <option value="preparing">Preparing (Persiapan)</option>
                                <option value="arrived_at_site">Arrived at Site</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-primary-light">No. Plat Kendaraan</label>
                            <input type="text" name="vehicle_plate" class="form-control form-control-sm radius-8" placeholder="B 1234 ABC">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nama Supir / Driver</label>
                            <input type="text" name="driver_name" class="form-control form-control-sm radius-8" placeholder="Nama supir pengantar">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nomor HP Driver</label>
                            <input type="text" name="driver_phone" class="form-control form-control-sm radius-8" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">PIC Penerima di Site</label>
                            <input type="text" name="recipient_name" class="form-control form-control-sm radius-8" placeholder="Material Coordinator / Warehouse Supervisor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-primary-light">Nomor HP Penerima</label>
                            <input type="text" name="recipient_phone" class="form-control form-control-sm radius-8" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-primary-light">Catatan Muatan & Instruksi Bongkar</label>
                            <textarea name="notes" class="form-control form-control-sm radius-8" rows="2" placeholder="Catatan kargo, handling khusus, atau instruksi penerimaan..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-16 px-24 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8 px-14" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-16">Simpan Pengiriman</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL UPDATE LOGISTICS STATUS --}}
<div class="modal fade" id="updateDeliveryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light">Perbarui Status Pengiriman Logistik</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-24">
                    <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200 mb-16">
                        <span class="text-xxs text-secondary-light d-block mb-1">Surat Jalan & Ekspedisi</span>
                        <div class="fw-bold text-primary-600 text-xs" id="modal_del_number">LOG-xxx</div>
                        <div class="text-xs text-primary-light" id="modal_del_expedition">Ekspedisi</div>
                    </div>
                    <div class="mb-16">
                        <label class="form-label text-xs fw-semibold text-primary-light">Status Terkini</label>
                        <select name="status" id="modal_del_status" class="form-select form-select-sm radius-8">
                            <option value="in_transit">In Transit (Sedang dalam Perjalanan)</option>
                            <option value="arrived_at_site">Arrived at Site (Tiba di Lokasi Proyek)</option>
                            <option value="inspected_and_received">Inspected & Received (Lolos QC & Diterima Lengkap)</option>
                            <option value="issue_damaged">Issue / Rusak / Kargo Cacat</option>
                            <option value="preparing">Preparing (Persiapan Muat)</option>
                        </select>
                    </div>
                    <div class="mb-16">
                        <label class="form-label text-xs fw-semibold text-primary-light">Tanggal Tiba Aktual</label>
                        <input type="date" name="actual_arrival_date" id="modal_del_arrival" class="form-control form-control-sm radius-8">
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-xs fw-semibold text-primary-light">Catatan Penerimaan / Berita Acara (BAP)</label>
                        <textarea name="notes" id="modal_del_notes" class="form-control form-control-sm radius-8" rows="3" placeholder="Kondisi kemasan, kelengkapan surat jalan, paraf penerima..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-16 px-24 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8 px-14" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-600 radius-8 px-16">Simpan Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL 360 LOGISTICS --}}
<div class="modal fade" id="viewDeliveryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header py-16 px-24 border-bottom">
                <div>
                    <h6 class="modal-title fs-6 fw-semibold text-primary-light mb-0" id="detail_del_number">LOG-xxx</h6>
                    <span class="text-xs text-secondary-light" id="detail_del_expedition">Ekspedisi</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24">
                <div class="row g-3">
                    <div class="col-md-6">
                        <span class="text-xxs text-secondary-light d-block mb-1">Referensi Purchase Order (PO)</span>
                        <div class="fw-semibold text-primary-light text-xs" id="detail_del_po">-</div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-xxs text-secondary-light d-block mb-1">Proyek Tujuan</span>
                        <div class="fw-semibold text-primary-light text-xs" id="detail_del_project">-</div>
                    </div>
                    <div class="col-12">
                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                            <span class="text-xxs text-secondary-light d-block mb-1">Rute Pengiriman Logistik</span>
                            <div class="fw-bold text-primary-600 text-xs" id="detail_del_route">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="text-xxs text-secondary-light d-block mb-1">Nomor Resi / AWB</span>
                        <div class="fw-medium text-primary-light text-xs font-monospace" id="detail_del_tracking">-</div>
                    </div>
                    <div class="col-md-4">
                        <span class="text-xxs text-secondary-light d-block mb-1">Tgl Berangkat</span>
                        <div class="text-xs text-primary-light" id="detail_del_shipment_date">-</div>
                    </div>
                    <div class="col-md-4">
                        <span class="text-xxs text-secondary-light d-block mb-1">Estimasi / Tiba Aktual</span>
                        <div class="text-xs text-primary-light">Est: <span id="detail_del_est_arrival">-</span></div>
                        <div class="text-xs text-success-main fw-semibold">Aktual: <span id="detail_del_act_arrival">-</span></div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-xxs text-secondary-light d-block mb-1">Driver & Armada</span>
                        <div class="text-xs text-primary-light" id="detail_del_driver">-</div>
                    </div>
                    <div class="col-md-6">
                        <span class="text-xxs text-secondary-light d-block mb-1">PIC Penerima Lapangan</span>
                        <div class="text-xs text-primary-light" id="detail_del_recipient">-</div>
                    </div>
                    <div class="col-12">
                        <span class="text-xxs text-secondary-light d-block mb-1">Catatan Pengiriman & Berita Acara</span>
                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200 text-xs text-secondary-light" id="detail_del_notes">-</div>
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
