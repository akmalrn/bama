@extends('layout.layout')

@php
    $title = 'Produk';
    $subTitle = 'Produk';
    $script = '<script>
        new DataTable("#dataTable");
    </script>';
@endphp

@section('content')
    @include('pages.products.partials.theme')
    @php($activeTab = session('activeTab', 'tab-data'))

   
    <div class="card mb-3" id="product-tab-container-card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'tab-data' ? 'active' : '' }}"
                        data-bs-toggle="tab" data-bs-target="#tab-products">Data Produk</button>
                </li>
                @can('lihat kategori-produk')
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'tab-category' ? 'active' : '' }}"
                            data-bs-toggle="tab" data-bs-target="#tab-category">Kategori Produk</button>
                    </li>
                @endcan
                @can('edit produk')
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'tab-label' ? 'active' : '' }}"
                            data-bs-toggle="tab" data-bs-target="#tab-label">Label Produk</button>
                    </li>
                @endcan
            </ul>
        </div>

        <div class="card-body tab-content {{ $activeTab === 'tab-label' ? '' : 'd-none' }}" id="product-label-card-body">
            <div class="tab-pane fade {{ $activeTab === 'tab-data' ? 'show active' : '' }}" id="tab-products"></div>
            @can('lihat kategori-produk')
                <div class="tab-pane fade {{ $activeTab === 'tab-category' ? 'show active' : '' }}" id="tab-category"></div>
            @endcan
            @can('edit produk')
                <div class="tab-pane fade {{ $activeTab === 'tab-label' ? 'show active' : '' }}" id="tab-label">
                    <form action="{{ route('dashboard-admin.products.storeOrUpdate') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="active_tab" value="tab-label">

                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label" for="product-label-subtitle">Sub Judul (Badge Hero)</label>
                                <input type="text" id="product-label-subtitle" name="subTitle"
                                    value="{{ old('subTitle', $label->subTitle ?? '') }}" class="form-control"
                                    placeholder="Contoh: Solusi & Produk Unggulan">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="product-label-span">Teks Sorotan (Span Berwarna)</label>
                                <input type="text" id="product-label-span" name="span"
                                    value="{{ old('span', $label->span ?? '') }}" class="form-control"
                                    placeholder="Contoh: Modul Enterprise">
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="product-label-title">Judul Utama Hero</label>
                                <input type="text" id="product-label-title" name="title"
                                    value="{{ old('title', $label->title ?? '') }}" class="form-control"
                                    placeholder="Contoh: Rangkaian Solusi ERP Terlengkap untuk Seluruh Lini Bisnis">
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="product-label-description">Deskripsi Hero</label>
                                <textarea id="product-label-description" name="description" class="form-control" rows="3"
                                    placeholder="Tuliskan deskripsi ringkas pengantar produk...">{{ old('description', $label->description ?? '') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Gambar / Ilustrasi Hero (Kanan)</label>
                                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp"
                                    onchange="if (this.files && this.files[0]) { var r = new FileReader(); r.onload = function(e){ document.getElementById('preview-product-hero-img').src = e.target.result; document.getElementById('preview-product-hero-wrap').classList.remove('d-none'); }; r.readAsDataURL(this.files[0]); }">
                                <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG, WEBP. Maks 5MB. Otomatis dikonversi ke WebP.</small>

                                <div class="mt-3 {{ !empty($label->image) ? '' : 'd-none' }}" id="preview-product-hero-wrap">
                                    <label class="form-label small text-muted">Preview Gambar Saat Ini:</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img id="preview-product-hero-img" src="{{ !empty($label->image) ? asset($label->image) : '' }}"
                                            alt="Preview Hero Produk" class="rounded border shadow-sm" style="max-width: 240px; max-height: 150px; object-fit: cover;">
                                        @if (!empty($label->image))
                                            <div class="form-check">
                                                <input type="checkbox" name="remove_image" value="1" id="remove_product_image" class="form-check-input">
                                                <label for="remove_product_image" class="form-check-label text-danger small">Hapus gambar saat ini</label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-end">
                                <button type="submit" id="btnUpdate" class="btn btn-primary">Simpan Label</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endcan
        </div>
    </div>

    <div class="service-admin-page">
       
        <div class="card basic-data-table" id="module-data-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="card-title mb-0">Produk</h5>
                @can('buat produk')
                    <a href="{{ route('dashboard-admin.products.create') }}"
                        class="btn btn-primary d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:round-add"></iconify-icon> Tambah Produk
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>Icon</th>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Ringkasan</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if ($item->icon)
                                            <div class="fs-4 d-inline-flex align-items-center justify-content-center text-primary">
                                                {!! $item->icon !!}
                                            </div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->image)
                                            <img src="{{ asset($item->image) }}" width="100" class="rounded"
                                                alt="{{ $item->title }}">
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column align-items-start gap-1">
                                            <strong>{{ Str::limit($item->title, 30) }}</strong>
                                            @if ($item->is_featured)
                                                <span
                                                    class="badge service-featured-badge bg-warning-focus text-warning-main px-10 py-4 radius-4">Produk
                                                    Unggulan</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $item->category?->name ?: ($item->category?->title ?? '-') }}</td>
                                    <td>{{ Str::limit($item->overview, 30) }}</td>
                                    <td>
                                        @can('edit produk')
                                            <a href="{{ route('dashboard-admin.products.edit', $item->id) }}"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                                                aria-label="Edit {{ $item->title }}">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </a>
                                        @endcan
                                        @can('hapus produk')
                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('dashboard-admin.products.destroy', $item->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn-delete-id w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center"
                                                    data-id="{{ $item->id }}"
                                                    aria-label="Hapus {{ $item->title }}">
                                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       
        @can('lihat kategori-produk')
            <div class="card basic-data-table {{ $activeTab === 'tab-category' ? '' : 'd-none' }}" id="module-category-card">
                @include('pages.products.partials.category-tab')
            </div>
        @endcan
    </div>

    @include('pages.partials.module-tabs-script')
@endsection