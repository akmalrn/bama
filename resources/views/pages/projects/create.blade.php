@extends('layout.layout')

@php
    $title = 'Perbandingan';
    $subTitle = 'Tambah Perbandingan';
@endphp

@section('content')
    @include('pages.projects.partials.theme')
    <div class="service-admin-page">
        <form action="{{ route('dashboard-admin.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('pages.projects.partials.form-fields')
            <div class="card mt-4 product-form-actions">
                <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <span class="text-secondary small">Pastikan data utama dan section perbandingan sudah terisi dengan benar.</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dashboard-admin.projects.index') }}" class="btn btn-light">Kembali</a>
                        <button type="submit" id="btnSubmit" class="btn btn-primary px-32 py-12 radius-8">Simpan Data</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
