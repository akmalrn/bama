@extends('layout.layout')

@php
    $title = 'Jadwal Maintenance & Kalibrasi';
    $subTitle = 'Preventive Maintenance, Kalibrasi Tekanan, Sertifikasi Alat — PT ELBEMA Integra Indonesia';
@endphp

@section('content')


<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-20">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-8 py-2 text-xs fw-bold text-uppercase">Maintenance &amp; Calibration</span>
            @if ($overdueCount > 0)
                <span class="badge bg-danger-50 text-danger-700 border border-danger-200 px-8 py-2 text-xs fw-semibold">
                    {{ $overdueCount }} Jadwal Terlambat (Overdue)
                </span>
            @else
                <span class="badge bg-success-50 text-success-700 border border-success-200 px-8 py-2 text-xs fw-semibold">
                    Semua Jadwal On Track
                </span>
            @endif
        </div>
        <h5 class="mb-2 fw-bold text-primary-light">Jadwal Perawatan, Kalibrasi, &amp; Sertifikasi Alat</h5>
        <p class="text-secondary-light mb-0 text-xs">
            Pastikan instrumen ukur terkalibrasi akurat dan mesin bertekanan tinggi lolos standar K3 Migas &amp; Kemenaker.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        @can('asset.maintenance.schedule')
            <button type="button" class="btn btn-primary btn-sm radius-8 px-14 py-8 text-xs fw-semibold d-flex align-items-center gap-1 shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#createMaintenanceModal">
                <iconify-icon icon="solar:wrench-bold-duotone" class="text-sm"></iconify-icon>
                + Buat Jadwal Perawatan
            </button>
        @endcan
    </div>
</div>


<div class="row g-3 mb-20">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-secondary-light d-block mb-1">Total Record Perawatan</span>
            <h5 class="fw-bold text-primary-light mb-0">{{ $totalCount }}</h5>
            <span class="text-2xs text-secondary-light">Tercatat dalam sistem</span>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-info-main d-block mb-1">Terjadwal (Scheduled)</span>
            <h5 class="fw-bold text-info-main mb-0">{{ $scheduledCount }}</h5>
            <span class="text-2xs text-secondary-light">Akan dilaksanakan</span>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-warning-700 d-block mb-1">Sedang Dikerjakan</span>
            <h5 class="fw-bold text-warning-700 mb-0">{{ $inProgressCount }}</h5>
            <span class="text-2xs text-secondary-light">Dalam workshop / vendor</span>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
            <span class="text-xs fw-semibold text-success-main d-block mb-1">Selesai (Completed)</span>
            <h5 class="fw-bold text-success-main mb-0">{{ $completedCount }}</h5>
            <span class="text-2xs text-secondary-light">Lolos uji &amp; terkalibrasi</span>
        </div>
    </div>
</div>


