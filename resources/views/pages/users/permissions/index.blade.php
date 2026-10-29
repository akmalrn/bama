@extends('layout.layout')

@php
    $title = 'Permission Table';
    $subTitle = 'Permission Table';
    $script = '<script>
        let table = new DataTable("#dataTable");
    </script>';
@endphp

@section('content')
    <div class="card basic-data-table">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">Permission Table</h5>

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('dashboard-admin.permissions.trash.list') }}"
                    class="btn btn-outline-danger d-flex align-items-center gap-2">
                    <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                    Trash
                </a>

                @can('buat permission')
                    <a href="{{ route('dashboard-admin.permissions.create') }}"
                        class="btn btn-primary d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:round-add"></iconify-icon>
                        Tambah Permission
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">

                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Permission</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($permissions as $index => $permission)
                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $permission->name }}</strong>
                                </td>

                                <td>
                                    @can('edit permission')
                                        <button>
                                            <a href="{{ route('dashboard-admin.permissions.edit', $permission->id) }}"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle 
            d-inline-flex align-items-center justify-content-center me-1">

                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </a>
                                        </button>
                                    @endcan

                                    @can('hapus permission')
                                        <div class="dropdown d-inline">

                                            <button
                                                class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0
                d-inline-flex align-items-center justify-content-center"
                                                type="button" data-bs-toggle="dropdown">

                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>

                                            <ul class="dropdown-menu">

                                                @if ($permission->roles->count() > 0)
                                                    <li>
                                                        <span class="dropdown-item text-secondary-light d-block mt-2">
                                                            🚫 Dipakai {{ $permission->roles->count() }} role
                                                        </span>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button"
                                                            class="dropdown-item text-warning btn-soft-delete"
                                                            data-url="{{ route('dashboard-admin.permissions.trash', $permission->id) }}">
                                                            <i class="bi bi-archive me-2"></i> Trash
                                                        </button>
                                                    </li>

                                                    <li>
                                                        <button type="button"
                                                            class="dropdown-item text-danger btn-force-delete"
                                                            data-url="{{ route('dashboard-admin.permissions.forceDelete', $permission->id) }}">
                                                            <i class="bi bi-x-circle me-2"></i> Hapus Permanen
                                                        </button>
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>
@endsection
