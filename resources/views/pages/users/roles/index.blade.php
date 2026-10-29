@extends('layout.layout')

@php
    $title = 'Role Table';
    $subTitle = 'Role Table';
    $script = '<script>
        let table = new DataTable("#dataTable");
    </script>';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">Role Table</h5>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('dashboard-admin.roles.trash.list') }}" class="btn btn-outline-danger d-flex align-items-center gap-2">
                    <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                    Trash
                </a>
                @can('buat role')
                    <a href="{{ route('dashboard-admin.roles.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:round-add"></iconify-icon>
                        Tambah Role
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
                            <th>Nama Role</th>
                            <th>Jumlah Permission</th>
                            <th>Permission</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($roles as $index => $role)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $role->name }}</strong>
                                    @if ($role->isGodAdmin())
                                        <span class="badge bg-danger-600 text-white ms-1 px-2 py-1">
                                            <iconify-icon icon="solar:crown-bold" class="me-1"></iconify-icon>Root / God Admin
                                        </span>
                                    @elseif ($role->isCore())
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
                                    @if ($role->permissions->count())
                                        <button class="btn btn-sm btn-light" data-bs-toggle="collapse"
                                            data-bs-target="#permission-{{ $role->id }}">
                                            Lihat Permission
                                        </button>

                                        <div class="collapse mt-2" id="permission-{{ $role->id }}">
                                            @foreach ($role->permissions as $permission)
                                                <span class="badge bg-info-main mb-1">
                                                    {{ $permission->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-secondary-light d-block mt-2">Tidak ada permission</span>
                                    @endif
                                </td>

                                <td>
                                    @can('edit role')
                                        <a href="{{ route('dashboard-admin.roles.edit', $role->id) }}"
                                            class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle 
                                             d-inline-flex align-items-center justify-content-center me-1"
                                            title="Kelola Role & Permission">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </a>
                                    @endcan

                                    @if ($role->isGodAdmin())
                                        <span class="badge bg-secondary-focus text-secondary-main py-1 px-2" title="Role God Admin tidak dapat dihapus">
                                            <iconify-icon icon="solar:lock-bold" class="me-1"></iconify-icon>Protected
                                        </span>
                                    @elseif ($role->isCore() && !($isGodAdmin ?? false))
                                        <span class="badge bg-warning-focus text-warning-main py-1 px-2" title="Role inti sistem terkunci. Hanya God Admin yang berhak menghapus.">
                                            <iconify-icon icon="solar:lock-bold" class="me-1"></iconify-icon>Kunci (God Admin)
                                        </span>
                                    @else
                                        @can('hapus role')
                                            <div class="dropdown d-inline">
                                                <button
                                                    class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0
                                                        d-inline-flex align-items-center justify-content-center"
                                                    type="button" data-bs-toggle="dropdown" title="Opsi Hapus">
                                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                </button>

                                                <ul class="dropdown-menu">
                                                    @if ($role->users->count() > 0)
                                                        <li>
                                                            <span class="dropdown-item text-secondary-light d-block mt-2">
                                                                Role sedang dipakai {{ $role->users->count() }} user
                                                            </span>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <button type="button"
                                                                class="dropdown-item text-warning btn-soft-delete"
                                                                data-url="{{ route('dashboard-admin.roles.trash', $role->id) }}">
                                                                <i class="bi bi-archive"></i> Trash (Soft Delete)
                                                            </button>
                                                        </li>

                                                        <li>
                                                            <button type="button"
                                                                class="dropdown-item text-danger btn-force-delete"
                                                                data-url="{{ route('dashboard-admin.roles.forceDelete', $role->id) }}">
                                                                <i class="bi bi-x-circle"></i> Hapus Permanen
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endcan
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
