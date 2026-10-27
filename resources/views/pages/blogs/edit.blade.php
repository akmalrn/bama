@extends('layout.layout')

@php
    $title = 'Blogs';
    $subTitle = 'Edit Blog';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Edit Blog</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Image</label>

                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    onchange="previewImage(event, 'preview_image')">

                                <small class="text-secondary-light d-block mt-2">
                                    Kosongkan jika tidak ingin mengganti gambar
                                </small>

                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="mt-2">
                                    <img id="preview_image" src="{{ asset($blog->image) }}" class="img-fluid rounded border"
                                        width="250">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Judul</label>
                                <input type="text" name="title" value="{{ old('title', $blog->title) }}"
                                    class="form-control @error('title') is-invalid @enderror">

                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select name="id_category" class="form-control @error('id_category') is-invalid @enderror">

                                    <option value="">-- Pilih Kategori --</option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('id_category', $blog->id_category) == $category->id ? 'selected' : '' }}>
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
                                <input type="datetime-local" name="published_at"
                                    value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"
                                    class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Overview</label>
                                <textarea name="overview" class="form-control @error('overview') is-invalid @enderror" rows="2">{{ old('overview', $blog->overview) }}</textarea>

                                @error('overview')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Description<span class="text-danger">*</span></label>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description', $blog->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @include('components.ckeditor', ['id' => 'description'])
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Meta Keywords</label>
                                <textarea name="meta_keywords" rows="3" class="form-control @error('meta_keywords') is-invalid @enderror">{{ old('meta_keywords', $blog->meta_keywords) }}</textarea>

                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Meta Descriptions</label>
                                <textarea name="meta_descriptions" rows="3" class="form-control @error('meta_descriptions') is-invalid @enderror">{{ old('meta_descriptions', $blog->meta_descriptions) }}</textarea>

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

                        <button type="submit" id="btnUpdate" class="btn btn-primary px-32 py-12 radius-8">
                            Update Blog
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection
