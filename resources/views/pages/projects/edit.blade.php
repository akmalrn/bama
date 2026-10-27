@extends('layout.layout')

@php
    $title = 'Perbandingan';
    $subTitle = 'Edit Perbandingan';
@endphp

@section('content')
    @include('pages.projects.partials.theme')
    <div class="service-admin-page">
        <form action="{{ route('dashboard-admin.projects.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('pages.projects.partials.form-fields')
            <div class="card mt-4 product-form-actions">
                <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <span class="text-secondary small">Perubahan seluruh section perbandingan disimpan bersamaan.</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dashboard-admin.projects.index') }}" class="btn btn-light">Kembali</a>
                        <button type="submit" id="btnUpdate" class="btn btn-primary px-32 py-12 radius-8">Update Data</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
