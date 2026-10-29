@extends('layout.layout')

@php
    $title = 'User Management ';
    $subTitle = 'User Management ';
    $script = '<script>
        let table = new DataTable("#dataTable");
    </script>';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

            <h5 class="card-title mb-0">User Management </h5>

            <div class="d-flex align-items-center gap-2">

                <a href="{{ route('dashboard-admin.users.trash.list') }}" class="btn btn-outline-danger d-flex align-items-center gap-2">
                    <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                    Trash
                </a>
                @can('buat user')
                    <a href="{{ route('dashboard-admin.users.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:round-add"></iconify-icon>
                        Tambah User
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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $user->name }}</strong>
                                </td>

                                <td>{{ $user->email }}</td>

                                <td>
                                    @if ($user->roles->count())
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-info-main">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-secondary-light d-block mt-2">-</span>
                                    @endif
                                </td>

                                <td>
                                    @can('impersonate user')
                                        <form action="{{ route('dashboard-admin.impersonate', $user->id) }}" method="GET"
                                            class="d-inline impersonate-form">

                                            <button type="button"
                                                class="w-32-px h-32-px bg-info-focus text-info-main rounded-circle border-0 
                                         d-inline-flex align-items-center justify-content-center btn-impersonate">

                                                <iconify-icon icon="solar:login-3-bold"></iconify-icon>
                                            </button>

                                        </form>
                                    @endcan
                                    @can('edit user')
                                        <button>
                                            <a href="{{ route('dashboard-admin.users.edit', $user->id) }}"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </a>
                                        </button>
                                    @endcan
                                    @can('hapus user')
                                        <div class="dropdown d-inline">
                                            <button
                                                class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 
                                         d-inline-flex align-items-center justify-content-center"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>

                                            <ul class="dropdown-menu">

                                                <li>
                                                    <button type="button" class="dropdown-item text-warning btn-soft-delete"
                                                        data-url="{{ route('dashboard-admin.users.trash', $user->id) }}">
                                                        <i class="bi bi-archive"></i> Trash (Soft Delete)
                                                    </button>
                                                </li>

                                                <li>
                                                    <button type="button" class="dropdown-item text-danger btn-force-delete"
                                                        data-url="{{ route('dashboard-admin.users.forceDelete', $user->id) }}">
                                                        <i class="bi bi-x-circle"></i> Hapus Permanen
                                                    </button>
                                                </li>

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
