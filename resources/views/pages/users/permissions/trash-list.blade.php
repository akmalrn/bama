@extends('layout.layout')

@php
    $title = 'Permission Trash';
    $subTitle = 'Deleted Permissions';
    $script = '<script>
        let table = new DataTable("#dataTable");
    </script>';
@endphp

@section('content')
    <div class="card basic-data-table">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">Trash Permissions</h5>

            <a href="{{ route('permissions.index') }}" class="btn btn-light d-flex align-items-center gap-2">
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
                            <th>Nama Permission</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($permissions as $index => $permission)
                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $permission->name }}</strong>
                                </td>

                                <td>

                                    {{-- Restore --}}
                                    <button type="button"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle border-0 
                                           d-inline-flex align-items-center justify-content-center me-1 btn-restore"
                                        data-url="{{ route('permissions.restore', $permission->id) }}">

                                        <iconify-icon icon="mdi:restore"></iconify-icon>
                                    </button>

                                    {{-- Force Delete --}}
                                    <button type="button"
                                        class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 
                                           d-inline-flex align-items-center justify-content-center btn-force-delete"
                                        data-url="{{ route('permissions.forceDelete', $permission->id) }}">

                                        <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                    </button>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-secondary-light d-block mt-2">
                                    Tidak ada permission di trash
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>
@endsection
