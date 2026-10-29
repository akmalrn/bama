@php
    $data = $data ?? null;
    $partnerSection = $data?->partnerSection ?? null;
    $partnerItems = old('partners', $partnerSection?->partners?->toArray() ?? []);

    $superioritySection = $data?->superioritySection ?? null;
    $superiorityItems = old('superiorities', $superioritySection?->superiorities?->toArray() ?? []);

    $featureSection = $data?->featureSection ?? null;
    $featureItems = old('features', $featureSection?->features?->toArray() ?? []);

    $whyUsSection = $data?->whyUsSection ?? null;
    $whyUsItems = old('why_us_items', $whyUsSection?->items?->toArray() ?? []);

    $ctaSection = $data?->ctaSection ?? null;

    $activeTab = request('section', 'hero');
@endphp

<nav class="project-form-nav" aria-label="Navigasi form perbandingan">
    @foreach ([
        'hero' => ['solar:document-text-linear', 'Hero / Informasi'],
        'partner' => ['solar:users-group-rounded-linear', 'Partner / Klien'],
        'superiority' => ['solar:shield-check-linear', 'Superiority'],
        'feature' => ['solar:star-linear', 'Fitur'],
        'why_us' => ['solar:lightbulb-linear', 'Why Us'],
        'cta' => ['solar:bell-bing-linear', 'CTA Banner'],
    ] as $sectionKey => [$sectionIcon, $sectionLabel])
        <a href="#section-{{ $sectionKey }}"
           class="project-form-nav__item {{ $activeTab === $sectionKey ? 'is-active' : '' }}"
           data-project-section="{{ $sectionKey }}">
            <iconify-icon icon="{{ $sectionIcon }}"></iconify-icon>
            <span>{{ $sectionLabel }}</span>
        </a>
    @endforeach
</nav>


