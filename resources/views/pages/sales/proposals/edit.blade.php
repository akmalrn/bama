@extends('layout.layout')

@php
    $title = 'Edit Proposal / Quotation';
    $subTitle = 'Perbarui Nilai Penawaran & Status — ' . $proposal->proposal_number;
@endphp

@section('content')
<div class="row gy-4 mb-24 justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden">
            <div class="card-header border-bottom py-16 px-24 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('dashboard-admin.sales.proposals.index') }}" class="w-36-px h-36-px radius-8 bg-neutral-100 text-secondary-light d-flex justify-content-center align-items-center hover-bg-primary-50 hover-text-primary">
                        <iconify-icon icon="solar:arrow-left-outline" class="text-xl"></iconify-icon>
                    </a>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Edit Proposal: {{ $proposal->proposal_number }}</h5>
                        <p class="text-secondary-light text-xs mb-0">Ubah harga penawaran komersial, RAB direct cost, atau status revisi</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-24">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 radius-8 mb-20" role="alert">
                        <div class="fw-semibold mb-4">Terdapat kesalahan pengisian data:</div>
                        <ul class="mb-0 text-sm ps-20">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('dashboard-admin.sales.proposals.update', $proposal) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-24">
                        
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Opportunity / Proyek <span class="text-danger">*</span>
                            </label>
                            <select name="opportunity_id" class="form-select radius-8 @error('opportunity_id') is-invalid @enderror" required>
                                @foreach ($opportunities as $opp)
                                    <option value="{{ $opp->id }}" {{ old('opportunity_id', $proposal->opportunity_id) == $opp->id ? 'selected' : '' }}>
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
                            <input type="text" name="proposal_number" value="{{ old('proposal_number', $proposal->proposal_number) }}"
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
                                <span class="input-group-text radius-8-start bg-neutral-100 text-xs fw-bold">Rp</span>
                                <input type="number" step="1000" name="amount" id="inputAmount" value="{{ old('amount', (float)$proposal->amount) }}"
                                       class="form-control radius-8-end @error('amount') is-invalid @enderror" required oninput="calculateMargin()">
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
                                <span class="input-group-text radius-8-start bg-neutral-100 text-xs fw-bold">Rp</span>
                                <input type="number" step="1000" name="estimated_cost" id="inputCost" value="{{ old('estimated_cost', (float)$proposal->estimated_cost) }}"
                                       class="form-control radius-8-end @error('estimated_cost') is-invalid @enderror" required oninput="calculateMargin()">
                            </div>
                            @error('estimated_cost')
                                <div class="invalid-feedback text-xs d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-12">
                            <div class="p-16 radius-8 bg-neutral-50-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <span class="text-xs text-secondary-light fw-bold text-uppercase d-block mb-1">Estimasi Gross Profit & Margin</span>
                                    <span class="text-sm fw-semibold text-primary-light" id="displayGrossProfit">Gross Profit: Rp 0</span>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary-100 text-primary-700 text-sm fw-bold px-12 py-6 radius-6" id="displayGrossMargin">
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
                                <option value="draft" {{ old('status', $proposal->status) === 'draft' ? 'selected' : '' }}>Draft (Konsep Internal)</option>
                                <option value="submitted" {{ old('status', $proposal->status) === 'submitted' ? 'selected' : '' }}>Submitted (Terkirim ke Klien)</option>
                                <option value="revised" {{ old('status', $proposal->status) === 'revised' ? 'selected' : '' }}>Revised (Revisi Penawaran)</option>
                                <option value="accepted" {{ old('status', $proposal->status) === 'accepted' ? 'selected' : '' }}>Accepted (Diterima / Deal)</option>
                                <option value="rejected" {{ old('status', $proposal->status) === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Tanggal Pengajuan ke Klien
                            </label>
                            <input type="datetime-local" name="submitted_at" value="{{ old('submitted_at', $proposal->submitted_at ? $proposal->submitted_at->format('Y-m-d\TH:i') : '') }}"
                                   class="form-control radius-8 @error('submitted_at') is-invalid @enderror">
                            @error('submitted_at')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-12">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Ganti Berkas Dokumen (Kosongkan jika tidak ingin mengubah)
                            </label>
                            <input type="file" name="document" class="form-control radius-8 @error('document') is-invalid @enderror">
                            @if($proposal->file_path)
                                <div class="mt-4 text-xs text-secondary-light">
                                    Berkas saat ini: <a href="{{ asset($proposal->file_path) }}" target="_blank" class="fw-semibold text-primary-600">Unduh Berkas Terlampir</a>
                                </div>
                            @endif
                            @error('document')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-12">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Catatan Komersial / Syarat Pembayaran (Payment Terms)
                            </label>
                            <textarea name="notes" rows="3" class="form-control radius-8 @error('notes') is-invalid @enderror">{{ old('notes', $proposal->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    
                    <div class="d-flex justify-content-end align-items-center gap-2 pt-16 border-top">
                        <a href="{{ route('dashboard-admin.sales.proposals.index') }}" class="btn btn-outline-secondary radius-8 px-20 py-10">
                            Batal
                        </a>
                        <button type="submit" id="btnUpdate" class="btn btn-primary radius-8 px-24 py-10 d-flex align-items-center gap-2 fw-semibold">
                            <iconify-icon icon="solar:diskette-bold-duotone" class="text-lg"></iconify-icon>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function calculateMargin() {
        const amount = parseFloat(document.getElementById('inputAmount').value) || 0;
        const cost = parseFloat(document.getElementById('inputCost').value) || 0;
        const profit = amount - cost;
        const margin = amount > 0 ? (profit / amount) * 100 : 0;

        document.getElementById('displayGrossProfit').innerText = 'Gross Profit: Rp ' + profit.toLocaleString('id-ID');
        document.getElementById('displayGrossMargin').innerText = 'Gross Margin: ' + margin.toFixed(1) + '%';
        
        const badge = document.getElementById('displayGrossMargin');
        if (margin >= 25) {
            badge.className = 'badge bg-success-100 text-success-700 text-sm fw-bold px-12 py-6 radius-6';
        } else if (margin >= 15) {
            badge.className = 'badge bg-warning-100 text-warning-700 text-sm fw-bold px-12 py-6 radius-6';
        } else {
            badge.className = 'badge bg-danger-100 text-danger-700 text-sm fw-bold px-12 py-6 radius-6';
        }
    }
    document.addEventListener('DOMContentLoaded', calculateMargin);
</script>
@endsection
