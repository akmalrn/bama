<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h5 class="mb-0">Kategori Projek</h5>
    @can('buat kategori-projek')
        <a href="{{ route('dashboard-admin.categorys-project.create') }}" class="btn btn-primary">
            <iconify-icon icon="ic:round-add"></iconify-icon> Tambah Kategori
        </a>
    @endcan
</div>

<div class="table-responsive">
    <table class="table bordered-table mb-0" id="categoryDataTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @can('edit kategori-projek')
                                <a href="{{ route('dashboard-admin.categorys-project.edit', $category->id) }}"
                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                    aria-label="Edit {{ $category->name }}">
                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                </a>
                            @endcan

                            @can('hapus kategori-projek')
                                <form id="delete-form-category-{{ $category->id }}"
                                    action="{{ route('dashboard-admin.categorys-project.destroy', $category->id) }}"
                                    method="POST" class="d-inline-flex m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="btn-delete-id w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center p-0"
                                        data-id="category-{{ $category->id }}"
                                        aria-label="Hapus {{ $category->name }}">
                                        <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
