@extends('layout.layout')

@php
    $title = 'Layanan';
    $subTitle = 'Edit Layanan';
@endphp

@section('content')
    @include('pages.services.partials.theme')

    <div class="service-admin-page">
        <form action="{{ route('dashboard-admin.services.update', $data->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('pages.services.partials.form-fields')

            <div class="card mt-4">
                <div class="card-body d-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('dashboard-admin.services.index') }}" class="btn btn-light">Kembali</a>
                    <button type="submit" id="btnUpdate" class="btn btn-primary px-32 py-12 radius-8">
                        Update Data
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
