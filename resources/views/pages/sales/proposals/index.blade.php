@extends('layout.layout')

@php
    $title = 'Proposal Register';
    $subTitle = 'Commercial Quotation Tracking & Margin Analytics — PT ELBEMA Integra Indonesia';
@endphp

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="row g-3 mb-24">
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-primary">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Proposal</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light fs-5">
                            {{ $totalProposalsCount }} <span class="fs-6 fw-normal text-secondary-light">Dokumen</span>
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Semua status penawaran</span>
                    <span class="badge bg-primary-50 text-primary-700 border border-primary-200">Register</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-warning">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div class="me-2 overflow-hidden">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main d-block text-truncate">Total Nilai Penawaran</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light fs-5 text-nowrap">
                            <span class="fs-6 fw-semibold text-secondary-light me-1">Rp</span>{{ number_format($totalProposalValue, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Akumulasi quotation</span>
                    <span class="badge bg-warning-50 text-warning-main border border-warning-200">Komersial</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-success">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div class="me-2 overflow-hidden">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main d-block text-truncate">Proposal Diterima (Deal)</span>
                        <h4 class="fw-bold mb-0 mt-1 text-success-main fs-5 text-nowrap">
                            <span class="fs-6 fw-semibold text-success-600 me-1">Rp</span>{{ number_format($acceptedValue, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-600 text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Deal disetujui klien</span>
                    <span class="badge bg-success-50 text-success-700 border border-success-200">Won</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-purple">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-purple-main">Rata-rata Margin</span>
                        <h4 class="fw-bold mb-0 mt-1 text-purple-main fs-5">
                            {{ number_format($avgGrossMargin, 1) }}%
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-purple-100 text-purple-600 text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:pie-chart-2-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Target margin &gt; 25%</span>
                    <span class="badge bg-purple-50 text-purple-700 border border-purple-200">Gross Margin</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
    <div class="card-header border-bottom py-16 px-24 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-50 text-warning-main text-xl">
                <iconify-icon icon="solar:document-add-bold-duotone"></iconify-icon>
            </div>
            <div>
                <h5 class="card-title mb-0 fw-bold">Commercial Proposal Register</h5>
                <p class="text-secondary-light text-xs mb-0">Daftar penawaran harga, estimasi direct cost RAB, dan kalkulasi margin</p>
            </div>
        </div>

        @can('sales.proposal.create')
            <button type="button" class="btn btn-primary btn-sm radius-8 px-16 py-8 d-flex align-items-center gap-2 fw-semibold"
                    data-bs-toggle="modal" data-bs-target="#createProposalModal">
                <iconify-icon icon="ic:round-add" class="text-lg"></iconify-icon>
                Buat Proposal Baru
            </button>
        @endcan
    </div>

    
    <div class="card-body p-16 border-bottom bg-neutral-50-light">
        <form action="{{ route('dashboard-admin.sales.proposals.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5 col-sm-6">
                <div class="position-relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nomor proposal, proyek, customer..."
                           class="form-control form-control-sm radius-8 pe-40">
                    <iconify-icon icon="ion:search-outline" class="position-absolute end-0 top-50 translate-middle-y me-12 text-secondary-light"></iconify-icon>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <select name="status" class="form-select form-select-sm radius-8" onchange="this.form.submit()">
                    <option value="">Semua Status Proposal</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="revised" {{ request('status') === 'revised' ? 'selected' : '' }}>Revised</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-3 col-sm-8">
                <select name="opportunity_id" class="form-select form-select-sm radius-8" onchange="this.form.submit()">
                    <option value="">Semua Opportunity</option>
                    @foreach ($opportunities as $opp)
                        <option value="{{ $opp->id }}" {{ request('opportunity_id') == $opp->id ? 'selected' : '' }}>
                            {{ $opp->title }} ({{ $opp->customer->company_name ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1 col-sm-4 d-flex gap-1">
                @if (request()->hasAny(['search', 'status', 'opportunity_id']))
                    <a href="{{ route('dashboard-admin.sales.proposals.index') }}" class="btn btn-outline-secondary btn-sm radius-8 w-100 d-flex justify-content-center align-items-center" title="Reset filter">
                        <iconify-icon icon="solar:restart-bold"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>

    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table crm-table-compact bordered-table mb-0 align-middle">
                <thead class="bg-neutral-100">
                    <tr>
                        <th class="text-xs fw-bold text-uppercase py-16 px-20">No</th>
                        <th class="text-xs fw-bold text-uppercase py-16 px-20">No. Proposal & Dokumen</th>
                        <th class="text-xs fw-bold text-uppercase py-16 px-20">Opportunity & Klien</th>
                        <th class="text-xs fw-bold text-uppercase py-16 px-20 text-end">Nilai Penawaran</th>
                        <th class="text-xs fw-bold text-uppercase py-16 px-20 text-end">Est. Direct Cost</th>
                        <th class="text-xs fw-bold text-uppercase py-16 px-20 text-center">Gross Margin</th>
                        <th class="text-xs fw-bold text-uppercase py-16 px-20 text-center">Status</th>
                        <th class="text-xs fw-bold text-uppercase py-16 px-20 text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proposals as $index => $prop)
                        @php
                            $statusBadges = [
                                'draft' => 'bg-secondary-subtle text-secondary-emphasis',
                                'submitted' => 'bg-primary-subtle text-primary-emphasis',
                                'revised' => 'bg-warning-subtle text-warning-emphasis',
                                'accepted' => 'bg-success-subtle text-success-emphasis',
                                'rejected' => 'bg-danger-subtle text-danger-emphasis',
                            ];
                        @endphp
                        <tr>
                            <td class="px-20 text-secondary-light text-sm">
                                {{ $proposals->firstItem() + $index }}
                            </td>
                            <td class="px-20">
                                <button type="button" class="btn p-0 border-0 text-start fw-bold text-primary-600 hover-text-primary text-sm d-block bg-transparent"
                                        data-proposal="{{ json_encode($prop) }}"
                                        onclick="openProposalDetailModal(this)">
                                    {{ $prop->proposal_number }}
                                </button>
                                @if($prop->file_path)
                                    <a href="{{ asset($prop->file_path) }}" target="_blank" class="badge bg-neutral-100 text-primary-600 text-2xs mt-2 d-inline-flex align-items-center gap-1 border">
                                        <iconify-icon icon="solar:paperclip-outline"></iconify-icon> Unduh Dokumen
                                    </a>
                                @endif
                            </td>
                            <td class="px-20">
                                <strong class="text-primary-light text-sm d-block">{{ $prop->opportunity->title ?? '-' }}</strong>
                                <span class="text-secondary-light text-xs">
                                    <iconify-icon icon="solar:buildings-bold-duotone" class="align-middle me-1"></iconify-icon>
                                    {{ $prop->opportunity->customer->company_name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-20 text-end">
                                <strong class="text-primary-600 text-sm">
                                    Rp {{ number_format((float) $prop->amount, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td class="px-20 text-end text-secondary-light text-sm">
                                Rp {{ number_format((float) $prop->estimated_cost, 0, ',', '.') }}
                            </td>
                            <td class="px-20 text-center">
                                @if($prop->gross_margin >= 25)
                                    <span class="badge bg-success-50 text-success-700 border border-success-200 text-xs fw-semibold px-8 py-4 radius-6">
                                        {{ number_format((float) $prop->gross_margin, 1) }}%
                                    </span>
                                @elseif($prop->gross_margin >= 15)
                                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200 text-xs fw-semibold px-8 py-4 radius-6">
                                        {{ number_format((float) $prop->gross_margin, 1) }}%
                                    </span>
                                @else
                                    <span class="badge bg-danger-50 text-danger-700 border border-danger-200 text-xs fw-semibold px-8 py-4 radius-6">
                                        {{ number_format((float) $prop->gross_margin, 1) }}%
                                    </span>
                                @endif
                            </td>
                            <td class="px-20 text-center">
                                <span class="badge {{ $statusBadges[$prop->status] ?? 'bg-secondary' }} px-10 py-6 radius-6 text-xs text-uppercase fw-semibold">
                                    {{ $prop->status }}
                                </span>
                            </td>
                            <td class="px-20 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <button type="button"
                                            data-proposal="{{ json_encode($prop) }}"
                                            onclick="openProposalDetailModal(this)"
                                            class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center border-0"
                                            title="Detail Proposal">
                                        <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                    </button>

                                    @can('sales.proposal.edit')
                                        <button type="button"
                                                data-proposal="{{ json_encode($prop) }}"
                                                onclick="openProposalEditModal(this)"
                                                class="w-32-px h-32-px rounded-circle bg-success-focus text-success-main d-inline-flex justify-content-center align-items-center border-0"
                                                title="Edit Proposal">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </button>
                                    @endcan

                                    @can('sales.proposal.delete')
                                        <form action="{{ route('dashboard-admin.sales.proposals.destroy', $prop) }}" method="POST"
                                              onsubmit="return confirm('Hapus proposal ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus">
                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-40 text-secondary-light">
                                Belum ada data proposal penawaran harga.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($proposals->hasPages())
            <div class="p-20 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-secondary-light text-xs">
                    Menampilkan {{ $proposals->firstItem() }} sampai {{ $proposals->lastItem() }} dari total {{ $proposals->total() }} proposal
                </span>
                <div>
                    {{ $proposals->links() }}
                </div>
            </div>
        @endif
    </div>
</div>


@can('sales.proposal.create')
<div class="modal fade" id="createProposalModal" tabindex="-1" aria-labelledby="createProposalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
            <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-40-px h-40-px radius-8 bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:document-add-bold-duotone" class="text-xl"></iconify-icon>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-primary-light mb-0" id="createProposalModalLabel">Buat Proposal / Quotation Baru</h5>
                        <p class="text-secondary-light text-xs mb-0">Registrasi penawaran harga komersial & kalkulasi gross margin</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-24">
                @if (!empty($errors) && $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 radius-8 mb-20" role="alert">
                        <div class="fw-semibold mb-4 text-sm">Terdapat kesalahan pengisian data:</div>
                        <ul class="mb-0 text-xs ps-20">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('dashboard-admin.sales.proposals.store') }}" method="POST" enctype="multipart/form-data" id="createProposalForm">
                    @csrf

                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Opportunity / Proyek <span class="text-danger">*</span>
                            </label>
                            <select name="opportunity_id" class="form-select radius-8 @error('opportunity_id') is-invalid @enderror" required id="modalOpportunitySelect">
                                <option value="">-- Pilih Opportunity --</option>
                                @foreach ($opportunities as $opp)
                                    <option value="{{ $opp->id }}" {{ old('opportunity_id', $selectedOpportunityId ?? '') == $opp->id ? 'selected' : '' }}>
                                        {{ $opp->title }} ({{ $opp->customer->company_name ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('opportunity_id')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nomor Proposal / Quotation <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="proposal_number" value="{{ old('proposal_number', $autoNumber ?? '') }}"
                                   class="form-control radius-8 @error('proposal_number') is-invalid @enderror" required>
                            @error('proposal_number')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nilai Penawaran (Selling Price) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text radius-8-start text-xs fw-bold">Rp</span>
                                <input type="number" step="1000" name="amount" id="modalInputAmount" value="{{ old('amount', 0) }}"
                                       class="form-control radius-8-end @error('amount') is-invalid @enderror" required oninput="calculateModalMargin()">
                            </div>
                            @error('amount')
                                <div class="invalid-feedback text-xs d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Estimasi Direct Cost (RAB) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text radius-8-start text-xs fw-bold">Rp</span>
                                <input type="number" step="1000" name="estimated_cost" id="modalInputCost" value="{{ old('estimated_cost', 0) }}"
                                       class="form-control radius-8-end @error('estimated_cost') is-invalid @enderror" required oninput="calculateModalMargin()">
                            </div>
                            @error('estimated_cost')
                                <div class="invalid-feedback text-xs d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-12">
                            <div class="p-16 radius-8 bg-neutral-50-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-1">Estimasi Gross Profit & Margin</span>
                                    <span class="text-sm fw-semibold text-primary-light" id="modalDisplayGrossProfit">Gross Profit: Rp 0</span>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary-100 text-primary-700 text-sm fw-bold px-12 py-6 radius-6" id="modalDisplayGrossMargin">
                                        Gross Margin: 0.0%
                                    </span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Status Proposal <span class="text-danger">*</span>
                            </label>
                            <select name="status" class="form-select radius-8 @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Konsep Internal)</option>
                                <option value="submitted" {{ old('status', 'submitted') === 'submitted' ? 'selected' : '' }}>Submitted (Terkirim ke Klien)</option>
                                <option value="revised" {{ old('status') === 'revised' ? 'selected' : '' }}>Revised (Revisi Penawaran)</option>
                                <option value="accepted" {{ old('status') === 'accepted' ? 'selected' : '' }}>Accepted (Diterima / Deal)</option>
                                <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Tanggal Pengajuan ke Klien
                            </label>
                            <input type="datetime-local" name="submitted_at" value="{{ old('submitted_at', date('Y-m-d\TH:i')) }}"
                                   class="form-control radius-8 @error('submitted_at') is-invalid @enderror">
                            @error('submitted_at')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-12">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Berkas Dokumen Proposal (PDF / DOCX / XLSX, maks. 20MB)
                            </label>
                            <input type="file" name="document" class="form-control radius-8 @error('document') is-invalid @enderror">
                            @error('document')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-12">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Catatan Komersial / Syarat Pembayaran (Payment Terms)
                            </label>
                            <textarea name="notes" rows="3" class="form-control radius-8 @error('notes') is-invalid @enderror"
                                      placeholder="Contoh: DP 30%, Progress Billing 50%, BAST 20%. Term of Payment 30 hari.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer py-14 px-24 border-top d-flex justify-content-end align-items-center gap-2">
                <button type="button" class="btn btn-outline-secondary radius-8 px-20 py-10" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" id="btnSubmit" form="createProposalForm" class="btn btn-primary radius-8 px-24 py-10 d-flex align-items-center gap-2 fw-semibold">
                    <iconify-icon icon="solar:diskette-bold-duotone" class="text-lg"></iconify-icon>
                    Simpan Proposal
                </button>
            </div>
        </div>
    </div>
</div>
@endcan

{{-- Modal Detail Proposal --}}
<div class="modal fade" id="detailProposalModal" tabindex="-1" aria-labelledby="detailProposalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
            <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="w-48-px h-48-px radius-12 d-flex justify-content-center align-items-center bg-warning-50 text-warning-main text-2xl flex-shrink-0">
                        <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h5 class="modal-title fw-bold text-primary-light mb-0" id="detailProposalNumber">-</h5>
                            <span class="badge text-xs text-uppercase px-10 py-6 radius-6" id="detailProposalStatusBadge">-</span>
                        </div>
                        <div class="d-flex align-items-center gap-3 text-secondary-light text-xs mt-4 flex-wrap">
                            <span>
                                <iconify-icon icon="solar:diagram-up-bold-duotone" class="text-primary-600 align-middle me-1"></iconify-icon>
                                Opportunity: <strong id="detailOpportunityTitle" class="text-primary-light">-</strong>
                            </span>
                            <span>
                                <iconify-icon icon="solar:buildings-bold-duotone" class="text-info-main align-middle me-1"></iconify-icon>
                                Klien: <strong id="detailCustomerName" class="text-primary-light">-</strong>
                            </span>
                            <span>
                                <iconify-icon icon="solar:calendar-date-bold-duotone" class="text-danger-main align-middle me-1"></iconify-icon>
                                Diajukan: <span id="detailSubmittedAt">-</span>
                            </span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-24">
                {{-- Financial & Margin Breakdown Cards --}}
                <div class="row g-3 mb-20">
                    <div class="col-md-4">
                        <div class="p-16 radius-8 bg-primary-50 border border-primary-200">
                            <span class="text-xs text-primary-800 fw-bold text-uppercase d-block mb-4">Nilai Penawaran (Selling)</span>
                            <h5 class="fw-bold text-primary-900 mb-0" id="detailAmount">Rp 0</h5>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-16 radius-8 bg-neutral-100 border">
                            <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-4">Est. Direct Cost (RAB)</span>
                            <h5 class="fw-bold text-secondary-light mb-0" id="detailCost">Rp 0</h5>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-16 radius-8 bg-success-50 border border-success-200">
                            <span class="text-xs text-success-800 fw-bold text-uppercase d-block mb-4">Gross Profit & Margin</span>
                            <h5 class="fw-bold text-success-900 mb-1" id="detailProfit">Rp 0</h5>
                            <span class="badge bg-success text-white text-xs px-8 py-4 radius-6" id="detailMarginBadge">
                                Gross Margin: 0.0%
                            </span>
                        </div>
                    </div>
                </div>

                {{-- File and Notes --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-16 radius-8 bg-neutral-50-light border h-100">
                            <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-8">Berkas Dokumen Penawaran</span>
                            <div id="detailFileWrapper">
                                <a href="#" id="detailDownloadBtn" target="_blank" class="btn btn-outline-primary btn-sm radius-8 d-inline-flex align-items-center gap-2">
                                    <iconify-icon icon="solar:paperclip-outline" class="text-lg"></iconify-icon>
                                    Unduh Dokumen Proposal
                                </a>
                            </div>
                            <div id="detailNoFileWrapper" class="text-xs text-secondary-light fst-italic">
                                Belum ada berkas dokumen yang dilampirkan.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-16 radius-8 bg-neutral-50-light border h-100">
                            <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-8">Catatan Komersial / Terms</span>
                            <p class="mb-0 text-sm text-primary-light" id="detailNotes" style="white-space: pre-line;">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer py-14 px-24 border-top d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-secondary radius-8 px-20 py-10" data-bs-dismiss="modal">
                    Tutup
                </button>
                @can('sales.proposal.edit')
                    <button type="button" id="btnSwitchToEditProposal" class="btn btn-primary radius-8 px-20 py-10 d-flex align-items-center gap-2 fw-semibold">
                        <iconify-icon icon="solar:pen-bold-duotone" class="text-lg"></iconify-icon>
                        Edit Proposal
                    </button>
                @endcan
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Proposal --}}
@can('sales.proposal.edit')
<div class="modal fade" id="editProposalModal" tabindex="-1" aria-labelledby="editProposalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg overflow-hidden">
            <div class="modal-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-40-px h-40-px radius-8 bg-success-50 text-success-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:pen-bold-duotone" class="text-xl"></iconify-icon>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-primary-light mb-0" id="editProposalModalLabel">Edit Proposal / Quotation</h5>
                        <p class="text-secondary-light text-xs mb-0">Perbarui nilai penawaran, RAB direct cost, atau status revisi</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-24">
                <form id="editProposalForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Opportunity Parent --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Opportunity / Proyek <span class="text-danger">*</span>
                            </label>
                            <select name="opportunity_id" id="editOpportunitySelect" class="form-select radius-8" required>
                                <option value="">-- Pilih Opportunity --</option>
                                @foreach ($opportunities as $opp)
                                    <option value="{{ $opp->id }}">
                                        {{ $opp->title }} ({{ $opp->customer->company_name ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nomor Proposal --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nomor Proposal / Quotation <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="proposal_number" id="editProposalNumber" class="form-control radius-8" required>
                        </div>

                        {{-- Nilai Penawaran (Amount) --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nilai Penawaran (Selling Price) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text radius-8-start text-xs fw-bold">Rp</span>
                                <input type="number" step="1000" name="amount" id="editInputAmount" class="form-control radius-8-end" required oninput="calculateEditModalMargin()">
                            </div>
                        </div>

                        {{-- Direct Cost (RAB) --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Estimasi Direct Cost (RAB) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text radius-8-start text-xs fw-bold">Rp</span>
                                <input type="number" step="1000" name="estimated_cost" id="editInputCost" class="form-control radius-8-end" required oninput="calculateEditModalMargin()">
                            </div>
                        </div>

                        {{-- Live Margin Calculator Display --}}
                        <div class="col-12">
                            <div class="p-16 radius-8 bg-neutral-50-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-1">Estimasi Gross Profit & Margin</span>
                                    <span class="text-sm fw-semibold text-primary-light" id="editDisplayGrossProfit">Gross Profit: Rp 0</span>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary-100 text-primary-700 text-sm fw-bold px-12 py-6 radius-6" id="editDisplayGrossMargin">
                                        Gross Margin: 0.0%
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Status Proposal --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Status Proposal <span class="text-danger">*</span>
                            </label>
                            <select name="status" id="editProposalStatus" class="form-select radius-8" required>
                                <option value="draft">Draft (Konsep Internal)</option>
                                <option value="submitted">Submitted (Terkirim ke Klien)</option>
                                <option value="revised">Revised (Revisi Penawaran)</option>
                                <option value="accepted">Accepted (Diterima / Deal)</option>
                                <option value="rejected">Rejected (Ditolak)</option>
                            </select>
                        </div>

                        {{-- Tanggal Submit --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Tanggal Pengajuan ke Klien
                            </label>
                            <input type="datetime-local" name="submitted_at" id="editProposalSubmittedAt" class="form-control radius-8">
                        </div>

                        {{-- Upload Dokumen --}}
                        <div class="col-12">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Berkas Dokumen Proposal (PDF / DOCX / XLSX, maks. 20MB)
                            </label>
                            <input type="file" name="document" class="form-control radius-8">
                            <div id="editCurrentFileWrapper" class="mt-2 text-xs d-none">
                                <span class="text-secondary-light">Berkas saat ini: </span>
                                <a href="#" id="editCurrentFileLink" target="_blank" class="fw-semibold text-primary-600">
                                    <iconify-icon icon="solar:paperclip-outline"></iconify-icon> Unduh Dokumen Terlampir
                                </a>
                            </div>
                        </div>

                        {{-- Catatan / Terms --}}
                        <div class="col-12">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Catatan Komersial / Syarat Pembayaran (Payment Terms)
                            </label>
                            <textarea name="notes" id="editProposalNotes" rows="3" class="form-control radius-8"
                                      placeholder="Contoh: DP 30%, Progress Billing 50%, BAST 20%. Term of Payment 30 hari."></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer py-14 px-24 border-top d-flex justify-content-end align-items-center gap-2">
                <button type="button" class="btn btn-outline-secondary radius-8 px-20 py-10" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="submit" form="editProposalForm" class="btn btn-success radius-8 px-24 py-10 d-flex align-items-center gap-2 fw-semibold">
                    <iconify-icon icon="solar:diskette-bold-duotone" class="text-lg"></iconify-icon>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
@endcan

<script>
    let currentSelectedProposal = null;

    function parseProposalData(target) {
        if (!target) return null;
        if (typeof target === 'string') {
            try { return JSON.parse(target); } catch(e) { return null; }
        }
        if (target instanceof HTMLElement) {
            const raw = target.getAttribute('data-proposal');
            try { return JSON.parse(raw); } catch(e) { return null; }
        }
        return target;
    }

    function formatIDR(num) {
        const val = parseFloat(num) || 0;
        return 'Rp ' + Math.round(val).toLocaleString('id-ID');
    }

    function openProposalDetailModal(target) {
        const prop = parseProposalData(target);
        if (!prop) return;
        currentSelectedProposal = prop;

        document.getElementById('detailProposalNumber').textContent = prop.proposal_number || '-';
        
        // Status Badge
        const statusBadge = document.getElementById('detailProposalStatusBadge');
        const statusClasses = {
            'draft': 'bg-secondary-subtle text-secondary-emphasis',
            'submitted': 'bg-primary-subtle text-primary-emphasis',
            'revised': 'bg-warning-subtle text-warning-emphasis',
            'accepted': 'bg-success-subtle text-success-emphasis',
            'rejected': 'bg-danger-subtle text-danger-emphasis'
        };
        statusBadge.className = 'badge text-xs text-uppercase px-10 py-6 radius-6 ' + (statusClasses[prop.status] || 'bg-secondary');
        statusBadge.textContent = prop.status || '-';

        // Meta Info
        document.getElementById('detailOpportunityTitle').textContent = prop.opportunity?.title || '-';
        document.getElementById('detailCustomerName').textContent = prop.opportunity?.customer?.company_name || '-';
        
        if (prop.submitted_at) {
            const d = new Date(prop.submitted_at);
            document.getElementById('detailSubmittedAt').textContent = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        } else {
            document.getElementById('detailSubmittedAt').textContent = '-';
        }

        // Financials
        const amount = parseFloat(prop.amount) || 0;
        const cost = parseFloat(prop.estimated_cost) || 0;
        const profit = amount - cost;
        const margin = amount > 0 ? (profit / amount) * 100 : 0;

        document.getElementById('detailAmount').textContent = formatIDR(amount);
        document.getElementById('detailCost').textContent = formatIDR(cost);
        document.getElementById('detailProfit').textContent = formatIDR(profit);
        
        const marginBadge = document.getElementById('detailMarginBadge');
        marginBadge.textContent = 'Gross Margin: ' + margin.toFixed(1) + '%';
        if (margin >= 25) {
            marginBadge.className = 'badge bg-success text-white text-xs px-8 py-4 radius-6';
        } else if (margin >= 15) {
            marginBadge.className = 'badge bg-warning text-dark text-xs px-8 py-4 radius-6';
        } else {
            marginBadge.className = 'badge bg-danger text-white text-xs px-8 py-4 radius-6';
        }

        // Berkas Dokumen
        const fileWrapper = document.getElementById('detailFileWrapper');
        const noFileWrapper = document.getElementById('detailNoFileWrapper');
        const downloadBtn = document.getElementById('detailDownloadBtn');
        if (prop.file_path) {
            downloadBtn.href = '/' + prop.file_path.replace(/^\/+/, '');
            fileWrapper.classList.remove('d-none');
            noFileWrapper.classList.add('d-none');
        } else {
            fileWrapper.classList.add('d-none');
            noFileWrapper.classList.remove('d-none');
        }

        // Catatan
        document.getElementById('detailNotes').textContent = prop.notes || 'Tidak ada catatan komersial tambahan.';

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('detailProposalModal'));
        modal.show();
    }

    function openProposalEditModal(target) {
        const prop = parseProposalData(target);
        if (!prop) return;
        currentSelectedProposal = prop;

        // Tutup detail modal bila sedang terbuka
        const detailModalEl = document.getElementById('detailProposalModal');
        if (detailModalEl) {
            const detailModal = bootstrap.Modal.getInstance(detailModalEl);
            if (detailModal) detailModal.hide();
        }

        const form = document.getElementById('editProposalForm');
        form.action = '/dashboard-admin/sales/proposals/' + prop.id;

        document.getElementById('editOpportunitySelect').value = prop.opportunity_id || '';
        document.getElementById('editProposalNumber').value = prop.proposal_number || '';
        document.getElementById('editInputAmount').value = prop.amount || 0;
        document.getElementById('editInputCost').value = prop.estimated_cost || 0;
        document.getElementById('editProposalStatus').value = prop.status || 'draft';

        if (prop.submitted_at) {
            const d = new Date(prop.submitted_at);
            const pad = (n) => String(n).padStart(2, '0');
            const formattedDate = `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
            document.getElementById('editProposalSubmittedAt').value = formattedDate;
        } else {
            document.getElementById('editProposalSubmittedAt').value = '';
        }

        // Current file link
        const currentFileWrapper = document.getElementById('editCurrentFileWrapper');
        const currentFileLink = document.getElementById('editCurrentFileLink');
        if (prop.file_path) {
            currentFileLink.href = '/' + prop.file_path.replace(/^\/+/, '');
            currentFileWrapper.classList.remove('d-none');
        } else {
            currentFileWrapper.classList.add('d-none');
        }

        document.getElementById('editProposalNotes').value = prop.notes || '';

        calculateEditModalMargin();

        const editModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editProposalModal'));
        editModal.show();
    }

    function calculateModalMargin() {
        const amountEl = document.getElementById('modalInputAmount');
        const costEl = document.getElementById('modalInputCost');
        if (!amountEl || !costEl) return;

        const amount = parseFloat(amountEl.value) || 0;
        const cost = parseFloat(costEl.value) || 0;
        const profit = amount - cost;
        const margin = amount > 0 ? (profit / amount) * 100 : 0;

        const profitEl = document.getElementById('modalDisplayGrossProfit');
        const marginEl = document.getElementById('modalDisplayGrossMargin');

        if (profitEl) {
            profitEl.innerText = 'Gross Profit: Rp ' + Math.round(profit).toLocaleString('id-ID');
        }
        if (marginEl) {
            marginEl.innerText = 'Gross Margin: ' + margin.toFixed(1) + '%';
            if (margin >= 25) {
                marginEl.className = 'badge bg-success-100 text-success-700 text-sm fw-bold px-12 py-6 radius-6';
            } else if (margin >= 15) {
                marginEl.className = 'badge bg-warning-100 text-warning-700 text-sm fw-bold px-12 py-6 radius-6';
            } else {
                marginEl.className = 'badge bg-danger-100 text-danger-700 text-sm fw-bold px-12 py-6 radius-6';
            }
        }
    }

    function calculateEditModalMargin() {
        const amountEl = document.getElementById('editInputAmount');
        const costEl = document.getElementById('editInputCost');
        if (!amountEl || !costEl) return;

        const amount = parseFloat(amountEl.value) || 0;
        const cost = parseFloat(costEl.value) || 0;
        const profit = amount - cost;
        const margin = amount > 0 ? (profit / amount) * 100 : 0;

        const profitEl = document.getElementById('editDisplayGrossProfit');
        const marginEl = document.getElementById('editDisplayGrossMargin');

        if (profitEl) {
            profitEl.innerText = 'Gross Profit: Rp ' + Math.round(profit).toLocaleString('id-ID');
        }
        if (marginEl) {
            marginEl.innerText = 'Gross Margin: ' + margin.toFixed(1) + '%';
            if (margin >= 25) {
                marginEl.className = 'badge bg-success-100 text-success-700 text-sm fw-bold px-12 py-6 radius-6';
            } else if (margin >= 15) {
                marginEl.className = 'badge bg-warning-100 text-warning-700 text-sm fw-bold px-12 py-6 radius-6';
            } else {
                marginEl.className = 'badge bg-danger-100 text-danger-700 text-sm fw-bold px-12 py-6 radius-6';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const btnSwitch = document.getElementById('btnSwitchToEditProposal');
        if (btnSwitch) {
            btnSwitch.addEventListener('click', function() {
                if (currentSelectedProposal) {
                    openProposalEditModal(currentSelectedProposal);
                }
            });
        }

        // Automatically open create modal if requested via query or on validation error
        @if (request('open_create') || (!empty($errors) && $errors->any() && old('_method') !== 'PUT'))
            const modalEl = document.getElementById('createProposalModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
                calculateModalMargin();
            }
        @endif
    });
</script>
@endsection
