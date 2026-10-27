@extends('layout.layout')

@php
    $title = 'Kategori Produk';
    $subTitle = 'Create Kategori Produk';
@endphp

@section('content')
    @include('pages.products.partials.theme')
    <div class="service-admin-page">
        <form action="{{ route('dashboard-admin.categorys-product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header"><h6 class="card-title mb-0">Informasi Kategori Produk</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Nama Tombol / Navbar <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: ERP">
                            <div class="service-form-help mt-1">Digunakan untuk tombol tab dan menu navbar.</div>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Judul Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Software ERP Terintegrasi untuk Seluruh Departemen Perusahaan">
                            <div class="service-form-help mt-1">Digunakan sebagai judul utama di card section produk.</div>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Overview (Navbar)</label>
                            <textarea class="form-control @error('overview') is-invalid @enderror" name="overview" rows="2" placeholder="Ringkasan singkat untuk tampilan dropdown menu produk di navbar...">{{ old('overview') }}</textarea>
                            <div class="service-form-help mt-1">Deskripsi singkat yang tampil di dropdown navbar.</div>
                            @error('overview')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Deskripsi lengkap yang tampil di card section produk...">{{ old('description') }}</textarea>
                            <div class="service-form-help mt-1">Deskripsi yang tampil di bawah judul card produk.</div>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        @include('pages.products.categorys.icon-field')

                        @include('pages.products.categorys.image-field')

                        <div class="col-md-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" min="0" class="form-control" name="order" value="{{ old('order', 0) }}">
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <div class="product-status-control w-100">
                                <span class="product-status-control__label"><strong>Status</strong><small>Tampilkan kategori</small></span>
                                <div class="form-check form-switch m-0">
                                    <input type="hidden" name="status" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body text-end">
                    <a href="{{ route('dashboard-admin.categorys-product.index') }}" class="btn btn-light me-2">Kembali</a>
                    <button type="submit" id="btnSubmit" class="btn btn-primary px-32 py-12 radius-8">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
@endsection
