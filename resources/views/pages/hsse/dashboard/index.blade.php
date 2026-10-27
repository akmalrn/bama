@extends('layout.layout')

@php
    $title = 'HSSE Executive Dashboard';
    $subTitle = 'Health, Safety, Security & Environment — Zero Accident & Compliance Monitor';
    $script = '<script>
        // HSSE Dashboard script
    </script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0 text-primary-light">{{ $title }}</h6>
        <p class="text-xs text-secondary-light mb-0">{{ $subTitle }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('dashboard-admin.hsse.permits.index') }}" class="btn btn-sm btn-outline-warning radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
            Permit to Work (PTW)
        </a>
        <a href="{{ route('dashboard-admin.hsse.inspections.index') }}" class="btn btn-sm btn-outline-success radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:checklist-minimalistic-bold-duotone"></iconify-icon>
            Inspeksi APD & Alat
        </a>
        <a href="{{ route('dashboard-admin.hsse.incidents.index') }}" class="btn btn-sm btn-danger radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:danger-triangle-bold-duotone"></iconify-icon>
            Laporan Insiden
        </a>
    </div>
</div>

{{-- 4 TOP EXECUTIVE SAFETY KPI CARDS --}}
<div class="row g-3 mb-24">
    {{-- Card 1: Zero Accident Counter --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Zero Accident Record</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-4">{{ number_format($zeroAccidentDays) }} <span class="fs-6 fw-normal text-secondary-light">Hari Kerja Selamat</span></h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:shield-check-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>LTI Terakhir: {{ $ltiCount == 0 ? 'Nol Insiden Fatal' : $ltiCount . ' LTI Tercatat' }}</span>
                    <span class="badge bg-success-50 text-success-700 border border-success-200">Zero Fatality</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Safe Man-Hours --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Safe Man-Hours</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-4">{{ number_format($safeManHours) }} <span class="fs-6 fw-normal text-secondary-light">Jam</span></h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Akumulasi Seluruh Plant Turnaround</span>
                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200">Audited</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Active Permits to Work --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Active Safety Permits</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-4">{{ $activePermitsCount }} <span class="fs-6 fw-normal text-secondary-light">/ {{ $totalPermits }} PTW</span></h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>{{ $approvedPermitsCount }} Disetujui · {{ $underReviewPermitsCount }} Review</span>
                    <span class="badge bg-warning-50 text-warning-main border border-warning-200">Controlled</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Inspection Pass Rate --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Inspection Pass Rate</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-4">{{ $passRate }}% <span class="fs-6 fw-normal text-secondary-light">Tingkat Lolos</span></h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-info-100 text-info-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:checklist-minimalistic-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>{{ $quarantinedInspections }} Alat Ditolak/Karantina</span>
                    <span class="badge {{ $quarantinedInspections > 0 ? 'bg-danger-50 text-danger-600 border border-danger-200' : 'bg-success-50 text-success-700' }}">
                        {{ $quarantinedInspections > 0 ? 'Tag Merah Aktif' : 'Semua Aman' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ROW 2: ACTIVE PTW STATUS & INCIDENT / NEAR MISS RADAR --}}
<div class="row g-3 mb-24">
    {{-- Col 1: Recent Active Permits to Work --}}
    <div class="col-xl-7">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-16 px-20 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-32-px h-32-px radius-6 bg-warning-50 text-warning-main d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:shield-warning-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Permit to Work & JSA Aktif</h6>
                        <span class="text-xs text-secondary-light">Izin kerja berisiko tinggi di area site</span>
                    </div>
                </div>
                <a href="{{ route('dashboard-admin.hsse.permits.index') }}" class="btn btn-sm btn-outline-primary radius-8 px-12 py-6 text-xs">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-xs text-secondary-light text-uppercase">
                                <th class="py-12 px-20">No. PTW & Pekerjaan</th>
                                <th class="py-12 px-16">Site / Proyek</th>
                                <th class="py-12 px-16">Risk Level</th>
                                <th class="py-12 px-16">Masa Berlaku</th>
                                <th class="py-12 px-20 text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($recentPermits as $permit)
                                <tr>
                                    <td class="py-12 px-20">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-primary-light">{{ $permit->permit_number }}</span>
                                            <span class="text-xs text-secondary-light">{{ Str::limit($permit->title, 35) }}</span>
                                            <span class="badge bg-neutral-100 text-secondary-light w-auto mt-1 align-self-start text-2xs">{{ $permit->permit_type }}</span>
                                        </div>
                                    </td>
                                    <td class="py-12 px-16">
                                        <span class="d-block fw-medium text-xs text-primary-light">{{ $permit->project ? Str::limit($permit->project->title, 25) : '-' }}</span>
                                        <span class="text-xs text-secondary-light">{{ $permit->location }}</span>
                                    </td>
                                    <td class="py-12 px-16">
                                        <span class="badge {{ $permit->risk_badge_class }} text-xs px-8 py-4 radius-6">
                                            {{ $permit->risk_level }}
                                        </span>
                                        @if($permit->gas_test_required)
                                            <div class="mt-1 text-2xs text-secondary-light">
                                                Gas: O₂ {{ $permit->gas_test_o2 ?? '-' }} | LEL {{ $permit->gas_test_lel ?? '-' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-12 px-16 text-xs text-secondary-light">
                                        {{ $permit->start_time ? $permit->start_time->format('d M H:i') : '-' }} s/d<br>
                                        {{ $permit->end_time ? $permit->end_time->format('d M H:i') : '-' }}
                                    </td>
                                    <td class="py-12 px-20 text-end">
                                        <span class="badge {{ $permit->status_badge_class }} text-xs px-10 py-6 radius-6">
                                            {{ ucfirst(str_replace('_', ' ', $permit->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-24 text-secondary-light text-sm">
                                        Belum ada izin kerja (PTW) yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Col 2: HSE Incident & Near Miss Monitor --}}
    <div class="col-xl-5">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-16 px-20 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-32-px h-32-px radius-6 bg-danger-50 text-danger-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Incident & Near-Miss Watch</h6>
                        <span class="text-xs text-secondary-light">Status investigasi & CAPA perbaikan</span>
                    </div>
                </div>
                <span class="badge {{ $openIncidents > 0 ? 'bg-danger-50 text-danger-600 border border-danger-200' : 'bg-success-50 text-success-700' }} text-xs">
                    {{ $openIncidents }} Open CAPA
                </span>
            </div>
            <div class="card-body p-20">
                {{-- Incident Severity Bar --}}
                <div class="row g-2 mb-20">
                    <div class="col-4">
                        <div class="p-12 radius-8 bg-info-50 border border-info-200 text-center">
                            <span class="text-2xs fw-bold text-uppercase text-info-700">Near Miss</span>
                            <h5 class="fw-bold mb-0 text-info-700 mt-1">{{ $nearMissCount }}</h5>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-12 radius-8 bg-warning-50 border border-warning-200 text-center">
                            <span class="text-2xs fw-bold text-uppercase text-warning-700">First Aid</span>
                            <h5 class="fw-bold mb-0 text-warning-700 mt-1">{{ $facCount }}</h5>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-12 radius-8 bg-danger-50 border border-danger-200 text-center">
                            <span class="text-2xs fw-bold text-uppercase text-danger-700">LTI / Fatality</span>
                            <h5 class="fw-bold mb-0 text-danger-700 mt-1">{{ $ltiCount }}</h5>
                        </div>
                    </div>
                </div>

                {{-- Recent Incidents Feed --}}
                <div class="d-flex flex-column gap-3">
                    @forelse($recentIncidents as $incident)
                        <div class="p-12 radius-8 border d-flex flex-column gap-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge {{ $incident->type_badge_class }} text-2xs px-8 py-4 radius-6">
                                    {{ $incident->incident_type }}
                                </span>
                                <span class="badge {{ $incident->status_badge_class }} text-2xs px-8 py-4 radius-6">
                                    {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                </span>
                            </div>
                            <span class="fw-semibold text-primary-light text-sm mt-1">{{ $incident->title }}</span>
                            <div class="d-flex align-items-center justify-content-between text-2xs text-secondary-light mt-1">
                                <span><i class="ri-map-pin-line me-1"></i>{{ $incident->location }}</span>
                                <span>{{ $incident->incident_date ? $incident->incident_date->format('d M Y') : '-' }}</span>
                            </div>
                            @if($incident->capa_action)
                                <div class="bg-light p-8 radius-6 mt-2 text-xs text-secondary-light">
                                    <strong class="text-primary-light">CAPA:</strong> {{ Str::limit($incident->capa_action, 80) }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-20 text-secondary-light text-sm">
                            Tidak ada insiden tercatat. Kondisi plant aman terkendali.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ROW 3: EQUIPMENT INSPECTION & QUARANTINE LOG --}}
<div class="row g-3">
    {{-- Col 1: Quarantined & Rejected Tools (Tag Merah) --}}
    <div class="col-xl-6">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-16 px-20 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-32-px h-32-px radius-6 bg-danger-50 text-danger-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:close-circle-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Karantina Alat & Tag Merah</h6>
                        <span class="text-xs text-secondary-light">Peralatan dilarang pakai sampai perbaikan tuntas</span>
                    </div>
                </div>
                <a href="{{ route('dashboard-admin.hsse.inspections.index', ['result' => 'failed_quarantined']) }}" class="btn btn-sm btn-outline-danger radius-8 px-12 py-6 text-xs">
                    Kelola Karantina
                </a>
            </div>
            <div class="card-body p-20">
                <div class="d-flex flex-column gap-3">
                    @forelse($quarantinedTools as $tool)
                        <div class="p-12 radius-8 border border-danger-200 bg-danger-50 d-flex justify-content-between align-items-start">
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-danger-main text-white text-2xs px-6 py-2 radius-4">RED TAG</span>
                                    <h6 class="mb-0 fs-sm fw-bold text-danger-700">{{ $tool->item_name }}</h6>
                                </div>
                                <p class="text-xs text-danger-600 mb-1 mt-1">S/N: {{ $tool->serial_number ?? 'N/A' }} · Lokasi: {{ $tool->location }}</p>
                                <p class="text-xs text-secondary-light mb-0"><strong>Temuan:</strong> {{ $tool->checklist_findings }}</p>
                            </div>
                            <span class="text-2xs text-secondary-light text-nowrap">
                                {{ $tool->inspection_date ? $tool->inspection_date->format('d M Y') : '-' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-24 text-success-main text-sm">
                            <iconify-icon icon="solar:check-circle-bold-duotone" class="text-3xl d-block mx-auto mb-2"></iconify-icon>
                            Tidak ada peralatan yang dikarantina. Seluruh unit siap operasi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Col 2: Upcoming Re-Inspections --}}
    <div class="col-xl-6">
        <div class="card radius-12 border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-16 px-20 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-32-px h-32-px radius-6 bg-info-50 text-info-main d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:calendar-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="card-title mb-0 fs-6 fw-semibold text-primary-light">Jadwal Kalibrasi & Inspeksi Ulang</h6>
                        <span class="text-xs text-secondary-light">Pemeriksaan berkala dalam 14 hari ke depan</span>
                    </div>
                </div>
                <a href="{{ route('dashboard-admin.hsse.inspections.index') }}" class="btn btn-sm btn-outline-info radius-8 px-12 py-6 text-xs">
                    Log Lengkap
                </a>
            </div>
            <div class="card-body p-20">
                <div class="d-flex flex-column gap-3">
                    @forelse($upcomingInspections as $insp)
                        <div class="p-12 radius-8 border d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fs-sm fw-bold text-primary-light">{{ $insp->item_name }}</h6>
                                <span class="text-xs text-secondary-light">Kategori: {{ $insp->item_category }} · Lokasi: {{ $insp->location }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning-50 text-warning-main border border-warning-200 text-xs">
                                    Due: {{ $insp->next_inspection_date ? $insp->next_inspection_date->format('d M Y') : '-' }}
                                </span>
                                <span class="d-block text-2xs text-secondary-light mt-1">Tag: {{ $insp->tag_color }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-24 text-secondary-light text-sm">
                            Belum ada jadwal inspeksi ulang dalam kurun waktu 14 hari ke depan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