<div class="card radius-12 border border-neutral-200 shadow-sm mb-24 overflow-hidden">
    <div class="card-header border-bottom py-14 px-20">
        <form method="GET" action="{{ route('dashboard-admin.assets.maintenance.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5 col-sm-12">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-neutral-100 border-neutral-300 text-secondary-light">
                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control text-xs border-neutral-300"
                           placeholder="Cari nomor maintenance, judul pekerjaan, nama vendor, sertifikat...">
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <select name="type" class="form-select form-select-sm text-xs border-neutral-300">
                    <option value="">Semua Tipe Pekerjaan</option>
                    <option value="preventive" {{ request('type') == 'preventive' ? 'selected' : '' }}>Preventive Maintenance</option>
                    <option value="corrective" {{ request('type') == 'corrective' ? 'selected' : '' }}>Corrective / Perbaikan</option>
                    <option value="calibration" {{ request('type') == 'calibration' ? 'selected' : '' }}>Kalibrasi Instrumen</option>
                    <option value="overhaul" {{ request('type') == 'overhaul' ? 'selected' : '' }}>Major Overhaul Mesin</option>
                    <option value="certification" {{ request('type') == 'certification' ? 'selected' : '' }}>Sertifikasi Disnaker/MIGAS</option>
                </select>
            </div>

            <div class="col-md-2 col-sm-6">
                <select name="status" class="form-select form-select-sm text-xs border-neutral-300">
                    <option value="">Semua Status</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="col-md-2 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary btn-sm radius-8 px-12 py-6 w-100 text-xs d-flex align-items-center justify-content-center gap-1">
                    <iconify-icon icon="solar:filter-bold-duotone"></iconify-icon> Filter
                </button>
                @if (request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('dashboard-admin.assets.maintenance.index') }}" class="btn btn-outline-secondary btn-sm radius-8 px-10 py-6" title="Reset filter">
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
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">No. Maintenance &amp; Asset</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">Uraian Pekerjaan / Servis</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16">Tipe &amp; Vendor</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Tgl Jadwal</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-end">Estimasi Biaya</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center">Status</th>
                    <th class="text-2xs fw-bold text-uppercase py-12 px-16 text-center" width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($maintenances as $mtc)
                    @php
                        $isCompleted = $mtc->status === 'completed';
                        $statusBadge = match($mtc->status) {
                            'completed' => 'bg-success-focus text-success-main border border-success-subtle',
                            'in_progress' => 'bg-warning-focus text-warning-main border border-warning-subtle',
                            default => 'bg-info-focus text-info-main border border-info-subtle'
                        };
                    @endphp
                    <tr>
                        <td class="px-16 py-12">
                            <span class="fw-bold text-primary-light text-sm d-block">{{ $mtc->asset->name ?? '-' }}</span>
                            <div class="text-2xs text-secondary-light d-flex align-items-center gap-1">
                                <span class="font-monospace text-primary-600 fw-semibold">{{ $mtc->maintenance_number }}</span>
                                <span>·</span>
                                <span>{{ $mtc->asset->asset_code ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-16 py-12 text-xs">
                            <span class="fw-semibold text-primary-light d-block">{{ $mtc->title }}</span>
                            @if ($mtc->certificate_number)
                                <span class="text-3xs text-success-main font-monospace">
                                    No. Sertifikat: {{ $mtc->certificate_number }}
                                </span>
                            @endif
                        </td>
                        <td class="px-16 py-12">
                            <span class="badge bg-neutral-100 text-secondary-light border border-neutral-300 px-8 py-3 radius-4 text-2xs fw-semibold d-inline-block mb-1 text-uppercase">
                                {{ $mtc->type }}
                            </span>
                            <span class="text-3xs text-secondary-light d-block">
                                {{ $mtc->vendor_name ?? 'Internal Workshop' }}
                            </span>
                        </td>
                        <td class="px-16 py-12 text-center text-xs text-secondary-light font-monospace">
                            {{ $mtc->scheduled_date->format('d M Y') }}
                            @if ($isCompleted && $mtc->completed_date)
                                <span class="text-3xs text-success-main d-block">
                                    Selesai: {{ $mtc->completed_date->format('d M Y') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-16 py-12 text-end text-nowrap">
                            <strong class="text-primary-600 text-xs font-monospace">
                                Rp {{ number_format((float) $mtc->cost, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td class="px-16 py-12 text-center">
                            <span class="badge {{ $statusBadge }} px-8 py-3 radius-4 text-2xs fw-bold text-uppercase">
                                {{ str_replace('_', ' ', $mtc->status) }}
                            </span>
                        </td>
                        <td class="px-16 py-12 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                
                                <button type="button"
                                        class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center border-0"
                                        title="Detail Pekerjaan"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailMaintenanceModal{{ $mtc->id }}">
                                    <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                </button>

                                @can('asset.maintenance.schedule')
                                    @if (!$isCompleted)
                                        
                                        <button type="button"
                                                class="w-32-px h-32-px rounded-circle bg-success-focus text-success-main d-inline-flex justify-content-center align-items-center border-0"
                                                title="Selesaikan &amp; Sertifikasi"
                                                data-bs-toggle="modal"
                                                data-bs-target="#completeMaintenanceModal{{ $mtc->id }}">
                                            <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>

                    
                    <div class="modal fade" id="detailMaintenanceModal{{ $mtc->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
                                <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                                    <h6 class="modal-title fw-bold text-primary-light mb-0">Record #{{ $mtc->maintenance_number }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-24 text-xs">
                                    <div class="mb-16">
                                        <span class="text-secondary-light d-block mb-1">Asset:</span>
                                        <strong class="text-primary-light text-sm">{{ $mtc->asset->name ?? '-' }} ({{ $mtc->asset->asset_code ?? '-' }})</strong>
                                    </div>
                                    <div class="mb-16">
                                        <span class="text-secondary-light d-block mb-1">Judul Pekerjaan:</span>
                                        <strong class="text-primary-light">{{ $mtc->title }}</strong>
                                    </div>
                                    <div class="row g-2 mb-16">
                                        <div class="col-6">
                                            <span class="text-secondary-light d-block mb-1">Tipe:</span>
                                            <span class="text-primary-light text-uppercase fw-semibold">{{ $mtc->type }}</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-secondary-light d-block mb-1">Vendor / Pelaksana:</span>
                                            <span class="text-primary-light">{{ $mtc->vendor_name ?? 'Internal Workshop' }}</span>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-16">
                                        <div class="col-6">
                                            <span class="text-secondary-light d-block mb-1">Tgl Terjadwal:</span>
                                            <span class="text-primary-light font-monospace">{{ $mtc->scheduled_date->format('d M Y') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-secondary-light d-block mb-1">Biaya:</span>
                                            <span class="text-primary-600 font-monospace fw-bold">Rp {{ number_format((float) $mtc->cost, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    @if ($isCompleted)
                                        <div class="p-12 radius-8 bg-success-50 border border-success-200 mb-16">
                                            <span class="fw-semibold text-success-800 d-block mb-1">Tindakan Perbaikan yang Dilakukan:</span>
                                            <p class="text-2xs text-success-700 mb-1">{{ $mtc->action_taken }}</p>
                                            @if ($mtc->parts_replaced)
                                                <span class="text-3xs text-secondary-light d-block">Suku Cadang Diganti: {{ $mtc->parts_replaced }}</span>
                                            @endif
                                            @if ($mtc->certificate_number)
                                                <span class="text-3xs text-success-main font-monospace fw-bold d-block mt-1">Sertifikat: {{ $mtc->certificate_number }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if ($mtc->notes)
                                        <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                                            <span class="fw-semibold text-primary-light d-block mb-1">Catatan Tambahan:</span>
                                            <p class="text-2xs text-secondary-light mb-0">{{ $mtc->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer py-12 px-24 border-top">
                                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-16" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    @can('asset.maintenance.schedule')
                        @if (!$isCompleted)
                            <div class="modal fade" id="completeMaintenanceModal{{ $mtc->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
                                        <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                                            <h6 class="modal-title fw-bold text-primary-light mb-0">Selesaikan Perawatan &amp; Kalibrasi</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('dashboard-admin.assets.maintenance.complete', $mtc) }}" method="POST">
                                            @csrf
                                            <div class="modal-body p-24">
                                                <div class="mb-16">
                                                    <span class="text-xs text-secondary-light d-block">Mesin / Asset:</span>
                                                    <strong class="text-primary-light text-sm">{{ $mtc->asset->name ?? '-' }} ({{ $mtc->asset->asset_code ?? '-' }})</strong>
                                                </div>
                                                <div class="row g-3 mb-16">
                                                    <div class="col-6">
                                                        <label class="form-label text-xs fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                                                        <input type="date" name="completed_date" value="{{ date('Y-m-d') }}" class="form-control form-control-sm radius-8 text-xs" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label text-xs fw-semibold">Kondisi Fisik Pasca Servis <span class="text-danger">*</span></label>
                                                        <select name="condition_after" class="form-select form-select-sm radius-8 text-xs" required>
                                                            <option value="good">Baik (Good / Lolos Uji)</option>
                                                            <option value="fair">Cukup (Fair)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-16">
                                                    <label class="form-label text-xs fw-semibold">Tindakan Perbaikan / Kalibrasi Dilakukan <span class="text-danger">*</span></label>
                                                    <textarea name="action_taken" class="form-control radius-8 text-xs" rows="2"
                                                              placeholder="Contoh: Kalibrasi jarum gauge 40k psi lolos uji akurasi 0.5% F.S, ganti seal plunger..." required></textarea>
                                                </div>
                                                <div class="mb-16">
                                                    <label class="form-label text-xs fw-semibold">Suku Cadang yang Diganti (Jika ada)</label>
                                                    <input type="text" name="parts_replaced" class="form-control form-control-sm radius-8 text-xs"
                                                           placeholder="Contoh: Plunger seal kit, burst disc 42.000 psi">
                                                </div>
                                                <div class="row g-3 mb-16">
                                                    <div class="col-6">
                                                        <label class="form-label text-xs fw-semibold">Nomor Sertifikat Kalibrasi / Disnaker</label>
                                                        <input type="text" name="certificate_number" class="form-control form-control-sm radius-8 text-xs"
                                                               placeholder="Contoh: KAN-CAL/2026/09912">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label text-xs fw-semibold">Jadwal Kalibrasi Berikutnya</label>
                                                        <input type="date" name="next_due_date" value="{{ date('Y-m-d', strtotime('+180 days')) }}" class="form-control form-control-sm radius-8 text-xs">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="form-label text-xs fw-semibold">Biaya Akhir Aktual (Rp)</label>
                                                    <input type="number" name="actual_cost" value="{{ (int)$mtc->cost }}" class="form-control form-control-sm radius-8 text-xs">
                                                </div>
                                            </div>
                                            <div class="modal-footer py-12 px-24 border-top">
                                                <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-16" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success btn-sm radius-8 px-16">Simpan Penyelesaian</button>
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
                            Belum ada jadwal maintenance yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($maintenances->hasPages())
        <div class="card-footer border-top py-12 px-20">
            {{ $maintenances->links() }}
        </div>
    @endif
</div>


@can('asset.maintenance.schedule')
<div class="modal fade" id="createMaintenanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
            <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-36-px h-36-px radius-8 bg-warning-50 text-warning-700 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:wrench-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-primary-light mb-0">Buat Jadwal Perawatan / Kalibrasi Mesin</h6>
                        <span class="text-secondary-light text-2xs">Nomor register otomatis digenerate dengan format MTC-2026-xxx</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.assets.maintenance.store') }}" method="POST">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Pilih Unit Mesin / Asset <span class="text-danger">*</span></label>
                            <select name="asset_id" class="form-select form-select-sm radius-8 text-xs" required>
                                <option value="">-- Pilih Asset --</option>
                                @foreach ($assets as $as)
                                    <option value="{{ $as->id }}">{{ $as->name }} ({{ $as->asset_code }}) - {{ $as->current_location }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Tipe Pekerjaan <span class="text-danger">*</span></label>
                            <select name="type" class="form-select form-select-sm radius-8 text-xs" required>
                                <option value="preventive">Preventive Maintenance (Servis Rutin)</option>
                                <option value="corrective">Corrective Maintenance (Perbaikan Kerusakan)</option>
                                <option value="calibration">Kalibrasi Instrumen Tekanan / Akurasi</option>
                                <option value="overhaul">Major Overhaul Mesin</option>
                                <option value="certification">Sertifikasi Kelaikan Kemenaker/MIGAS</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold">Uraian / Judul Pekerjaan Servis <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm radius-8 text-xs"
                                   placeholder="Contoh: Kalibrasi Berkala Pressure Gauge 40k PSI & Uji Tekan Rupture Disc" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Tanggal Rencana Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="scheduled_date" value="{{ date('Y-m-d') }}" class="form-control form-control-sm radius-8 text-xs" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Status Awal <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm radius-8 text-xs" required>
                                <option value="scheduled">Terjadwal (Scheduled)</option>
                                <option value="in_progress">Sedang Dikerjakan (In Progress)</option>
                                <option value="completed">Langsung Selesai (Completed)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Bengkel / Vendor Pelaksana Kalibrasi</label>
                            <input type="text" name="vendor_name" class="form-control form-control-sm radius-8 text-xs"
                                   placeholder="Contoh: Balai Kalibrasi Metrologi / PT Atlas Copco Service Center">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold">Estimasi Anggaran Biaya (Rp)</label>
                            <input type="number" name="cost" value="0" class="form-control form-control-sm radius-8 text-xs">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold">Catatan Servis / Rekomendasi Khusus</label>
                            <textarea name="notes" class="form-control radius-8 text-xs" rows="2"
                                      placeholder="Rincian kerusakan, prosedur uji coba, atau standar toleransi kalibrasi..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-12 px-24 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-16" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm radius-8 px-16">Jadwalkan Perawatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection
