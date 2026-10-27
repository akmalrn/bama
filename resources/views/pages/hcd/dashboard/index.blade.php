@extends('layout.layout')

@php
    $title = 'HCD Executive Dashboard';
    $subTitle = 'Human Capital, Manpower Distribution, Competency & Certification Compliance';
    $script = '<script>
        // HCD specific scripts if needed
    </script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0 text-primary-light">{{ $title }}</h6>
        <p class="text-xs text-secondary-light mb-0">{{ $subTitle }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('dashboard-admin.hcd.tasks.index') }}" class="btn btn-sm btn-outline-primary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:checklist-minimalistic-bold-duotone"></iconify-icon>
            Task Monitoring
        </a>
        <a href="{{ route('dashboard-admin.hcd.employees.index') }}" class="btn btn-sm btn-primary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1">
            <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
            Employee Register
        </a>
    </div>
</div>


<div class="row g-3 mb-24">
    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Headcount</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $totalEmployees }} <span class="fs-6 fw-normal text-secondary-light">Personel</span></h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>{{ $permanentCount }} Tetap · {{ $contractCount }} Kontrak</span>
                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200">Internal HR</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Mobilisasi Site Lapangan</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $siteCrewCount }} <span class="fs-6 fw-normal text-secondary-light">Kru Aktif</span></h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-info-100 text-info-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Bontang, Palembang, Sorowako</span>
                    <span class="badge bg-info-50 text-info-700 border border-info-200">{{ $headOfficeCount }} di HO</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider {{ $expiredCerts->count() > 0 ? 'text-danger-main' : 'text-success-main' }}">Sertifikasi Personel</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $validCerts }} / {{ $totalCerts }} <span class="fs-6 fw-normal text-secondary-light">Valid</span></h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center {{ $expiredCerts->count() > 0 ? 'bg-danger-100 text-danger-main' : 'bg-success-100 text-success-main' }} text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:diploma-verified-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span class="{{ $expiringCerts->count() > 0 ? 'text-warning-main fw-semibold' : '' }}">
                        {{ $expiringCerts->count() }} Expiring (&lt;30hr)
                    </span>
                    <span class="badge {{ $expiredCerts->count() > 0 ? 'bg-danger-50 text-danger-600 border border-danger-200' : 'bg-success-50 text-success-700 border border-success-200' }}">
                        {{ $expiredCerts->count() }} Expired
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
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Task Completion Rate</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">{{ $taskCompletionRate }}%</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:checklist-minimalistic-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>{{ $completedTasks }} Selesai dari {{ $totalTasks }} Tugas</span>
                    <span class="badge {{ $overdueTasks > 0 ? 'bg-danger-50 text-danger-600' : 'bg-warning-50 text-warning-700' }} border">
                        {{ $overdueTasks }} Overdue
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-24">
    
    <div class="col-lg-7">
        <div class="card radius-12 border-0 shadow-sm mb-24">
            <div class="card-header bg-transparent border-bottom py-16 px-20 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Distribusi Tenaga Kerja per Departemen</h6>
                    <span class="text-2xs text-secondary-light">Alokasi headcount aktif di seluruh unit kerja BAMA+</span>
                </div>
                <a href="{{ route('dashboard-admin.hcd.employees.index') }}" class="text-xs text-primary-600 fw-medium">Lihat Semua Karyawan &rarr;</a>
            </div>
            <div class="card-body p-20">
                <div class="d-flex flex-column gap-3">
                    @foreach ($departments as $dept)
                        @php
                            $pct = $totalEmployees > 0 ? round(($dept->employees_count / $totalEmployees) * 100, 1) : 0;
                            $barColor = match ($dept->code) {
                                'PMO' => 'bg-primary-600',
                                'OPS' => 'bg-info-main',
                                'HSE' => 'bg-success-main',
                                'SLS', 'SALES' => 'bg-warning-main',
                                default => 'bg-purple-main',
                            };
                        @endphp
                        <div>
                            <div class="d-flex justify-content-between align-items-center text-xs mb-1">
                                <span class="fw-semibold text-primary-light">
                                    <span class="badge bg-neutral-100 text-secondary-light font-monospace me-1">{{ $dept->code }}</span>
                                    {{ $dept->name }}
                                </span>
                                <span class="text-secondary-light">
                                    <strong>{{ $dept->employees_count }}</strong> personel ({{ $pct }}%)
                                </span>
                            </div>
                            <div class="progress radius-6" style="height: 8px; background-color: rgba(226, 232, 240, 0.4);">
                                <div class="progress-bar {{ $barColor }} radius-6" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        
        <div class="card radius-12 border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-16 px-20 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Alokasi Tenaga Ahli pada Proyek Aktif</h6>
                    <span class="text-2xs text-secondary-light">Integrasi penugasan PMO dengan ketersediaan kru lapangan HCD</span>
                </div>
                <span class="badge bg-primary-50 text-primary-600 border border-primary-200 text-xs">PMO Integration</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0 align-middle">
                        <thead class="bg-neutral-50 text-xs text-secondary-light">
                            <tr>
                                <th class="ps-20 py-12">Kode &amp; Proyek</th>
                                <th class="py-12">Customer &amp; Lokasi</th>
                                <th class="py-12">Project Manager (PM)</th>
                                <th class="py-12 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activeProjects as $p)
                                <tr>
                                    <td class="ps-20 py-12">
                                        <a href="{{ route('dashboard-admin.pmo.projects.show', $p->id) }}" class="fw-semibold text-primary-600 text-xs d-block">
                                            {{ $p->project_code }}
                                        </a>
                                        <span class="text-xs text-primary-light d-block text-truncate" style="max-width: 220px;" title="{{ $p->title }}">
                                            {{ $p->title }}
                                        </span>
                                    </td>
                                    <td class="py-12 text-xs">
                                        <span class="text-primary-light fw-medium d-block">{{ $p->customer->company_name ?? '-' }}</span>
                                        <span class="text-secondary-light">{{ $p->location ?? '-' }}</span>
                                    </td>
                                    <td class="py-12 text-xs">
                                        <span class="fw-medium text-primary-light">{{ $p->manager->name ?? 'Belum Ditunjuk' }}</span>
                                    </td>
                                    <td class="py-12 text-center">
                                        <span class="badge bg-success-focus text-success-main rounded-pill px-8 py-2 text-2xs">
                                            {{ $p->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-20 text-secondary-light text-xs">Tidak ada proyek aktif saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-5">
        
        <div class="card radius-12 border-0 shadow-sm mb-24">
            <div class="card-header bg-transparent border-bottom py-16 px-20 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="w-8-px h-8-px radius-circle bg-danger-main"></span>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Sertifikasi Perlu Perhatian</h6>
                </div>
                <a href="{{ route('dashboard-admin.hcd.training.index') }}" class="text-xs text-primary-600 fw-medium">Katalog &rarr;</a>
            </div>
            <div class="card-body p-16">
                @if ($urgentCertifications->isEmpty())
                    <div class="text-center py-24 text-secondary-light">
                        <iconify-icon icon="solar:shield-check-bold-duotone" class="text-4xl text-success-main mb-2"></iconify-icon>
                        <p class="text-xs mb-0">Semua sertifikasi personel valid dan belum ada yang kadaluarsa dalam waktu dekat.</p>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach ($urgentCertifications as $cert)
                            @php
                                $isExpired = $cert->computed_status === \App\Models\HcdEmployeeTraining::STATUS_EXPIRED;
                            @endphp
                            <div class="p-12 radius-8 border {{ $isExpired ? 'border-danger-200 bg-danger-50 text-danger-900' : 'border-warning-200 bg-warning-50 text-warning-900' }}">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <strong class="text-xs">{{ $cert->employee->name ?? 'Karyawan' }}</strong>
                                    <span class="badge {{ $isExpired ? 'bg-danger-main text-white' : 'bg-warning-main text-white' }} px-6 py-2 text-3xs rounded-pill">
                                        {{ $isExpired ? 'EXPIRED' : 'Expiring Soon' }}
                                    </span>
                                </div>
                                <span class="text-2xs d-block text-truncate fw-medium" title="{{ $cert->training->title ?? '' }}">
                                    {{ $cert->training->title ?? '-' }}
                                </span>
                                <div class="d-flex justify-content-between align-items-center mt-6 text-3xs text-secondary-light">
                                    <span>No: {{ $cert->certificate_number ?? '-' }}</span>
                                    <span>Exp: <strong>{{ $cert->expiry_date ? $cert->expiry_date->format('d M Y') : 'N/A' }}</strong></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        
        <div class="card radius-12 border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-16 px-20 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Tugas &amp; Penugasan Terbaru</h6>
                    <span class="text-2xs text-secondary-light">Status pengerjaan operasional terkini</span>
                </div>
                <a href="{{ route('dashboard-admin.hcd.tasks.index') }}" class="text-xs text-primary-600 fw-medium">Semua Tugas &rarr;</a>
            </div>
            <div class="card-body p-16">
                <div class="d-flex flex-column gap-3">
                    @forelse ($recentTasks as $task)
                        <div class="p-10 radius-8 border d-flex justify-content-between align-items-center gap-2">
                            <div class="overflow-hidden">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-neutral-100 text-secondary-light font-monospace text-3xs">{{ $task->task_code }}</span>
                                    <span class="badge {{ $task->priority_badge_class }} text-3xs">{{ strtoupper($task->priority) }}</span>
                                </div>
                                <span class="text-xs fw-semibold text-primary-light d-block text-truncate" style="max-width: 250px;" title="{{ $task->title }}">
                                    {{ $task->title }}
                                </span>
                                <small class="text-3xs text-secondary-light d-block">
                                    PIC: {{ $task->assignee->name ?? '-' }} · Due: {{ $task->due_date ? $task->due_date->format('d M Y') : '-' }}
                                </small>
                            </div>
                            <span class="badge {{ $task->status_badge_class }} text-2xs px-8 py-3 rounded-pill flex-shrink-0">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-secondary-light text-center py-16 mb-0">Belum ada tugas tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
