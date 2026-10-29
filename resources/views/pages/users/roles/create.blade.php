@extends('layout.layout')

@php
    $title = 'Role';
    $subTitle = 'Create Role';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.roles.store') }}" method="POST">
        @csrf

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Role Information</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-md-12">
                                <label class="form-label">Nama Role</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Masukan Nama Role">

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Permission</label>

                                @php
                                    // Group permission berdasarkan kata terakhir
                                    $groupedPermissions = $permissions->groupBy(function ($permission) {
                                        $parts = explode(' ', $permission->name);
                                        return end($parts); // ambil kata terakhir (asset, lokasi, dll)
                                    });
                                @endphp

                                <div class="row">
                                    @foreach ($groupedPermissions as $groupName => $groupPermissions)
                                        <div class="col-md-6 mb-4">

                                            <div class="border rounded p-3 h-100">

                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <strong>{{ ucfirst($groupName) }}</strong>

                                                    <div
                                                        class="form-check d-flex align-items-center border rounded px-3 py-2 select-all-box">
                                                        <input type="checkbox"
                                                            class="form-check-input me-2 select-all-group"
                                                            data-group="group-{{ Str::slug($groupName) }}">

                                                        <label class="form-check-label mb-0 fw-semibold"
                                                            for="select-all-{{ Str::slug($groupName) }}">
                                                            Pilih Semuanya
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="row group-{{ Str::slug($groupName) }}">
                                                    @foreach ($groupPermissions as $permission)
                                                        <div class="col-12 mb-2">
                                                            <div
                                                                class="form-check d-flex align-items-center border rounded px-3 py-2 permission-item">
                                                                <input class="form-check-input me-2 permission-checkbox"
                                                                    type="checkbox" name="permissions[]"
                                                                    value="{{ $permission->name }}"
                                                                    id="permission-{{ $permission->id }}"
                                                                    {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>

                                                                <label class="form-check-label flex-grow-1 mb-0"
                                                                    for="permission-{{ $permission->id }}">
                                                                    {{ ucwords(str_replace('-', ' ', $permission->name)) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @error('permissions')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body text-end">
                        <a href="{{ route('dashboard-admin.roles.index') }}"
                            class="btn btn-light me-2 px-32 py-12 radius-8">
                            Kembali
                        </a>

                        <button type="submit" id="btnSubmit" class="btn btn-primary px-32 py-12 radius-8">
                            Simpan Role
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // SELECT ALL
            document.querySelectorAll(".select-all-group").forEach(function(selectAll) {
                selectAll.addEventListener("change", function() {

                    let groupClass = this.dataset.group;
                    let checkboxes = document.querySelectorAll("." + groupClass +
                        " .permission-checkbox");

                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAll.checked;
                    });

                });
            });


            // AUTO CHECK SELECT ALL
            document.querySelectorAll(".permission-checkbox").forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {

                    let group = this.closest('[class*="group-"]');
                    let groupClass = [...group.classList].find(c => c.startsWith('group-'));

                    let allCheckboxes = document.querySelectorAll("." + groupClass +
                        " .permission-checkbox");
                    let selectAll = document.querySelector('.select-all-group[data-group="' +
                        groupClass + '"]');

                    let allChecked = true;

                    allCheckboxes.forEach(function(cb) {
                        if (!cb.checked) {
                            allChecked = false;
                        }
                    });

                    if (selectAll) {
                        selectAll.checked = allChecked;
                    }

                });
            });

        });
    </script>
@endsection
