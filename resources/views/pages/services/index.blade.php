@extends('layout.layout')

@php
    $title = 'Layanan';
    $subTitle = 'Layanan';
    $script = '<script>
        let table = new DataTable("#dataTable");
    </script>';
@endphp

@section('content')
    @include('pages.services.partials.theme')

    @php
        $activeTab = session('activeTab', old('active_tab', 'tab-layanan'));
    @endphp

    <div class="service-admin-page">
        <div class="card mb-3">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="serviceTab" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link {{ $activeTab === 'tab-layanan' ? 'active' : '' }}"
                            id="tab-layanan-button" data-bs-toggle="tab" data-bs-target="#tab-layanan" role="tab"
                            aria-controls="tab-layanan"
                            aria-selected="{{ $activeTab === 'tab-layanan' ? 'true' : 'false' }}">

                            Data Layanan
                        </button>
                    </li>
                    @can('lihat kategori-layanan')
                        {{-- Kategori layanan di-hide sementara sesuai arahan --}}
                        <li class="nav-item d-none" role="presentation">
                            <button type="button" class="nav-link {{ $activeTab === 'tab-category' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tab-category">Kategori Layanan</button>
                        </li>
                    @endcan

                    @can('edit label layanan')
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link {{ $activeTab === 'tab-label' ? 'active' : '' }}"
                                id="tab-label-button" data-bs-toggle="tab" data-bs-target="#tab-label" role="tab"
                                aria-controls="tab-label" aria-selected="{{ $activeTab === 'tab-label' ? 'true' : 'false' }}">

                                Label Layanan
                            </button>
                        </li>

                        {{-- <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link {{ $activeTab === 'tab-informasi' ? 'active' : '' }}"
                                id="tab-informasi-button" data-bs-toggle="tab" data-bs-target="#tab-informasi" role="tab"
                                aria-controls="tab-informasi"
                                aria-selected="{{ $activeTab === 'tab-informasi' ? 'true' : 'false' }}">

                                CTA Layanan
                            </button>
                        </li> --}}
                    @endcan
                </ul>
            </div>

            <div class="card-body tab-content" id="serviceTabContent">

                <div class="tab-pane fade {{ $activeTab === 'tab-layanan' ? 'show active' : '' }}" id="tab-layanan"
                    role="tabpanel" aria-labelledby="tab-layanan-button">
                </div>
                @can('lihat kategori-layanan')
                    <div class="tab-pane fade {{ $activeTab === 'tab-category' ? 'show active' : '' }}" id="tab-category">
                    </div>
                @endcan

                @can('edit label layanan')
                    <div class="tab-pane fade {{ $activeTab === 'tab-label' ? 'show active' : '' }}" id="tab-label"
                        role="tabpanel" aria-labelledby="tab-label-button">

                        <form action="{{ route('dashboard-admin.services.storeOrUpdate') }}" method="POST" enctype="multipart/form-data">

                            @csrf

                            <input type="hidden" name="active_tab" value="tab-label">

                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <label class="form-label">Sub Judul (Badge Hero)</label>
                                    <input type="text" name="subTitle" value="{{ old('subTitle', $label->subTitle ?? '') }}"
                                        class="form-control" placeholder="Contoh: Layanan & Solusi Kami">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Teks Sorotan (Span Berwarna)</label>
                                    <input type="text" name="span" value="{{ old('span', $label->span ?? '') }}"
                                        class="form-control" placeholder="Contoh: Solusi Terpadu">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Judul Utama Hero</label>
                                    <input type="text" name="title" value="{{ old('title', $label->title ?? '') }}"
                                        class="form-control" placeholder="Contoh: Untuk Transformasi Digital Perusahaan Anda">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Deskripsi Hero</label>
                                    <textarea name="description" class="form-control" rows="3"
                                        placeholder="Tuliskan deskripsi ringkas pengantar layanan...">{{ old('description', $label->description ?? '') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Gambar / Ilustrasi Hero (Kanan)</label>
                                    <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp"
                                        onchange="if (this.files && this.files[0]) { var r = new FileReader(); r.onload = function(e){ document.getElementById('preview-service-hero-img').src = e.target.result; document.getElementById('preview-service-hero-wrap').classList.remove('d-none'); }; r.readAsDataURL(this.files[0]); }">
                                    <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG, WEBP. Maks 5MB. Otomatis dikonversi ke WebP.</small>

                                    <div class="mt-3 {{ !empty($label->image) ? '' : 'd-none' }}" id="preview-service-hero-wrap">
                                        <label class="form-label small text-muted">Preview Gambar Saat Ini:</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <img id="preview-service-hero-img" src="{{ !empty($label->image) ? asset($label->image) : '' }}"
                                                alt="Preview Hero Layanan" class="rounded border shadow-sm" style="max-width: 240px; max-height: 150px; object-fit: cover;">
                                            @if (!empty($label->image))
                                                <div class="form-check">
                                                    <input type="checkbox" name="remove_image" value="1" id="remove_service_image" class="form-check-input">
                                                    <label for="remove_service_image" class="form-check-label text-danger small">Hapus gambar saat ini</label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" id="btnSubmit" class="btn btn-primary">
                                        Simpan Label
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade {{ $activeTab === 'tab-informasi' ? 'show active' : '' }}" id="tab-informasi"
                        role="tabpanel" aria-labelledby="tab-informasi-button">

                        <form action="{{ route('dashboard-admin.services.storeOrUpdate') }}" method="POST">

                            @csrf

                            <input type="hidden" name="active_tab" value="tab-informasi">

                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <label class="form-label">Sub Judul</label>

                                    <input type="text" name="subTitle_cta"
                                        value="{{ old('subTitle_cta', $label->subTitle_cta ?? '') }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Judul</label>

                                    <input type="text" name="title_cta"
                                        value="{{ old('title_cta', $label->title_cta ?? '') }}" class="form-control">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Deskripsi</label>

                                    <textarea name="description_cta" class="form-control" rows="3">{{ old('description_cta', $label->description_cta ?? '') }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nama Link</label>

                                    <input type="text" name="name_link"
                                        value="{{ old('name_link', $label->name_link ?? '') }}" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Link</label>

                                    <input type="text" name="link" value="{{ old('link', $label->link ?? '') }}"
                                        class="form-control">
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" id="btnSubmit2" class="btn btn-primary">

                                        Simpan CTA
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endcan
            </div>
        </div>

        @can('lihat kategori-layanan')
            @include('pages.services.partials.category-tab')
        @endcan

        <div class="card basic-data-table {{ $activeTab === 'tab-layanan' ? '' : 'd-none' }}" id="module-data-card">

            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="card-title mb-0">Layanan</h5>
                @can('buat layanan')
                    <a href="{{ route('dashboard-admin.services.create') }}"
                        class="btn btn-primary d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:round-add"></iconify-icon>
                        Tambah Layanan
                    </a>
                @endcan
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">

                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th width="80">Urutan</th>
                                {{-- <th>Gambar</th> --}}
                                <th>Ikon</th>
                                <th>Judul</th>
                                <th>Ringkasan</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($datas as $index => $item)
                                <tr>

                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-neutral-200 text-neutral-800">
                                            {{ $item->order ?? 0 }}
                                        </span>
                                    </td>

                                    {{-- <td>
                                        @if ($item->image)
                                            <img src="{{ asset($item->image) }}" width="100" class="rounded">
                                        @endif
                                    </td> --}}

                                    <td>
                                        @if ($item->icon)
                                            {!! $item->icon !!}
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column align-items-start gap-1">
                                            <strong>{{ Str::limit($item->title, 20) }}</strong>
                                            @if ($item->is_featured)
                                                <span
                                                    class="badge service-featured-badge bg-warning-focus text-warning-main px-10 py-4 radius-4">
                                                    Produk Unggulan
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        {{ Str::limit($item->overview, 20) }}
                                    </td>

                                    <td>
                                        @can('edit layanan')
                                            <button>
                                                <a href="{{ route('dashboard-admin.services.edit', $item->id) }}"
                                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle
                                                d-inline-flex align-items-center justify-content-center me-1"
                                                    aria-label="Edit {{ $item->title }}">

                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </a>
                                            </button>
                                        @endcan
                                        @can('hapus layanan')
                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('dashboard-admin.services.destroy', $item->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                    class="btn-delete-id w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0
                                        d-inline-flex align-items-center justify-content-center"
                                                    data-id="{{ $item->id }}">

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
    </div>
    @include('pages.partials.module-tabs-script')
@endsection
