@extends('layout.layout')

@php
    $title = 'Detail Opportunity';
    $subTitle = '#OPP-' . str_pad((string)$opportunity->id, 4, '0', STR_PAD_LEFT) . ' — ' . $opportunity->title;
@endphp

@section('content')
<div class="row gy-4 mb-24">
    <div class="col-12">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden">
            <div class="card-body p-24">
                
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="w-64-px h-64-px radius-12 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-3xl flex-shrink-0">
                            <iconify-icon icon="solar:diagram-up-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h4 class="fw-bold mb-0 text-primary-light">{{ $opportunity->title }}</h4>
                                <span class="badge bg-neutral-100 text-secondary-light text-xs">
                                    #OPP-{{ str_pad((string)$opportunity->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-3 text-secondary-light text-xs mt-6 flex-wrap">
                                <span>
                                    <iconify-icon icon="solar:buildings-bold-duotone" class="text-primary-600 align-middle me-1"></iconify-icon>
                                    Customer: <strong>{{ $opportunity->customer->company_name ?? '-' }}</strong>
                                </span>
                                <span>
                                    <iconify-icon icon="solar:user-bold-duotone" class="text-info-main align-middle me-1"></iconify-icon>
                                    Sales PIC: <strong>{{ $opportunity->owner->name ?? '-' }}</strong>
                                </span>
                                <span>
                                    <iconify-icon icon="solar:calendar-date-bold-duotone" class="text-danger-main align-middle me-1"></iconify-icon>
                                    Target Close: <strong>{{ $opportunity->target_close_date ? $opportunity->target_close_date->format('d M Y') : '-' }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('dashboard-admin.sales.opportunities.index') }}" class="btn btn-outline-secondary btn-sm radius-8 px-16 py-8">
                            Kembali ke Pipeline
                        </a>
                        @can('sales.opportunity.edit')
                            <a href="{{ route('dashboard-admin.sales.opportunities.edit', $opportunity) }}" class="btn btn-primary btn-sm radius-8 px-16 py-8 d-flex align-items-center gap-2">
                                <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon> Edit Deal
                            </a>
                        @endcan
                    </div>
                </div>

                <hr class="my-20">

                
                @php
                    $stagesList = [
                        'qualification' => '1. Qualification',
                        'need_analysis' => '2. Need Analysis',
                        'tender_rfq' => '3. Tender / RFQ',
                        'proposal_submitted' => '4. Proposal Submitted',
                        'negotiation' => '5. Negotiation',
                        'closed_won' => 'Closed Won',
                        'closed_lost' => 'Closed Lost',
                    ];
                    $currentIdx = array_search($opportunity->stage, array_keys($stagesList));
                @endphp
                <div class="p-16 radius-8 bg-neutral-50-light border mb-24">
                    <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-12">Tahapan Pipeline Saat Ini</span>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @foreach ($stagesList as $stKey => $stLabel)
                            @php
                                $isCurrent = ($opportunity->stage === $stKey);
                                $isWon = ($stKey === 'closed_won');
                                $isLost = ($stKey === 'closed_lost');
                            @endphp
                            <span class="badge {{ $isCurrent ? ($isWon ? 'bg-success text-white' : ($isLost ? 'bg-danger text-white' : 'btn-primary text-white')) : 'bg-neutral-200 text-secondary-light' }} px-12 py-8 radius-6 text-xs fw-semibold">
                                {{ $stLabel }}
                            </span>
                            @if (!$loop->last && $stKey !== 'closed_won')
                                <iconify-icon icon="solar:alt-arrow-right-outline" class="text-secondary-light"></iconify-icon>
                            @endif
                        @endforeach
                    </div>
                </div>

                
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-16 radius-8 bg-primary-50 border border-primary-200">
                            <span class="text-xs text-primary-800 fw-bold text-uppercase d-block mb-4">Estimasi Nilai Deal</span>
                            <h4 class="fw-bold text-primary-900 mb-0">
                                Rp {{ number_format((float)$opportunity->estimated_value, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="p-16 radius-8 bg-neutral-50-light border h-100">
                            <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-4">Rencana Tindak Lanjut (Next Action)</span>
                            <p class="mb-0 text-sm text-primary-light fw-medium">
                                {{ $opportunity->next_step ?: 'Belum ada langkah tindak lanjut yang dicatat.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
    <div class="card-header border-bottom py-16 px-24">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-semibold text-sm d-flex align-items-center gap-2" data-bs-toggle="tab" data-bs-target="#tab-proposals" type="button" role="tab">
                    <iconify-icon icon="solar:document-text-bold-duotone" class="text-lg"></iconify-icon>
                    Proposal Penawaran ({{ $opportunity->proposals->count() }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold text-sm d-flex align-items-center gap-2" data-bs-toggle="tab" data-bs-target="#tab-activities" type="button" role="tab">
                    <iconify-icon icon="solar:chat-round-line-bold-duotone" class="text-lg"></iconify-icon>
                    Aktivitas & Log Sales ({{ $opportunity->salesActivities->count() }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-semibold text-sm d-flex align-items-center gap-2" data-bs-toggle="tab" data-bs-target="#tab-followups" type="button" role="tab">
                    <iconify-icon icon="solar:calendar-bold-duotone" class="text-lg"></iconify-icon>
                    Follow-Up Tasks ({{ $opportunity->followUps->count() }})
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body p-0 tab-content">
        
        <div class="tab-pane fade show active" id="tab-proposals" role="tabpanel">
            <div class="table-responsive">
                <table class="table bordered-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20">Nomor Proposal</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20 text-end">Nilai Penawaran</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20 text-center">Status</th>
                            <th class="text-xs fw-bold text-uppercase py-16 px-20">Tanggal Pengajuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($opportunity->proposals as $prop)
                            <tr>
                                <td class="px-20 fw-bold text-primary-600">{{ $prop->proposal_number }}</td>
                                <td class="px-20 text-end fw-bold text-primary-light">
                                    Rp {{ number_format((float)$prop->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-20 text-center">
                                    <span class="badge bg-primary-subtle text-primary-emphasis px-10 py-4 radius-6 text-xs text-uppercase">
                                        {{ $prop->status }}
                                    </span>
                                </td>
                                <td class="px-20 text-secondary-light text-sm">
                                    {{ $prop->submitted_at ? \Carbon\Carbon::parse($prop->submitted_at)->format('d M Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-32 text-secondary-light text-sm">
                                    Belum ada proposal penawaran harga yang didaftarkan untuk opportunity ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="tab-pane fade" id="tab-activities" role="tabpanel">
            <div class="p-20">
                @forelse ($opportunity->salesActivities as $act)
                    <div class="d-flex align-items-start gap-3 pb-16 mb-16 border-bottom">
                        <div class="w-36-px h-36-px radius-circle bg-info-50 text-info-600 d-flex justify-content-center align-items-center text-lg flex-shrink-0">
                            <iconify-icon icon="solar:chat-square-call-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold mb-0 text-sm text-primary-light">{{ $act->title }}</h6>
                                <span class="badge bg-neutral-100 text-secondary-light text-2xs px-6 py-2 radius-4 text-uppercase">
                                    {{ str_replace('_', ' ', $act->type) }}
                                </span>
                            </div>
                            <p class="text-secondary-light text-xs mb-4 mt-2">{{ $act->notes ?? '-' }}</p>
                            <span class="text-2xs text-secondary-light">
                                Oleh <strong>{{ $act->user->name ?? 'Sales' }}</strong> &bull; {{ \Carbon\Carbon::parse($act->activity_at)->format('d M Y H:i') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-24 text-secondary-light text-sm">
                        Belum ada catatan aktivitas sales.
                    </div>
                @endforelse
            </div>
        </div>

        
        <div class="tab-pane fade" id="tab-followups" role="tabpanel">
            <div class="p-20">
                @forelse ($opportunity->followUps as $fu)
                    <div class="d-flex justify-content-between align-items-center p-12 radius-8 border mb-8 bg-neutral-50-light">
                        <div class="d-flex align-items-center gap-2">
                            <iconify-icon icon="{{ $fu->is_completed ? 'solar:check-circle-bold-duotone' : 'solar:clock-circle-bold-duotone' }}"
                                          class="{{ $fu->is_completed ? 'text-success-main' : 'text-warning-main' }} text-xl"></iconify-icon>
                            <div>
                                <span class="text-sm fw-semibold {{ $fu->is_completed ? 'text-decoration-line-through text-secondary-light' : 'text-primary-light' }}">
                                    {{ $fu->task_title }}
                                </span>
                                <div class="text-2xs text-secondary-light">
                                    Assigned: {{ $fu->assignedTo->name ?? '-' }} &bull; Due Date: {{ \Carbon\Carbon::parse($fu->due_date)->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                        <span class="badge {{ $fu->is_completed ? 'bg-success-subtle text-success-emphasis' : 'bg-warning-subtle text-warning-emphasis' }} text-xs">
                            {{ $fu->is_completed ? 'Selesai' : 'Pending' }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-24 text-secondary-light text-sm">
                        Tidak ada task follow-up untuk opportunity ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
