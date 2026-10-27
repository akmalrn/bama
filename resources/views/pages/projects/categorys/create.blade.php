@extends('layout.layout')

@php
    $title = 'Kategori Projek';
    $subTitle = 'Create Kategori Projek';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.categorys-project.store') }}" method="POST">
        @csrf

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Informasi Kategori Projek</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-12 col-md-6">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror">

                                @error('name')
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
                        <a href="{{ route('dashboard-admin.categorys-project.index') }}" class="btn btn-light me-2">
                            Kembali
                        </a>

                        <button type="submit"  id="btnSubmit" class="btn btn-primary px-32 py-12 radius-8">
                            Simpan Data
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection
