@extends('layout.layout')

@php
    $title = 'Sales AI Opportunities';
    $subTitle = 'Sales CRM';
    $script = '<script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".btn-convert-ai").forEach(btn => {
                btn.addEventListener("click", function() {
                    const title = this.getAttribute("data-title");
                    const company = this.getAttribute("data-company");
                    const val = this.getAttribute("data-value");
                    const sector = this.getAttribute("data-sector");
                    const region = this.getAttribute("data-region");
                    const summary = this.getAttribute("data-summary");

                    document.getElementById("modal_title").value = title;
                    document.getElementById("modal_company").value = company;
                    document.getElementById("modal_value").value = val;
                    document.getElementById("modal_sector").value = sector;
                    document.getElementById("modal_region").value = region;
                    document.getElementById("modal_next_step").value = "Follow up: " + summary;
                });
            });
        });
    </script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <h6 class="fw-semibold mb-0">Sales AI Opportunities</h6>
            <span class="badge bg-warning-subtle text-warning-main text-xs fw-bold px-8 py-2 radius-4">MARKET INTELLIGENCE</span>
        </div>
        <p class="text-neutral-500 text-sm mb-0">Telusuri sinyal peluang dan tender dari publikasi industri, lakukan evaluasi, lalu simpan ke Sales Pipeline.</p>
    </div>
</div>


<div class="alert alert-warning border-warning-200 bg-warning-50 text-warning-900 radius-8 p-16 d-flex align-items-start gap-3 mb-24" role="alert">
    <iconify-icon icon="solar:info-circle-bold" class="text-xl text-warning-main flex-shrink-0 mt-2"></iconify-icon>
    <div class="text-sm">
        <strong class="d-block mb-1">Rekomendasi Peluang Cerdas (AI Match Scoring)</strong>
        Peluang berikut dianalisis berdasarkan kecocokan kapabilitas layanan ELBEMA (Online Leak Sealing, Tank Cleaning, HE Cleaning, Flange Management). Anda dapat memvalidasi dan langsung mengonversinya menjadi Opportunity di pipeline.
    </div>
</div>


