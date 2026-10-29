@extends('layout.layout')

@php
    $title = 'Role Trash';
    $subTitle = 'Deleted Roles';
    $script = '<script>
        let table = new DataTable("#dataTable");
    </script>';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">Trash Roles</h5>

            <a href="{{ route('dashboard-admin.roles.index') }}" class="btn btn-light d-flex align-items-center gap-2">
                <iconify-icon icon="mdi:arrow-left"></iconify-icon>
                Kembali
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Role</th>
                            <th>Jumlah Permission</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($roles as $index => $role)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $role->name }}</strong>
                                    @if ($role->isCore())
                                        <span class="badge bg-warning-focus text-warning-main border border-warning-main px-2 py-1 ms-1">
                                            <iconify-icon icon="solar:shield-check-bold" class="me-1"></iconify-icon>Core Role
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-primary-600">
                                        {{ $role->permissions->count() }}
                                    </span>
                                </td>

                                <td>
                                    @if ($role->isCore() && !($isGodAdmin ?? false))
                                        <span class="badge bg-warning-focus text-warning-main py-1 px-2" title="Hanya God Admin yang dapat mengelola role inti di trash">
                                            <iconify-icon icon="solar:lock-bold" class="me-1"></iconify-icon>Kunci (God Admin)
                                        </span>
                                    @else
                                        {{-- Restore --}}
                                        <button type="button"
                                            class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle border-0 
                                               d-inline-flex align-items-center justify-content-center me-1 btn-restore"
                                            data-url="{{ route('dashboard-admin.roles.restore', $role->id) }}"
                                            title="Pulihkan Role">
                                            <iconify-icon icon="mdi:restore"></iconify-icon>
                                        </button>

                                        {{-- Force Delete --}}
                                        <button type="button"
                                            class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 
                                               d-inline-flex align-items-center justify-content-center btn-force-delete"
                                            data-url="{{ route('dashboard-admin.roles.forceDelete', $role->id) }}"
                                            title="Hapus Permanen">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
