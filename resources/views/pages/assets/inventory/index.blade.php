@extends('layout.layout')

@php
    $title = 'Inventaris Mesin & Peralatan';
    $subTitle = 'Master Register Asset, Spesifikasi Teknis, & Status Kelaikan — PT ELBEMA Integra Indonesia';
@endphp

@section('content')


<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-20">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">Machinery Register</span>
            <span class="badge bg-info-50 text-info-700 border border-info-200 px-8 py-2 text-xs fw-semibold">
                {{ $totalCount }} Unit Terdaftar
            </span>
        </div>
        <h5 class="mb-2 fw-bold text-primary-light">Inventaris Mesin, Alat Berat, &amp; Instrumen</h5>
        <p class="text-secondary-light mb-0 text-xs">
            Kelola data mesin UHP pump, torque wrench, beveling machine, dan instrumen uji lapangan.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        @can('asset.inventory.manage')
            <button type="button" class="btn btn-primary btn-sm radius-8 px-14 py-8 text-xs fw-semibold d-flex align-items-center gap-1 shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#createAssetModal">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="text-sm"></iconify-icon>
                + Daftarkan Asset Baru
            </button>
        @endcan
    </div>
</div>


<div class="row g-3 mb-20">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-secondary-light d-block mb-1">Total Unit Asset</span>
            <h5 class="fw-bold text-primary-light mb-0">{{ $totalCount }}</h5>
            <span class="text-2xs text-secondary-light">Tercatat dalam basis data</span>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-success-main d-block mb-1">Tersedia di Workshop</span>
            <h5 class="fw-bold text-success-main mb-0">{{ $availableCount }}</h5>
            <span class="text-2xs text-secondary-light">Siap dimobilisasi segera</span>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-primary-600 d-block mb-1">Dimobilisasi ke Proyek</span>
            <h5 class="fw-bold text-primary-600 mb-0">{{ $mobilizedCount }}</h5>
            <span class="text-2xs text-secondary-light">Sedang bertugas di site klien</span>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-warning-700 d-block mb-1">Sedang Maintenance</span>
            <h5 class="fw-bold text-warning-700 mb-0">{{ $maintenanceCount }}</h5>
            <span class="text-2xs text-secondary-light">Dalam servis / perbaikan workshop</span>
        </div>
    </div>
</div>


