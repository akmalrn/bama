@php
    $galleryImages = old('old_images', data_get($data ?? null, 'images', []));
    $galleryImages = is_array($galleryImages) ? $galleryImages : [];
@endphp

<nav class="product-form-nav" aria-label="Navigasi form produk">
    @foreach ([
        'information' => ['solar:document-text-linear', 'Informasi'],
        'feature' => ['solar:star-linear', 'Features'],
        'ecosystem' => ['solar:gallery-wide-linear', 'Ecosystem'],
        'solution' => ['solar:lightbulb-linear', 'Solutions'],
        'overview' => ['solar:eye-linear', 'Overview'],
        'faq' => ['solar:question-circle-linear', 'FAQ'],
    ] as $sectionKey => [$sectionIcon, $sectionLabel])
        <a href="#product-{{ $sectionKey }}"
            class="product-form-nav__item {{ request('section', 'information') === $sectionKey ? 'is-active' : '' }}"
            data-product-section="{{ $sectionKey }}">
            <iconify-icon icon="{{ $sectionIcon }}"></iconify-icon><span>{{ $sectionLabel }}</span>
        </a>
    @endforeach
</nav>

<div id="product-information">@include('pages.services.partials.form-fields', ['showServiceButtonFields' => false])</div>
@include('pages.products.partials.content-fields')
<div class="card mt-4 product-form-card product-publish-card">
    <div class="card-body">
        <div class="row gy-3">
            <div class="col-md-6">
                {{-- <div class="product-switch-field">
                    <div>
                        <label class="form-label fw-semibold mb-1" for="product-featured">Produk Unggulan</label>
                        <div class="service-form-help">Aktifkan untuk menampilkan produk sebagai produk unggulan.</div>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input type="hidden" name="is_featured" value="0">
                        <input class="form-check-input product-switch" type="checkbox" role="switch"
                            id="product-featured" name="is_featured" value="1" @checked((bool) old('is_featured', $data->is_featured ?? false))>
                    </div>
                </div>
            </div> --}}
                <div class="col-md-6">
                    <div class="product-switch-field">
                        <div>
                            <label class="form-label fw-semibold mb-1" for="product-status">Status</label>
                            <div class="service-form-help">Aktifkan agar produk ditampilkan pada website.</div>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="status" value="0">
                            <input class="form-check-input product-switch" type="checkbox" role="switch"
                                id="product-status" name="status" value="1" @checked((bool) old('status', $data->status ?? true))>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <label class="form-label fw-semibold mb-1">Galeri Gambar</label>
                            <div class="service-form-help">
                                Gambar lama tetap disimpan selama tidak dihapus dari daftar. Jika ingin mengganti
                                gambar,
                                hapus terlebih dahulu gambar lama, kemudian tambahkan gambar baru.
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-gallery">
                            + Tambah Gambar
                        </button>
                    </div>

                    <input type="hidden" name="gallery_state_submitted" value="1">

                    <div id="gallery-wrapper">
                        @foreach ($galleryImages as $image)
                            <div class="service-repeater-row gallery-item">
                                <div class="row g-3 align-items-center">
                                    <div class="col-12 col-md-3">
                                        <div class="service-gallery-preview">
                                            <img src="{{ asset($image) }}" class="image-preview"
                                                alt="Gambar galeri lama">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="hidden" name="old_images[]" value="{{ $image }}"
                                            class="old-image-input">
                                        <input type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp"
                                            class="form-control gallery-input">
                                        <div class="service-form-help mt-2">Kosongkan untuk mempertahankan gambar lama.
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <button type="button" class="btn btn-outline-danger remove-gallery w-100">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($errors->has('images') || $errors->has('images.*'))
                        <div class="text-danger small mt-2">
                            {{ $errors->first('images') ?: $errors->first('images.*') }}
                        </div>
                    @endif
                </div> --}}
            </div>
        </div>
    </div>

    <style>
        .product-form-nav {
            position: sticky;
            top: 86px;
            z-index: 20;
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            padding: 10px;
            overflow-x: auto;
            border: 1px solid var(--admin-border);
            border-radius: 14px;
            background: var(--admin-surface);
            box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
            scrollbar-width: thin;
        }

        .product-form-nav__item {
            display: inline-flex;
            min-height: 42px;
            padding: 9px 14px;
            align-items: center;
            justify-content: center;
            gap: 7px;
            flex: 1 0 auto;
            border: 1px solid transparent;
            border-radius: 10px;
            color: var(--admin-muted);
            background: transparent;
            font-size: .875rem;
            font-weight: 600;
            text-decoration: none;
            transition: .2s ease;
        }

        .product-form-nav__item iconify-icon {
            font-size: 19px;
        }

        .product-form-nav__item:hover {
            border-color: rgba(37, 99, 235, .2);
            color: #2563eb;
            background: rgba(37, 99, 235, .06);
        }

        .product-form-nav__item.is-active {
            color: #fff;
            background: #2563eb;
            box-shadow: 0 6px 16px rgba(37, 99, 235, .22);
        }

        .product-form-card,
        #product-information>.card,
        #product-information>.service-form-card {
            overflow: hidden;
            border: 1px solid var(--admin-border);
            background: var(--admin-surface);
            color: var(--admin-text);
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .045);
        }

        .product-form-card>.card-header,
        #product-information>.card>.card-header,
        #product-information>.service-form-card>.card-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--admin-border);
            background: var(--admin-surface-raised);
            color: var(--admin-text);
        }

        .product-form-card>.card-body,
        #product-information>.card>.card-body,
        #product-information>.service-form-card>.card-body {
            padding: 22px;
        }

        .product-card-kicker {
            display: block;
            margin-bottom: 3px;
            color: #2563eb;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .product-form-actions {
            position: sticky;
            bottom: 12px;
            z-index: 19;
            overflow: hidden;
            border: 1px solid var(--admin-border);
            border-radius: 14px;
            background: var(--admin-surface);
            box-shadow: 0 12px 34px rgba(15, 23, 42, .14);
        }

        .product-form-actions .card-body {
            padding: 14px 18px;
            background: var(--admin-surface);
            color: var(--admin-text);
        }

        .product-form-actions .text-secondary {
            color: var(--admin-muted) !important;
        }

        .product-switch-field {
            display: flex;
            min-height: 82px;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 16px 18px;
            border: 1px solid var(--bs-border-color);
            border-radius: 12px;
            background: var(--bs-tertiary-bg);
        }

        .product-switch {
            width: 3rem !important;
            height: 1.5rem;
            cursor: pointer;
            border: 1px solid #94a3b8;
            background-color: #cbd5e1;
            box-shadow: none;
            transition: background-color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .product-switch:checked {
            border-color: #2563eb;
            background-color: #2563eb;
        }

        .product-switch:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .2);
        }

        html[data-theme="dark"] .product-switch:not(:checked) {
            border-color: #94a3b8;
            background-color: #475569;
        }

        html[data-theme="dark"] .product-switch:checked {
            border-color: #60a5fa;
            background-color: #3b82f6;
        }

        html[data-theme="dark"] .product-switch:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 .2rem rgba(96, 165, 250, .25);
        }

        html[data-theme="dark"] .product-switch-field {
            border-color: var(--admin-border);
            background: var(--admin-surface-raised);
        }

        html[data-theme="dark"] .product-form-nav {
            border-color: var(--admin-border);
            background: var(--admin-surface);
            box-shadow: 0 10px 28px rgba(2, 6, 23, .28);
        }

        html[data-theme="dark"] .product-form-nav__item {
            color: var(--admin-muted);
        }

        html[data-theme="dark"] .product-form-nav__item:hover {
            border-color: rgba(96, 165, 250, .34);
            color: #bfdbfe;
            background: rgba(96, 165, 250, .1);
        }

        html[data-theme="dark"] .product-form-nav__item.is-active {
            color: #fff;
            background: #2563eb;
        }

        html[data-theme="dark"] .product-form-card,
        html[data-theme="dark"] #product-information>.card,
        html[data-theme="dark"] #product-information>.service-form-card,
        html[data-theme="dark"] .product-form-actions {
            border-color: var(--admin-border);
            background: var(--admin-surface);
            color: var(--admin-text);
        }

        html[data-theme="dark"] .product-form-card>.card-header,
        html[data-theme="dark"] #product-information>.card>.card-header,
        html[data-theme="dark"] #product-information>.service-form-card>.card-header {
            border-bottom-color: var(--admin-border);
            background: var(--admin-surface-raised);
            color: var(--admin-text);
        }

        html[data-theme="dark"] .product-form-actions .card-body {
            background: var(--admin-surface);
            color: var(--admin-text);
        }

        @media (max-width: 991.98px) {
            .product-form-nav {
                top: 76px;
            }

            .product-form-nav__item {
                padding-inline: 12px;
            }
        }

        @media (max-width: 575.98px) {
            .product-form-nav {
                position: relative;
                top: auto;
                margin-bottom: 18px;
            }

            .product-form-nav__item span {
                font-size: .78rem;
            }

            .product-form-card>.card-body,
            #product-information>.card>.card-body,
            #product-information>.service-form-card>.card-body {
                padding: 16px;
            }

            .product-form-actions {
                position: relative;
                bottom: auto;
            }

            .product-form-actions .card-body>div {
                width: 100%;
            }

            .product-form-actions .btn {
                flex: 1;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-product-section]').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    const section = link.dataset.productSection;
                    const target = document.getElementById('product-' + section);
                    if (!target) return;

                    if (['feature', 'ecosystem', 'solution', 'overview', 'faq'].includes(section)) {
                        document.querySelector(`[data-bs-target="#product-${section}"]`)?.click();
                    }

                    event.preventDefault();
                    document.querySelectorAll('[data-product-section]').forEach(item => item
                        .classList.remove('is-active'));
                    link.classList.add('is-active');
                    window.setTimeout(() => target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    }), 80);
                });
            });

            document.addEventListener('shown.bs.tab', function(event) {
                const targetSelector = event.target.getAttribute('data-bs-target');
                if (!targetSelector?.startsWith('#product-')) return;
                const section = targetSelector.replace('#product-', '');
                document.querySelectorAll('[data-product-section]').forEach(function(item) {
                    item.classList.toggle('is-active', item.dataset.productSection === section);
                });
            });

            document.addEventListener('change', function(event) {
                const input = event.target.closest('[data-product-image-input]');
                if (!input) return;
                const preview = input.closest('[data-product-image-field]')?.querySelector(
                    '[data-product-image-preview]');
                const image = preview?.querySelector('img');
                const empty = preview?.querySelector('.product-image-live-preview__empty');
                const file = input.files?.[0];
                if (!preview || !image || !file) return;
                image.src = URL.createObjectURL(file);
                image.classList.remove('d-none');
                empty?.classList.add('d-none');
            });

            const productForms = document.querySelectorAll('.service-admin-page form');
            productForms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    if (form.checkValidity && !form.checkValidity()) {
                        return;
                    }
                    const submitButtons = form.querySelectorAll('button[type="submit"]');
                    submitButtons.forEach(function(btn) {
                        setTimeout(function() {
                            btn.disabled = true;
                            btn.classList.add('disabled');
                        }, 0);
                    });
                });
            });
        });
    </script>