<div class="project-section-panel {{ $activeTab === 'hero' ? '' : 'd-none' }}" id="section-hero">
    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">Informasi Utama Perbandingan (Hero Section)</h6>
        </div>
        <div class="card-body">
            <div class="row gy-3">
                {{-- FIELD KATEGORI PROJEK OPSIONAL (DIKOMEN AGAR DAPAT DI-AKTIFKAN KEMBALI KETIKA DIMINTA) --}}
                {{--
                <div class="col-12 col-md-6">
                    <label class="form-label">Kategori Projek</label>
                    <select name="id_category" class="form-control @error('id_category') is-invalid @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('id_category', $data->id_category ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                --}}

                <div class="col-12 col-md-4">
                    <label class="form-label">Badge / Kicker</label>
                    <input type="text" name="badge" value="{{ old('badge', $data->badge ?? '') }}"
                           class="form-control" placeholder="Contoh: Solusi ERP Alternatif Terbaik">
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Judul Perbandingan *</label>
                    <input type="text" name="title" value="{{ old('title', $data->title ?? '') }}"
                           class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Total vs Zahir" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Nama Menu Navbar (Singkat)</label>
                    <input type="text" name="name_link" value="{{ old('name_link', $data->name_link ?? '') }}"
                           class="form-control" placeholder="Contoh: Total vs Zahir">
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Gambar Hero</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                           accept=".jpg,.jpeg,.png,.webp" onchange="previewImage(event, 'preview_hero_image')">
                    <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG, WEBP (Maksimal 2MB)</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="project-image-live-preview mt-2">
                        <img id="preview_hero_image"
                             src="{{ !empty($data->image) ? asset($data->image) : '' }}"
                             class="{{ !empty($data->image) ? '' : 'd-none' }}"
                             alt="Preview Hero">
                        <div class="project-image-live-preview__empty {{ !empty($data->image) ? 'd-none' : '' }}" id="empty_hero_preview">
                            <iconify-icon icon="solar:gallery-add-linear"></iconify-icon>
                            <span>Belum ada gambar hero</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Ringkasan / Overview Singkat</label>
                    <textarea name="overview" class="form-control" rows="5"
                              placeholder="Penjelasan ringkas yang muncul di bawah judul hero">{{ old('overview', $data->overview ?? '') }}</textarea>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Nama Tombol 1</label>
                    <input type="text" name="button_name" value="{{ old('button_name', $data->button_name ?? '') }}"
                           class="form-control" placeholder="Contoh: Selengkapnya">
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">URL Tombol 1</label>
                    <input type="text" name="button_url" value="{{ old('button_url', $data->button_url ?? '') }}"
                           class="form-control" placeholder="Contoh: #alasan-memilih">
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Nama Tombol 2</label>
                    <input type="text" name="button_name_2" value="{{ old('button_name_2', $data->button_name_2 ?? '') }}"
                           class="form-control" placeholder="Contoh: Bandingkan Fitur!">
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">URL Tombol 2</label>
                    <input type="text" name="button_url_2" value="{{ old('button_url_2', $data->button_url_2 ?? '') }}"
                           class="form-control" placeholder="Contoh: #fitur-lengkap">
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $data->meta_title ?? '') }}" class="form-control">
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $data->meta_keywords ?? '') }}" class="form-control">
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Meta Description</label>
                    <input type="text" name="meta_descriptions" value="{{ old('meta_descriptions', $data->meta_descriptions ?? '') }}" class="form-control">
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Urutan</label>
                    <input type="number" min="0" name="order" value="{{ old('order', $data->order ?? 0) }}" class="form-control">
                </div>

                <div class="col-12 col-md-4">
                    <div class="project-status-control mt-4">
                        <span class="project-status-control__label">
                            <strong>Status Publikasi</strong>
                            <small>Tampilkan di website</small>
                        </span>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="status" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="status" value="1"
                                   @checked((bool) old('status', $data->status ?? true))>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="project-status-control mt-4">
                        <span class="project-status-control__label">
                            <strong>Perbandingan Unggulan</strong>
                            <small>Tandai sebagai unggulan</small>
                        </span>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="is_featured" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_featured" value="1"
                                   @checked((bool) old('is_featured', $data->is_featured ?? false))>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="project-section-panel {{ $activeTab === 'partner' ? '' : 'd-none' }}" id="section-partner">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">Pengaturan Section Partner / Klien</h6>
            <div class="form-check form-switch m-0">
                <input type="hidden" name="partner_section[status]" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="partner_section[status]" value="1"
                       @checked((bool) old('partner_section.status', $partnerSection->status ?? true))>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Judul Section Partner</label>
                    <input type="text" name="partner_section[title]"
                           value="{{ old('partner_section.title', $partnerSection->title ?? 'Kami Telah Dipercaya Ribuan Bisnis di Indonesia') }}"
                           class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header project-repeater-heading m-0">
            <div>
                <h6>Daftar Logo Partner / Klien</h6>
                <p>Logo ini akan tampil pada slider berjalan di bawah hero.</p>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-row="partners">
                <iconify-icon icon="ic:round-add"></iconify-icon> Tambah Partner
            </button>
        </div>
        <div class="card-body" data-rows="partners">
            @foreach ($partnerItems as $i => $item)
                <div class="project-repeater-row">
                    <input type="hidden" name="partners[{{ $i }}][id]" value="{{ $item['id'] ?? '' }}">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label">Nama Klien / Partner</label>
                            <input type="text" class="form-control" name="partners[{{ $i }}][name]"
                                   value="{{ $item['name'] ?? '' }}" placeholder="Contoh: Loka Supermarket">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gambar Logo</label>
                            <input type="file" class="form-control" name="partners[{{ $i }}][image]" accept=".jpg,.jpeg,.png,.webp">
                            @if (!empty($item['image']))
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="{{ asset($item['image']) }}" height="32" class="rounded bg-light p-1">
                                    <small class="text-muted">Logo saat ini</small>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Urutan</label>
                            <input type="number" min="0" class="form-control" name="partners[{{ $i }}][order]"
                                   value="{{ $item['order'] ?? $i }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger w-100" data-remove-row>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<div class="project-section-panel {{ $activeTab === 'superiority' ? '' : 'd-none' }}" id="section-superiority">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">Pengaturan Section Superiority (Alasan Memilih)</h6>
            <div class="form-check form-switch m-0">
                <input type="hidden" name="superiority_section[status]" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="superiority_section[status]" value="1"
                       @checked((bool) old('superiority_section.status', $superioritySection->status ?? true))>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Judul Section Superiority</label>
                    <input type="text" name="superiority_section[title]"
                           value="{{ old('superiority_section.title', $superioritySection->title ?? 'Alasan Bisnis Memilih Total ERP Dibandingkan Zahir') }}"
                           class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header project-repeater-heading m-0">
            <div>
                <h6>Daftar Kartu Superiority / Nilai Unggul</h6>
                <p>4 kartu pilar keunggulan (misal: Designed to Fit, Support Team, Tax Compliance, AI Automation).</p>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-row="superiorities">
                <iconify-icon icon="ic:round-add"></iconify-icon> Tambah Superiority
            </button>
        </div>
        <div class="card-body" data-rows="superiorities">
            @foreach ($superiorityItems as $i => $item)
                <div class="project-repeater-row">
                    <input type="hidden" name="superiorities[{{ $i }}][id]" value="{{ $item['id'] ?? '' }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Judul Keunggulan *</label>
                            <input type="text" class="form-control" name="superiorities[{{ $i }}][title]"
                                   value="{{ $item['title'] ?? '' }}" placeholder="Contoh: Designed to Fit Your Business" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ikon / Gambar</label>
                            <input type="file" class="form-control" name="superiorities[{{ $i }}][image]" accept=".jpg,.jpeg,.png,.webp">
                            @if (!empty($item['image']))
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="{{ asset($item['image']) }}" height="32" class="rounded bg-light p-1">
                                    <small class="text-muted">Ikon saat ini</small>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Urutan</label>
                            <input type="number" min="0" class="form-control" name="superiorities[{{ $i }}][order]"
                                   value="{{ $item['order'] ?? $i }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger w-100" data-remove-row>
                                Hapus
                            </button>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi Keunggulan</label>
                            <textarea class="form-control" rows="2" name="superiorities[{{ $i }}][description]"
                                      placeholder="Penjelasan keunggulan">{{ $item['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<div class="project-section-panel {{ $activeTab === 'feature' ? '' : 'd-none' }}" id="section-feature">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">Pengaturan Section Fitur Komprehensif</h6>
            <div class="form-check form-switch m-0">
                <input type="hidden" name="feature_section[status]" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="feature_section[status]" value="1"
                       @checked((bool) old('feature_section.status', $featureSection->status ?? true))>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Judul Section Fitur</label>
                    <input type="text" name="feature_section[title]"
                           value="{{ old('feature_section.title', $featureSection->title ?? 'Fitur ERP Komprehensif yang Siap Mendukung Skalabilitas Bisnis Anda') }}"
                           class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sub Judul</label>
                    <input type="text" name="feature_section[subtitle]"
                           value="{{ old('feature_section.subtitle', $featureSection->subtitle ?? 'Tingkatkan efisiensi dan kembangkan bisnis Anda dengan sistem ERP yang siap mendukung setiap kebutuhan.') }}"
                           class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header project-repeater-heading m-0">
            <div>
                <h6>Daftar Modul Fitur (Tab Navigasi)</h6>
                <p>Setiap modul memiliki screenshot dashboard dan daftar poin "Fitur Unggulan yang Tidak Ditemui di Zahir".</p>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-row="features">
                <iconify-icon icon="ic:round-add"></iconify-icon> Tambah Modul Fitur
            </button>
        </div>
        <div class="card-body" data-rows="features">
            @foreach ($featureItems as $i => $item)
                @php
                    $bullets = $item['bullet_points'] ?? [];
                    $bulletsText = is_array($bullets) ? implode("\n", $bullets) : (is_string($bullets) ? $bullets : '');
                @endphp
                <div class="project-repeater-row">
                    <input type="hidden" name="features[{{ $i }}][id]" value="{{ $item['id'] ?? '' }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Tab Tombol *</label>
                            <input type="text" class="form-control" name="features[{{ $i }}][tab_name]"
                                   value="{{ $item['tab_name'] ?? '' }}" placeholder="Contoh: Kelola Laporan Keuangan" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Judul Fitur Utama *</label>
                            <input type="text" class="form-control" name="features[{{ $i }}][title]"
                                   value="{{ $item['title'] ?? '' }}" placeholder="Contoh: Kelola Keuangan dan Pajak Lebih Efektif" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" min="0" class="form-control" name="features[{{ $i }}][order]"
                                   value="{{ $item['order'] ?? $i }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Sub Judul / Ringkasan</label>
                            <textarea class="form-control" rows="2" name="features[{{ $i }}][subtitle]"
                                      placeholder="Penjelasan ringkas modul">{{ $item['subtitle'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Gambar Screenshot / Mockup</label>
                            <input type="file" class="form-control" name="features[{{ $i }}][image]" accept=".jpg,.jpeg,.png,.webp">
                            @if (!empty($item['image']))
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="{{ asset($item['image']) }}" height="45" class="rounded border p-1">
                                    <small class="text-muted">Screenshot saat ini</small>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">
                                Poin Keunggulan (Fitur yang Tidak Ditemui di Zahir)
                                <small class="text-muted">(Satu poin per baris)</small>
                            </label>
                            <textarea class="form-control" rows="5" name="features[{{ $i }}][bullet_points]"
                                      placeholder="Laporan keuangan detail per proyek&#10;Transaksi otomatis ke bank&#10;Pantau kurva S anggaran">{{ $bulletsText }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama Tombol CTA</label>
                            <input type="text" class="form-control" name="features[{{ $i }}][button_name]"
                                   value="{{ $item['button_name'] ?? 'Coba Gratis' }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">URL Tombol CTA</label>
                            <input type="text" class="form-control" name="features[{{ $i }}][button_url]"
                                   value="{{ $item['button_url'] ?? '#form-demo' }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger w-100" data-remove-row>
                                Hapus Modul Fitur
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<div class="project-section-panel {{ $activeTab === 'why_us' ? '' : 'd-none' }}" id="section-why_us">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">Pengaturan Section Why Us (Poin Pertimbangan)</h6>
            <div class="form-check form-switch m-0">
                <input type="hidden" name="why_us_section[status]" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="why_us_section[status]" value="1"
                       @checked((bool) old('why_us_section.status', $whyUsSection->status ?? true))>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Judul Section Why Us</label>
                    <input type="text" name="why_us_section[title]"
                           value="{{ old('why_us_section.title', $whyUsSection->title ?? 'Masih Mempertimbangkan Total ERP? Simak Beberapa Poin Berikut Ini') }}"
                           class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header project-repeater-heading m-0">
            <div>
                <h6>Daftar Kartu Why Us (4 Kartu Asimetris)</h6>
                <p>Kartu keunggulan dengan visual asimetris (Small Card 38-40% & Big Card 60%).</p>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-row="why_us_items">
                <iconify-icon icon="ic:round-add"></iconify-icon> Tambah Poin Why Us
            </button>
        </div>
        <div class="card-body" data-rows="why_us_items">
            @foreach ($whyUsItems as $i => $item)
                <div class="project-repeater-row">
                    <input type="hidden" name="why_us_items[{{ $i }}][id]" value="{{ $item['id'] ?? '' }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Judul Kartu *</label>
                            <input type="text" class="form-control" name="why_us_items[{{ $i }}][title]"
                                   value="{{ $item['title'] ?? '' }}" placeholder="Contoh: Operasional Lebih Efisien" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipe Ukuran Kartu</label>
                            <select class="form-control" name="why_us_items[{{ $i }}][card_type]">
                                <option value="small" {{ ($item['card_type'] ?? '') === 'small' ? 'selected' : '' }}>Small Card (38-40%)</option>
                                <option value="big" {{ ($item['card_type'] ?? '') === 'big' ? 'selected' : '' }}>Big Card (60%)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Gambar Ilustrasi</label>
                            <input type="file" class="form-control" name="why_us_items[{{ $i }}][image]" accept=".jpg,.jpeg,.png,.webp">
                            @if (!empty($item['image']))
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="{{ asset($item['image']) }}" height="36" class="rounded border p-1">
                                    <small class="text-muted">Gambar saat ini</small>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Urutan</label>
                            <input type="number" min="0" class="form-control" name="why_us_items[{{ $i }}][order]"
                                   value="{{ $item['order'] ?? $i }}">
                        </div>
                        <div class="col-10">
                            <label class="form-label">Deskripsi Poin</label>
                            <textarea class="form-control" rows="2" name="why_us_items[{{ $i }}][description]"
                                      placeholder="Penjelasan poin pertimbangan">{{ $item['description'] ?? '' }}</textarea>
                        </div>
                        <div class="col-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger w-100" data-remove-row>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<div class="project-section-panel {{ $activeTab === 'cta' ? '' : 'd-none' }}" id="section-cta">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">1. Mid CTA Banner (Banner Ungu Tengah)</h6>
            <div class="form-check form-switch m-0">
                <input type="hidden" name="cta_section[status]" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="cta_section[status]" value="1"
                       @checked((bool) old('cta_section.status', $ctaSection->status ?? true))>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Judul Banner Mid CTA</label>
                    <input type="text" name="cta_section[banner_title]"
                           value="{{ old('cta_section.banner_title', $ctaSection->banner_title ?? 'Tanpa Biaya Tersembunyi. Konsultasikan Kebutuhan Anda dan Dapatkan Penawaran Terbaik!') }}"
                           class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Tombol</label>
                    <input type="text" name="cta_section[banner_button_name]"
                           value="{{ old('cta_section.banner_button_name', $ctaSection->banner_button_name ?? 'Coba Gratis') }}"
                           class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">URL Tombol</label>
                    <input type="text" name="cta_section[banner_button_url]"
                           value="{{ old('cta_section.banner_button_url', $ctaSection->banner_button_url ?? '#form-demo') }}"
                           class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Gambar Dekorasi Banner</label>
                    <input type="file" name="cta_section[banner_image]" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    @if (!empty($ctaSection->banner_image))
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <img src="{{ asset($ctaSection->banner_image) }}" height="40" class="rounded bg-dark p-1">
                            <small class="text-muted">Dekorasi saat ini</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">2. Solution Impact Banner (Banner Solusi Efisiensi Bisnis)</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Judul Banner Solusi</label>
                    <input type="text" name="cta_section[solution_title]"
                           value="{{ old('cta_section.solution_title', $ctaSection->solution_title ?? 'Total ERP, Solusi Tepat untuk Mewujudkan Efisiensi Bisnis yang Lebih Maksimal') }}"
                           class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Banner Solusi</label>
                    <textarea name="cta_section[solution_subtitle]" class="form-control" rows="2">{{ old('cta_section.solution_subtitle', $ctaSection->solution_subtitle ?? 'Kelola operasional bisnis lebih efisien dengan fitur lengkap untuk mendorong profitabilitas dengan Total ERP.') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Tombol</label>
                    <input type="text" name="cta_section[solution_button_name]"
                           value="{{ old('cta_section.solution_button_name', $ctaSection->solution_button_name ?? 'Coba Gratis') }}"
                           class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">URL Tombol</label>
                    <input type="text" name="cta_section[solution_button_url]"
                           value="{{ old('cta_section.solution_button_url', $ctaSection->solution_button_url ?? '#form-demo') }}"
                           class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Gambar Dashboard Solusi</label>
                    <input type="file" name="cta_section[solution_image]" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    @if (!empty($ctaSection->solution_image))
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <img src="{{ asset($ctaSection->solution_image) }}" height="50" class="rounded border p-1">
                            <small class="text-muted">Gambar dashboard saat ini</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Tab navigation switching
        const navItems = document.querySelectorAll('.project-form-nav__item');
        const panels = document.querySelectorAll('.project-section-panel');

        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const section = item.dataset.projectSection;

                navItems.forEach(n => n.classList.remove('is-active'));
                item.classList.add('is-active');

                panels.forEach(p => {
                    if (p.id === `section-${section}`) {
                        p.classList.remove('d-none');
                    } else {
                        p.classList.add('d-none');
                    }
                });
            });
        });

        // Add dynamic repeater rows
        document.querySelectorAll('[data-add-row]').forEach(btn => {
            btn.addEventListener('click', () => {
                const type = btn.dataset.addRow;
                const container = document.querySelector(`[data-rows="${type}"]`);
                const i = Date.now();

                let html = '';
                if (type === 'partners') {
                    html = `
                    <div class="project-repeater-row">
                        <input type="hidden" name="partners[${i}][id]">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label">Nama Klien / Partner</label>
                                <input type="text" class="form-control" name="partners[${i}][name]" placeholder="Nama Klien">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gambar Logo</label>
                                <input type="file" class="form-control" name="partners[${i}][image]" accept=".jpg,.jpeg,.png,.webp">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Urutan</label>
                                <input type="number" min="0" class="form-control" name="partners[${i}][order]" value="0">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger w-100" data-remove-row>Hapus</button>
                            </div>
                        </div>
                    </div>`;
                } else if (type === 'superiorities') {
                    html = `
                    <div class="project-repeater-row">
                        <input type="hidden" name="superiorities[${i}][id]">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Judul Keunggulan *</label>
                                <input type="text" class="form-control" name="superiorities[${i}][title]" placeholder="Judul keunggulan" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ikon / Gambar</label>
                                <input type="file" class="form-control" name="superiorities[${i}][image]" accept=".jpg,.jpeg,.png,.webp">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Urutan</label>
                                <input type="number" min="0" class="form-control" name="superiorities[${i}][order]" value="0">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger w-100" data-remove-row>Hapus</button>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Deskripsi Keunggulan</label>
                                <textarea class="form-control" rows="2" name="superiorities[${i}][description]" placeholder="Penjelasan keunggulan"></textarea>
                            </div>
                        </div>
                    </div>`;
                } else if (type === 'features') {
                    html = `
                    <div class="project-repeater-row">
                        <input type="hidden" name="features[${i}][id]">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nama Tab Tombol *</label>
                                <input type="text" class="form-control" name="features[${i}][tab_name]" placeholder="Nama Tab" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Judul Fitur Utama *</label>
                                <input type="text" class="form-control" name="features[${i}][title]" placeholder="Judul Fitur" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Urutan</label>
                                <input type="number" min="0" class="form-control" name="features[${i}][order]" value="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Sub Judul / Ringkasan</label>
                                <textarea class="form-control" rows="2" name="features[${i}][subtitle]"></textarea>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Gambar Screenshot / Mockup</label>
                                <input type="file" class="form-control" name="features[${i}][image]" accept=".jpg,.jpeg,.png,.webp">
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Poin Keunggulan (Satu poin per baris)</label>
                                <textarea class="form-control" rows="5" name="features[${i}][bullet_points]"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nama Tombol CTA</label>
                                <input type="text" class="form-control" name="features[${i}][button_name]" value="Coba Gratis">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">URL Tombol CTA</label>
                                <input type="text" class="form-control" name="features[${i}][button_url]" value="#form-demo">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger w-100" data-remove-row>Hapus</button>
                            </div>
                        </div>
                    </div>`;
                } else if (type === 'why_us_items') {
                    html = `
                    <div class="project-repeater-row">
                        <input type="hidden" name="why_us_items[${i}][id]">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Judul Kartu *</label>
                                <input type="text" class="form-control" name="why_us_items[${i}][title]" placeholder="Judul Kartu" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tipe Ukuran Kartu</label>
                                <select class="form-control" name="why_us_items[${i}][card_type]">
                                    <option value="small">Small Card (38-40%)</option>
                                    <option value="big">Big Card (60%)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Gambar Ilustrasi</label>
                                <input type="file" class="form-control" name="why_us_items[${i}][image]" accept=".jpg,.jpeg,.png,.webp">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Urutan</label>
                                <input type="number" min="0" class="form-control" name="why_us_items[${i}][order]" value="0">
                            </div>
                            <div class="col-10">
                                <label class="form-label">Deskripsi Poin</label>
                                <textarea class="form-control" rows="2" name="why_us_items[${i}][description]"></textarea>
                            </div>
                            <div class="col-2 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger w-100" data-remove-row>Hapus</button>
                            </div>
                        </div>
                    </div>`;
                }

                if (container && html) {
                    container.insertAdjacentHTML('beforeend', html);
                }
            });
        });

        // Remove row delegation
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-remove-row]');
            if (btn && confirm('Hapus baris ini?')) {
                btn.closest('.project-repeater-row').remove();
            }
        });
    });

    function previewImage(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);
        const emptyState = document.getElementById('empty_hero_preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                if (emptyState) {
                    emptyState.classList.add('d-none');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
