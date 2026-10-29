@php
    $isEdit = isset($data);
    $databaseImages = $isEdit ? $data->images ?? [] : [];
    if (is_string($databaseImages)) {
        $databaseImages = json_decode($databaseImages, true);
    }
    $databaseImages = is_array($databaseImages) ? $databaseImages : [];

    $galleryImages = old('gallery_state_submitted') ? old('old_images', []) : $databaseImages;
    $galleryImages = is_array($galleryImages) ? array_values(array_filter($galleryImages)) : [];

    $isService = $showServiceButtonFields ?? true;
    $projectsList = $isService
        ? old('projects_data', $isEdit && is_array(data_get($data, 'projects_data')) ? $data->projects_data : [])
        : [];
    $workflowList = $isService
        ? old('workflow_data', $isEdit && is_array(data_get($data, 'workflow_data')) ? $data->workflow_data : [])
        : [];
    $portfoliosList = $isService
        ? old('portfolios_data', $isEdit && is_array(data_get($data, 'portfolios_data')) ? $data->portfolios_data : [])
        : [];
@endphp

<style>
    .service-form-card+.service-form-card {
        margin-top: 24px;
    }

    .service-repeater-item-box,
    .service-section-heading-box {
        position: relative;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--bs-border-color, #e2e8f0);
        background: var(--bs-tertiary-bg, #f8fafc);
        margin-bottom: 20px;
    }

    html[data-theme="dark"] .service-repeater-item-box,
    html[data-theme="dark"] .service-section-heading-box {
        background: var(--admin-surface-raised, #263449) !important;
        border-color: rgba(148, 163, 184, 0.25) !important;
    }

    .service-repeater-row {
        padding: 16px;
        border: 1px solid var(--bs-border-color);
        border-radius: 12px;
        background: var(--bs-tertiary-bg);
    }

    .service-repeater-row+.service-repeater-row {
        margin-top: 12px;
    }

    .service-gallery-preview {
        display: grid;
        width: 100%;
        min-height: 135px;
        overflow: hidden;
        place-items: center;
        color: var(--bs-secondary-color);
        border: 1px dashed var(--bs-border-color);
        border-radius: 12px;
        background: var(--bs-tertiary-bg);
    }

    .service-gallery-preview img {
        width: 100%;
        height: 135px;
        object-fit: cover;
    }

    .service-form-help {
        color: var(--bs-secondary-color);
        font-size: 0.8125rem;
    }

    .service-main-image-preview {
        display: grid;
        width: 100%;
        min-height: 170px;
        margin-top: 10px;
        overflow: hidden;
        place-items: center;
        color: var(--bs-secondary-color);
        border: 1px dashed var(--bs-border-color);
        border-radius: 12px;
        background: var(--bs-tertiary-bg);
    }

    .service-main-image-preview img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    @media (max-width: 575.98px) {
        .service-repeater-row {
            padding: 12px;
        }

        .service-repeater-remove {
            width: 100%;
        }
    }
</style>

<div class="card service-form-card">
    @if ($showServiceButtonFields ?? true)
    
        <div class="card service-form-card">
            <div class="card-header bg-light d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0 d-flex align-items-center gap-2">
                    <iconify-icon icon="solar:star-bold-duotone" class="text-primary fs-5"></iconify-icon>
                    Hero Section Layanan (Tampilan Atas Detail Layanan)
                </h6>                
            </div>
            <div class="card-body">
                <p class="service-form-help mb-3 text-muted">
                    Sesuaikan teks dan gambar hero khusus untuk halaman detail layanan ini. Jika dikosongkan, sistem
                    akan otomatis menggunakan judul dan ringkasan layanan di atas.
                </p>

                <div class="row gy-3">
                    <div class="col-md-6">
                        <label for="service-hero-badge" class="form-label">Badge / Label Hero</label>
                        <input type="text" name="hero_badge" id="service-hero-badge"
                            value="{{ old('hero_badge', $isEdit ? $data->hero_badge : '') }}"
                            class="form-control @error('hero_badge') is-invalid @enderror"
                            placeholder="Contoh: Layanan & Solusi Terintegrasi">
                        @error('hero_badge')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Label kecil di atas judul hero (Default: Kategori / 'Layanan
                            Enterprise').</small>
                    </div>

                    <div class="col-md-6">
                        <label for="service-hero-span" class="form-label">Teks Sorotan (Span Ungu)</label>
                        <input type="text" name="hero_span" id="service-hero-span"
                            value="{{ old('hero_span', $isEdit ? $data->hero_span : '') }}"
                            class="form-control @error('hero_span') is-invalid @enderror" placeholder="Contoh: Solusi">
                        @error('hero_span')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kata yang diberi warna ungu pada judul utama.</small>
                    </div>

                    <div class="col-12">
                        <label for="service-hero-title" class="form-label">Judul Utama Hero (Opsional)</label>
                        <input type="text" name="hero_title" id="service-hero-title"
                            value="{{ old('hero_title', $isEdit ? $data->hero_title : '') }}"
                            class="form-control @error('hero_title') is-invalid @enderror"
                            placeholder="Contoh: Sistem ERP Khusus Industri Manufaktur">
                        @error('hero_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kosongkan jika ingin otomatis menggunakan Judul Layanan di
                            atas.</small>
                    </div>

                    <div class="col-12">
                        <label for="service-hero-description" class="form-label">Deskripsi Hero (Opsional)</label>
                        <textarea name="hero_description" id="service-hero-description" rows="3"
                            class="form-control @error('hero_description') is-invalid @enderror"
                            placeholder="Tuliskan deskripsi ringkas pengantar hero...">{{ old('hero_description', $isEdit ? $data->hero_description : '') }}</textarea>
                        @error('hero_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kosongkan jika ingin otomatis menggunakan Ringkasan Layanan di
                            atas.</small>
                    </div>

                    <div class="col-12">
                        <label for="service-hero-image" class="form-label">Gambar Khusus Hero (Sisi Kanan)</label>
                        <input type="file" name="hero_image" id="service-hero-image" accept=".jpg,.jpeg,.png,.webp"
                            class="form-control @error('hero_image') is-invalid @enderror"
                            onchange="if (this.files && this.files[0]) { var r = new FileReader(); r.onload = function(e){ document.getElementById('preview-service-custom-hero-img').src = e.target.result; document.getElementById('preview-service-custom-hero-wrap').classList.remove('d-none'); }; r.readAsDataURL(this.files[0]); }">
                        <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG, WEBP. Maks 5MB. Otomatis
                            dikonversi ke WebP. (Kosongkan jika ingin menggunakan Gambar Utama).</small>
                        @error('hero_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="mt-3 {{ $isEdit && filled($data->hero_image) ? '' : 'd-none' }}"
                            id="preview-service-custom-hero-wrap">
                            <label class="form-label small text-muted">Preview Gambar Hero Khusus:</label>
                            <div class="d-flex align-items-center gap-3">
                                <img id="preview-service-custom-hero-img"
                                    src="{{ $isEdit && filled($data->hero_image) ? asset($data->hero_image) : '' }}"
                                    alt="Preview Hero Khusus" class="rounded border shadow-sm"
                                    style="max-width: 240px; max-height: 150px; object-fit: cover;">
                                @if ($isEdit && filled($data->hero_image))
                                    <div class="form-check">
                                        <input type="checkbox" name="remove_hero_image" value="1"
                                            id="remove_hero_image_cb" class="form-check-input">
                                        <label for="remove_hero_image_cb"
                                            class="form-check-label text-danger small">Hapus gambar hero khusus</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card-header">
        <h6 class="card-title mb-0">Informasi {{ $showServiceButtonFields ?? true ? 'Layanan' : 'Produk' }}</h6>
    </div>

    <div class="card-body">
        <div class="row gy-3">
            @if ($showServiceButtonFields ?? true)
                <div class="col-12 col-md-6 d-none">
                    <label for="service-category" class="form-label">
                        Kategori
                    </label>
                    <select name="id_category" id="service-category"
                        class="form-select @error('id_category') is-invalid @enderror">
                        <option value="">Tanpa Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('id_category', $isEdit ? $data->id_category : '') === (string) $category->id)>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <div class="col-12 col-md-6">
                    <label for="product-category" class="form-label">
                        Kategori Produk <span class="text-danger">*</span>
                    </label>
                    <select name="id_category" id="product-category"
                        class="form-select @error('id_category') is-invalid @enderror" required>
                        <option value="" disabled @selected(!old('id_category', $isEdit ? $data->id_category : ''))>-- Pilih Kategori Produk --
                        </option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('id_category', $isEdit ? $data->id_category : '') === (string) $category->id)>
                                {{ $category->name && $category->name !== $category->title ? $category->name . ' — ' . $category->title : $category->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($categories->isEmpty())
                        <div class="service-form-help text-warning mt-2">
                            Belum ada kategori produk. Silakan buat kategori produk terlebih dahulu.
                        </div>
                    @else
                        <div class="service-form-help mt-2">
                            Pilih kategori untuk produk ini. Kategori dapat memiliki banyak produk.
                        </div>
                    @endif
                </div>
            @endif

            @if (isset($swapProducts) && $swapProducts->isNotEmpty())
                <div class="col-12">
                    <div class="product-swap-box">
                        <div><strong>Tukar Kategori</strong>
                            <p class="mb-0">Pilih produk lain untuk menukar kategori kedua produk secara otomatis.
                            </p>
                        </div>
                        <select name="swap_product_id" class="form-select">
                            <option value="">Tidak menukar kategori</option>
                            @foreach ($swapProducts as $swapProduct)
                                <option value="{{ $swapProduct->id }}" @selected((string) old('swap_product_id') === (string) $swapProduct->id)>
                                    {{ $swapProduct->title }} — {{ $swapProduct->category?->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($showServiceButtonFields ?? true)
                <div class="col-12 col-md-8">
                    <label for="service-title" class="form-label">
                        Judul <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="service-title"
                        value="{{ old('title', $isEdit ? $data->title : '') }}"
                        class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label for="service-order" class="form-label">
                        Urutan Layanan
                    </label>
                    <input type="number" name="order" id="service-order" min="0"
                        value="{{ old('order', $isEdit ? $data->order : 0) }}"
                        class="form-control @error('order') is-invalid @enderror"
                        placeholder="Contoh: 1">
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Urutan posisi layanan (angka lebih kecil tampil lebih awal).</small>
                </div>
            @else
                <div class="col-12 col-md-6">
                    <label for="service-title" class="form-label">
                        Judul <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" id="service-title"
                        value="{{ old('title', $isEdit ? $data->title : '') }}"
                        class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            @if (($showServiceButtonFields ?? true) === false)
                <div class="col-12 col-md-6">
                    <label for="product-icon" class="form-label">Icon Produk</label>
                    <textarea name="icon" id="icon_input" rows="3"
                        placeholder='<iconify-icon icon="solar:box-bold-duotone" style="color:#513bbd;"></iconify-icon>'
                        class="form-control @error('icon')
is-invalid
@enderror">{{ old('icon', $isEdit ? $data->icon : '') }}</textarea>
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="service-form-help mt-2">
                        Gunakan markup Iconify atau SVG, misalnya
                        <code>&lt;iconify-icon icon="solar:box-bold-duotone"&gt;&lt;/iconify-icon&gt;</code>.
                    </div>
                    <div class="mt-2">
                        <label class="form-label d-block">Preview Icon</label>
                        <div id="preview_icon" class="border rounded p-2"
                            style="font-size: 24px; min-height: 48px; display: flex; align-items: center;">
                            {!! old('icon', $isEdit ? $data->icon : '') !!}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label for="product-image" class="form-label">Gambar Utama</label>
                    <input type="file" name="image" id="product-image" accept=".jpg,.jpeg,.png,.webp"
                        class="form-control @error('image') is-invalid @enderror"
                        onchange="previewImage(event, 'product-image-preview'); document.getElementById('product-image-placeholder')?.classList.add('d-none');">
                    <div class="service-form-help mt-2">Format: JPG, JPEG, PNG, atau WEBP.</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="service-main-image-preview">
                        @if ($isEdit && filled($data->image))
                            <img src="{{ asset($data->image) }}" id="product-image-preview"
                                alt="Preview {{ $data->title }}">
                        @else
                            <span id="product-image-placeholder">Belum ada gambar</span>
                            <img id="product-image-preview" class="d-none" alt="Preview gambar utama">
                        @endif
                    </div>
                </div>

                <div class="col-12">
                    <label for="service-overview" class="form-label">Ringkasan</label>
                    <textarea name="overview" id="service-overview" rows="3"
                        class="form-control @error('overview') is-invalid @enderror" placeholder="Ringkasan singkat produk...">{{ old('overview', $isEdit ? $data->overview : '') }}</textarea>
                    @error('overview')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <div class="col-12 col-md-6">
                    <label for="service-icon" class="form-label">Icon</label>
                    <textarea name="icon" id="icon_input" rows="3"
                        placeholder='<iconify-icon icon="solar:car-bold-duotone" style="color:#f31b0c;"></iconify-icon>'
                        class="form-control @error('icon')
is-invalid
@enderror">{{ old('icon', $isEdit ? $data->icon : '') }}</textarea>
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="service-form-help mt-2">
                        Gunakan markup Iconify, misalnya
                        <code>&lt;iconify-icon icon="solar:car-bold-duotone"&gt;&lt;/iconify-icon&gt;</code>.
                    </div>
                    <div class="mt-2">
                        <label class="form-label d-block">Preview</label>
                        <div id="preview_icon" class="border rounded p-2">
                            {!! old('icon', $isEdit ? $data->icon : '') !!}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label for="service-image" class="form-label">Gambar Utama / Hero Layanan</label>
                    <input type="file" name="image" id="service-image" accept=".jpg,.jpeg,.png,.webp"
                        class="form-control @error('image') is-invalid @enderror"
                        onchange="previewImage(event, 'service-image-preview'); document.getElementById('service-image-placeholder')?.classList.add('d-none'); document.getElementById('service-image-preview')?.classList.remove('d-none');">
                    <div class="service-form-help mt-2">Format: JPG, JPEG, PNG, WEBP (Ditampilkan di sisi kanan Hero
                        halaman layanan).</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="service-main-image-preview mt-2">
                        @if ($isEdit && filled($data->image))
                            <img src="{{ asset($data->image) }}" id="service-image-preview"
                                alt="Preview {{ $data->title }}">
                        @else
                            <span id="service-image-placeholder">Belum ada gambar</span>
                            <img id="service-image-preview" class="d-none" alt="Preview gambar utama">
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <label for="service-overview" class="form-label">Ringkasan</label>
                    <textarea name="overview" id="service-overview" rows="3"
                        class="form-control @error('overview') is-invalid @enderror">{{ old('overview', $isEdit ? $data->overview : '') }}</textarea>
                    @error('overview')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            {{-- <div class="col-12">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" id="description" rows="6"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $isEdit ? $data->description : '') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @include('components.ckeditor', ['name' => 'description'])
            </div> --}}
            {{-- <div class="col-12 col-md-6">
                <label for="service-button-name" class="form-label">Teks Tombol (CTA / Konsultasi)</label>
                <input type="text" name="button_name" id="service-button-name"
                    value="{{ old('button_name', $isEdit ? $data->button_name : '') }}"
                    class="form-control @error('button_name') is-invalid @enderror"
                    placeholder="Contoh: Konsultasi Sekarang">
                @error('button_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Teks tombol yang tampil di slider beranda (misal: "Konsultasi Sekarang", "Konsultasikan Kebutuhan ERP").</small>
            </div>
            <div class="col-12 col-md-6">
                <label for="service-button-url" class="form-label">URL Tombol (Opsional)</label>
                <input type="text" name="button_url" id="service-button-url"
                    value="{{ old('button_url', $isEdit ? $data->button_url : '') }}"
                    class="form-control @error('button_url') is-invalid @enderror"
                    placeholder="Contoh: /kontak atau https://wa.me/...">
                @error('button_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Biarkan kosong jika ingin otomatis mengarah ke {{ ($showServiceButtonFields ?? true) ? 'halaman detail layanan' : 'halaman kontak default' }}.</small>
            </div> --}}
        </div>
    </div>
</div>

@if ($showServiceButtonFields ?? true)

    <div class="card service-form-card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h6 class="card-title mb-0">Jenis Project / Solusi Layanan</h6>
                <div class="service-form-help mt-1">
                    Showcase jenis website/projek yang dikerjakan seperti di referensi "OUR WEB PROJECT" (mockup,
                    deskripsi, checklist keunggulan & tombol CTA).
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2 bg-body-tertiary px-3 py-1 rounded border">
                    <span class="text-muted small fw-semibold">Status Section:</span>
                    <div class="form-check form-switch m-0">
                        <input type="hidden" name="show_projects" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" id="switch-show-projects" name="show_projects" value="1"
                               @checked((bool) old('show_projects', $isEdit ? ($data->show_projects ?? true) : true))>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm px-3" id="btn-add-project">
                    + Tambah Project
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="service-section-heading-box mb-4">
                <div class="row gy-3">
                    <div class="col-12 col-md-4">
                        <label for="service-projects-badge" class="form-label fw-semibold">
                            Label / Badge Section
                        </label>
                        <input type="text" name="projects_badge" id="service-projects-badge"
                            value="{{ old('projects_badge', $isEdit ? $data->projects_badge : 'OUR PROJECTS') }}"
                            class="form-control" placeholder="Contoh: OUR PROJECTS">
                        <div class="service-form-help mt-1">Badge teks di atas judul section.</div>
                    </div>
                    <div class="col-12 col-md-8">
                        <label for="service-projects-title" class="form-label fw-semibold">
                            Judul Utama Section
                        </label>
                        <input type="text" name="projects_title" id="service-projects-title"
                            value="{{ old('projects_title', $isEdit ? $data->projects_title : 'Jenis Solusi Yang Kami Kerjakan') }}"
                            class="form-control" placeholder="Contoh: Jenis Solusi Yang Kami Kerjakan">
                        <div class="service-form-help mt-1">Judul utama bagian jenis solusi / project.</div>
                    </div>
                </div>
            </div>

            <div id="projects-container">
                @forelse($projectsList as $pIdx => $p)
                    <div class="service-repeater-item-box project-item-row" data-index="{{ $pIdx }}">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <span class="fw-bold text-primary">
                                <iconify-icon icon="solar:widget-bold-duotone" class="me-1"></iconify-icon>
                                Project #<span class="project-number">{{ $pIdx + 1 }}</span>
                            </span>
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-check-label small text-muted mb-0" for="proj-status-{{ $pIdx }}">Tampilkan</label>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="projects_data[{{ $pIdx }}][status]" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="proj-status-{{ $pIdx }}"
                                               name="projects_data[{{ $pIdx }}][status]" value="1"
                                               @checked((bool) ($p['status'] ?? true))>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row">
                                    <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon> Hapus Project
                                </button>
                            </div>
                        </div>

                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Judul Project <span class="text-danger">*</span></label>
                                <input type="text" name="projects_data[{{ $pIdx }}][title]"
                                    value="{{ $p['title'] ?? '' }}" class="form-control"
                                    placeholder="Contoh: Website Company Profile" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Gambar Mockup / Screenshot Project</label>
                                <input type="file" name="projects_data[{{ $pIdx }}][image]"
                                    accept=".jpg,.jpeg,.png,.webp" class="form-control project-img-input">
                                @if (!empty($p['image']))
                                    <input type="hidden" name="projects_data[{{ $pIdx }}][old_image]"
                                        value="{{ $p['image'] }}">
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <img src="{{ asset($p['image']) }}"
                                            class="rounded border object-fit-cover project-img-preview" width="90"
                                            height="60" alt="Preview">
                                        <small class="text-muted">Gambar saat ini. Pilih file baru untuk
                                            mengganti.</small>
                                    </div>
                                @else
                                    <div class="mt-2 d-none preview-box-new">
                                        <img src=""
                                            class="rounded border object-fit-cover project-img-preview" width="90"
                                            height="60" alt="Preview">
                                    </div>
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi Project</label>
                                <textarea name="projects_data[{{ $pIdx }}][description]" rows="3" class="form-control"
                                    placeholder="Jelaskan jenis project, tujuan, dan solusinya...">{{ $p['description'] ?? '' }}</textarea>
                            </div>

                            
                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded service-feature-box dark-bg-card">
                                    <label class="form-label fw-bold text-success mb-1">
                                        <iconify-icon icon="solar:check-circle-bold"></iconify-icon> Keunggulan 1
                                    </label>
                                    <input type="text" name="projects_data[{{ $pIdx }}][point_1_title]"
                                        value="{{ $p['point_1_title'] ?? '' }}" class="form-control mb-2"
                                        placeholder="Judul (Contoh: Always Online)">
                                    <textarea name="projects_data[{{ $pIdx }}][point_1_desc]" rows="2" class="form-control"
                                        placeholder="Keterangan singkat keunggulan 1...">{{ $p['point_1_desc'] ?? '' }}</textarea>
                                </div>
                            </div>

                            
                            <div class="col-12 col-md-6">
                                <div class="p-3 border rounded service-feature-box dark-bg-card">
                                    <label class="form-label fw-bold text-success mb-1">
                                        <iconify-icon icon="solar:check-circle-bold"></iconify-icon> Keunggulan 2
                                    </label>
                                    <input type="text" name="projects_data[{{ $pIdx }}][point_2_title]"
                                        value="{{ $p['point_2_title'] ?? '' }}" class="form-control mb-2"
                                        placeholder="Judul (Contoh: Grow Up Your Business)">
                                    <textarea name="projects_data[{{ $pIdx }}][point_2_desc]" rows="2" class="form-control"
                                        placeholder="Keterangan singkat keunggulan 2...">{{ $p['point_2_desc'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Teks Tombol CTA</label>
                                <input type="text" name="projects_data[{{ $pIdx }}][button_text]"
                                    value="{{ $p['button_text'] ?? 'Buat Website Anda Sekarang' }}"
                                    class="form-control" placeholder="Contoh: Buat Website Anda Sekarang">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">URL Tombol CTA (WhatsApp / Link)</label>
                                <input type="text" name="projects_data[{{ $pIdx }}][button_url]"
                                    value="{{ $p['button_url'] ?? '' }}" class="form-control"
                                    placeholder="Contoh: https://wa.me/... atau /kontak">
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-center py-4 empty-notice" id="empty-projects-notice">
                        <iconify-icon icon="solar:folder-open-linear" style="font-size: 36px;"></iconify-icon>
                        <div class="mt-2">Belum ada data project. Klik tombol <strong>+ Tambah Project</strong> di
                            atas untuk menambahkan.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>


    <div class="card service-form-card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h6 class="card-title mb-0">Alur Kerja / Cara Kerja Kami</h6>
                <div class="service-form-help mt-1">
                    Langkah-langkah proses kerja bertahap (seperti: 1. Konsultasi, 2. Perencanaan, 3. Pengembangan, 4.
                    Testing & Support).
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2 bg-body-tertiary px-3 py-1 rounded border">
                    <span class="text-muted small fw-semibold">Status Section:</span>
                    <div class="form-check form-switch m-0">
                        <input type="hidden" name="show_workflow" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" id="switch-show-workflow" name="show_workflow" value="1"
                               @checked((bool) old('show_workflow', $isEdit ? ($data->show_workflow ?? true) : true))>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm px-3" id="btn-add-workflow">
                    + Tambah Langkah
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="service-section-heading-box mb-4">
                <div class="row gy-3">
                    <div class="col-12 col-md-5">
                        <label for="service-workflow-title" class="form-label fw-semibold">
                            Judul Section Alur Kerja
                        </label>
                        <input type="text" name="workflow_title" id="service-workflow-title"
                            value="{{ old('workflow_title', $isEdit ? $data->workflow_title : 'Cara kerja kami') }}"
                            class="form-control" placeholder="Contoh: Cara kerja kami">
                        <div class="service-form-help mt-1">Judul utama timeline langkah kerja.</div>
                    </div>
                    <div class="col-12 col-md-7">
                        <label for="service-workflow-subtitle" class="form-label fw-semibold">
                            Subjudul / Penjelasan Alur Kerja
                        </label>
                        <input type="text" name="workflow_subtitle" id="service-workflow-subtitle"
                            value="{{ old('workflow_subtitle', $isEdit ? $data->workflow_subtitle : 'Dari konsultasi sampai aplikasi dipakai, prosesnya jelas dan bisa dipantau.') }}"
                            class="form-control"
                            placeholder="Contoh: Dari konsultasi sampai aplikasi dipakai, prosesnya jelas dan bisa dipantau.">
                        <div class="service-form-help mt-1">Kalimat penjelas di bawah judul timeline.</div>
                    </div>
                </div>
            </div>

            <div id="workflow-container">
                @forelse($workflowList as $wfIdx => $wf)
                    <div class="service-repeater-item-box workflow-item-row" data-index="{{ $wfIdx }}">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <span class="fw-bold text-success">
                                <iconify-icon icon="solar:routing-2-bold-duotone" class="me-1"></iconify-icon>
                                Langkah #<span class="workflow-number">{{ $wfIdx + 1 }}</span>
                            </span>
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-check-label small text-muted mb-0" for="wf-status-{{ $wfIdx }}">Tampilkan</label>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="workflow_data[{{ $wfIdx }}][status]" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="wf-status-{{ $wfIdx }}"
                                               name="workflow_data[{{ $wfIdx }}][status]" value="1"
                                               @checked((bool) ($wf['status'] ?? true))>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row">
                                    <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon> Hapus Langkah
                                </button>
                            </div>
                        </div>

                        <div class="row gy-3">
                            <div class="col-12 col-md-3">
                                <label class="form-label">Urutan Langkah</label>
                                <input type="text" name="workflow_data[{{ $wfIdx }}][step]"
                                    value="{{ $wf['step'] ?? $wfIdx + 1 }}" class="form-control"
                                    placeholder="Contoh: 01 atau 1">
                            </div>

                            <div class="col-12 col-md-9">
                                <label class="form-label">Judul Langkah <span class="text-danger">*</span></label>
                                <input type="text" name="workflow_data[{{ $wfIdx }}][title]"
                                    value="{{ $wf['title'] ?? '' }}" class="form-control"
                                    placeholder="Contoh: Konsultasi & Identifikasi Kebutuhan" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Icon Langkah (Iconify / SVG)</label>
                                <input type="text" name="workflow_data[{{ $wfIdx }}][icon]"
                                    value="{{ $wf['icon'] ?? '' }}" class="form-control"
                                    placeholder='<iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>'>
                                <small class="text-muted d-block mt-1">Masukkan tag Iconify atau class icon.</small>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Atau Upload Gambar Icon / Ilustrasi</label>
                                <input type="file" name="workflow_data[{{ $wfIdx }}][image]"
                                    accept=".jpg,.jpeg,.png,.webp" class="form-control workflow-img-input">
                                @if (!empty($wf['image']))
                                    <input type="hidden" name="workflow_data[{{ $wfIdx }}][old_image]"
                                        value="{{ $wf['image'] }}">
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <img src="{{ asset($wf['image']) }}" class="rounded border object-fit-cover"
                                            width="50" height="50" alt="Preview">
                                        <small class="text-muted">Gambar tersimpan.</small>
                                    </div>
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi Langkah</label>
                                <textarea name="workflow_data[{{ $wfIdx }}][description]" rows="3" class="form-control"
                                    placeholder="Jelaskan kegiatan pada langkah ini...">{{ $wf['description'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-center py-4 empty-notice" id="empty-workflow-notice">
                        <iconify-icon icon="solar:route-linear" style="font-size: 36px;"></iconify-icon>
                        <div class="mt-2">Belum ada alur kerja. Klik tombol <strong>+ Tambah Langkah</strong> di atas
                            untuk menambahkan.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>


    <div class="card service-form-card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h6 class="card-title mb-0">Portofolio Project Digital</h6>
                <div class="service-form-help mt-1">
                    Daftar portofolio karya nyata yang berkaitan dengan layanan ini (seperti: Bluefire Homestay,
                    Kulamani Booking Engine, dll).
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2 bg-body-tertiary px-3 py-1 rounded border">
                    <span class="text-muted small fw-semibold">Status Section:</span>
                    <div class="form-check form-switch m-0">
                        <input type="hidden" name="show_portfolios" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" id="switch-show-portfolios" name="show_portfolios" value="1"
                               @checked((bool) old('show_portfolios', $isEdit ? ($data->show_portfolios ?? true) : true))>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm px-3" id="btn-add-portfolio">
                    + Tambah Portofolio
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="service-section-heading-box mb-4">
                <div class="row gy-3">
                    <div class="col-12 col-md-4">
                        <label for="service-portfolios-badge" class="form-label fw-semibold">
                            Label / Badge Section
                        </label>
                        <input type="text" name="portfolios_badge" id="service-portfolios-badge"
                            value="{{ old('portfolios_badge', $isEdit ? $data->portfolios_badge : 'Portofolio') }}"
                            class="form-control" placeholder="Contoh: Portofolio">
                        <div class="service-form-help mt-1">Badge teks di atas judul portofolio.</div>
                    </div>
                    <div class="col-12 col-md-8">
                        <label for="service-portfolios-title" class="form-label fw-semibold">
                            Judul Utama Section
                        </label>
                        <input type="text" name="portfolios_title" id="service-portfolios-title"
                            value="{{ old('portfolios_title', $isEdit ? $data->portfolios_title : 'Project Digital Kami') }}"
                            class="form-control" placeholder="Contoh: Project Digital Kami">
                        <div class="service-form-help mt-1">Judul utama bagian slider portofolio.</div>
                    </div>
                </div>
            </div>

            <div id="portfolios-container">
                @forelse($portfoliosList as $pfIdx => $pf)
                    <div class="service-repeater-item-box portfolio-item-row" data-index="{{ $pfIdx }}">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <span class="fw-bold text-info">
                                <iconify-icon icon="solar:gallery-wide-bold-duotone" class="me-1"></iconify-icon>
                                Portofolio #<span class="portfolio-number">{{ $pfIdx + 1 }}</span>
                            </span>
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="form-check-label small text-muted mb-0" for="pf-status-{{ $pfIdx }}">Tampilkan</label>
                                    <div class="form-check form-switch m-0">
                                        <input type="hidden" name="portfolios_data[{{ $pfIdx }}][status]" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="pf-status-{{ $pfIdx }}"
                                               name="portfolios_data[{{ $pfIdx }}][status]" value="1"
                                               @checked((bool) ($pf['status'] ?? true))>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row">
                                    <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon> Hapus Portofolio
                                </button>
                            </div>
                        </div>

                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Nama / Judul Portofolio <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="portfolios_data[{{ $pfIdx }}][title]"
                                    value="{{ $pf['title'] ?? '' }}" class="form-control"
                                    placeholder="Contoh: Bluefire Homestay" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Subjudul / Kategori Layanan</label>
                                <input type="text" name="portfolios_data[{{ $pfIdx }}][subtitle]"
                                    value="{{ $pf['subtitle'] ?? 'JASA PEMBUATAN WEBSITE PROFESIONAL' }}"
                                    class="form-control" placeholder="Contoh: JASA PEMBUATAN WEBSITE PROFESIONAL">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Gambar Portofolio (Mockup / Preview)</label>
                                <input type="file" name="portfolios_data[{{ $pfIdx }}][image]"
                                    accept=".jpg,.jpeg,.png,.webp" class="form-control portfolio-img-input">
                                @if (!empty($pf['image']))
                                    <input type="hidden" name="portfolios_data[{{ $pfIdx }}][old_image]"
                                        value="{{ $pf['image'] }}">
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <img src="{{ asset($pf['image']) }}"
                                            class="rounded border object-fit-cover portfolio-img-preview"
                                            width="90" height="60" alt="Preview">
                                        <small class="text-muted">Gambar saat ini. Pilih file baru untuk
                                            mengganti.</small>
                                    </div>
                                @else
                                    <div class="mt-2 d-none preview-box-new">
                                        <img src=""
                                            class="rounded border object-fit-cover portfolio-img-preview"
                                            width="90" height="60" alt="Preview">
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Link Website Portofolio (Opsional)</label>
                                <input type="text" name="portfolios_data[{{ $pfIdx }}][url]"
                                    value="{{ $pf['url'] ?? '' }}" class="form-control"
                                    placeholder="Contoh: https://example.com">
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted text-center py-4 empty-notice" id="empty-portfolios-notice">
                        <iconify-icon icon="solar:album-linear" style="font-size: 36px;"></iconify-icon>
                        <div class="mt-2">Belum ada data portofolio. Klik tombol <strong>+ Tambah Portofolio</strong>
                            di atas untuk menambahkan.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card service-form-card">
        <div class="card-header">
            <h6 class="card-title mb-0">SEO</h6>
        </div>

        <div class="card-body">
            <div class="row gy-3">
                <div class="col-12 col-md-6">
                    <label for="service-meta-keywords" class="form-label">Meta Keywords</label>
                    <textarea name="meta_keywords" id="service-meta-keywords" rows="3"
                        class="form-control @error('meta_keywords') is-invalid @enderror">{{ old('meta_keywords', $isEdit ? $data->meta_keywords : '') }}</textarea>
                    @error('meta_keywords')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="service-meta-description" class="form-label">Meta Description</label>
                    <textarea name="meta_descriptions" id="service-meta-description" rows="3"
                        class="form-control @error('meta_descriptions') is-invalid @enderror">{{ old('meta_descriptions', $isEdit ? $data->meta_descriptions : '') }}</textarea>
                    @error('meta_descriptions')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="col-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div>
                        <label class="form-label fw-semibold mb-1">Galeri Gambar</label>
                        <div class="service-form-help">
                            Gambar lama tetap disimpan selama tidak dihapus dari daftar.
                            Jika ingin mengganti gambar, hapus terlebih dahulu gambar lama,
                            kemudian tambahkan gambar baru agar perubahan tersimpan dengan benar.
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
                                    <button type="button"
                                        class="btn btn-outline-danger remove-gallery w-100">Hapus</button>
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

    <template id="gallery-template">
        <div class="service-repeater-row gallery-item">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-3">
                    <div class="service-gallery-preview">
                        <span class="gallery-placeholder">Preview</span>
                        <img class="image-preview d-none" alt="Preview gambar galeri">
                    </div>
                </div>
                <div class="col-12 col-md-7">
                    <input type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp"
                        class="form-control gallery-input">
                </div>
                <div class="col-12 col-md-2">
                    <button type="button" class="btn btn-outline-danger remove-gallery w-100">Hapus</button>
                </div>
            </div>
        </div>
    </template>

    <template id="project-row-template">
        <div class="service-repeater-item-box project-item-row" data-index="__INDEX__">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <span class="fw-bold text-primary">
                    <iconify-icon icon="solar:folder-with-files-bold-duotone" class="me-1"></iconify-icon>
                    Project #<span class="project-number">__NUM__</span>
                </span>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-check-label small text-muted mb-0">Tampilkan</label>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="projects_data[__INDEX__][status]" value="0">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="projects_data[__INDEX__][status]" value="1" checked>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row">
                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon> Hapus Project
                    </button>
                </div>
            </div>

            <div class="row gy-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Nama / Judul Project <span class="text-danger">*</span></label>
                    <input type="text" name="projects_data[__INDEX__][title]" class="form-control"
                        placeholder="Contoh: Website Company Profile" required>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Gambar Mockup / Showcase Project</label>
                    <input type="file" name="projects_data[__INDEX__][image]" accept=".jpg,.jpeg,.png,.webp"
                        class="form-control project-img-input">
                    <div class="mt-2 d-none preview-box-new">
                        <img src="" class="rounded border object-fit-cover project-img-preview"
                            width="90" height="60" alt="Preview">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi Project</label>
                    <textarea name="projects_data[__INDEX__][description]" rows="3" class="form-control"
                        placeholder="Jelaskan jenis project, tujuan, dan solusinya..."></textarea>
                </div>

                <div class="col-12 col-md-6">
                    <div class="p-3 border rounded service-feature-box dark-bg-card">
                        <label class="form-label fw-bold text-success mb-1">
                            <iconify-icon icon="solar:check-circle-bold"></iconify-icon> Keunggulan 1
                        </label>
                        <input type="text" name="projects_data[__INDEX__][point_1_title]"
                            class="form-control mb-2" placeholder="Judul (Contoh: Always Online)">
                        <textarea name="projects_data[__INDEX__][point_1_desc]" rows="2" class="form-control"
                            placeholder="Keterangan singkat keunggulan 1..."></textarea>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="p-3 border rounded service-feature-box dark-bg-card">
                        <label class="form-label fw-bold text-success mb-1">
                            <iconify-icon icon="solar:check-circle-bold"></iconify-icon> Keunggulan 2
                        </label>
                        <input type="text" name="projects_data[__INDEX__][point_2_title]"
                            class="form-control mb-2" placeholder="Judul (Contoh: Grow Up Your Business)">
                        <textarea name="projects_data[__INDEX__][point_2_desc]" rows="2" class="form-control"
                            placeholder="Keterangan singkat keunggulan 2..."></textarea>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Teks Tombol CTA</label>
                    <input type="text" name="projects_data[__INDEX__][button_text]"
                        value="Buat Website Anda Sekarang" class="form-control"
                        placeholder="Contoh: Buat Website Anda Sekarang">
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">URL Tombol CTA (WhatsApp / Link)</label>
                    <input type="text" name="projects_data[__INDEX__][button_url]" class="form-control"
                        placeholder="Contoh: https://wa.me/... atau /kontak">
                </div>
            </div>
        </div>
    </template>

    <template id="workflow-row-template">
        <div class="service-repeater-item-box workflow-item-row" data-index="__INDEX__">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <span class="fw-bold text-success">
                    <iconify-icon icon="solar:routing-2-bold-duotone" class="me-1"></iconify-icon>
                    Langkah #<span class="workflow-number">__NUM__</span>
                </span>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-check-label small text-muted mb-0">Tampilkan</label>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="workflow_data[__INDEX__][status]" value="0">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="workflow_data[__INDEX__][status]" value="1" checked>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row">
                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon> Hapus Langkah
                    </button>
                </div>
            </div>

            <div class="row gy-3">
                <div class="col-12 col-md-3">
                    <label class="form-label">Nomor / Urutan Langkah</label>
                    <input type="text" name="workflow_data[__INDEX__][step]" value="__NUM__" class="form-control"
                        placeholder="Contoh: 1 atau Step 1">
                </div>

                <div class="col-12 col-md-9">
                    <label class="form-label">Judul Langkah <span class="text-danger">*</span></label>
                    <input type="text" name="workflow_data[__INDEX__][title]" class="form-control"
                        placeholder="Contoh: Konsultasi & Identifikasi Kebutuhan" required>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Gambar Icon Ilustrasi Langkah</label>
                    <input type="file" name="workflow_data[__INDEX__][image]" accept=".jpg,.jpeg,.png,.webp"
                        class="form-control workflow-img-input">
                    <div class="mt-2 d-none preview-box-new">
                        <img src="" class="rounded border object-fit-contain workflow-img-preview"
                            width="60" height="60" alt="Preview">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Atau Icon SVG / Iconify (Opsional)</label>
                    <input type="text" name="workflow_data[__INDEX__][icon]" class="form-control"
                        placeholder='<iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>'>
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi Proses Kerja</label>
                    <textarea name="workflow_data[__INDEX__][description]" rows="3" class="form-control"
                        placeholder="Jelaskan apa yang dilakukan pada tahap ini..."></textarea>
                </div>
            </div>
        </div>
    </template>

    <template id="portfolio-row-template">
        <div class="service-repeater-item-box portfolio-item-row" data-index="__INDEX__">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <span class="fw-bold text-info">
                    <iconify-icon icon="solar:gallery-wide-bold-duotone" class="me-1"></iconify-icon>
                    Portofolio #<span class="portfolio-number">__NUM__</span>
                </span>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-check-label small text-muted mb-0">Tampilkan</label>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="portfolios_data[__INDEX__][status]" value="0">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="portfolios_data[__INDEX__][status]" value="1" checked>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row">
                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon> Hapus Portofolio
                    </button>
                </div>
            </div>

            <div class="row gy-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Nama / Judul Portofolio <span class="text-danger">*</span></label>
                    <input type="text" name="portfolios_data[__INDEX__][title]" class="form-control"
                        placeholder="Contoh: Bluefire Homestay" required>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Subjudul / Kategori Layanan</label>
                    <input type="text" name="portfolios_data[__INDEX__][subtitle]"
                        value="JASA PEMBUATAN WEBSITE PROFESIONAL" class="form-control"
                        placeholder="Contoh: JASA PEMBUATAN WEBSITE PROFESIONAL">
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Gambar Portofolio (Mockup / Preview)</label>
                    <input type="file" name="portfolios_data[__INDEX__][image]" accept=".jpg,.jpeg,.png,.webp"
                        class="form-control portfolio-img-input">
                    <div class="mt-2 d-none preview-box-new">
                        <img src="" class="rounded border object-fit-cover portfolio-img-preview"
                            width="90" height="60" alt="Preview">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Link Website Portofolio (Opsional)</label>
                    <input type="text" name="portfolios_data[__INDEX__][url]" class="form-control"
                        placeholder="Contoh: https://example.com">
                </div>
            </div>
        </div>
    </template>

    <script>
        (function() {
            const initializeServiceForm = function() {
                const iconInput = document.getElementById('icon_input');
                const iconPreview = document.getElementById('preview_icon');

                const updateIconPreview = function() {
                    if (!iconPreview) return;
                    iconPreview.innerHTML = iconInput?.value || '';
                };

                iconInput?.addEventListener('input', updateIconPreview);
                updateIconPreview();

                const imageInput = document.getElementById('service-image');
                const imagePreview = document.getElementById('service-image-preview');
                const imagePlaceholder = document.getElementById('service-image-placeholder');

                imageInput?.addEventListener('change', function() {
                    const file = imageInput.files?.[0];
                    if (!file || !imagePreview) return;

                    imagePreview.src = URL.createObjectURL(file);
                    imagePreview.classList.remove('d-none');
                    imagePlaceholder?.classList.add('d-none');
                });

                // ================= REPEATER LOGIC FOR PROJECT, WORKFLOW & PORTFOLIO =================
                let projectIndexTracker = {{ count($projectsList) }};
                let workflowIndexTracker = {{ count($workflowList) }};
                let portfolioIndexTracker = {{ count($portfoliosList) }};

                const btnAddProject = document.getElementById('btn-add-project');
                const projectsContainer = document.getElementById('projects-container');
                const projectTemplate = document.getElementById('project-row-template');

                if (btnAddProject && projectsContainer && projectTemplate) {
                    btnAddProject.addEventListener('click', function() {
                        const emptyNotice = document.getElementById('empty-projects-notice');
                        if (emptyNotice) emptyNotice.remove();

                        const currentCount = projectsContainer.querySelectorAll('.project-item-row')
                            .length + 1;
                        const html = projectTemplate.innerHTML
                            .replaceAll('__INDEX__', projectIndexTracker)
                            .replaceAll('__NUM__', currentCount);

                        projectsContainer.insertAdjacentHTML('beforeend', html);
                        projectIndexTracker++;
                    });
                }

                const btnAddWorkflow = document.getElementById('btn-add-workflow');
                const workflowContainer = document.getElementById('workflow-container');
                const workflowTemplate = document.getElementById('workflow-row-template');

                if (btnAddWorkflow && workflowContainer && workflowTemplate) {
                    btnAddWorkflow.addEventListener('click', function() {
                        const emptyNotice = document.getElementById('empty-workflow-notice');
                        if (emptyNotice) emptyNotice.remove();

                        const currentCount = workflowContainer.querySelectorAll('.workflow-item-row')
                            .length + 1;
                        const html = workflowTemplate.innerHTML
                            .replaceAll('__INDEX__', workflowIndexTracker)
                            .replaceAll('__NUM__', currentCount);

                        workflowContainer.insertAdjacentHTML('beforeend', html);
                        workflowIndexTracker++;
                    });
                }

                const btnAddPortfolio = document.getElementById('btn-add-portfolio');
                const portfoliosContainer = document.getElementById('portfolios-container');
                const portfolioTemplate = document.getElementById('portfolio-row-template');

                if (btnAddPortfolio && portfoliosContainer && portfolioTemplate) {
                    btnAddPortfolio.addEventListener('click', function() {
                        const emptyNotice = document.getElementById('empty-portfolios-notice');
                        if (emptyNotice) emptyNotice.remove();

                        const currentCount = portfoliosContainer.querySelectorAll('.portfolio-item-row')
                            .length + 1;
                        const html = portfolioTemplate.innerHTML
                            .replaceAll('__INDEX__', portfolioIndexTracker)
                            .replaceAll('__NUM__', currentCount);

                        portfoliosContainer.insertAdjacentHTML('beforeend', html);
                        portfolioIndexTracker++;
                    });
                }

                // Universal delete handler for repeater rows
                document.addEventListener('click', function(e) {
                    const removeBtn = e.target.closest('.btn-remove-row');
                    if (!removeBtn) return;

                    const row = removeBtn.closest('.service-repeater-item-box');
                    if (!row) return;

                    const parent = row.parentElement;
                    row.remove();

                    // Update row numbers
                    if (parent) {
                        parent.querySelectorAll('.project-item-row').forEach((item, idx) => {
                            const numEl = item.querySelector('.project-number');
                            if (numEl) numEl.textContent = idx + 1;
                        });
                        parent.querySelectorAll('.workflow-item-row').forEach((item, idx) => {
                            const numEl = item.querySelector('.workflow-number');
                            if (numEl) numEl.textContent = idx + 1;
                        });
                        parent.querySelectorAll('.portfolio-item-row').forEach((item, idx) => {
                            const numEl = item.querySelector('.portfolio-number');
                            if (numEl) numEl.textContent = idx + 1;
                        });
                    }
                });

                // Live image preview handler for file inputs inside repeaters
                document.addEventListener('change', function(e) {
                    const target = e.target;
                    if (!target.matches('.project-img-input, .workflow-img-input, .portfolio-img-input'))
                        return;

                    const file = target.files?.[0];
                    if (!file) return;

                    const container = target.closest('.col-12');
                    if (!container) return;

                    let previewBox = container.querySelector('.preview-box-new');
                    let imgEl = container.querySelector('img');

                    if (previewBox) {
                        previewBox.classList.remove('d-none');
                    }
                    if (imgEl) {
                        imgEl.src = URL.createObjectURL(file);
                    }
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeServiceForm, {
                    once: true
                });
            } else {
                initializeServiceForm();
            }
        })();
    </script>
@endif
