@extends('layout.layout')

@php
    $title = 'Corporate Finance Tax Management';
    $subTitle = 'Finance & Tax';
    $script = '';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0">Corporate Finance & Tax Management</h6>
        <p class="text-neutral-500 text-sm mb-0">Rekapitulasi SPT Masa PPN 11% (Pajak Keluaran vs Masukan), pemotongan PPh 21/23/4(2), dan pengarsipan bukti setor NTPN / BPE DJP.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <form method="GET" action="{{ route('dashboard-admin.finance.tax.index') }}" class="d-flex align-items-center gap-2">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <select name="year" class="form-select form-select-sm text-sm" onchange="this.form.submit()">
                @for($y = date('Y') + 1; $y >= 2024; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun Pajak {{ $y }}</option>
                @endfor
            </select>
        </form>
        <button type="button" class="btn btn-primary btn-sm px-16 py-8 d-flex align-items-center gap-2 text-nowrap" data-bs-toggle="modal" data-bs-target="#createTaxFilingModal">
            <iconify-icon icon="solar:document-add-bold" class="text-lg"></iconify-icon>
            <span>+ Rekam Setoran Pajak / NTPN</span>
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 radius-8 mb-24" role="alert">
        <iconify-icon icon="solar:check-circle-bold" class="text-xl flex-shrink-0"></iconify-icon>
        <div class="text-sm fw-medium">{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show radius-8 mb-24" role="alert">
        <div class="d-flex align-items-center gap-2 mb-1">
            <iconify-icon icon="solar:danger-triangle-bold" class="text-xl flex-shrink-0"></iconify-icon>
            <span class="text-sm fw-bold">Gagal menyimpan data pajak:</span>
        </div>
        <ul class="mb-0 text-xs ps-24">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- 4 KPI Summary Cards --}}
