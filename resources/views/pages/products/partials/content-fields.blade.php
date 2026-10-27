@php
    $featureSection = $data->featureSection ?? null;
    $ecosystem = $data->ecosystemSection ?? null;
    $solutionSection = $data->solutionSection ?? null;
    $productOverview = $data->productOverview ?? null;
    $featureItems = old('features', $featureSection?->features?->toArray() ?? []);
    $solutionItems = old('solutions', $solutionSection?->solutions?->toArray() ?? []);
    $faqItems = old('faqs', isset($data) ? $data->faqs->toArray() : []);
    $activeContentSection = in_array(request('section'), ['feature', 'ecosystem', 'solution', 'overview', 'faq'], true)
        ? request('section')
        : 'feature';
@endphp

<div class="card mt-4 product-form-card product-content-card">
    <div class="card-header product-content-card__header">
        <div><span class="product-card-kicker">Konten dinamis</span>
            <h6 class="mb-0">Konten Halaman Produk</h6>
        </div>
        <ul class="nav nav-pills product-content-tabs" role="tablist">
            @foreach (['feature' => 'Features', 'ecosystem' => 'Ecosystem', 'solution' => 'Solutions', 'overview' => 'Overview', 'faq' => 'FAQ'] as $key => $label)
                <li class="nav-item"><button type="button"
                        class="nav-link {{ $activeContentSection === $key ? 'active' : '' }}" data-bs-toggle="tab"
                        data-bs-target="#product-{{ $key }}">{{ $label }}</button></li>
            @endforeach
        </ul>
    </div>
    <div class="card-body tab-content">
        <div class="tab-pane fade {{ $activeContentSection === 'feature' ? 'show active' : '' }}" id="product-feature">
            @include('pages.products.partials.section-fields', [
                'prefix' => 'feature_section',
                'section' => $featureSection,
                'minimal' => true,
            ])
            <hr>
            <div class="product-repeater-heading">
                <div>
                    <h6>Daftar Feature</h6>
                    <p>Tambahkan konten feature dan atur urutan tampilnya.</p>
                </div><button type="button" class="btn btn-sm btn-outline-primary"
                    data-add-row="features"><iconify-icon icon="ic:round-add"></iconify-icon> Tambah Feature</button>
            </div>
            <div data-rows="features">
                @foreach ($featureItems as $i => $item)
                    @include('pages.products.partials.item-row', [
                        'type' => 'features',
                        'i' => $i,
                        'item' => $item,
                        'tabName' => true,
                    ])
                @endforeach
            </div>
        </div>
        <div class="tab-pane fade {{ $activeContentSection === 'ecosystem' ? 'show active' : '' }}"
            id="product-ecosystem">
            @include('pages.products.partials.section-fields', [
                'prefix' => 'ecosystem',
                'section' => $ecosystem,
                'withButton' => true,
                'withImage' => true,
            ])
            <div class="alert alert-info mt-3 mb-0">Ecosystem menggunakan satu gambar penuh, tanpa item atau icon
                terpisah.</div>
        </div>
        <div class="tab-pane fade {{ $activeContentSection === 'solution' ? 'show active' : '' }}"
            id="product-solution">
            @include('pages.products.partials.section-fields', [
                'prefix' => 'solution_section',
                'section' => $solutionSection,
            ])
            <hr>
            <div class="product-repeater-heading">
                <div>
                    <h6>Daftar Solution</h6>
                    <p>Tambahkan solusi yang berkaitan dengan produk ini.</p>
                </div><button type="button" class="btn btn-sm btn-outline-primary"
                    data-add-row="solutions"><iconify-icon icon="ic:round-add"></iconify-icon> Tambah Solution</button>
            </div>
            <div data-rows="solutions">
                @foreach ($solutionItems as $i => $item)
                    @include('pages.products.partials.item-row', [
                        'type' => 'solutions',
                        'i' => $i,
                        'item' => $item,
                    ])
                @endforeach
            </div>
        </div>
        <div class="tab-pane fade {{ $activeContentSection === 'overview' ? 'show active' : '' }}"
            id="product-overview">
            @include('pages.products.partials.section-fields', [
                'prefix' => 'product_overview',
                'section' => $productOverview,
                'overviewMode' => true,
                'withEditor' => true,
            ])
        </div>
        <div class="tab-pane fade {{ $activeContentSection === 'faq' ? 'show active' : '' }}" id="product-faq">
            <div class="product-repeater-heading">
                <div>
                    <h6>Daftar FAQ</h6>
                    <p>Pertanyaan yang sering diajukan terkait produk.</p>
                </div><button type="button" class="btn btn-sm btn-outline-primary" data-add-row="faqs"><iconify-icon
                        icon="ic:round-add"></iconify-icon> Tambah FAQ</button>
            </div>
            <div data-rows="faqs">
                @foreach ($faqItems as $i => $item)
                    @include('pages.products.partials.item-row', [
                        'type' => 'faqs',
                        'i' => $i,
                        'item' => $item,
                    ])
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .product-content-card__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .product-content-tabs {
        gap: 6px;
    }

    .product-content-tabs .nav-link {
        padding: 8px 13px;
        border: 1px solid transparent;
        border-radius: 8px;
        color: var(--admin-muted);
        background: transparent;
        font-size: .82rem;
        font-weight: 600;
    }

    .product-content-tabs .nav-link.active {
        color: #fff;
        background: #2563eb;
    }

    .product-content-card .tab-pane {
        padding-top: 4px;
    }

    .product-repeater-heading {
        display: flex;
        margin-bottom: 16px;
        margin-top: 16px;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .product-repeater-heading h6 {
        margin: 0 0 3px;
    }

    .product-repeater-heading p {
        margin: 0;
        color: var(--admin-muted);
        font-size: .8rem;
    }

    .product-repeater-heading .btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    [data-rows] .service-repeater-row {
        position: relative;
        margin-bottom: 20px !important;
        padding: 20px;
        border-left: 3px solid #2563eb;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .04);
    }

    [data-rows] .service-repeater-row+.service-repeater-row {
        margin-top: 20px;
    }

    html[data-theme="dark"] .product-content-tabs .nav-link {
        color: var(--admin-muted);
    }

    html[data-theme="dark"] .product-content-tabs .nav-link:hover {
        border-color: rgba(96, 165, 250, .3);
        color: #bfdbfe;
        background: rgba(96, 165, 250, .08);
    }

    html[data-theme="dark"] .product-content-tabs .nav-link.active {
        color: #fff;
        background: #2563eb;
    }

    html[data-theme="dark"] .product-repeater-heading :is(h6, p) {
        color: var(--admin-text);
    }

    html[data-theme="dark"] .product-repeater-heading p {
        color: var(--admin-muted);
    }

    html[data-theme="dark"] [data-rows] .service-repeater-row {
        border-color: var(--admin-border);
        border-left-color: #60a5fa;
        background: var(--admin-surface-raised);
        color: var(--admin-text);
        box-shadow: 0 6px 18px rgba(2, 6, 23, .18);
    }

    html[data-theme="dark"] .product-content-card hr {
        border-color: var(--admin-border);
        opacity: 1;
    }

    html[data-theme="dark"] .product-content-card .alert-info {
        border-color: rgba(96, 165, 250, .3);
        background: rgba(59, 130, 246, .12);
        color: #bfdbfe;
    }

    @media (max-width: 767.98px) {
        .product-content-tabs {
            width: 100%;
            overflow-x: auto;
            flex-wrap: nowrap;
        }

        .product-repeater-heading {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-add-row]').forEach(button => button.addEventListener('click', () => {
            const type = button.dataset.addRow,
                box = document.querySelector(`[data-rows="${type}"]`),
                i = Date.now();
            const faq = type === 'faqs';
            box.insertAdjacentHTML('beforeend',
                `<div class="service-repeater-row mb-3"><input type="hidden" name="${type}[${i}][id]"><div class="row g-3">
        ${type === 'features' ? `<div class="col-md-4"><label class="form-label">Nama Tab</label><input class="form-control" name="${type}[${i}][tab_name]"></div>` : ''}
        <div class="col-md-${type === 'features' ? '8' : '6'}"><label class="form-label">${faq ? 'Pertanyaan' : 'Judul'} *</label><input class="form-control" name="${type}[${i}][${faq ? 'question' : 'title'}]"></div>
        <div class="col-12"><label class="form-label">${faq ? 'Jawaban' : 'Deskripsi'}</label><textarea class="form-control" rows="3" ${faq ? `id="faq-answer-${i}"` : ''} name="${type}[${i}][${faq ? 'answer' : 'description'}]"></textarea></div>
        ${faq ? '' : `<div class="col-md-4" data-product-image-field><label class="form-label">Gambar</label><input type="file" class="form-control" name="${type}[${i}][image]" accept=".jpg,.jpeg,.png,.webp" data-product-image-input><div class="product-image-live-preview" data-product-image-preview><img class="d-none" alt="Preview gambar"><div class="product-image-live-preview__empty"><iconify-icon icon="solar:gallery-add-linear"></iconify-icon><span>Preview gambar</span></div></div></div><div class="col-md-4"><label class="form-label">Nama Tombol</label><input class="form-control" name="${type}[${i}][button_name]"></div><div class="col-md-4"><label class="form-label">URL Tombol</label><input class="form-control" name="${type}[${i}][button_url]"></div>`}
        <div class="col-md-3"><label class="form-label">Urutan</label><input type="number" min="0" class="form-control" name="${type}[${i}][order]" value="0"></div><div class="col-md-4 d-flex align-items-end"><div class="product-status-control w-100"><span class="product-status-control__label"><strong>Status</strong><small>Tampilkan item</small></span><div class="form-check form-switch m-0"><input type="hidden" name="${type}[${i}][status]" value="0"><input class="form-check-input" type="checkbox" role="switch" name="${type}[${i}][status]" value="1" checked></div></div></div><div class="col-md-3 ms-auto d-flex align-items-end"><button type="button" class="btn btn-outline-danger w-100" data-remove-row>Hapus</button></div></div></div>`
                );
            if (faq) {
                const editorId = `faq-answer-${i}`;
                window.adminCkeditorFields = window.adminCkeditorFields || [];
                window.adminCkeditorFields.push(editorId);
                window.setTimeout(() => window.initializeAdminCkeditor?.(editorId), 0);
            }
        }));
        document.addEventListener('click', e => {
            const b = e.target.closest('[data-remove-row]');
            if (b && confirm('Hapus item ini?')) b.closest('.service-repeater-row').remove();
        });
    });
</script>
