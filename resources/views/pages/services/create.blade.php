@extends('layout.layout')

@php
    $title = 'Layanan';
    $subTitle = 'Create Layanan';
@endphp

@section('content')
    @include('pages.services.partials.theme')

    <div class="service-admin-page">
        <form action="{{ route('dashboard-admin.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('pages.services.partials.form-fields')

            <div class="card mt-4">
                <div class="card-body d-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('dashboard-admin.services.index') }}" class="btn btn-light">Kembali</a>
                    <button type="submit" id="btnSubmit" class="btn btn-primary px-32 py-12 radius-8">
                        Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
