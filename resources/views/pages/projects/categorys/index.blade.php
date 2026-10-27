@extends('layout.layout')

@php
    $title = 'Kategori Projek';
    $subTitle = 'Projek - Kategori Projek';
    $script = '<script>
        let table = new DataTable("#dataTable");
    </script>';
@endphp

@section('content')
    <div class="card basic-data-table">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">Kategori Projek</h5>
            @can('buat kategori-portofolio')
                <a href="{{ route('dashboard-admin.categorys-project.create') }}"
                    class="btn btn-primary d-flex align-items-center gap-2">
                    <iconify-icon icon="ic:round-add"></iconify-icon>
                    Tambah Data
                </a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">

                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Kategori</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categories as $index => $category)
                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $category->name }}</strong>
                                </td>

                                <td>
                                    @can('edit kategori-portofolio')
                                        <button>
                                            <a href="{{ route('dashboard-admin.categorys-project.edit', $category->id) }}"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle 
                                            d-inline-flex align-items-center justify-content-center me-1">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </a>
                                        </button>
                                    @endcan
                                    @can('hapus kategori-portofolio')
                                        <form id="delete-form-{{ $category->id }}"
                                            action="{{ route('dashboard-admin.categorys-project.destroy', $category->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                class="btn-delete-id w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0
                                        d-inline-flex align-items-center justify-content-center"
                                                data-id="{{ $category->id }}">
                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>
                                        </form>
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
