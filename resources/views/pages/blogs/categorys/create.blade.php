@extends('layout.layout')

@php
    $title = 'Kategori Blog';
    $subTitle = 'Create Kategori';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.categorys-blog.store') }}" method="POST">
        @csrf

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Kategori Information</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-md-12">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" name="title" value="{{ old('title') }}"
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

                        <a href="{{ route('dashboard-admin.categorys-blog.index') }}" class="btn btn-light me-2">
                            Kembali
                        </a>

                        <button type="submit" id="btnSubmit" class="btn btn-primary px-32 py-12 radius-8">
                            Simpan Kategori
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection
