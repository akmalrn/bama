@extends('layout.layout')
@php
    $title = 'Configuration';
    $subTitle = 'Create Configuration';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.configurations.storeOrUpdate') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-exclamation-triangle"></i>
                            Format gambar tidak boleh <strong>SVG</strong>. Disarankan Menggunakan Format JPG, JPEG, PNG,
                            atau
                            WEBP.
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-4" id="configTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#dashboard"
                                    type="button">
                                    Dashboard Configuration
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#login" type="button">
                                    Login Configuration
                                </button>
                            </li>
                            {{-- <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#page" type="button">
                                    Page Configuration
                                </button>
                            </li> --}}
                            {{-- <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cta" type="button">
                                    Page CTA
                                </button>
                            </li> --}}
                            {{-- <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#social" type="button">
                                    Social Meta
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#geo" type="button">
                                    Geo Location
                                </button>
                            </li> --}}
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="dashboard">
                                <div class="row gy-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Nama Dashboard</label>
                                        <input type="text" name="name_dashboard"
                                            value="{{ old('name_dashboard', $configuration->name_dashboard ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Judul Dashboard</label>
                                        <input type="text" name="title_dashboard"
                                            value="{{ old('title_dashboard', $configuration->title_dashboard ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Logo Dashboard (Hitam)</label>
                                        <input class="form-control" type="file" name="logo_dashboard" accept="image/*"
                                            onchange="previewImage(event, 'previewLogoDashboard')">

                                        <div class="mt-3">
                                            <img id="previewLogoDashboard"
                                                src="{{ isset($configuration) && $configuration->logo_dashboard ? asset($configuration->logo_dashboard) : '' }}"
                                                class="radius-8 border"
                                                style="max-width:100px; {{ isset($configuration) && $configuration->logo_dashboard ? '' : 'd-none;' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Logo Dashboard (Putih)</label>
                                        <input class="form-control" type="file" name="logo_dashboard_2" accept="image/*"
                                            onchange="previewImage(event, 'previewLogoDashboard_2')">

                                        <div class="mt-3">
                                            <img id="previewLogoDashboard_2"
                                                src="{{ isset($configuration) && $configuration->logo_dashboard_2 ? asset($configuration->logo_dashboard_2) : '' }}"
                                                class="radius-8 border"
                                                style="max-width:100px; {{ isset($configuration) && $configuration->logo_dashboard_2 ? '' : 'd-none;' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Favicon Dashboard</label>
                                        <input class="form-control" type="file" name="favicon_dashboard" accept="image/*"
                                            onchange="previewImage(event, 'previewFaviconDashboard')">

                                        <div class="mt-3">
                                            <img id="previewFaviconDashboard"
                                                src="{{ isset($configuration) && $configuration->favicon_dashboard ? asset($configuration->favicon_dashboard) : '' }}"
                                                class="radius-8 border"
                                                style="max-height:80px; {{ isset($configuration) && $configuration->favicon_dashboard ? '' : 'd-none;' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">CopyRight (Hak Cipta)</label>
                                        <input type="text" name="copyright"
                                            value="{{ old('copyright', $configuration->copyright ?? '') }}"
                                            class="form-control">
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="login">
                                <div class="row gy-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Judul Login</label>
                                        <input type="text" name="title_login"
                                            value="{{ old('title_login', $configuration->title_login ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Label Login</label>
                                        <input type="text" name="name_login"
                                            value="{{ old('name_login', $configuration->name_login ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Logo Login</label>
                                        <input class="form-control" type="file" name="logo_login" accept="image/*"
                                            onchange="previewImage(event, 'previewLogoLogin')">

                                        <div class="mt-3">
                                            <img id="previewLogoLogin"
                                                src="{{ isset($configuration) && $configuration->logo_login ? asset($configuration->logo_login) : '' }}"
                                                class="radius-8 border"
                                                style="max-width:100px; {{ isset($configuration) && $configuration->logo_login ? '' : 'd-none;' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Favicon Login</label>
                                        <input class="form-control" type="file" name="favicon_login" accept="image/*"
                                            onchange="previewImage(event, 'previewFaviconLogin')">

                                        <div class="mt-3">
                                            <img id="previewFaviconLogin"
                                                src="{{ isset($configuration) && $configuration->favicon_login ? asset($configuration->favicon_login) : '' }}"
                                                class="radius-8 border"
                                                style="max-height:80px; {{ isset($configuration) && $configuration->favicon_login ? '' : 'd-none;' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Gambar Login</label>
                                        <input class="form-control" type="file" name="image_login" accept="image/*"
                                            onchange="previewImage(event, 'previewImageLogin')">

                                        <div class="mt-3">
                                            <img id="previewImageLogin"
                                                src="{{ isset($configuration) && $configuration->image_login ? asset($configuration->image_login) : '' }}"
                                                class="radius-8 border"
                                                style="max-height:120px; {{ isset($configuration) && $configuration->image_login ? '' : 'd-none;' }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="page">
                                <div class="row gy-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Nama Page (Web)</label>
                                        <input type="text" name="name_page"
                                            value="{{ old('name_page', $configuration->name_page ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Judul Web</label>
                                        <input type="text" name="title_page"
                                            value="{{ old('title_page', $configuration->title_page ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Logo Web</label>
                                        <input class="form-control" type="file" name="logo_hitam_page"
                                            accept="image/*" onchange="previewImage(event,'previewLogoHitamPage')">

                                        <div class="mt-3">
                                            <img id="previewLogoHitamPage"
                                                src="{{ isset($configuration) && $configuration->logo_hitam_page ? asset($configuration->logo_hitam_page) : '' }}"
                                                class="radius-8 border"
                                                style="max-width:100px; display:{{ isset($configuration) && $configuration->logo_hitam_page ? 'block' : 'none' }};">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Logo Putih</label>
                                        <input class="form-control" type="file" name="logo_putih_page"
                                            accept="image/*" onchange="previewImage(event,'previewLogoPutihPage')">

                                        <div class="mt-3">
                                            <img id="previewLogoPutihPage"
                                                src="{{ isset($configuration) && $configuration->logo_putih_page ? asset($configuration->logo_putih_page) : '' }}"
                                                class="radius-8 border"
                                                style="max-width:100px; display:{{ isset($configuration) && $configuration->logo_putih_page ? 'block' : 'none' }};">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Logo Footer</label>
                                        <input class="form-control" type="file" name="logo_footer_page"
                                            accept="image/*" onchange="previewImage(event,'previewLogoFooterPage')">

                                        <div class="mt-3">
                                            <img id="previewLogoFooterPage"
                                                src="{{ isset($configuration) && $configuration->logo_footer_page ? asset($configuration->logo_footer_page) : '' }}"
                                                class="radius-8 border"
                                                style="max-width:100px; display:{{ isset($configuration) && $configuration->logo_footer_page ? 'block' : 'none' }};">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Favicon</label>
                                        <input class="form-control" type="file" name="favicon_page" accept="image/*"
                                            onchange="previewImage(event,'previewFaviconPage')">

                                        <div class="mt-3">
                                            <img id="previewFaviconPage"
                                                src="{{ isset($configuration) && $configuration->favicon_page ? asset($configuration->favicon_page) : '' }}"
                                                class="radius-8 border"
                                                style="max-height:80px; display:{{ isset($configuration) && $configuration->favicon_page ? 'block' : 'none' }};">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Company Profile (PDF)</label>
                                        <input class="form-control @error('company_profile_pdf') is-invalid @enderror"
                                            type="file" name="company_profile_pdf" accept="application/pdf,.pdf">

                                        @error('company_profile_pdf')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <small class="text-secondary-light d-block mt-2">
                                            Format PDF, maksimal 5 MB.
                                        </small>

                                        @if (!empty($configuration?->company_profile_pdf))
                                            <div class="mt-2 d-flex align-items-center gap-2">
                                                <a href="{{ asset($configuration->company_profile_pdf) }}" target="_blank"
                                                    rel="noopener" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i>
                                                    Lihat PDF Saat Ini
                                                </a>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                    <i class="bi bi-check-circle me-1"></i> PDF Terpasang
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- <div class="col-md-12">
                                        <label class="form-label">Off Canvas Page</label>
                                        <input type="text" name="off_canvas_page"
                                            value="{{ old('off_canvas_page', $configuration->off_canvas_page ?? '') }}"
                                            class="form-control">
                                    </div> --}}

                                    <div class="col-md-6">
                                        <label class="form-label">CopyRight</label>
                                        <input type="text" name="footer_page"
                                            value="{{ old('footer_page', $configuration->footer_page ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Teks Footer Page</label>
                                        <textarea name="text_footer_page" class="form-control" rows="3">{{ old('text_footer_page', $configuration->text_footer_page ?? '') }}</textarea>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <label class="form-label">Banner Setiap Halaman</label>
                                        <input class="form-control" type="file" name="banner" accept="image/*"
                                            onchange="previewImage(event,'previewBanner')">

                                        <div class="mt-3">
                                            <img id="previewBanner"
                                                src="{{ isset($configuration) && $configuration->banner ? asset($configuration->banner) : '' }}"
                                                class="radius-8 border"
                                                style="max-width:100px; display:{{ isset($configuration) && $configuration->banner ? 'block' : 'none' }};">
                                        </div>
                                    </div> --}}

                                </div>
                            </div>

                            <div class="tab-pane fade" id="cta">
                                <div class="row gy-3">

                                    {{-- <div class="col-md-6">
                                        <label class="form-label">Span </label>
                                        <input type="text" name="cta"
                                            value="{{ old('cta', $configuration->cta ?? '') }}" class="form-control">
                                    </div> --}}

                                    <div class="col-md-6">
                                        <label class="form-label">Judul</label>
                                        <input type="text" name="title_link_cta"
                                            value="{{ old('title_link_cta', $configuration->title_link_cta ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Background</label>
                                        <input class="form-control" type="file" name="banner_cta" accept="image/*"
                                            onchange="previewImage(event,'previewBannerCta')">

                                        <div class="mt-3">
                                            <img id="previewBannerCta"
                                                src="{{ isset($configuration) && $configuration->banner_cta ? asset($configuration->banner_cta) : '' }}"
                                                class="radius-8 border"
                                                style="max-width:100px; display:{{ isset($configuration) && $configuration->banner_cta ? 'block' : 'none' }};">
                                        </div>
                                    </div>

                                     <div class="col-md-6">
                                        <label class="form-label">Gambar 1</label>
                                        <input class="form-control" type="file" name="image_cta" accept="image/*"
                                            onchange="previewImage(event,'previewImageCta')">

                                        <div class="mt-3">
                                            <img id="previewImageCta"
                                                src="{{ isset($configuration) && $configuration->image_cta ? asset($configuration->image_cta) : '' }}"
                                                class="radius-8 border"
                                                style="max-width:100px; display:{{ isset($configuration) && $configuration->image_cta ? 'block' : 'none' }};">
                                        </div>
                                    </div>

                                     <div class="col-md-6">
                                        <label class="form-label">Gambar 2</label>
                                        <input class="form-control" type="file" name="image_cta_2" accept="image/*"
                                            onchange="previewImage(event,'previewImageCta2')">

                                        <div class="mt-3">
                                            <img id="previewImageCta2"
                                                src="{{ isset($configuration) && $configuration->image_cta_2 ? asset($configuration->image_cta_2) : '' }}"
                                                class="radius-8 border"
                                                style="max-width:100px; display:{{ isset($configuration) && $configuration->image_cta_2 ? 'block' : 'none' }};">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Link</label>
                                        <input type="text" name="link_cta"
                                            value="{{ old('link_cta', $configuration->link_cta ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Name Link</label>
                                        <input type="text" name="name_link_cta"
                                            value="{{ old('name_link_cta', $configuration->name_link_cta ?? '') }}"
                                            class="form-control">
                                    </div>

                                    {{-- <div class="col-md-12">
                                        <label class="form-label">Description</label>
                                        <textarea name="description_cta" class="form-control">{{ old('description_cta', $configuration->description_cta ?? '') }}</textarea>
                                    </div> --}}

                                </div>
                            </div>
                            <div class="tab-pane fade" id="social">
                                <div class="row gy-3">

                                    {{-- META INFORMATION --}}
                                    <div class="col-12">
                                        <h6 class="fw-semibold mb-0">Meta Information</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Meta Title</label>
                                        <input type="text" name="meta_title"
                                            value="{{ old('meta_title', $configuration->meta_title ?? '') }}"
                                            class="form-control" placeholder="Judul Meta Website">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Meta Author</label>
                                        <input type="text" name="meta_author"
                                            value="{{ old('meta_author', $configuration->meta_author ?? '') }}"
                                            class="form-control" placeholder="Nama Pembuat / Author">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Meta Subject</label>
                                        <input type="text" name="meta_subject"
                                            value="{{ old('meta_subject', $configuration->meta_subject ?? '') }}"
                                            class="form-control" placeholder="Subjek atau Topik Website">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Meta Description</label>
                                        <textarea name="meta_descriptions" class="form-control" rows="3"
                                            placeholder="Deskripsi ringkas website untuk mesin pencari...">{{ old('meta_descriptions', $configuration->meta_descriptions ?? '') }}</textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Meta Keywords</label>
                                        <textarea name="meta_keywords" class="form-control" rows="2"
                                            placeholder="kata kunci 1, kata kunci 2, kata kunci 3">{{ old('meta_keywords', $configuration->meta_keywords ?? '') }}</textarea>
                                    </div>

                                    {{-- URL CONFIGURATION --}}
                                    <div class="col-12 mt-4">
                                        <h6 class="fw-semibold mb-0">URL Configuration</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Base URL</label>
                                        <input type="url" name="base_url"
                                            value="{{ old('base_url', $configuration->base_url ?? '') }}"
                                            class="form-control" placeholder="https://example.com">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Site URL</label>
                                        <input type="url" name="site_url"
                                            value="{{ old('site_url', $configuration->site_url ?? '') }}"
                                            class="form-control" placeholder="https://example.com">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Canonical URL</label>
                                        <input type="url" name="canonical_url"
                                            value="{{ old('canonical_url', $configuration->canonical_url ?? '') }}"
                                            class="form-control" placeholder="https://example.com">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Meta Robots</label>
                                        <input type="text" name="robots"
                                            value="{{ old('robots', $configuration->robots ?? 'index, follow, max-image-preview:large') }}"
                                            class="form-control" placeholder="index, follow, max-image-preview:large">
                                        <small class="text-secondary-light d-block mt-1">
                                            Default: <code>index, follow, max-image-preview:large</code>
                                        </small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Language / Locale (OG Locale)</label>
                                        <input type="text" name="language"
                                            value="{{ old('language', $configuration->language ?? 'id_ID') }}"
                                            class="form-control" placeholder="id_ID">
                                        <small class="text-secondary-light d-block mt-1">
                                            Contoh: <code>id_ID</code> atau <code>en_US</code>
                                        </small>
                                    </div>

                                    {{-- OPEN GRAPH --}}
                                    <div class="col-12 mt-4">
                                        <h6 class="fw-semibold mb-0">Open Graph</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">OG Title</label>
                                        <input type="text" name="og_title"
                                            value="{{ old('og_title', $configuration->og_title ?? '') }}"
                                            class="form-control" placeholder="Judul untuk Open Graph">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">OG Site Name</label>
                                        <input type="text" name="og_site_name"
                                            value="{{ old('og_site_name', $configuration->og_site_name ?? '') }}"
                                            class="form-control" placeholder="Nama Situs / Brand">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">OG URL</label>
                                        <input type="url" name="og_url"
                                            value="{{ old('og_url', $configuration->og_url ?? '') }}"
                                            class="form-control" placeholder="https://example.com">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">OG Type</label>
                                        <select name="og_type" class="form-select">
                                            <option value="">Pilih OG Type</option>

                                            <option value="website"
                                                {{ old('og_type', $configuration->og_type ?? '') === 'website' ? 'selected' : '' }}>
                                                Website
                                            </option>

                                            <option value="article"
                                                {{ old('og_type', $configuration->og_type ?? '') === 'article' ? 'selected' : '' }}>
                                                Article
                                            </option>

                                            <option value="profile"
                                                {{ old('og_type', $configuration->og_type ?? '') === 'profile' ? 'selected' : '' }}>
                                                Profile
                                            </option>

                                            <option value="business.business"
                                                {{ old('og_type', $configuration->og_type ?? '') === 'business.business' ? 'selected' : '' }}>
                                                Business
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">OG Description</label>
                                        <textarea name="og_description" class="form-control" rows="4"
                                            placeholder="Deskripsi ringkas Open Graph saat dibagikan ke media sosial.">{{ old('og_description', $configuration->og_description ?? '') }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">OG Image</label>

                                        <input class="form-control" type="file" name="og_image" accept="image/*"
                                            onchange="previewImage(event, 'previewOgImage')">

                                        <div class="mt-3">
                                            <img id="previewOgImage"
                                                src="{{ isset($configuration) && $configuration->og_image ? asset($configuration->og_image) : '' }}"
                                                class="radius-8 border" alt="OG Image Preview"
                                                style="width: 180px; height: 95px; object-fit: cover;
                    {{ isset($configuration) && $configuration->og_image ? '' : 'display:none;' }}">
                                        </div>

                                        <small class="text-secondary-light d-block mt-2">
                                            Rekomendasi ukuran 1200 × 630 piksel.
                                        </small>
                                    </div>

                                    {{-- TWITTER CARD --}}
                                    <div class="col-12 mt-4">
                                        <h6 class="fw-semibold mb-0">Twitter Card</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Twitter Card Type</label>

                                        <select name="twitter_card" class="form-select">
                                            <option value="">Pilih Twitter Card</option>

                                            <option value="summary"
                                                {{ old('twitter_card', $configuration->twitter_card ?? '') === 'summary' ? 'selected' : '' }}>
                                                Summary
                                            </option>

                                            <option value="summary_large_image"
                                                {{ old('twitter_card', $configuration->twitter_card ?? '') === 'summary_large_image' ? 'selected' : '' }}>
                                                Summary Large Image
                                            </option>

                                            <option value="app"
                                                {{ old('twitter_card', $configuration->twitter_card ?? '') === 'app' ? 'selected' : '' }}>
                                                App
                                            </option>

                                            <option value="player"
                                                {{ old('twitter_card', $configuration->twitter_card ?? '') === 'player' ? 'selected' : '' }}>
                                                Player
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Twitter Site</label>
                                        <input type="text" name="twitter_site"
                                            value="{{ old('twitter_site', $configuration->twitter_site ?? '') }}"
                                            class="form-control" placeholder="@username">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Twitter Creator</label>
                                        <input type="text" name="twitter_creator"
                                            value="{{ old('twitter_creator', $configuration->twitter_creator ?? '') }}"
                                            class="form-control" placeholder="@username">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Twitter Title</label>
                                        <input type="text" name="twitter_title"
                                            value="{{ old('twitter_title', $configuration->twitter_title ?? '') }}"
                                            class="form-control" placeholder="Judul untuk Twitter Card">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Twitter Description</label>
                                        <textarea name="twitter_description" class="form-control" rows="4"
                                            placeholder="Deskripsi ringkas konten untuk Twitter Card.">{{ old('twitter_description', $configuration->twitter_description ?? '') }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Twitter Image</label>

                                        <input class="form-control" type="file" name="twitter_image" accept="image/*"
                                            onchange="previewImage(event, 'previewTwitterImage')">

                                        <div class="mt-3">
                                            <img id="previewTwitterImage"
                                                src="{{ isset($configuration) && $configuration->twitter_image ? asset($configuration->twitter_image) : '' }}"
                                                class="radius-8 border" alt="Twitter Image Preview"
                                                style="width: 180px; height: 95px; object-fit: cover;
                    {{ isset($configuration) && $configuration->twitter_image ? '' : 'display:none;' }}">
                                        </div>

                                        <small class="text-secondary-light d-block mt-2">
                                            Rekomendasi ukuran 1200 × 630 piksel.
                                        </small>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="geo">
                                <div class="row gy-3">

                                    <div class="col-md-6">
                                        <label>City</label>
                                        <input type="text" name="city"
                                            value="{{ old('city', $configuration->city ?? '') }}" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Country</label>
                                        <input type="text" name="country"
                                            value="{{ old('country', $configuration->country ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Latitude</label>
                                        <input type="text" name="geo_latitude"
                                            value="{{ old('geo_latitude', $configuration->geo_latitude ?? '') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Longitude</label>
                                        <input type="text" name="geo_longitude"
                                            value="{{ old('geo_longitude', $configuration->geo_longitude ?? '') }}"
                                            class="form-control">
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body text-end">
                    <a href="{{ route('dashboard-admin.configurations.create') }}"
                        class="btn btn-light me-2 px-32 py-12 radius-8">
                        Kembali
                    </a>
                    @can('edit pengaturan-website')
                        <button type="submit" id="btnSubmit" class="btn btn-primary px-32 py-12 radius-8">
                            Simpan Pengaturan
                        </button>
                    @endcan
                </div>
            </div>
        </div>

    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Ambil tab terakhir dari localStorage
            let activeTab = localStorage.getItem('activeConfigTab');

            // Jika ada tab tersimpan
            if (activeTab) {

                let triggerEl = document.querySelector(
                    `button[data-bs-target="${activeTab}"]`
                );

                if (triggerEl) {
                    let tab = new bootstrap.Tab(triggerEl);
                    tab.show();
                }
            }

            // Simpan tab aktif saat diklik
            let tabButtons = document.querySelectorAll(
                '#configTab button[data-bs-toggle="tab"]'
            );

            tabButtons.forEach(button => {

                button.addEventListener('shown.bs.tab', function(event) {

                    let target = event.target.getAttribute('data-bs-target');

                    localStorage.setItem('activeConfigTab', target);
                });

            });

        });
    </script>
@endsection
