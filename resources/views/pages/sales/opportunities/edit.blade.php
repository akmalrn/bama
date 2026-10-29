@extends('layout.layout')

@php
    $title = 'Edit Opportunity';
    $subTitle = 'Perbarui Status Pipeline — ' . $opportunity->title;
@endphp

@section('content')
<div class="row gy-4 mb-24 justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden">
            <div class="card-header border-bottom py-16 px-24 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('dashboard-admin.sales.opportunities.index') }}" class="w-36-px h-36-px radius-8 bg-neutral-100 text-secondary-light d-flex justify-content-center align-items-center hover-bg-primary-50 hover-text-primary">
                        <iconify-icon icon="solar:arrow-left-outline" class="text-xl"></iconify-icon>
                    </a>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Edit Opportunity</h5>
                        <p class="text-secondary-light text-xs mb-0">ID: #OPP-{{ str_pad((string)$opportunity->id, 4, '0', STR_PAD_LEFT) }} &bull; {{ $opportunity->title }}</p>
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

                <form action="{{ route('dashboard-admin.sales.opportunities.update', $opportunity) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-24">
                        {{-- Judul Opportunity --}}
                        <div class="col-12">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Judul Proyek / Paket Pekerjaan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $opportunity->title) }}"
                                   class="form-control radius-8 @error('title') is-invalid @enderror"
                                   placeholder="Contoh: Overhaul & Maintenance High Pressure Boiler Package" required>
                            @error('title')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Customer --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Perusahaan Klien (Customer) <span class="text-danger">*</span>
                            </label>
                            <select name="customer_id" class="form-select radius-8 @error('customer_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id', $opportunity->customer_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->company_name }} ({{ $c->industry ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sales PIC / Owner --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                PIC Sales Representative (Owner) <span class="text-danger">*</span>
                            </label>
                            <select name="owner_id" class="form-select radius-8 @error('owner_id') is-invalid @enderror" required>
                                @foreach ($salesUsers as $su)
                                    <option value="{{ $su->id }}" {{ old('owner_id', $opportunity->owner_id) == $su->id ? 'selected' : '' }}>
                                        {{ $su->name }} ({{ $su->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('owner_id')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estimasi Nilai Deal --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Estimasi Nilai Kontrak (Rp) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text radius-8-start text-xs fw-bold">Rp</span>
                                <input type="text" inputmode="numeric" name="estimated_value" value="{{ number_format((float) old('estimated_value', $opportunity->estimated_value), 0, ',', '.') }}"
                                       class="form-control radius-8-end rupiah-input @error('estimated_value') is-invalid @enderror" required placeholder="10.000" autocomplete="off">
                            </div>
                            @error('estimated_value')
                                <div class="invalid-feedback text-xs d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Pipeline Stage --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Tahap Pipeline <span class="text-danger">*</span>
                            </label>
                            <select name="stage" class="form-select radius-8 @error('stage') is-invalid @enderror" required>
                                <option value="qualification" {{ old('stage', $opportunity->stage) === 'qualification' ? 'selected' : '' }}>1. Qualification</option>
                                <option value="need_analysis" {{ old('stage', $opportunity->stage) === 'need_analysis' ? 'selected' : '' }}>2. Need Analysis</option>
                                <option value="tender_rfq" {{ old('stage', $opportunity->stage) === 'tender_rfq' ? 'selected' : '' }}>3. Tender / RFQ</option>
                                <option value="proposal_submitted" {{ old('stage', $opportunity->stage) === 'proposal_submitted' ? 'selected' : '' }}>4. Proposal Submitted</option>
                                <option value="negotiation" {{ old('stage', $opportunity->stage) === 'negotiation' ? 'selected' : '' }}>5. Negotiation</option>
                                <option value="closed_won" {{ old('stage', $opportunity->stage) === 'closed_won' ? 'selected' : '' }}>Closed Won</option>
                                <option value="closed_lost" {{ old('stage', $opportunity->stage) === 'closed_lost' ? 'selected' : '' }}>Closed Lost</option>
                            </select>
                            @error('stage')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Target Close Date --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Target Tanggal Closing
                            </label>
                            <input type="date" name="target_close_date" value="{{ old('target_close_date', $opportunity->target_close_date ? $opportunity->target_close_date->format('Y-m-d') : '') }}"
                                   class="form-control radius-8 @error('target_close_date') is-invalid @enderror">
                            @error('target_close_date')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Next Action Plan --}}
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Langkah Tindak Lanjut Selanjutnya (Next Action)
                            </label>
                            <input type="text" name="next_step" value="{{ old('next_step', $opportunity->next_step) }}"
                                   class="form-control radius-8 @error('next_step') is-invalid @enderror"
                                   placeholder="Contoh: Kirim dokumen proposal teknis & jadwal survey site">
                            @error('next_step')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-end align-items-center gap-2 pt-16 border-top">
                        <a href="{{ route('dashboard-admin.sales.opportunities.index') }}" class="btn btn-outline-secondary radius-8 px-20 py-10">
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
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.rupiah-input').forEach(function(input) {
            input.addEventListener('input', function() {
                let cursor = this.selectionStart;
                let prevLen = this.value.length;
                let raw = this.value.replace(/[^0-9]/g, '');
                if (raw === '') {
                    this.value = '';
                    return;
                }
                let formatted = new Intl.NumberFormat('id-ID').format(raw);
                this.value = formatted;
                let diff = formatted.length - prevLen;
                this.setSelectionRange(cursor + diff, cursor + diff);
            });
        });
    });
</script>
@endsection
