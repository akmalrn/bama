@extends('layout.layout')

@php
    $title = 'Detail Customer (360 View)';
    $subTitle = $customer->company_name . ' — Histori Pipeline & Project';
@endphp

@section('content')

<div class="row gy-4 mb-24">
    <div class="col-12">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden">
            <div class="card-body p-24">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="w-64-px h-64-px radius-12 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-3xl flex-shrink-0">
                            <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h4 class="fw-bold mb-0 text-primary-light">{{ $customer->company_name }}</h4>
                                <span class="badge bg-primary-50 text-primary-700 border border-primary-200 text-xs">
                                    {{ $customer->industry ?? 'General Industry' }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-3 text-secondary-light text-xs mt-6 flex-wrap">
                                @if($customer->location)
                                    <span>
                                        <iconify-icon icon="solar:map-point-bold-duotone" class="text-danger-main align-middle me-1"></iconify-icon>
                                        {{ $customer->location }}
                                    </span>
                                @endif
                                @if($customer->npwp)
                                    <span>
                                        <iconify-icon icon="solar:document-text-outline" class="text-info-main align-middle me-1"></iconify-icon>
                                        NPWP: <strong>{{ $customer->npwp }}</strong>
                                    </span>
                                @endif
                                <span>
                                    <iconify-icon icon="solar:calendar-date-bold-duotone" class="text-primary-600 align-middle me-1"></iconify-icon>
                                    Terdaftar: {{ $customer->created_at ? $customer->created_at->format('d M Y') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('dashboard-admin.sales.customers.index') }}" class="btn btn-outline-secondary btn-sm radius-8 px-16 py-8">
                            Kembali
                        </a>
                        @can('sales.customer.edit')
                            <a href="{{ route('dashboard-admin.sales.customers.edit', $customer) }}" class="btn btn-primary btn-sm radius-8 px-16 py-8 d-flex align-items-center gap-2">
                                <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon> Edit Profil Klien
                            </a>
                        @endcan
                    </div>
                </div>

                <hr class="my-20">

                
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-16 radius-8 bg-neutral-50-light border">
                            <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-4">Person In Charge (PIC)</span>
                            <h6 class="fw-bold text-primary-light mb-4">{{ $customer->pic_name }}</h6>
                            <div class="text-xs text-secondary-light d-flex flex-column gap-1">
                                @if($customer->pic_phone)
                                    <span><iconify-icon icon="solar:phone-bold-duotone" class="text-success-main align-middle"></iconify-icon> {{ $customer->pic_phone }}</span>
                                @endif
                                @if($customer->pic_email)
                                    <span><iconify-icon icon="solar:letter-bold-duotone" class="text-info-main align-middle"></iconify-icon> {{ $customer->pic_email }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-16 radius-8 bg-warning-50 border border-warning-200">
                            <span class="text-xs text-warning-800 fw-bold text-uppercase d-block mb-4">Pipeline Aktif (On-Going Deals)</span>
                            <h5 class="fw-bold text-warning-900 mb-2">Rp {{ number_format($activePipelineValue, 0, ',', '.') }}</h5>
                            <span class="text-xs text-warning-700">
                                {{ $customer->opportunities->whereNotIn('stage', ['closed_won', 'closed_lost'])->count() }} Opportunity berjalan
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-16 radius-8 bg-success-50 border border-success-200">
                            <span class="text-xs text-success-800 fw-bold text-uppercase d-block mb-4">Total Closed Won (Project Didapat)</span>
                            <h5 class="fw-bold text-success-900 mb-2">Rp {{ number_format($closedWonValue, 0, ',', '.') }}</h5>
                            <span class="text-xs text-success-700">
                                {{ $customer->opportunities->where('stage', 'closed_won')->count() }} Opportunity Sukses
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
    <div class="card-header border-bottom py-16 px-24">
        <ul class="nav nav-tabs card-header-tabs" id="customerTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-semibold text-sm d-flex align-items-center gap-2" id="opportunities-tab" data-bs-toggle="tab" data-bs-target="#opportunities" type="button" role="tab">
                    <iconify-icon icon="solar:diagram-up-bold-duotone" class="text-lg"></iconify-icon>
                    Sales Opportunities ({{ $customer->opportunities->count() }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold text-sm d-flex align-items-center gap-2" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects" type="button" role="tab">
                    <iconify-icon icon="solar:folder-with-files-bold-duotone" class="text-lg"></iconify-icon>
                    Project Eksekusi ({{ $customer->projects->count() }})
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-0 tab-content" id="customerTabContent">
        
        <div class="tab-pane fade show active" id="opportunities" role="tabpanel">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20">Judul Opportunity</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20">Stage Saat Ini</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20 text-end">Estimasi Nilai</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20">PIC Sales (Owner)</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20">Next Action</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20">Target Close</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customer->opportunities as $opp)
                            @php
                                $stageBadges = [
                                    'qualification' => 'bg-secondary-subtle text-secondary-emphasis',
                                    'need_analysis' => 'bg-info-subtle text-info-emphasis',
                                    'tender_rfq' => 'bg-warning-subtle text-warning-emphasis',
                                    'proposal_submitted' => 'bg-primary-subtle text-primary-emphasis',
                                    'negotiation' => 'bg-purple-50 text-purple-700 border border-purple-200',
                                    'closed_won' => 'bg-success-subtle text-success-emphasis',
                                    'closed_lost' => 'bg-danger-subtle text-danger-emphasis',
                                ];
                                $stageLabels = [
                                    'qualification' => '1. Qualification',
                                    'need_analysis' => '2. Need Analysis',
                                    'tender_rfq' => '3. Tender / RFQ',
                                    'proposal_submitted' => '4. Proposal Submitted',
                                    'negotiation' => '5. Negotiation',
                                    'closed_won' => 'Closed Won',
                                    'closed_lost' => 'Closed Lost',
                                ];
                            @endphp
                            <tr>
                                <td class="px-20">
                                    <strong class="text-primary-light text-sm d-block">{{ $opp->title }}</strong>
                                    <span class="text-secondary-light text-xs">ID: #OPP-{{ str_pad((string)$opp->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-20">
                                    <span class="badge badge-stage-{{ $opp->stage }} px-10 py-6 radius-6 text-xs">
                                        {{ $stageLabels[$opp->stage] ?? $opp->stage }}
                                    </span>
                                </td>
                                <td class="px-20 text-end">
                                    <strong class="text-primary-600 text-sm">
                                        Rp {{ number_format((float) $opp->estimated_value, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="px-20">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="w-24-px h-24-px radius-circle bg-primary-100 text-primary-600 text-xs fw-bold d-flex justify-content-center align-items-center">
                                            {{ substr($opp->owner->name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="text-sm fw-medium text-secondary-light">{{ $opp->owner->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-20 text-sm text-secondary-light" style="max-width: 250px;">
                                    <span class="text-truncate-2">{{ $opp->next_step ?? '-' }}</span>
                                </td>
                                <td class="px-20 text-sm text-secondary-light">
                                    {{ $opp->target_close_date ? \Carbon\Carbon::parse($opp->target_close_date)->format('d M Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-32 text-secondary-light text-sm">
                                    Belum ada opportunity untuk customer ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="tab-pane fade" id="projects" role="tabpanel">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20">Project Name & Code</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20">Status</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20 text-end">Contract Value</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20">Durasi Kontrak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customer->projects as $prj)
                            <tr>
                                <td class="px-20">
                                    <strong class="text-primary-light text-sm d-block">{{ $prj->name }}</strong>
                                    <span class="text-secondary-light text-xs">SO Code: {{ $prj->code ?? '-' }}</span>
                                </td>
                                <td class="px-20">
                                    <span class="badge bg-info-subtle text-info-emphasis px-10 py-6 radius-6 text-xs text-uppercase">
                                        {{ $prj->status ?? 'Active' }}
                                    </span>
                                </td>
                                <td class="px-20 text-end">
                                    <strong class="text-primary-600 text-sm">
                                        Rp {{ number_format((float) ($prj->contract_value ?? 0), 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="px-20 text-sm text-secondary-light">
                                    {{ $prj->start_date ? \Carbon\Carbon::parse($prj->start_date)->format('d M Y') : '-' }}
                                    s/d
                                    {{ $prj->end_date ? \Carbon\Carbon::parse($prj->end_date)->format('d M Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-32 text-secondary-light text-sm">
                                    Belum ada project aktif yang dikonversi untuk klien ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
