@extends('layout.layout')

@php
    $title = 'Permission';
    $subTitle = 'Create Permission';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.permissions.store') }}" method="POST">
        @csrf

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Permission Information</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">                            
                            <div class="col-md-12">
                                <label class="form-label">Nama Permission</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Contoh: user_create">

                                <small class="text-secondary-light d-block mt-2">
                                    Gunakan format: module_action (contoh: user_create)
                                </small>

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
                        <a href="{{ route('dashboard-admin.permissions.index') }}" class="btn btn-light me-2">
                            Kembali
                        </a>

                        <button type="submit" id="btnSubmit" class="btn btn-primary">
                            Simpan Permission
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </form>
@endsection
