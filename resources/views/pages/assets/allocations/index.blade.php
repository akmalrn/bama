@extends('layout.layout')

@php
    $title = 'Alokasi & Mobilisasi Asset Lapangan';
    $subTitle = 'Pengawasan Penugasan Mesin Proyek, Surat Jalan (BAST), & Pengembalian — PT ELBEMA Integra Indonesia';
@endphp

@section('content')
{{-- Alert Notification --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Header Banner & Quick Action --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-20">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">Site Mobilization</span>
            <span class="badge bg-success-50 text-success-700 border border-success-200 px-8 py-2 text-xs fw-semibold">
                {{ $activeAllocations }} Unit Sedang di Site
            </span>
        </div>
        <h5 class="mb-2 fw-bold text-primary-light">Alokasi Mesin &amp; Peralatan ke Proyek Lapangan</h5>
        <p class="text-secondary-light mb-0 text-xs">
            Lacak perpindahan alat dari workshop ke plant industri migas, nomor surat jalan (BAST), dan pencatatan kondisi saat kembali.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        @can('asset.allocation.request')
            <button type="button" class="btn btn-primary btn-sm radius-8 px-14 py-8 text-xs fw-semibold d-flex align-items-center gap-1 shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#createAllocationModal">
                <iconify-icon icon="solar:routing-2-bold-duotone" class="text-sm"></iconify-icon>
                + Mobilisasi Asset ke Proyek
            </button>
        @endcan
    </div>
</div>

{{-- SECTION 1: 3 METRIC STATUS CARDS --}}
<div class="row g-3 mb-20">
    <div class="col-md-4">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-secondary-light d-block mb-1">Total Riwayat Alokasi</span>
            <h5 class="fw-bold text-primary-light mb-0">{{ $totalAllocations }}</h5>
            <span class="text-2xs text-secondary-light">Akumulasi mobilisasi seluruh proyek</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-success-main d-block mb-1">Alokasi Lapangan Aktif</span>
            <h5 class="fw-bold text-success-main mb-0">{{ $activeAllocations }}</h5>
            <span class="text-2xs text-secondary-light">Unit beroperasi di site klien</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-primary-600 d-block mb-1">Sudah Dikembalikan (Returned)</span>
            <h5 class="fw-bold text-primary-600 mb-0">{{ $returnedAllocations }}</h5>
            <span class="text-2xs text-secondary-light">Telah diterima kembali di workshop</span>
        </div>
    </div>
</div>

{{-- SECTION 2: FILTER & DATA TABLE --}}
<div class="card radius-12 border border-neutral-200 shadow-sm mb-24 overflow-hidden">
    <div class="card-header border-bottom py-14 px-20">
        <form method="GET" action="{{ route('dashboard-admin.assets.allocations.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5 col-sm-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-neutral-100 border-neutral-300 text-secondary-light">
                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control text-xs border-neutral-300"
                           placeholder="Cari no alokasi, nama asset, site tujuan, no BAST...">
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <select name="status" class="form-select form-select-sm text-xs border-neutral-300">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif di Lapangan</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Selesai / Dikembalikan</option>
                </select>
            </div>

            <div class="col-md-2 col-sm-6">
                <select name="project_id" class="form-select form-select-sm text-xs border-neutral-300">
                    <option value="">Semua Proyek</option>
                    @foreach ($projects as $prj)
                        <option value="{{ $prj->id }}" {{ request('project_id') == $prj->id ? 'selected' : '' }}>
                            {{ $prj->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary btn-sm radius-8 px-12 py-6 w-100 text-xs d-flex align-items-center justify-content-center gap-1">
                    <iconify-icon icon="solar:filter-bold-duotone"></iconify-icon> Filter
                </button>
                @if (request()->hasAny(['search', 'status', 'project_id']))
                    <a href="{{ route('dashboard-admin.assets.allocations.index') }}" class="btn btn-outline-secondary btn-sm radius-8 px-10 py-6" title="Reset filter">
                        <iconify-icon icon="solar:restart-bold"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table bordered-table mb-0 align-middle">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">No. Alokasi &amp; Asset</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">Proyek PMO Tujuan</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">Site Lapangan</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Tgl Berangkat</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Est. Kembali</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Status</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center" width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($allocations as $alc)
                    @php
                        $isReturned = $alc->status === 'returned';
                        $statusBadge = $isReturned
                            ? 'bg-secondary-focus text-secondary-main border border-secondary-subtle'
                            : 'bg-success-focus text-success-main border border-success-subtle';
                    @endphp
                    <tr>
                        <td class="px-16 py-12">
                            <span class="fw-bold text-primary-light text-sm d-block">{{ $alc->asset->name ?? '-' }}</span>
                            <div class="text-2xs text-secondary-light d-flex align-items-center gap-1">
                                <span class="font-monospace text-primary-600 fw-semibold">{{ $alc->allocation_number }}</span>
                                <span>·</span>
                                <span>{{ $alc->asset->asset_code ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-16 py-12 text-xs text-primary-light fw-medium">
                            {{ $alc->project->title ?? 'Proyek Terjadwal' }}
                        </td>
                        <td class="px-16 py-12 text-xs text-secondary-light">
                            <iconify-icon icon="solar:map-point-bold-duotone" class="text-primary-600 align-middle me-1"></iconify-icon>
                            {{ $alc->destination_site }}
                            @if ($alc->letter_of_handover_number)
                                <span class="text-3xs text-secondary-light font-monospace d-block mt-1">
                                    BAST: {{ $alc->letter_of_handover_number }}
                                </span>
                            @endif
                        </td>
                        <td class="px-16 py-12 text-center text-xs text-secondary-light font-monospace">
                            {{ $alc->start_date->format('d M Y') }}
                        </td>
                        <td class="px-16 py-12 text-center text-xs text-secondary-light font-monospace">
                            {{ $alc->expected_return_date->format('d M Y') }}
                            @if ($isReturned && $alc->actual_return_date)
                                <span class="text-3xs text-success-main d-block">
                                    Kembali: {{ $alc->actual_return_date->format('d M Y') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-16 py-12 text-center">
                            <span class="badge {{ $statusBadge }} px-8 py-3 radius-4 text-2xs fw-bold text-uppercase">
                                {{ $isReturned ? 'Dikembalikan' : 'Aktif di Site' }}
                            </span>
                        </td>
                        <td class="px-16 py-12 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                {{-- View Detail Button --}}
                                <button type="button"
                                        class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center border-0"
                                        title="Detail Alokasi"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailAllocationModal{{ $alc->id }}">
                                    <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                </button>

                                @can('asset.allocation.approve')
                                    @if (!$isReturned)
                                        {{-- Return Asset Button --}}
                                        <button type="button"
                                                class="w-32-px h-32-px rounded-circle bg-success-focus text-success-main d-inline-flex justify-content-center align-items-center border-0"
                                                title="Catat Pengembalian Asset"
                                                data-bs-toggle="modal"
                                                data-bs-target="#returnAssetModal{{ $alc->id }}">
                                            <iconify-icon icon="solar:inbox-in-bold-duotone"></iconify-icon>
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL DETAIL ALOKASI --}}
                    <div class="modal fade" id="detailAllocationModal{{ $alc->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
                                <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                                    <h6 class="modal-title fw-bold text-primary-light mb-0">Alokasi #{{ $alc->allocation_number }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-24 text-xs">
                                    <div class="mb-16">
                                        <span class="text-secondary-light d-block mb-1">Asset:</span>
                                        <strong class="text-primary-light text-sm">{{ $alc->asset->name ?? '-' }} ({{ $alc->asset->asset_code ?? '-' }})</strong>
                                    </div>
                                    <div class="mb-16">
                                        <span class="text-secondary-light d-block mb-1">Proyek / Site Tujuan:</span>
                                        <strong class="text-primary-light">{{ $alc->project->title ?? '-' }} · {{ $alc->destination_site }}</strong>
                                    </div>
                                    <div class="row g-2 mb-16">
                                        <div class="col-6">
                                            <span class="text-secondary-light d-block mb-1">Tgl Mobilisasi:</span>
                                            <span class="text-primary-light font-monospace">{{ $alc->start_date->format('d M Y') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-secondary-light d-block mb-1">Est. Pengembalian:</span>
                                            <span class="text-primary-light font-monospace">{{ $alc->expected_return_date->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-16">
                                        <span class="text-secondary-light d-block mb-1">Armada / Pengiriman:</span>
                                        <span class="text-primary-light">{{ $alc->courier_vehicle ?? '-' }}</span>
                                    </div>
                                    <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200 mb-16">
                                        <span class="fw-semibold text-primary-light d-block mb-1">Kondisi Saat Keluar:</span>
                                        <p class="text-2xs text-secondary-light mb-0">{{ $alc->condition_out ?? 'Normal tanpa catatan.' }}</p>
                                    </div>
                                    @if ($isReturned)
                                        <div class="p-12 radius-8 bg-success-50 border border-success-200">
                                            <span class="fw-semibold text-success-800 d-block mb-1">Kondisi Diterima Kembali ({{ $alc->actual_return_date?->format('d M Y') }}):</span>
                                            <p class="text-2xs text-success-700 mb-0">{{ $alc->condition_in ?? 'Diterima dalam kondisi baik.' }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer py-12 px-24 border-top">
                                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-16" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL PENGEMBALIAN ASSET --}}
                    @can('asset.allocation.approve')
                        @if (!$isReturned)
                            <div class="modal fade" id="returnAssetModal{{ $alc->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
                                        <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                                            <h6 class="modal-title fw-bold text-primary-light mb-0">Catat Pengembalian Asset ke Workshop</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('dashboard-admin.assets.allocations.return', $alc) }}" method="POST">
                                            @csrf
                                            <div class="modal-body p-24">
                                                <div class="mb-16">
                                                    <span class="text-xs text-secondary-light d-block">Unit Asset:</span>
                                                    <strong class="text-primary-light text-sm">{{ $alc->asset->name ?? '-' }} ({{ $alc->asset->asset_code ?? '-' }})</strong>
                                                </div>
                                                <div class="mb-16">
                                                    <label class="form-label text-xs fw-semibold">Tanggal Diterima Kembali <span class="text-danger">*</span></label>
                                                    <input type="date" name="actual_return_date" value="{{ date('Y-m-d') }}" class="form-control form-control-sm radius-8 text-xs" required>
                                                </div>
                                                <div class="mb-16">
                                                    <label class="form-label text-xs fw-semibold">Lokasi Penyimpanan Gudang <span class="text-danger">*</span></label>
                                                    <input type="text" name="return_location" value="Workshop Balikpapan" class="form-control form-control-sm radius-8 text-xs" required>
                                                </div>
                                                <div class="mb-16">
                                                    <label class="form-label text-xs fw-semibold">Kondisi Alat Saat Diterima <span class="text-danger">*</span></label>
                                                    <textarea name="condition_in" class="form-control radius-8 text-xs" rows="2"
                                                              placeholder="Contoh: Unit bersih, tekanan pompa normal, hose & nozzle lengkap tanpa cacat..." required></textarea>
                                                </div>
                                                <div>
                                                    <label class="form-label text-xs fw-semibold">Catatan BAP Serah Terima</label>
                                                    <textarea name="notes" class="form-control radius-8 text-xs" rows="2" placeholder="Catatan opsional dari inspector gudang..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer py-12 px-24 border-top">
                                                <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-16" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success btn-sm radius-8 px-16">Konfirmasi Penerimaan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endcan

                @empty
                    <tr>
                        <td colspan="7" class="text-center py-20 text-secondary-light text-xs">
                            Belum ada riwayat alokasi asset yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($allocations->hasPages())
        <div class="card-footer border-top py-12 px-20">
            {{ $allocations->links() }}
        </div>
    @endif
</div>

{{-- MODAL TAMBAH ALOKASI ASSET KE PROYEK --}}
@can('asset.allocation.request')
<div class="modal fade" id="createAllocationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
            <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-36-px h-36-px radius-8 bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:routing-2-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-primary-light mb-0">Form Mobilisasi Asset ke Proyek</h6>
                        <span class="text-secondary-light text-2xs">Nomor surat alokasi otomatis digenerate dengan format ALC-2026-xxx</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.assets.allocations.store') }}" method="POST">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Pilih Unit Mesin / Asset <span class="text-danger">*</span></label>
                            <select name="asset_id" class="form-select form-select-sm radius-8 text-xs" required>
                                <option value="">-- Pilih Asset Tersedia --</option>
                                @foreach ($availableAssets as $av)
                                    <option value="{{ $av->id }}">{{ $av->name }} ({{ $av->asset_code }}) - {{ $av->current_location }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Proyek Lapangan Tujuan <span class="text-danger">*</span></label>
                            <select name="project_id" class="form-select form-select-sm radius-8 text-xs" required>
                                <option value="">-- Pilih Proyek PMO --</option>
                                @foreach ($projects as $prj)
                                    <option value="{{ $prj->id }}">{{ $prj->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Tanggal Berangkat / Mobilisasi <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="form-control form-control-sm radius-8 text-xs" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Estimasi Tanggal Pengembalian <span class="text-danger">*</span></label>
                            <input type="date" name="expected_return_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="form-control form-control-sm radius-8 text-xs" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Lokasi Site Spesifik <span class="text-danger">*</span></label>
                            <input type="text" name="destination_site" class="form-control form-control-sm radius-8 text-xs"
                                   placeholder="Contoh: Site Bontang (Area Ammonia 1B / Kiln Sorowako)" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Nomor BAST / Surat Jalan</label>
                            <input type="text" name="letter_of_handover_number" class="form-control form-control-sm radius-8 text-xs"
                                   placeholder="Contoh: BAST-AST/PKT/2026/099">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Armada Kendaraan / Ekspedisi</label>
                            <input type="text" name="courier_vehicle" class="form-control form-control-sm radius-8 text-xs"
                                   placeholder="Contoh: Trailer Flatbed B-9122-UW / Kapal Roro">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Penanggung Jawab Lapangan (PIC)</label>
                            <select name="requested_by" class="form-select form-select-sm radius-8 text-xs">
                                <option value="">-- Pilih Karyawan PIC --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold">Kondisi Fisik &amp; Kelengkapan Saat Berangkat</label>
                            <textarea name="condition_out" class="form-control radius-8 text-xs" rows="2"
                                      placeholder="Catat kondisi mesin, kabel power, selang, nozzle, sertifikat kalibrasi yang disertakan..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold">Catatan Operasional</label>
                            <textarea name="notes" class="form-control radius-8 text-xs" rows="2"
                                      placeholder="Instruksi handling, jam kerja maksimal di site, dll."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-12 px-24 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-16" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm radius-8 px-16">Mobilisasi Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection
