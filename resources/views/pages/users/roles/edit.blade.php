@extends('layout.layout')

@php
    $title = 'Role';
    $subTitle = 'Edit Role';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

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
                                @if ($role->isCore())
                                    <div class="input-group">
                                        <input type="text" name="name" value="{{ $role->name }}"
                                            class="form-control bg-light text-muted fw-semibold" readonly>
                                        <span class="input-group-text bg-warning-focus text-warning-main border-warning-main">
                                            <iconify-icon icon="solar:lock-bold" class="me-1"></iconify-icon>Dikunci
                                        </span>
                                    </div>
                                    <small class="text-warning d-flex align-items-center gap-1 mt-2">
                                        <iconify-icon icon="solar:shield-warning-bold"></iconify-icon>
                                        <strong>Role Inti Sistem:</strong> Nama role ini merupakan pondasi arsitektur sistem dan tidak dapat diubah. Anda tetap dapat mengonfigurasi permission di bawah.
                                    </small>
                                @else
                                    <input type="text" name="name" value="{{ old('name', $role->name) }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Masukan Nama Role">

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endif
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Permission</label>

                                @php
                                    use Illuminate\Support\Str;

                                    $groupedPermissions = $permissions->groupBy(function ($permission) {
                                        $parts = explode(' ', $permission->name);
                                        return end($parts);
                                    });

                                    $rolePermissions = $role->permissions->pluck('name')->toArray();
                                @endphp

                                <div class="row">
                                    @foreach ($groupedPermissions as $groupName => $groupPermissions)
                                        <div class="col-md-6 mb-4">
                                            <div class="border rounded p-3 h-100">

                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <strong>{{ ucfirst($groupName) }}</strong>

                                                    <div
                                                        class="form-check d-flex align-items-center border rounded px-3 py-2">
                                                        <input type="checkbox"
                                                            class="form-check-input me-2 select-all-group"
                                                            data-group="group-{{ Str::slug($groupName) }}">

                                                        <label class="form-check-label mb-0 fw-semibold">
                                                            Pilih Semuanya
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="row group-{{ Str::slug($groupName) }}">
                                                    @foreach ($groupPermissions as $permission)
                                                        <div class="col-12 mb-2">
                                                            <div
                                                                class="form-check d-flex align-items-center border rounded px-3 py-2">

                                                                <input class="form-check-input me-2 permission-checkbox"
                                                                    type="checkbox" name="permissions[]"
                                                                    value="{{ $permission->name }}"
                                                                    id="permission-{{ $permission->id }}"
                                                                    {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>

                                                                <label class="form-check-label flex-grow-1 mb-0"
                                                                    for="permission-{{ $permission->id }}">
                                                                    {{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}
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

                        <button type="submit" id="btnUpdate" class="btn btn-primary px-32 py-12 radius-8">
                            Perbarui Role
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function updateSelectAll(groupClass) {
                let allCheckboxes = document.querySelectorAll("." + groupClass + " .permission-checkbox");
                let selectAll = document.querySelector('.select-all-group[data-group="' + groupClass + '"]');

                let allChecked = true;

                allCheckboxes.forEach(function(cb) {
                    if (!cb.checked) {
                        allChecked = false;
                    }
                });

                if (selectAll) {
                    selectAll.checked = allChecked;
                }
            }

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

            document.querySelectorAll(".permission-checkbox").forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {

                    let group = this.closest('[class*="group-"]');
                    let groupClass = [...group.classList].find(c => c.startsWith('group-'));

                    updateSelectAll(groupClass);

                });
            });

            document.querySelectorAll(".select-all-group").forEach(function(selectAll) {
                let groupClass = selectAll.dataset.group;
                updateSelectAll(groupClass);
            });

        });
    </script>
@endsection