<div class="card radius-12 border border-neutral-200 shadow-sm mb-24 overflow-hidden">
    <div class="card-header border-bottom py-14 px-20">
        <form method="GET" action="{{ route('dashboard-admin.assets.inventory.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4 col-sm-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-neutral-100 border-neutral-300 text-secondary-light">
                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control text-xs border-neutral-300"
                           placeholder="Cari kode asset, nama mesin, merk, serial number...">
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <select name="category" class="form-select form-select-sm text-xs border-neutral-300">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 col-sm-6">
                <select name="status" class="form-select form-select-sm text-xs border-neutral-300">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
                    <option value="mobilized" {{ request('status') == 'mobilized' ? 'selected' : '' }}>Dimobilisasi (Mobilized)</option>
                    <option value="under_maintenance" {{ request('status') == 'under_maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="decommissioned" {{ request('status') == 'decommissioned' ? 'selected' : '' }}>Non-Aktif / Afkir</option>
                </select>
            </div>

            <div class="col-md-2 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary btn-sm radius-8 px-12 py-6 w-100 text-xs d-flex align-items-center justify-content-center gap-1">
                    <iconify-icon icon="solar:filter-bold-duotone"></iconify-icon> Filter
                </button>
                @if (request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('dashboard-admin.assets.inventory.index') }}" class="btn btn-outline-secondary btn-sm radius-8 px-10 py-6" title="Reset filter">
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
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">Kode &amp; Nama Mesin</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">Kategori &amp; Spesifikasi</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">Lokasi Saat Ini</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Kondisi Fisik</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Status Operasional</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Next Service</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center" width="110">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assets as $asset)
                    @php
                        $condBadge = match($asset->condition) {
                            'good' => 'bg-success-50 text-success-700 border border-success-200',
                            'fair' => 'bg-info-50 text-info-700 border border-info-200',
                            'maintenance_needed' => 'bg-warning-50 text-warning-700 border border-warning-200',
                            'damaged' => 'bg-danger-50 text-danger-700 border border-danger-200',
                            default => 'bg-neutral-100 text-secondary-light'
                        };

                        $statusBadge = match($asset->status) {
                            'available' => 'bg-success-focus text-success-main border border-success-subtle',
                            'mobilized' => 'bg-primary-focus text-primary-main border border-primary-subtle',
                            'under_maintenance' => 'bg-warning-focus text-warning-main border border-warning-subtle',
                            'decommissioned' => 'bg-secondary-focus text-secondary-main border border-secondary-subtle',
                            default => 'bg-neutral-100 text-secondary-light'
                        };
                    @endphp
                    <tr>
                        <td class="px-16 py-12">
                            <span class="fw-bold text-primary-light text-sm d-block">{{ $asset->name }}</span>
                            <div class="text-2xs text-secondary-light d-flex align-items-center gap-1">
                                <span class="font-monospace text-primary-600 fw-semibold">{{ $asset->asset_code }}</span>
                                <span>·</span>
                                <span>{{ $asset->brand_model ?? 'No Model' }}</span>
                            </div>
                        </td>
                        <td class="px-16 py-12">
                            <span class="badge bg-neutral-100 text-secondary-light border border-neutral-300 px-8 py-3 radius-4 text-2xs fw-semibold d-inline-block mb-1">
                                {{ $asset->category }}
                            </span>
                            <span class="text-3xs text-secondary-light d-block text-truncate" style="max-width: 220px;" title="{{ $asset->technical_specifications }}">
                                {{ $asset->technical_specifications ?? 'SN: ' . ($asset->serial_number ?? '-') }}
                            </span>
                        </td>
                        <td class="px-16 py-12 text-xs text-secondary-light">
                            <iconify-icon icon="solar:map-point-bold-duotone" class="text-primary-600 align-middle me-1"></iconify-icon>
                            {{ $asset->current_location }}
                            @if ($asset->assignedProject)
                                <span class="text-3xs text-primary-600 d-block mt-1">
                                    Proyek: {{ $asset->assignedProject->title }}
                                </span>
                            @endif
                        </td>
                        <td class="px-16 py-12 text-center">
                            <span class="badge {{ $condBadge }} px-8 py-3 radius-4 text-2xs fw-bold text-uppercase">
                                {{ str_replace('_', ' ', $asset->condition) }}
                            </span>
                        </td>
                        <td class="px-16 py-12 text-center">
                            <span class="badge {{ $statusBadge }} px-8 py-3 radius-4 text-2xs fw-bold text-uppercase">
                                {{ str_replace('_', ' ', $asset->status) }}
                            </span>
                        </td>
                        <td class="px-16 py-12 text-center text-xs text-secondary-light font-monospace">
                            {{ $asset->next_maintenance_date ? $asset->next_maintenance_date->format('d M Y') : '-' }}
                        </td>
                        <td class="px-16 py-12 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                
                                <button type="button"
                                        class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center border-0"
                                        title="Detail Asset"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailAssetModal{{ $asset->id }}">
                                    <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                </button>

                                @can('asset.inventory.manage')
                                    
                                    <button type="button"
                                            class="w-32-px h-32-px rounded-circle bg-success-focus text-success-main d-inline-flex justify-content-center align-items-center border-0"
                                            title="Edit Asset"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editAssetModal{{ $asset->id }}">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </button>

                                    
                                    <form action="{{ route('dashboard-admin.assets.inventory.destroy', $asset) }}" method="POST"
                                          onsubmit="return confirm('Hapus asset {{ $asset->asset_code }} ini dari inventaris?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus Asset">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>

                    
                    <div class="modal fade" id="detailAssetModal{{ $asset->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
                                <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="w-36-px h-36-px radius-8 bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center">
                                            <iconify-icon icon="solar:tuning-2-bold-duotone" class="text-lg"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 class="modal-title fw-bold text-primary-light mb-0">{{ $asset->name }}</h6>
                                            <span class="text-secondary-light text-2xs">{{ $asset->asset_code }} · {{ $asset->brand_model }}</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-24">
                                    <div class="row g-3 mb-20">
                                        <div class="col-md-4">
                                            <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                                                <span class="text-2xs text-secondary-light d-block mb-1">Status Operasi</span>
                                                <span class="badge {{ $statusBadge }} text-2xs fw-bold text-uppercase">
                                                    {{ str_replace('_', ' ', $asset->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                                                <span class="text-2xs text-secondary-light d-block mb-1">Kondisi Fisik</span>
                                                <span class="badge {{ $condBadge }} text-2xs fw-bold text-uppercase">
                                                    {{ str_replace('_', ' ', $asset->condition) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                                                <span class="text-2xs text-secondary-light d-block mb-1">Valuasi Pembelian</span>
                                                <strong class="text-primary-600 text-xs font-monospace">
                                                    Rp {{ number_format((float) $asset->purchase_price, 0, ',', '.') }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 text-xs mb-20">
                                        <div class="col-md-6">
                                            <span class="text-secondary-light d-block mb-1">Kategori:</span>
                                            <strong class="text-primary-light">{{ $asset->category }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-secondary-light d-block mb-1">Nomor Seri (Serial Number):</span>
                                            <strong class="text-primary-light font-monospace">{{ $asset->serial_number ?? '-' }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-secondary-light d-block mb-1">Lokasi Saat Ini:</span>
                                            <strong class="text-primary-light">{{ $asset->current_location }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-secondary-light d-block mb-1">Proyek Yang Ditugaskan:</span>
                                            <strong class="text-primary-light">{{ $asset->assignedProject->title ?? 'Belum Ditugaskan' }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-secondary-light d-block mb-1">Tgl Perawatan Terakhir:</span>
                                            <span class="text-primary-light font-monospace">{{ $asset->last_maintenance_date ? $asset->last_maintenance_date->format('d M Y') : '-' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-secondary-light d-block mb-1">Jadwal Perawatan Berikutnya:</span>
                                            <span class="text-primary-light font-monospace">{{ $asset->next_maintenance_date ? $asset->next_maintenance_date->format('d M Y') : '-' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-secondary-light d-block mb-1">Tgl Kalibrasi Terakhir:</span>
                                            <span class="text-primary-light font-monospace">{{ $asset->last_calibration_date ? $asset->last_calibration_date->format('d M Y') : '-' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-secondary-light d-block mb-1">Jadwal Kalibrasi Berikutnya:</span>
                                            <span class="text-primary-light font-monospace">{{ $asset->next_calibration_date ? $asset->next_calibration_date->format('d M Y') : '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200 mb-16">
                                        <span class="text-xs fw-semibold text-primary-light d-block mb-1">Spesifikasi Teknis:</span>
                                        <p class="text-2xs text-secondary-light mb-0">{{ $asset->technical_specifications ?? 'Tidak ada catatan spesifikasi tambahan.' }}</p>
                                    </div>

                                    @if($asset->notes)
                                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                                            <span class="text-xs fw-semibold text-primary-light d-block mb-1">Catatan Tambahan:</span>
                                            <p class="text-2xs text-secondary-light mb-0">{{ $asset->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer py-12 px-24 border-top">
                                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-16" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    @can('asset.inventory.manage')
                        <div class="modal fade" id="editAssetModal{{ $asset->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
                                    <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                                        <h6 class="modal-title fw-bold text-primary-light mb-0">Edit Asset #{{ $asset->asset_code }}</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('dashboard-admin.assets.inventory.update', $asset) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-24">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-xs fw-semibold">Nama Mesin / Asset <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" value="{{ $asset->name }}" class="form-control form-control-sm radius-8 text-xs" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-xs fw-semibold">Kategori <span class="text-danger">*</span></label>
                                                    <input type="text" name="category" value="{{ $asset->category }}" class="form-control form-control-sm radius-8 text-xs" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-xs fw-semibold">Merk &amp; Tipe Model</label>
                                                    <input type="text" name="brand_model" value="{{ $asset->brand_model }}" class="form-control form-control-sm radius-8 text-xs">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-xs fw-semibold">Nomor Seri (Serial Number)</label>
                                                    <input type="text" name="serial_number" value="{{ $asset->serial_number }}" class="form-control form-control-sm radius-8 text-xs">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label text-xs fw-semibold">Kondisi Fisik <span class="text-danger">*</span></label>
                                                    <select name="condition" class="form-select form-select-sm radius-8 text-xs" required>
                                                        <option value="good" {{ $asset->condition == 'good' ? 'selected' : '' }}>Baik (Good)</option>
                                                        <option value="fair" {{ $asset->condition == 'fair' ? 'selected' : '' }}>Cukup (Fair)</option>
                                                        <option value="maintenance_needed" {{ $asset->condition == 'maintenance_needed' ? 'selected' : '' }}>Perlu Maintenance</option>
                                                        <option value="damaged" {{ $asset->condition == 'damaged' ? 'selected' : '' }}>Rusak (Damaged)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label text-xs fw-semibold">Status Operasional <span class="text-danger">*</span></label>
                                                    <select name="status" class="form-select form-select-sm radius-8 text-xs" required>
                                                        <option value="available" {{ $asset->status == 'available' ? 'selected' : '' }}>Available (Siap Pakai)</option>
                                                        <option value="mobilized" {{ $asset->status == 'mobilized' ? 'selected' : '' }}>Mobilized (Di Lapangan)</option>
                                                        <option value="under_maintenance" {{ $asset->status == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                                        <option value="decommissioned" {{ $asset->status == 'decommissioned' ? 'selected' : '' }}>Decommissioned</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label text-xs fw-semibold">Lokasi Saat Ini <span class="text-danger">*</span></label>
                                                    <input type="text" name="current_location" value="{{ $asset->current_location }}" class="form-control form-control-sm radius-8 text-xs" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label text-xs fw-semibold">Spesifikasi Teknis</label>
                                                    <textarea name="technical_specifications" class="form-control radius-8 text-xs" rows="2">{{ $asset->technical_specifications }}</textarea>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label text-xs fw-semibold">Catatan Operasional</label>
                                                    <textarea name="notes" class="form-control radius-8 text-xs" rows="2">{{ $asset->notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer py-12 px-24 border-top">
                                            <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-16" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm radius-8 px-16">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endcan

                @empty
                    <tr>
                        <td colspan="7" class="text-center py-20 text-secondary-light text-xs">
                            Tidak ada data asset yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($assets->hasPages())
        <div class="card-footer border-top py-12 px-20">
            {{ $assets->links() }}
        </div>
    @endif
</div>


@can('asset.inventory.manage')
<div class="modal fade" id="createAssetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
            <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-36-px h-36-px radius-8 bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:tuning-2-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-primary-light mb-0">Daftarkan Mesin &amp; Asset Baru</h6>
                        <span class="text-secondary-light text-2xs">Kode registrasi otomatis digenerate dengan format AST-2026-xxx</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.assets.inventory.store') }}" method="POST">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Nama Mesin / Asset <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm radius-8 text-xs"
                                   placeholder="Contoh: UHP Water Jetting Unit 40.000 PSI" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Kategori Alat <span class="text-danger">*</span></label>
                            <input type="text" name="category" class="form-control form-control-sm radius-8 text-xs"
                                   placeholder="Contoh: High Pressure Pump, Flange & Bolting Tool..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Merk &amp; Tipe Model</label>
                            <input type="text" name="brand_model" class="form-control form-control-sm radius-8 text-xs"
                                   placeholder="Contoh: Hammelmann HDP 170 / Enerpac W8000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Nomor Seri (Serial Number)</label>
                            <input type="text" name="serial_number" class="form-control form-control-sm radius-8 text-xs"
                                   placeholder="Contoh: HDP-40K-9921">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold">Tahun Pengadaan</label>
                            <input type="number" name="year_acquired" value="2024" class="form-control form-control-sm radius-8 text-xs">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold">Estimasi Harga Beli (Rp)</label>
                            <input type="number" name="purchase_price" value="0" class="form-control form-control-sm radius-8 text-xs">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold">Lokasi Standby Awal <span class="text-danger">*</span></label>
                            <input type="text" name="current_location" value="Workshop Balikpapan" class="form-control form-control-sm radius-8 text-xs" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Kondisi Fisik <span class="text-danger">*</span></label>
                            <select name="condition" class="form-select form-select-sm radius-8 text-xs" required>
                                <option value="good">Baik (Good)</option>
                                <option value="fair">Cukup (Fair)</option>
                                <option value="maintenance_needed">Perlu Maintenance</option>
                                <option value="damaged">Rusak (Damaged)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Status Operasional <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm radius-8 text-xs" required>
                                <option value="available">Available (Siap Pakai)</option>
                                <option value="mobilized">Mobilized (Di Lapangan)</option>
                                <option value="under_maintenance">Under Maintenance</option>
                                <option value="decommissioned">Decommissioned</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold">Spesifikasi Teknis &amp; Kapasitas</label>
                            <textarea name="technical_specifications" class="form-control radius-8 text-xs" rows="2"
                                      placeholder="Contoh: Tekanan kerja 40.000 PSI, Flow 26 L/min, Engine Volvo Penta Diesel..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold">Catatan</label>
                            <textarea name="notes" class="form-control radius-8 text-xs" rows="2"
                                      placeholder="Catatan inspeksi, kelengkapan aksesoris, dll."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-12 px-24 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-16" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm radius-8 px-16">Simpan Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection
