@extends('layout.layout')

@php
    $title = 'Blogs';
    $subTitle = 'Create Blog';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Blog Information</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Image<span class="text-danger">*</span></label>

                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    onchange="previewImage(event, 'preview_image')">

                                <small class="text-secondary-light d-block mt-2">
                                    Format: JPG, JPEG, PNG, WEBP
                                </small>

                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="mt-2">
                                    <img id="preview_image" class="img-fluid rounded border d-none" width="250">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Judul<span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror">

                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori<span class="text-danger">*</span></label>
                                <select name="id_category" class="form-control @error('id_category') is-invalid @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('id_category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('id_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Tanggal & Jam Publish (Optional)
                                </label>
                                <input type="datetime-local" name="published_at" value="{{ old('published_at') }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Overview<span class="text-danger">*</span></label>
                                <textarea name="overview" class="form-control @error('overview') is-invalid @enderror" rows="2">{{ old('overview') }}</textarea>

                                @error('overview')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description<span class="text-danger">*</span></label>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @include('components.ckeditor', ['id' => 'description'])
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Meta Keywords<span class="text-danger">*</span></label>
                                <textarea name="meta_keywords" rows="3" class="form-control @error('meta_keywords') is-invalid @enderror">{{ old('meta_keywords') }}</textarea>

                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Meta Descriptions<span class="text-danger">*</span></label>
                                <textarea name="meta_descriptions" rows="3" class="form-control @error('meta_descriptions') is-invalid @enderror">{{ old('meta_descriptions') }}</textarea>

                                @error('meta_descriptions')
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

                        <a href="{{ route('dashboard-admin.blogs.index') }}" class="btn btn-light me-2">
                            Kembali
                        </a>

                        <button type="submit" id="btnSubmit" class="btn btn-primary px-32 py-12 radius-8">
                            Simpan Blog
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection
