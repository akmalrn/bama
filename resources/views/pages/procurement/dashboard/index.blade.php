@extends('layout.layout')

@php
    $title = 'Procurement & Logistics Executive Dashboard';
    $subTitle = 'Pengadaan Material, Pengelolaan Rekanan Vendor & Pelacakan Ekspedisi Lapangan';
    $script = '<script>
        // Procurement Dashboard Charts & Scripts
        document.addEventListener("DOMContentLoaded", function() {
            // ApexCharts: Spending by Vendor Category
            var categoryOptions = {
                series: ' . json_encode(array_values($vendorSpending->toArray())) . ',
                labels: ' . json_encode(array_keys($vendorSpending->toArray())) . ',
                chart: {
                    type: "donut",
                    height: 260,
                    sparkline: { enabled: false }
                },
                colors: ["#487fff", "#10b981", "#f59e0b", "#ef4444", "#8b5cf6", "#06b6d4"],
                legend: {
                    position: "bottom",
                    fontSize: "12px",
                    labels: { colors: "#64748b" }
                },
                dataLabels: { enabled: false },
                stroke: { width: 0 },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "Rp " + new Intl.NumberFormat("id-ID").format(val);
                        }
                    }
                }
            };
            var chartEl = document.querySelector("#vendorSpendingChart");
            if (chartEl && categoryOptions.series.length > 0) {
                var chart = new ApexCharts(chartEl, categoryOptions);
                chart.render();
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
        <a href="{{ route('dashboard-admin.procurement.purchase-requests.index') }}" class="btn btn-sm btn-outline-warning radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:document-add-bold-duotone"></iconify-icon>
            Buat PR Baru
        </a>
        <a href="{{ route('dashboard-admin.procurement.purchase-orders.index') }}" class="btn btn-sm btn-outline-success radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:cart-check-bold-duotone"></iconify-icon>
            Terbitkan PO
        </a>
        <a href="{{ route('dashboard-admin.procurement.logistics.index') }}" class="btn btn-sm btn-primary-600 radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:box-minimalistic-bold-duotone"></iconify-icon>
            Lacak Pengiriman
        </a>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Nilai PO Diterbitkan</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-4">Rp {{ number_format($totalPOValue, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Bulan Ini: Rp {{ number_format($thisMonthPOValue, 0, ',', '.') }}</span>
                    <span class="badge bg-primary-50 text-primary-700 border border-primary-200">{{ $activePOCount }} PO Berjalan</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Purchase Request (PR)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-4">{{ $pendingPRCount }} <span class="fs-6 fw-normal text-secondary-light">Menunggu Approval</span></h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-warning-50 text-warning-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Total Diajukan: {{ $totalPRCount }} PR</span>
                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200">{{ $urgentPRCount }} Urgent</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Mitra Rekanan / Vendor</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-4">{{ $activeVendors }} <span class="fs-6 fw-normal text-secondary-light">Supplier Aktif</span></h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-info-50 text-info-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Total Rekanan: {{ $totalVendors }} Rekanan</span>
                    <span class="badge bg-info-50 text-info-700 border border-info-200">ASME / API Certified</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Ekspedisi Logistik Site</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-4">{{ $inTransitDeliveriesCount }} <span class="fs-6 fw-normal text-secondary-light">Dalam Pengiriman</span></h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:delivery-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Telah Diterima Site: {{ $completedDeliveriesCount }} Pengiriman</span>
                    <span class="badge bg-success-50 text-success-700 border border-success-200">On Schedule</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-24">
    
    <div class="col-xxl-8 col-lg-7">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent py-16 px-20 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Alur Pengadaan & Eksekusi Lapangan</h6>
                    <span class="text-xs text-secondary-light">Siklus dari Purchase Request (PR) hingga Pengiriman Selesai di Site</span>
                </div>
                <div class="badge bg-primary-50 text-primary-600 border border-primary-200 px-10 py-6 radius-6 text-xs">
                    Terintegrasi PMO & Finance
                </div>
            </div>
            <div class="card-body p-20">
                
                <div class="row g-2 text-center mb-24">
                    <div class="col">
                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                            <span class="text-xs text-secondary-light d-block mb-1">1. Draft PR</span>
                            <span class="fw-bold fs-6 text-neutral-600">{{ $prStatusCounts['draft'] }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-12 radius-8 bg-warning-50 border border-warning-200">
                            <span class="text-xs text-warning-700 d-block mb-1">2. Review PR</span>
                            <span class="fw-bold fs-6 text-warning-main">{{ $prStatusCounts['pending_approval'] }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-12 radius-8 bg-info-50 border border-info-200">
                            <span class="text-xs text-info-700 d-block mb-1">3. PO Diterbitkan</span>
                            <span class="fw-bold fs-6 text-info-main">{{ $poStatusCounts['issued'] }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-12 radius-8 bg-primary-50 border border-primary-200">
                            <span class="text-xs text-primary-700 d-block mb-1">4. Logistik / Transit</span>
                            <span class="fw-bold fs-6 text-primary-600">{{ $inTransitDeliveriesCount }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-12 radius-8 bg-success-50 border border-success-200">
                            <span class="text-xs text-success-700 d-block mb-1">5. Selesai di Site</span>
                            <span class="fw-bold fs-6 text-success-main">{{ $poStatusCounts['completed'] }}</span>
                        </div>
                    </div>
                </div>

                
                <div class="d-flex align-items-center justify-content-between mb-12">
                    <h6 class="fs-6 fw-semibold text-primary-light mb-0">Permintaan Pengadaan (PR) Prioritas</h6>
                    <a href="{{ route('dashboard-admin.procurement.purchase-requests.index') }}" class="text-xs text-primary-600 fw-semibold text-decoration-none">Lihat Semua PR &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle">
                        <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                            <tr>
                                <th>No. PR</th>
                                <th>Judul Kebutuhan</th>
                                <th>Proyek Site</th>
                                <th>Prioritas</th>
                                <th>Estimasi Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @forelse ($urgentPRs as $pr)
                                <tr>
                                    <td class="fw-semibold text-primary-600">{{ $pr->pr_number }}</td>
                                    <td>
                                        <div class="fw-medium text-primary-light">{{ Str::limit($pr->title, 40) }}</div>
                                        <span class="text-secondary-light">{{ $pr->category }} &bull; {{ $pr->items->count() }} Item</span>
                                    </td>
                                    <td>{{ $pr->project?->title ?? 'Umum / Workshop' }}</td>
                                    <td>
                                        @if($pr->priority === 'urgent')
                                            <span class="badge bg-danger-100 text-danger-700 border border-danger-200">Urgent</span>
                                        @elseif($pr->priority === 'high')
                                            <span class="badge bg-warning-100 text-warning-800 border border-warning-200">High</span>
                                        @else
                                            <span class="badge bg-neutral-100 text-neutral-600 border border-neutral-200">Normal</span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold text-neutral-800">Rp {{ number_format($pr->estimated_total, 0, ',', '.') }}</td>
                                    <td>
                                        @if($pr->status === 'approved')
                                            <span class="badge bg-success-50 text-success-700 border border-success-200">Disetujui</span>
                                        @elseif($pr->status === 'pending_approval')
                                            <span class="badge bg-warning-50 text-warning-700 border border-warning-200">Review</span>
                                        @else
                                            <span class="badge bg-neutral-50 text-neutral-700 border border-neutral-200">{{ ucfirst(str_replace('_', ' ', $pr->status)) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-16 text-secondary-light">Belum ada PR mendesak yang menunggu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xxl-4 col-lg-5">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent py-16 px-20 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Alokasi Belanja per Kategori</h6>
                    <span class="text-xs text-secondary-light">Distribusi Pengeluaran PO Berdasarkan Spesialisasi</span>
                </div>
            </div>
            <div class="card-body p-20">
                @if($vendorSpending->isNotEmpty())
                    <div id="vendorSpendingChart" class="mb-16"></div>
                @else
                    <div class="text-center py-40 text-secondary-light text-xs">
                        <iconify-icon icon="solar:pie-chart-2-outline" class="text-3xl text-neutral-300 d-block mb-8"></iconify-icon>
                        Belum ada data transaksi PO untuk dianalisis.
                    </div>
                @endif

                <div class="pt-16 border-top">
                    <h6 class="fs-6 fw-semibold text-primary-light mb-12">Top Rekanan Vendor Terbesar</h6>
                    <div class="d-flex flex-column gap-10">
                        @forelse ($topVendors as $tv)
                            <div class="d-flex align-items-center justify-content-between p-10 radius-8 bg-neutral-50 border border-neutral-200">
                                <div>
                                    <span class="fw-semibold text-xs text-primary-light d-block">{{ $tv['vendor']->name }}</span>
                                    <span class="text-xxs text-secondary-light">{{ $tv['vendor']->category }} &bull; {{ $tv['total_orders'] }} Pesanan</span>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-xs text-primary-600">Rp {{ number_format($tv['total_spend'], 0, ',', '.') }}</span>
                                    <div class="text-warning-main text-xxs">
                                        @for ($i = 1; $i <= $tv['vendor']->rating; $i++) ★ @endfor
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-secondary-light mb-0">Belum ada rekanan vendor tercatat.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-header bg-transparent py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Pelacakan Logistik & Ekspedisi Material ke Site</h6>
            <span class="text-xs text-secondary-light">Status Pengiriman Barang/Alat Terhubung Surat Jalan & Nomor Resi AWB</span>
        </div>
        <a href="{{ route('dashboard-admin.procurement.logistics.index') }}" class="btn btn-sm btn-outline-primary radius-8 px-12 py-6 text-xs">
            Buka Pelacak Logistik &rarr;
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-50 text-secondary-light text-xs text-uppercase">
                    <tr>
                        <th>No. Surat Jalan</th>
                        <th>No. PO / Vendor</th>
                        <th>Tujuan Site</th>
                        <th>Ekspedisi & No. Resi</th>
                        <th>Tgl Kirim & Estimasi Tiba</th>
                        <th>Penerima Site</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse ($activeShipments as $del)
                        <tr>
                            <td class="fw-semibold text-primary-600">{{ $del->delivery_number }}</td>
                            <td>
                                @if($del->purchaseOrder)
                                    <span class="fw-medium text-primary-light">{{ $del->purchaseOrder->po_number }}</span>
                                    <span class="d-block text-secondary-light">{{ $del->purchaseOrder->vendor?->name }}</span>
                                @else
                                    <span class="text-secondary-light">Internal Dispatch</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-medium text-primary-light">{{ $del->destination }}</span>
                                <span class="d-block text-secondary-light">Dari: {{ $del->origin }}</span>
                            </td>
                            <td>
                                <span class="fw-medium text-primary-light">{{ $del->expedition_name }}</span>
                                <span class="d-block text-secondary-light font-monospace">{{ $del->tracking_number ?? '-' }}</span>
                            </td>
                            <td>
                                <div>{{ $del->shipment_date?->format('d M Y') }}</div>
                                <span class="text-secondary-light">Est. Tiba: {{ $del->estimated_arrival_date?->format('d M Y') }}</span>
                            </td>
                            <td>
                                <div>{{ $del->recipient_name ?? '-' }}</div>
                                <span class="text-secondary-light">{{ $del->recipient_phone ?? '-' }}</span>
                            </td>
                            <td>
                                @if($del->status === 'in_transit')
                                    <span class="badge bg-primary-50 text-primary-700 border border-primary-200">In Transit</span>
                                @elseif($del->status === 'arrived_at_site')
                                    <span class="badge bg-info-50 text-info-700 border border-info-200">Tiba di Site</span>
                                @elseif($del->status === 'inspected_and_received')
                                    <span class="badge bg-success-50 text-success-700 border border-success-200">Diterima Lengkap</span>
                                @else
                                    <span class="badge bg-neutral-50 text-neutral-700 border border-neutral-200">{{ ucfirst($del->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-20 text-secondary-light">Tidak ada kargo ekspedisi yang sedang aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
