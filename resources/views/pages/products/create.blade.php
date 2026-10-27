@extends('layout.layout')

@php
    $title = 'Produk';
    $subTitle = 'Create Produk';
@endphp

@section('content')
    @include('pages.products.partials.theme')
    <div class="service-admin-page">
        <form action="{{ route('dashboard-admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('pages.products.partials.form-fields')
            <div class="card mt-4 product-form-actions">
                <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <span class="text-secondary small">Pastikan data utama dan status produk sudah benar.</span>
                    <div class="d-flex gap-2">
                    <a href="{{ route('dashboard-admin.products.index') }}" class="btn btn-light">Kembali</a>
                    <button type="submit" id="btnSubmit" class="btn btn-primary px-32 py-12 radius-8">Simpan Data</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
