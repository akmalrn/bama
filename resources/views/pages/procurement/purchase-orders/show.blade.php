@extends('layout.layout')

@php
    $title = 'Surat Purchase Order (PO): ' . $purchaseOrder->po_number;
    $subTitle = 'Dokumen Resmi Pemesanan Barang & Jasa PT ELBEMA Integra Indonesia';
@endphp

@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printablePOArea, #printablePOArea * {
            visibility: visible;
        }
        #printablePOArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20px;
            box-shadow: none !important;
            border: none !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24 no-print">
    <div>
        <h6 class="fw-semibold mb-0 text-primary-light">{{ $title }}</h6>
        <p class="text-xs text-secondary-light mb-0">{{ $subTitle }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('dashboard-admin.procurement.purchase-orders.index') }}" class="btn btn-sm btn-outline-secondary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:arrow-left-outline"></iconify-icon>
            Kembali ke Daftar PO
        </a>
        <button type="button" onclick="window.print()" class="btn btn-sm btn-primary-600 radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:printer-minimalistic-bold-duotone"></iconify-icon>
            Cetak / Download PDF (A4)
        </button>
    </div>
</div>

<div class="card radius-16 border-0 shadow-sm mb-24 p-40" id="printablePOArea" style="background: #fff; max-width: 950px; margin: 0 auto;">
    
    <div class="d-flex justify-content-between align-items-start pb-24 border-bottom border-2 border-primary-600 mb-24">
        <div>
            <h4 class="fw-bold text-primary-600 mb-1 tracking-tight">PT ELBEMA INTEGRA INDONESIA</h4>
            <p class="text-xs text-secondary-light mb-0">Turnaround Maintenance, Industrial Cleaning & Engineering Services</p>
            <p class="text-xxs text-secondary-light mb-0">Head Office: Gd. Graha Kencana Lt. 5, Jl. Raya Perjuangan No. 88, Jakarta Barat</p>
            <p class="text-xxs text-secondary-light mb-0">Telp: (021) 5366-0888 &bull; Email: procurement@citacontrac.co.id &bull; Web: www.elbema.co.id</p>
        </div>
        <div class="text-end">
            <h3 class="fw-bolder text-uppercase tracking-wider text-dark mb-2">PURCHASE ORDER</h3>
            <div class="font-monospace fw-bold text-primary-600 fs-5">{{ $purchaseOrder->po_number }}</div>
            <span class="badge {{ $purchaseOrder->status === 'completed' ? 'bg-success' : 'bg-primary' }} text-white text-xxs mt-1 px-8 py-4">
                {{ strtoupper(str_replace('_', ' ', $purchaseOrder->status)) }}
            </span>
        </div>
    </div>

    
    <div class="row g-4 mb-24 text-xs">
        <div class="col-6">
            <div class="p-16 radius-8 bg-neutral-50 border border-neutral-200 h-100">
                <span class="text-xxs fw-bold text-uppercase text-secondary-light d-block mb-1">Kepada (Vendor / Supplier):</span>
                <h6 class="fw-bold text-primary-light mb-1 fs-6">{{ $purchaseOrder->vendor?->name }}</h6>
                <div class="text-secondary-light">{{ $purchaseOrder->vendor?->address ?? '-' }}</div>
                <div class="text-secondary-light">{{ $purchaseOrder->vendor?->city }}</div>
                <div class="mt-2 pt-2 border-top">
                    <div>Attn: <span class="fw-semibold text-primary-light">{{ $purchaseOrder->vendor?->contact_person ?? '-' }}</span></div>
                    <div>Telp: {{ $purchaseOrder->vendor?->phone ?? '-' }} &bull; Email: {{ $purchaseOrder->vendor?->email ?? '-' }}</div>
                    <div>NPWP: {{ $purchaseOrder->vendor?->npwp ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="p-16 radius-8 bg-neutral-50 border border-neutral-200 h-100">
                <span class="text-xxs fw-bold text-uppercase text-secondary-light d-block mb-1">Alamat Pengiriman (Ship-To):</span>
                <div class="fw-semibold text-primary-light mb-1">
                    Proyek: {{ $purchaseOrder->project ? $purchaseOrder->project->title . ' (' . $purchaseOrder->project->project_code . ')' : 'Workshop / Gudang Pusat' }}
                </div>
                <div class="text-secondary-light mb-2">{{ $purchaseOrder->delivery_address }}</div>
                <div class="pt-2 border-top">
                    <div class="row g-1">
                        <div class="col-6">
                            <span class="text-secondary-light d-block">Tanggal PO:</span>
                            <span class="fw-semibold text-primary-light">{{ $purchaseOrder->po_date?->format('d F Y') }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-secondary-light d-block">Batas Tiba (Deadline):</span>
                            <span class="fw-semibold text-danger-main">{{ $purchaseOrder->delivery_deadline?->format('d F Y') }}</span>
                        </div>
                        <div class="col-12 mt-1">
                            <span class="text-secondary-light">Syarat Pembayaran:</span>
                            <span class="fw-semibold text-primary-light">{{ $purchaseOrder->payment_terms }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="table-responsive mb-24">
        <table class="table bordered-table mb-0 text-xs">
            <thead class="bg-neutral-100 text-uppercase text-xxs text-secondary-light">
                <tr>
                    <th style="width: 40px;" class="text-center">No</th>
                    <th>Deskripsi Barang / Jasa & Spesifikasi Teknis</th>
                    <th style="width: 90px;" class="text-center">Jumlah</th>
                    <th style="width: 80px;" class="text-center">Satuan</th>
                    <th style="width: 150px;" class="text-end">Harga Satuan</th>
                    <th style="width: 160px;" class="text-end">Total Harga (IDR)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrder->items as $idx => $item)
                    <tr>
                        <td class="text-center text-secondary-light">{{ $idx + 1 }}</td>
                        <td>
                            <div class="fw-semibold text-primary-light">{{ $item->item_name }}</div>
                            @if($item->specifications)
                                <div class="text-secondary-light text-xxs">{{ $item->specifications }}</div>
                            @endif
                        </td>
                        <td class="text-center fw-medium">{{ number_format($item->qty, 0) }}</td>
                        <td class="text-center text-secondary-light">{{ $item->unit }}</td>
                        <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-end fw-semibold text-primary-light">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" rowspan="4" class="align-top p-16 bg-neutral-50 border-end">
                        <span class="text-xxs fw-bold text-uppercase text-secondary-light d-block mb-1">Syarat & Ketentuan Pengadaan:</span>
                        <div class="text-xxs text-secondary-light mb-2">
                            {!! nl2br(e($purchaseOrder->terms_conditions ?? "1. Surat Jalan dan Invoice wajib mencantumkan Nomor PO ini.\n2. Barang harus disertai Mill Test Certificate (MTC) dan lolos inspeksi QC di site.\n3. Penagihan ditujukan ke Departemen Keuangan PT ELBEMA Integra Indonesia.")) !!}
                        </div>
                        @if($purchaseOrder->notes)
                            <div class="text-xxs text-primary-600 mt-2">
                                <strong>Catatan:</strong> {{ $purchaseOrder->notes }}
                            </div>
                        @endif
                    </td>
                    <td class="text-end fw-semibold text-secondary-light">Subtotal (DPP):</td>
                    <td class="text-end fw-semibold text-primary-light">Rp {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($purchaseOrder->discount_amount > 0)
                    <tr>
                        <td class="text-end text-secondary-light">Diskon:</td>
                        <td class="text-end text-danger-main">- Rp {{ number_format($purchaseOrder->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="text-end fw-semibold text-secondary-light">PPN ({{ $purchaseOrder->tax_rate }}%):</td>
                    <td class="text-end fw-semibold text-primary-light">Rp {{ number_format($purchaseOrder->tax_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="bg-primary-50">
                    <td class="text-end fw-bold text-primary-light fs-6">GRAND TOTAL:</td>
                    <td class="text-end fw-bold text-primary-600 fs-6">Rp {{ number_format($purchaseOrder->grand_total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    
    <div class="row text-center text-xs mt-32 pt-20">
        <div class="col-4">
            <span class="text-secondary-light d-block mb-60">Diterbitkan Oleh,</span>
            <div class="fw-bold text-primary-light border-bottom d-inline-block pb-1 px-24">{{ $purchaseOrder->issuedByUser?->name ?? 'Procurement Officer' }}</div>
            <div class="text-xxs text-secondary-light mt-1">Departemen Procurement</div>
        </div>
        <div class="col-4">
            <span class="text-secondary-light d-block mb-60">Disetujui Oleh,</span>
            <div class="fw-bold text-primary-light border-bottom d-inline-block pb-1 px-24">Juliyanto / Direktur Operasional</div>
            <div class="text-xxs text-secondary-light mt-1">PT ELBEMA Integra Indonesia</div>
        </div>
        <div class="col-4">
            <span class="text-secondary-light d-block mb-60">Dikonfirmasi & Diterima Oleh,</span>
            <div class="fw-bold text-primary-light border-bottom d-inline-block pb-1 px-24">{{ $purchaseOrder->vendor?->contact_person ?? 'Perwakilan Vendor' }}</div>
            <div class="text-xxs text-secondary-light mt-1">{{ $purchaseOrder->vendor?->name }}</div>
        </div>
    </div>
</div>
@endsection
