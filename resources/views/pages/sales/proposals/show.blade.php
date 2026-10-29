@extends('layout.layout')

@php
    $title = 'Detail Proposal';
    $subTitle = $proposal->proposal_number . ' — ' . ($proposal->opportunity->title ?? '');
@endphp

@section('content')
<div class="row gy-4 mb-24 justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
            <div class="card-body p-24">
                
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="w-64-px h-64-px radius-12 d-flex justify-content-center align-items-center bg-warning-50 text-warning-main text-3xl flex-shrink-0">
                            <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h4 class="fw-bold mb-0 text-primary-light">{{ $proposal->proposal_number }}</h4>
                                @php
                                    $statusBadges = [
                                        'draft' => 'bg-secondary-subtle text-secondary-emphasis',
                                        'submitted' => 'bg-primary-subtle text-primary-emphasis',
                                        'revised' => 'bg-warning-subtle text-warning-emphasis',
                                        'accepted' => 'bg-success-subtle text-success-emphasis',
                                        'rejected' => 'bg-danger-subtle text-danger-emphasis',
                                    ];
                                @endphp
                                <span class="badge {{ $statusBadges[$proposal->status] ?? 'bg-secondary' }} text-xs text-uppercase px-10 py-6 radius-6">
                                    {{ $proposal->status }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-3 text-secondary-light text-xs mt-6 flex-wrap">
                                <span>
                                    <iconify-icon icon="solar:diagram-up-bold-duotone" class="text-primary-600 align-middle me-1"></iconify-icon>
                                    Opportunity: <strong>{{ $proposal->opportunity->title ?? '-' }}</strong>
                                </span>
                                <span>
                                    <iconify-icon icon="solar:buildings-bold-duotone" class="text-info-main align-middle me-1"></iconify-icon>
                                    Customer: <strong>{{ $proposal->opportunity->customer->company_name ?? '-' }}</strong>
                                </span>
                                <span>
                                    <iconify-icon icon="solar:calendar-date-bold-duotone" class="text-danger-main align-middle me-1"></iconify-icon>
                                    Diajukan: {{ $proposal->submitted_at ? $proposal->submitted_at->format('d M Y H:i') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('dashboard-admin.sales.proposals.index') }}" class="btn btn-outline-secondary btn-sm radius-8 px-16 py-8">
                            Kembali
                        </a>
                        @can('sales.proposal.edit')
                            <a href="{{ route('dashboard-admin.sales.proposals.edit', $proposal) }}" class="btn btn-primary btn-sm radius-8 px-16 py-8 d-flex align-items-center gap-2">
                                <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon> Edit Proposal
                            </a>
                        @endcan
                    </div>
                </div>

                <hr class="my-20">

                
                <div class="row g-3 mb-24">
                    <div class="col-md-4">
                        <div class="p-16 radius-8 bg-primary-50 border border-primary-200">
                            <span class="text-xs text-primary-800 fw-bold text-uppercase d-block mb-4">Nilai Penawaran (Selling Price)</span>
                            <h4 class="fw-bold text-primary-900 mb-0">
                                Rp {{ number_format((float)$proposal->amount, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-16 radius-8 bg-neutral-100 border">
                            <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-4">Estimasi Direct Cost (RAB)</span>
                            <h4 class="fw-bold text-secondary-light mb-0">
                                Rp {{ number_format((float)$proposal->estimated_cost, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-16 radius-8 bg-success-50 border border-success-200">
                            <span class="text-xs text-success-800 fw-bold text-uppercase d-block mb-4">Gross Profit & Margin</span>
                            @php
                                $profit = (float)$proposal->amount - (float)$proposal->estimated_cost;
                            @endphp
                            <h4 class="fw-bold text-success-900 mb-1">
                                Rp {{ number_format($profit, 0, ',', '.') }}
                            </h4>
                            <span class="badge bg-success text-white text-xs px-8 py-4 radius-6">
                                Gross Margin: {{ number_format((float)$proposal->gross_margin, 1) }}%
                            </span>
                        </div>
                    </div>
                </div>

                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-16 radius-8 bg-neutral-50-light border h-100">
                            <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-8">Berkas Dokumen Penawaran</span>
                            @if($proposal->file_path)
                                <a href="{{ asset($proposal->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm radius-8 d-inline-flex align-items-center gap-2">
                                    <iconify-icon icon="solar:paperclip-outline" class="text-lg"></iconify-icon>
                                    Unduh Dokumen Proposal
                                </a>
                            @else
                                <span class="text-xs text-secondary-light italic">Belum ada berkas PDF yang dilampirkan.</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-16 radius-8 bg-neutral-50-light border h-100">
                            <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-8">Catatan Komersial / Terms</span>
                            <p class="mb-0 text-sm text-primary-light">
                                {{ $proposal->notes ?: 'Tidak ada catatan komersial tambahan.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
