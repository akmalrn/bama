@extends('layout.layout')

@php
    $title = 'Kategori Blog';
    $subTitle = 'Edit Kategori';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.categorys-blog.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Edit Kategori</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-md-12">
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

                        <a href="{{ route('dashboard-admin.categorys-blog.index') }}" class="btn btn-light me-2">
                            Kembali
                        </a>

                        <button type="submit" id="btnUpdate" class="btn btn-primary px-32 py-12 radius-8">
                            Update Kategori
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </form>


    <script>
        document.querySelector('input[name="name"]').addEventListener('keyup', function() {
            let slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');

            document.querySelector('input[name="slug"]').value = slug;
        });
    </script>
@endsection