<div class="card shadow-none border mb-24">
    <div class="card-body p-20">
        <form method="GET" action="{{ route('dashboard-admin.sales.ai-opportunities.index') }}" class="row g-3">
            <div class="col-lg-4 col-md-6">
                <label class="form-label text-sm fw-medium">Kata Kunci / Layanan</label>
                <div class="position-relative">
                    <input type="text" name="keyword" class="form-control text-sm pe-40" placeholder="Cari judul, client, atau scope..." value="{{ $keyword }}">
                    <iconify-icon icon="solar:magnifer-outline" class="position-absolute end-0 top-50 translate-middle-y me-12 text-neutral-400"></iconify-icon>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label text-sm fw-medium">Sektor</label>
                <select name="sector" class="form-select text-sm">
                    <option value="">Semua Sektor</option>
                    @foreach($sectors as $s)
                        <option value="{{ $s }}" {{ $sector == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label text-sm fw-medium">Wilayah</label>
                <select name="region" class="form-select text-sm">
                    <option value="">Semua Wilayah</option>
                    @foreach($regions as $r)
                        <option value="{{ $r }}" {{ $region == $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100 py-10">Filter</button>
                @if(request()->anyFilled(['keyword', 'sector', 'region', 'type']))
                    <a href="{{ route('dashboard-admin.sales.ai-opportunities.index') }}" class="btn btn-light btn-sm py-10" title="Reset">
                        <iconify-icon icon="solar:restart-outline"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>


<div class="row g-3">
    @forelse($items as $item)
        @php
            $isConverted = in_array($item['title'], $convertedTitles);
            $scoreClass = $item['score'] >= 90 ? 'text-success-main border-success-main' : ($item['score'] >= 80 ? 'text-info-main border-info-main' : 'text-warning-main border-warning-main');
        @endphp
        <div class="col-12">
            <div class="card shadow-none border hover-scale-sm transition-2">
                <div class="card-body p-20">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                        <div class="flex-grow-1 max-w-800-px">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-8">
                                <h6 class="text-md fw-bold mb-0 text-neutral-900">{{ $item['title'] }}</h6>
                                <span class="badge bg-neutral-100 text-neutral-700 text-xs px-8 py-2 radius-4 fw-medium">
                                    {{ $item['type'] }}
                                </span>
                                <a href="https://www.google.com/search?q={{ urlencode($item['title'] . ' ' . $item['company'] . ' tender') }}" target="_blank" rel="noopener noreferrer" class="badge bg-primary-subtle text-primary-main text-xs px-8 py-2 radius-4 text-decoration-none d-flex align-items-center gap-1">
                                    <span>{{ $item['source'] }}</span>
                                    <iconify-icon icon="solar:arrow-right-up-outline" class="text-xs"></iconify-icon>
                                </a>
                            </div>

                            <p class="text-sm fw-semibold text-neutral-700 mb-6">
                                {{ $item['company'] }} <span class="text-neutral-400 font-normal">·</span> {{ $item['sector'] }} <span class="text-neutral-400 font-normal">·</span> {{ $item['region'] }}
                            </p>

                            <p class="text-neutral-600 text-sm mb-12">{{ $item['summary'] }}</p>

                            <div class="d-flex flex-wrap align-items-center gap-3 text-xs text-neutral-500">
                                <div>
                                    <span class="text-neutral-400">Target Layanan:</span>
                                    <strong class="text-neutral-800">{{ $item['service'] }}</strong>
                                </div>
                                <span class="text-neutral-300">•</span>
                                <div>
                                    <span class="text-neutral-400">Estimasi Nilai:</span>
                                    <strong class="text-neutral-800">Rp {{ number_format($item['estimated_value'], 0, ',', '.') }}</strong>
                                </div>
                                <span class="text-neutral-300">•</span>
                                <div>
                                    <span class="text-neutral-400">Penutupan Indikatif:</span>
                                    <strong class="text-neutral-800">{{ \Carbon\Carbon::parse($item['closing'])->translatedFormat('d F Y') }}</strong>
                                </div>
                            </div>
                        </div>

                        
                        <div class="d-flex flex-column align-items-center align-items-sm-end gap-12 flex-shrink-0">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-48-px h-48-px rounded-circle border border-2 d-flex align-items-center justify-content-center fw-bold text-md {{ $scoreClass }}">
                                    {{ $item['score'] }}%
                                </div>
                                <div class="text-end d-none d-sm-block">
                                    <span class="d-block text-xs text-neutral-400">AI Match</span>
                                    <strong class="text-xs text-neutral-700">Tinggi</strong>
                                </div>
                            </div>

                            @if($isConverted)
                                <button type="button" class="btn btn-success text-sm btn-sm px-16 py-8 d-flex align-items-center gap-2" disabled>
                                    <iconify-icon icon="solar:check-circle-bold"></iconify-icon>
                                    Sudah di Pipeline
                                </button>
                            @else
                                <button type="button" class="btn btn-primary text-sm btn-sm px-16 py-8 d-flex align-items-center gap-2 btn-convert-ai"
                                    data-bs-toggle="modal" data-bs-target="#convertModal"
                                    data-title="{{ $item['title'] }}"
                                    data-company="{{ $item['company'] }}"
                                    data-value="{{ $item['estimated_value'] }}"
                                    data-sector="{{ $item['sector'] }}"
                                    data-region="{{ $item['region'] }}"
                                    data-summary="{{ $item['summary'] }}">
                                    <iconify-icon icon="solar:add-circle-outline"></iconify-icon>
                                    Simpan ke Pipeline
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card shadow-none border">
                <div class="card-body p-40 text-center text-neutral-400">
                    <iconify-icon icon="solar:magnifer-outline" class="text-4xl mb-8 d-block mx-auto"></iconify-icon>
                    Tidak ada indikasi peluang AI yang sesuai dengan filter pencarian.
                </div>
            </div>
        </div>
    @endforelse
</div>


<div class="modal fade" id="convertModal" tabindex="-1" aria-labelledby="convertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 radius-12 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title" id="convertModalLabel">Simpan Peluang AI ke Pipeline</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.sales.ai-opportunities.convert') }}" method="POST">
                @csrf
                <input type="hidden" name="sector" id="modal_sector">
                <input type="hidden" name="region" id="modal_region">
                <div class="modal-body p-20">
                    <div class="mb-16">
                        <label for="modal_title" class="form-label text-sm fw-medium">Judul Opportunity <span class="text-danger-main">*</span></label>
                        <input type="text" name="title" id="modal_title" class="form-control text-sm" required>
                    </div>

                    <div class="row g-3 mb-16">
                        <div class="col-md-6">
                            <label for="modal_company" class="form-label text-sm fw-medium">Customer / Client <span class="text-danger-main">*</span></label>
                            <input type="text" name="company" id="modal_company" class="form-control text-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_value" class="form-label text-sm fw-medium">Estimasi Nilai (IDR) <span class="text-danger-main">*</span></label>
                            <input type="number" name="estimated_value" id="modal_value" class="form-control text-sm" min="0" step="1000000" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-16">
                        <div class="col-md-6">
                            <label for="modal_owner_id" class="form-label text-sm fw-medium">Sales Owner</label>
                            <select name="owner_id" id="modal_owner_id" class="form-select text-sm">
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ auth()->id() == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_target_close" class="form-label text-sm fw-medium">Target Close Date</label>
                            <input type="date" name="target_close_date" id="modal_target_close" class="form-control text-sm" value="{{ now()->addMonth()->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="modal_next_step" class="form-label text-sm fw-medium">Rencana Tindak Lanjut</label>
                        <textarea name="next_step" id="modal_next_step" rows="2" class="form-control text-sm"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-20">
                    <button type="button" class="btn btn-secondary text-sm btn-sm px-16 py-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary text-sm btn-sm px-16 py-8">Tambahkan ke Pipeline</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
