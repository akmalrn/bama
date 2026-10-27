@extends('layout.layout')

@php
    $title = 'Kategori Blog';
    $subTitle = 'Blog - Kategori Blog';
    $script = '<script>
        let table = new DataTable("#dataTable");

        document.addEventListener("click", function (event) {
            const button = event.target.closest(".btn-category-in-use");

            if (!button) return;

            const total = button.dataset.count;

            Swal.fire({
                icon: "info",
                title: "Kategori sedang digunakan",
                text: `Kategori ini digunakan oleh ${total} blog sehingga tidak dapat dihapus.`,
                confirmButtonText: "Mengerti"
            });
        });
    </script>';
@endphp

@section('content')
    <div class="card basic-data-table">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">Kategori Blog</h5>
            @can('buat kategori-blog')
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('dashboard-admin.categorys-blog.create') }}"
                        class="btn btn-primary d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:round-add"></iconify-icon>
                        Tambah Kategori
                    </a>
                </div>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">

                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categories as $index => $category)
                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $category->title }}</strong>
                                </td>

                                <td>
                                    {{ $category->slug ?? '-' }}
                                </td>

                                <td>
                                    @can('edit kategori-blog')
                                        <button>
                                            <a href="{{ route('dashboard-admin.categorys-blog.edit', $category->id) }}"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle 
                                    d-inline-flex align-items-center justify-content-center me-1">

                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </a>
                                        </button>
                                    @endcan
                                    @can('hapus kategori-blog')
                                        @if ($category->blog_count > 0)
                                            <button type="button"
                                                class="btn-category-in-use w-32-px h-32-px bg-neutral-200 text-neutral-500 rounded-circle border-0
            d-inline-flex align-items-center justify-content-center"
                                                data-count="{{ $category->blog_count }}"
                                                title="Kategori digunakan oleh {{ $category->blog_count }} blog"
                                                aria-label="Kategori digunakan oleh {{ $category->blog_count }} blog">

                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>
                                        @else
                                            <form id="delete-form-{{ $category->id }}"
                                                action="{{ route('dashboard-admin.categorys-blog.destroy', $category->id) }}"
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
                                        @endif
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
