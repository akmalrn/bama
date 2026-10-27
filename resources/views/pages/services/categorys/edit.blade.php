@extends('layout.layout')

@php
    $title = 'Kategori Layanan';
    $subTitle = 'Layanan - Edit Kategori Layanan';
@endphp

@section('content')
    @include('pages.services.partials.theme')

    <div class="service-admin-page">
    <form action="{{ route('dashboard-admin.categorys-service.update', $category->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Edit Kategori Layanan</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-12 col-md-6">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" name="title" value="{{ old('title', $category->title) }}"
                                    class="form-control @error('title') is-invalid @enderror">

                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body text-end">
                        <a href="{{ route('dashboard-admin.categorys-service.index') }}" class="btn btn-light me-2">
                            Kembali
                        </a>

                        <button type="submit" id="btnUpdate" class="btn btn-primary px-32 py-12 radius-8">
                            Update Data
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
    </div>
@endsection
