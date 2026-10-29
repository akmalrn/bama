@extends('layout.layout')

@php
    $title = 'Dashboard Asset & Peralatan';
    $subTitle = 'Monitoring Utilisasi, Lokasi Alat, & Jadwal Kalibrasi Mesin — PT ELBEMA Integra Indonesia';
@endphp

@section('content')


<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-20">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">Asset Control</span>
            <span class="badge bg-success-50 text-success-700 border border-success-200 px-8 py-2 text-xs fw-semibold d-inline-flex align-items-center gap-1">
                <span class="w-6-px h-6-px radius-circle bg-success-main d-inline-block"></span>
                Workshop &amp; Site Ready
            </span>
        </div>
        <h5 class="mb-2 fw-bold text-primary-light">Manajemen Asset &amp; Mesin Spesialisasi</h5>
        <p class="text-secondary-light mb-0 text-xs">
            Pengawasan ketersediaan unit UHP Hydrojetting, Torque Wrench, Machining Tool, dan sertifikasi kelaikan.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        @can('asset.allocation.request')
            <a href="{{ route('dashboard-admin.assets.allocations.index') }}" class="btn btn-outline-primary btn-sm radius-8 px-14 py-8 text-xs fw-semibold d-flex align-items-center gap-1">
                <iconify-icon icon="solar:routing-2-bold-duotone" class="text-sm"></iconify-icon>
                Alokasi Lapangan
            </a>
        @endcan
        @can('asset.inventory.manage')
            <a href="{{ route('dashboard-admin.assets.inventory.index') }}" class="btn btn-primary btn-sm radius-8 px-14 py-8 text-xs fw-semibold d-flex align-items-center gap-1 shadow-sm">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="text-sm"></iconify-icon>
                + Tambah Asset Baru
            </a>
        @endcan
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-semibold text-secondary-light">Total Valuasi Asset</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-lg">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <h5 class="fw-bold text-primary-light mb-2 text-nowrap" title="Rp {{ number_format($totalValuation, 0, ',', '.') }}">
                    Rp {{ number_format($totalValuation / 1000000000, 2, ',', '.') }} M
                </h5>
                <span class="text-2xs text-secondary-light">
                    {{ $totalAssets }} unit mesin &amp; instrumen aktif
                </span>
            </div>
            <div class="pt-8 border-top mt-12 d-flex justify-content-between align-items-center text-2xs">
                <span class="text-secondary-light">Peralatan Spesialisasi</span>
                <span class="fw-bold text-primary-600">Terdaftar</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-semibold text-secondary-light">Utilisasi Lapangan</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-600 text-lg">
                        <iconify-icon icon="solar:pie-chart-2-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <h5 class="fw-bold text-primary-light mb-2">
                    {{ $utilizationRate }}%
                </h5>
                <span class="text-2xs text-secondary-light">
                    <span class="text-success-main fw-bold">{{ $mobilizedAssets }} Unit</span> bekerja di proyek klien
                </span>
            </div>
            <div class="pt-8 border-top mt-12 d-flex justify-content-between align-items-center text-2xs">
                <span class="text-secondary-light">Standby di Workshop</span>
                <span class="fw-bold text-success-600">{{ $availableAssets }} Siap Pakai</span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-semibold text-secondary-light">Perawatan &amp; Kalibrasi</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-warning-50 text-warning-700 text-lg">
                        <iconify-icon icon="solar:wrench-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <h5 class="fw-bold text-warning-700 mb-2">
                    {{ $urgentMaintenanceCount }} Jadwal
                </h5>
                <span class="text-2xs text-secondary-light">
                    Jatuh tempo servis dalam ≤ 30 hari
                </span>
            </div>
            <div class="pt-8 border-top mt-12 d-flex justify-content-between align-items-center text-2xs">
                <span class="text-secondary-light">Dalam Perbaikan</span>
                <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-6 py-2 radius-4 text-2xs fw-bold">
                    {{ $underMaintenance }} Unit
                </span>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <span class="text-xs fw-semibold text-secondary-light">Kesiapan Operasional</span>
                    <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-info-50 text-info-main text-lg">
                        <iconify-icon icon="solar:shield-check-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <h5 class="fw-bold text-success-main mb-2">
                    {{ $totalAssets > 0 ? round((($totalAssets - $underMaintenance) / $totalAssets) * 100, 1) : 100 }}%
                </h5>
                <span class="text-2xs text-secondary-light">
                    Unit laik operasi tanpa kendala kritis
                </span>
            </div>
            <div class="pt-8 border-top mt-12 d-flex justify-content-between align-items-center text-2xs">
                <span class="text-secondary-light">Kondisi Rusak / Servis</span>
                <span class="fw-bold text-danger-main">{{ $underMaintenance }} Unit</span>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-lg-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm h-100">
            <div class="card-header border-bottom py-12 px-16 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-primary-light mb-0 text-sm">Distribusi Kategori Asset</h6>
                    <span class="text-2xs text-secondary-light">Pengelompokan alat berdasarkan fungsi operasional</span>
                </div>
                <span class="badge bg-neutral-100 text-secondary-light border border-neutral-200 text-2xs px-8 py-3 radius-4 fw-medium">
                    {{ count($categoryBreakdown) }} Kategori
                </span>
            </div>

            <div class="card-body p-16 d-flex flex-column gap-12">
                @foreach ($categoryBreakdown as $cat)
                    @php
                        $pct = $totalAssets > 0 ? round(($cat->count / $totalAssets) * 100) : 0;
                        $color = match($cat->category) {
                            'High Pressure Pump' => '#2563eb',
                            'Flange & Bolting Tool' => '#16a34a',
                            'Machining Tool' => '#d97706',
                            'Compressor & Power' => '#7c3aed',
                            'Testing Instrument' => '#0284c7',
                            default => '#64748b'
                        };
                    @endphp
                    <div>
                        <div class="d-flex justify-content-between align-items-center text-xs mb-1">
                            <span class="fw-semibold text-primary-light">{{ $cat->category }}</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-secondary-light font-monospace text-2xs">Rp {{ number_format($cat->total_val / 1000000, 0, ',', '.') }} Jt</span>
                                <span class="fw-bold text-primary-600">{{ $cat->count }} Unit</span>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 3px;">
                            <div class="progress-bar radius-3" style="width: {{ $pct }}%; background-color: {{ $color }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm h-100">
            <div class="card-header border-bottom py-12 px-16 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-primary-light mb-0 text-sm">Status Unit Kritis &amp; Kalibrasi</h6>
                    <span class="text-2xs text-secondary-light">Mesin dalam perbaikan atau membutuhkan inspeksi ulang</span>
                </div>
                <span class="badge bg-danger-50 text-danger-main border border-danger-200 text-2xs fw-bold">
                    {{ count($flaggedAssets) }} Perlu Atensi
                </span>
            </div>

            <div class="card-body p-16 d-flex flex-column gap-12">
                @forelse ($flaggedAssets as $fl)
                    <div class="d-flex align-items-center justify-content-between gap-3 pb-10 border-bottom {{ $loop->last ? 'border-bottom-0 pb-0' : '' }}">
                        <div class="d-flex align-items-center gap-10">
                            <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-danger-50 text-danger-main text-base flex-shrink-0">
                                <iconify-icon icon="solar:danger-triangle-bold-duotone"></iconify-icon>
                            </div>
                            <div>
                                <span class="fw-semibold text-primary-light text-xs d-block">
                                    {{ $fl->name }}
                                </span>
                                <span class="text-2xs text-secondary-light d-block">
                                    {{ $fl->asset_code }} · {{ $fl->brand_model }}
                                </span>
                            </div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <span class="badge bg-danger-50 text-danger-main border border-danger-200 px-8 py-3 radius-4 text-2xs fw-bold text-uppercase d-block mb-1">
                                {{ str_replace('_', ' ', $fl->status) }}
                            </span>
                            <span class="text-3xs text-secondary-light">{{ $fl->current_location }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-24 text-secondary-light text-xs">
                        <iconify-icon icon="solar:check-circle-line" class="text-2xl text-success-main mb-1 d-block"></iconify-icon>
                        Seluruh asset dalam kondisi baik dan siap digunakan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border border-neutral-200 shadow-sm mb-24 overflow-hidden">
    <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="fw-bold text-primary-light mb-0 text-sm">Alokasi Asset Lapangan Terkini (Proyek Aktif)</h6>
            <span class="text-2xs text-secondary-light">Monitoring penugasan mesin dan surat jalan pengembalian</span>
        </div>
        <a href="{{ route('dashboard-admin.assets.allocations.index') }}" class="text-xs fw-semibold text-primary-600 hover-text-primary d-inline-flex align-items-center gap-1">
            Lihat Semua Alokasi <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table bordered-table mb-0 align-middle">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">Kode &amp; Nama Asset</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">Proyek / Klien</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">Lokasi Site</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Tgl Mobilisasi</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Estimasi Kembali</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Status</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center" width="80">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activeAllocations as $alc)
                    <tr>
                        <td class="px-16 py-12">
                            <span class="fw-bold text-primary-light text-sm d-block">{{ $alc->asset->name ?? '-' }}</span>
                            <span class="text-2xs text-secondary-light font-monospace">{{ $alc->asset->asset_code ?? '-' }} · {{ $alc->asset->category ?? '-' }}</span>
                        </td>
                        <td class="px-16 py-12 text-xs text-primary-light fw-medium">
                            {{ $alc->project->title ?? 'Proyek Terjadwal' }}
                        </td>
                        <td class="px-16 py-12 text-xs text-secondary-light">
                            <iconify-icon icon="solar:map-point-bold-duotone" class="text-primary-600 align-middle me-1"></iconify-icon>
                            {{ $alc->destination_site }}
                        </td>
                        <td class="px-16 py-12 text-center text-xs text-secondary-light font-monospace">
                            {{ $alc->start_date->format('d M Y') }}
                        </td>
                        <td class="px-16 py-12 text-center text-xs text-secondary-light font-monospace">
                            {{ $alc->expected_return_date->format('d M Y') }}
                        </td>
                        <td class="px-16 py-12 text-center">
                            <span class="badge bg-success-50 text-success-700 border border-success-200 px-8 py-3 radius-4 text-2xs fw-bold text-uppercase">
                                Lapangan Aktif
                            </span>
                        </td>
                        <td class="px-16 py-12 text-center">
                            <a href="{{ route('dashboard-admin.assets.allocations.index') }}"
                               class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center"
                               title="Lihat Alokasi">
                                <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-20 text-secondary-light text-xs">
                            Belum ada alokasi mesin lapangan yang aktif saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
