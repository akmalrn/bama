<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h5 class="card-title mb-0">Kategori Produk</h5>
    @can('buat kategori-produk')
        <a href="{{ route('dashboard-admin.categorys-product.create') }}"
            class="btn btn-primary d-flex align-items-center gap-2">
            <iconify-icon icon="ic:round-add"></iconify-icon> Tambah Kategori
        </a>
    @endcan
</div>
<div class="card-body">
    <div class="table-responsive">
        <table class="table bordered-table mb-0" id="categoryDataTable" data-page-length="10">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>Gambar</th>
                    <th>Nama Tombol</th>
                    <th>Judul Kategori</th>
                    <th>Overview</th>
                    <th>Icon</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $index => $category)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if ($category->image)
                                <img src="{{ asset($category->image) }}" width="100" class="rounded"
                                    alt="{{ $category->name ?: $category->title }}">
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $category->name ?: $category->title }}</strong>
                        </td>
                        <td>
                            <strong>{{ Str::limit($category->title, 40) }}</strong>
                        </td>
                        <td>{{ Str::limit($category->overview ?: $category->description, 40) }}</td>
                        <td>
                            @if ($category->icon)
                                <span class="d-inline-flex align-items-center justify-content-center p-1 border rounded">
                                    {!! $category->icon !!}
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @can('edit kategori-produk')
                                <a href="{{ route('dashboard-admin.categorys-product.edit', $category->id) }}"
                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                                    aria-label="Edit {{ $category->name ?: $category->title }}">
                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                </a>
                            @endcan
                            @can('hapus kategori-produk')
                                <form id="delete-form-category-{{ $category->id }}"
                                    action="{{ route('dashboard-admin.categorys-product.destroy', $category->id) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="btn-delete-id w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center"
                                        data-id="category-{{ $category->id }}"
                                        aria-label="Hapus {{ $category->name ?: $category->title }}">
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