<div class="row g-3 mb-24">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Pajak Keluaran (PK)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">Rp {{ number_format($totalOutputTax, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:hand-money-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>PPN 11% dari Faktur Penjualan</span>
                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200">Sales AR</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Pajak Masukan (PM)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">Rp {{ number_format($totalInputTax, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-info-100 text-info-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:card-send-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>PPN 11% dari Faktur Vendor</span>
                    <span class="badge bg-info-50 text-info-700 border border-info-200">Purchases AP</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider {{ $netPpnPayable >= 0 ? 'text-warning-main' : 'text-success-main' }}">
                            {{ $netPpnPayable >= 0 ? 'Net Kurang Bayar (KB)' : 'Net Lebih Bayar (LB)' }}
                        </span>
                        <h4 class="fw-bold mb-0 mt-2 {{ $netPpnPayable >= 0 ? 'text-warning-main' : 'text-success-main' }} fs-5">
                            Rp {{ number_format(abs($netPpnPayable), 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center {{ $netPpnPayable >= 0 ? 'bg-warning-100 text-warning-main' : 'bg-success-100 text-success-main' }} text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:shield-check-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Selisih PK dikurangi PM</span>
                    <span class="badge {{ $netPpnPayable >= 0 ? 'bg-warning-50 text-warning-main border border-warning-200' : 'bg-success-50 text-success-700 border border-success-200' }}">
                        {{ $netPpnPayable >= 0 ? 'Kewajiban Billing' : 'Kompensasi SPT' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Kredit PPh 23 Klien</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">Rp {{ number_format($pphKreditClient, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:diploma-verified-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Bukti potong pengurang PPh Badan</span>
                    <span class="badge bg-success-50 text-success-700 border border-success-200">Tax Credit</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Navigation Tabs --}}
<div class="card border-0 shadow-sm radius-12 mb-24">
    <div class="card-body p-16">
        <ul class="nav nav-pills gap-2">
            <li class="nav-item">
                <a class="nav-link py-8 px-20 text-xs radius-8 {{ $activeTab === 'ppn' ? 'active' : '' }}" 
                   href="{{ route('dashboard-admin.finance.tax.index', ['year' => $year, 'tab' => 'ppn']) }}">
                    <iconify-icon icon="solar:calculator-minimalistic-bold-duotone" class="me-1 text-sm"></iconify-icon>
                    Rekonsiliasi SPT Masa PPN 11% (Bulanan)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-8 px-20 text-xs radius-8 {{ $activeTab === 'pph' ? 'active' : '' }}" 
                   href="{{ route('dashboard-admin.finance.tax.index', ['year' => $year, 'tab' => 'pph']) }}">
                    <iconify-icon icon="solar:document-text-bold-duotone" class="me-1 text-sm"></iconify-icon>
                    Bukti Potong PPh 23 & PPh 4(2)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-8 px-20 text-xs radius-8 {{ $activeTab === 'filings' ? 'active' : '' }}" 
                   href="{{ route('dashboard-admin.finance.tax.index', ['year' => $year, 'tab' => 'filings']) }}">
                    <iconify-icon icon="solar:folder-with-files-bold-duotone" class="me-1 text-sm"></iconify-icon>
                    Buku Arsip NTPN & Tanda Terima BPE DJP
                </a>
            </li>
        </ul>
    </div>
</div>

@if($activeTab === 'ppn')
    {{-- TAB 1: Rekonsiliasi SPT Masa PPN (Bulanan) --}}
    <div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
        <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="text-sm fw-semibold mb-0 text-primary-light">Rekonsiliasi Pajak Pertambahan Nilai (PPN 11%) — Tahun {{ $year }}</h6>
                <span class="text-2xs text-secondary-light">Kompilasi otomatis Faktur Pajak Keluaran (Penjualan) vs Faktur Pajak Masukan (Pembelian).</span>
            </div>
            <span class="badge bg-neutral-100 text-primary-light border border-neutral-200 text-xs px-10 py-4 radius-4">
                12 Masa Pajak
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">MASA PAJAK</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">DPP PENJUALAN</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">PAJAK KELUARAN (PK)</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">DPP PEMBELIAN</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">PAJAK MASUKAN (PM)</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">NET (KURANG / LEBIH BAYAR)</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light">STATUS SPT</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light" width="100">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyPpn as $m)
                            @php
                                $net = $m['net_amount'];
                                $statusBadge = 'bg-neutral-100 text-secondary-light border border-neutral-200';
                                $statusLabel = 'Belum Lapor';

                                if ($m['status'] === 'reported') {
                                    $statusBadge = 'bg-success-focus text-success-main border border-success-subtle';
                                    $statusLabel = 'Dilaporkan';
                                } elseif ($m['status'] === 'paid') {
                                    $statusBadge = 'bg-info-focus text-info-main border border-info-subtle';
                                    $statusLabel = 'Disetor';
                                } elseif ($net == 0) {
                                    $statusLabel = 'Nihil';
                                }
                            @endphp
                            <tr>
                                <td class="px-16 py-12">
                                    <span class="fw-semibold text-sm text-primary-light d-block">{{ $m['month_name'] }} {{ $year }}</span>
                                    <small class="text-2xs text-secondary-light">Masa {{ str_pad((string) $m['month_num'], 2, '0', STR_PAD_LEFT) }}</small>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-medium text-xs font-monospace text-secondary-light">
                                        Rp {{ number_format($m['dpp_sales'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-semibold text-sm font-monospace text-primary-600">
                                        Rp {{ number_format($m['output_tax'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-medium text-xs font-monospace text-secondary-light">
                                        Rp {{ number_format($m['dpp_purchases'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-semibold text-sm font-monospace text-info-main">
                                        Rp {{ number_format($m['input_tax'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-bold text-sm font-monospace {{ $net > 0 ? 'text-warning-main' : ($net < 0 ? 'text-success-main' : 'text-secondary-light') }}">
                                        {{ $net < 0 ? '(' : '' }}Rp {{ number_format(abs($net), 0, ',', '.') }}{{ $net < 0 ? ')' : '' }}
                                    </span>
                                    <small class="text-3xs d-block text-secondary-light">
                                        {{ $net > 0 ? 'Kurang Bayar' : ($net < 0 ? 'Lebih Bayar' : 'Nihil') }}
                                    </small>
                                </td>
                                <td class="px-16 py-12 text-center">
                                    <span class="badge {{ $statusBadge }} px-8 py-3 radius-4 text-xs fw-semibold">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-center">
                                    @if($net > 0 && $m['status'] !== 'reported')
                                        <button type="button" class="btn btn-sm btn-primary-subtle text-primary-600 radius-6 px-10 py-4 text-2xs fw-semibold btn-quick-file-tax"
                                                data-month="{{ $m['month_num'] }}"
                                                data-year="{{ $year }}"
                                                data-type="ppn"
                                                data-dpp="{{ $m['dpp_sales'] }}"
                                                data-pk="{{ $m['output_tax'] }}"
                                                data-pm="{{ $m['input_tax'] }}"
                                                data-due="{{ $net }}"
                                                title="Input NTPN Setoran PPN">
                                            Lapor
                                        </button>
                                    @else
                                        <span class="text-secondary-light text-2xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@elseif($activeTab === 'pph')
    {{-- TAB 2: Bukti Potong PPh (Withholding Tax) --}}
    <div class="row g-3 mb-24">
        {{-- Sisi Kiri: PPh Dipotong Klien (Kredit Pajak AR) --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
                <div class="card-header border-bottom py-14 px-16 bg-success-subtle">
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:diploma-verified-bold" class="text-success-main text-lg"></iconify-icon>
                        <h6 class="text-sm fw-semibold mb-0 text-success-main">PPh Dipotong Klien (Kredit Pajak Perusahaan)</h6>
                    </div>
                    <small class="text-2xs text-secondary-light">Potongan PPh 23 / 4(2) pada faktur penagihan invoice resmi ke klien.</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0 align-middle text-xs">
                            <thead class="bg-neutral-100">
                                <tr>
                                    <th class="py-10 px-12">INVOICE & KLIEN</th>
                                    <th class="py-10 px-12 text-center">JENIS</th>
                                    <th class="py-10 px-12 text-end">DPP</th>
                                    <th class="py-10 px-12 text-end">POTONGAN PPH</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withholdingInvoices as $wi)
                                    <tr>
                                        <td class="py-10 px-12">
                                            <span class="fw-bold font-monospace text-primary-light d-block">{{ $wi->invoice_number }}</span>
                                            <small class="text-secondary-light">{{ $wi->customer->company_name ?? '-' }}</small>
                                        </td>
                                        <td class="py-10 px-12 text-center">
                                            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 text-3xs">{{ strtoupper(str_replace('_', ' ', $wi->pph_type)) }}</span>
                                        </td>
                                        <td class="py-10 px-12 text-end font-monospace">Rp {{ number_format($wi->subtotal, 0, ',', '.') }}</td>
                                        <td class="py-10 px-12 text-end font-monospace fw-bold text-success-main">Rp {{ number_format($wi->pph_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-24 text-secondary-light">Belum ada invoice dengan potongan PPh pada tahun {{ $year }}.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: PPh Dipotong ke Vendor (Kewajiban Setor AP) --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
                <div class="card-header border-bottom py-14 px-16 bg-warning-subtle">
                    <div class="d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:card-send-bold" class="text-warning-main text-lg"></iconify-icon>
                        <h6 class="text-sm fw-semibold mb-0 text-warning-main">PPh Dipotong ke Vendor (Kewajiban Setor)</h6>
                    </div>
                    <small class="text-2xs text-secondary-light">Potongan PPh yang dipotong atas tagihan jasa rekanan/subkontraktor.</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0 align-middle text-xs">
                            <thead class="bg-neutral-100">
                                <tr>
                                    <th class="py-10 px-12">VENDOR & PROYEK</th>
                                    <th class="py-10 px-12">NO. REF</th>
                                    <th class="py-10 px-12 text-end">DPP</th>
                                    <th class="py-10 px-12 text-end">POTONGAN PPH</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withholdingCosts as $wc)
                                    <tr>
                                        <td class="py-10 px-12">
                                            <span class="fw-bold text-primary-light d-block">{{ $wc->vendor ?? 'Vendor' }}</span>
                                            <small class="text-secondary-light">{{ $wc->project->name ?? '-' }}</small>
                                        </td>
                                        <td class="py-10 px-12 font-monospace">{{ $wc->reference_no ?? '-' }}</td>
                                        <td class="py-10 px-12 text-end font-monospace">Rp {{ number_format($wc->amount, 0, ',', '.') }}</td>
                                        <td class="py-10 px-12 text-end font-monospace fw-bold text-warning-main">Rp {{ number_format($wc->pph_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-24 text-secondary-light">Belum ada pengeluaran dengan potongan PPh vendor.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif($activeTab === 'filings')
    {{-- TAB 3: Buku Arsip NTPN & Pelaporan SPT --}}
    <div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
        <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="text-sm fw-semibold mb-0 text-primary-light">Buku Arsip Bukti Setor NTPN & Tanda Terima Elektronik (BPE DJP)</h6>
                <span class="text-2xs text-secondary-light">Catatan resmi penyetoran kas negara dan kepatuhan perpajakan perusahaan.</span>
            </div>
            <button type="button" class="btn btn-primary btn-sm px-14 py-6" data-bs-toggle="modal" data-bs-target="#createTaxFilingModal">
                + Tambah Bukti Setor
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">MASA PAJAK</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light">JENIS PAJAK</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">NOMINAL DISETOR</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">KODE NTPN</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">NO. BPE DJP</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light">STATUS</th>
                            <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light" width="100">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($filings as $f)
                            <tr>
                                <td class="px-16 py-12">
                                    <span class="fw-semibold text-sm text-primary-light d-block">{{ $f->period_label }}</span>
                                    <small class="text-2xs text-secondary-light">Setor: {{ $f->payment_date ? $f->payment_date->format('d M Y') : '-' }}</small>
                                </td>
                                <td class="px-16 py-12 text-center">
                                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200 text-xs px-8 py-3">
                                        {{ strtoupper(str_replace('_', ' ', $f->tax_type)) }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-end">
                                    <span class="fw-bold text-sm font-monospace text-primary-light">
                                        Rp {{ number_format($f->tax_due, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-16 py-12">
                                    <span class="fw-medium text-xs font-monospace text-success-main">{{ $f->ntpn ?? '-' }}</span>
                                </td>
                                <td class="px-16 py-12">
                                    <span class="fw-medium text-xs font-monospace text-primary-light">{{ $f->bpe_number ?? '-' }}</span>
                                </td>
                                <td class="px-16 py-12 text-center">
                                    <span class="badge {{ $f->status === 'reported' ? 'bg-success-focus text-success-main border border-success-subtle' : ($f->status === 'paid' ? 'bg-info-focus text-info-main border border-info-subtle' : 'bg-neutral-100 text-secondary-light') }} px-8 py-3 radius-4 text-xs fw-semibold">
                                        {{ ucfirst($f->status) }}
                                    </span>
                                </td>
                                <td class="px-16 py-12 text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        @if($f->bpe_file)
                                            <a href="{{ asset($f->bpe_file) }}" target="_blank" class="btn btn-sm btn-icon btn-light text-primary-600 rounded-circle w-32-px h-32-px d-flex align-items-center justify-content-center" title="Download Bukti BPE">
                                                <iconify-icon icon="solar:file-download-bold"></iconify-icon>
                                            </a>
                                        @endif
                                        <form action="{{ route('dashboard-admin.finance.tax.destroy', $f->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus arsip pajak ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-light text-danger-main rounded-circle w-32-px h-32-px d-flex align-items-center justify-content-center" title="Hapus">
                                                <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-32 text-secondary-light">Belum ada arsip pelaporan pajak pada tahun {{ $year }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

{{-- Modal: Rekam Setoran Pajak / NTPN / BPE DJP --}}
<div class="modal fade" id="createTaxFilingModal" tabindex="-1" aria-labelledby="createTaxFilingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light d-flex align-items-center gap-2" id="createTaxFilingModalLabel">
                    <iconify-icon icon="solar:document-add-bold" class="text-primary-600 text-xl"></iconify-icon>
                    Rekam Penyetoran Pajak & Bukti Lapor SPT Masa
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.finance.tax.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Jenis Pajak <span class="text-danger">*</span></label>
                            <select name="tax_type" id="modal_tax_type" class="form-select text-sm" required>
                                <option value="ppn" selected>PPN (Pajak Pertambahan Nilai)</option>
                                <option value="pph_23">PPh Pasal 23 (Jasa & Sewa)</option>
                                <option value="pph_4_2">PPh Final Pasal 4 Ayat 2 (Konstruksi)</option>
                                <option value="pph_21">PPh Pasal 21 (Tenaga Kerja / Karyawan)</option>
                                <option value="pph_badan">PPh Badan (Tahunan)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-sm fw-medium">Masa Pajak (Bulan) <span class="text-danger">*</span></label>
                            <select name="tax_period_month" id="modal_tax_month" class="form-select text-sm" required>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>Bulan {{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-sm fw-medium">Tahun Pajak <span class="text-danger">*</span></label>
                            <input type="number" name="tax_period_year" id="modal_tax_year" class="form-control text-sm" value="{{ $year }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Nilai Dasar Pengenaan Pajak (DPP)</label>
                            <div class="input-group">
                                <span class="input-group-text text-sm">Rp</span>
                                <input type="number" step="0.01" name="gross_base_amount" id="modal_gross_base" class="form-control text-sm font-monospace text-end" value="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Pajak Terutang / Nominal Disetor (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-sm">Rp</span>
                                <input type="number" step="0.01" name="tax_due" id="modal_tax_due" class="form-control text-sm font-monospace text-end fw-bold" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Kode NTPN (Nomor Transaksi Penerimaan Negara)</label>
                            <input type="text" name="ntpn" class="form-control text-sm font-monospace" placeholder="16 Karakter NTPN dari Bank/Kantor Pos">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Nomor BPE DJP (Bukti Penerimaan Elektronik)</label>
                            <input type="text" name="bpe_number" class="form-control text-sm font-monospace" placeholder="Nomor Tanda Terima SPT DJP Online">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Tanggal Pembayaran / Setor</label>
                            <input type="date" name="payment_date" class="form-control text-sm" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Tanggal Lapor SPT</label>
                            <input type="date" name="reporting_date" class="form-control text-sm" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select text-sm" required>
                                <option value="paid">Disetor (Menunggu Lapor)</option>
                                <option value="reported" selected>Dilaporkan (SPT Selesai)</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Upload File BPE DJP / Bukti SSP (PDF)</label>
                            <input type="file" name="bpe_file" class="form-control form-control-sm text-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Catatan / Keterangan Pembetulan</label>
                            <input type="text" name="notes" class="form-control form-control-sm text-sm" placeholder="SPT Masa Normal / Pembetulan 1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-light btn-sm px-16" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-20">Simpan Bukti Setor & Lapor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Quick File Tax Button handler from PPN Table
    document.querySelectorAll('.btn-quick-file-tax').forEach(btn => {
        btn.addEventListener('click', function () {
            const month = this.getAttribute('data-month');
            const year = this.getAttribute('data-year');
            const type = this.getAttribute('data-type');
            const dpp = this.getAttribute('data-dpp');
            const due = this.getAttribute('data-due');

            document.getElementById('modal_tax_month').value = month;
            document.getElementById('modal_tax_year').value = year;
            document.getElementById('modal_tax_type').value = type;
            document.getElementById('modal_gross_base').value = dpp;
            document.getElementById('modal_tax_due').value = due;

            const modal = new bootstrap.Modal(document.getElementById('createTaxFilingModal'));
            modal.show();
        });
    });
});
</script>
@endsection
