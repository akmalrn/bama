<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h5 class="card-title mb-0">Kategori Blog</h5>
    @can('buat kategori-blog')
        <a href="{{ route('dashboard-admin.categorys-blog.create') }}"
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
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th>Jumlah Blog</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $index => $category)
                    @php($blogCount = $category->blogs_count ?? $category->blog_count ?? 0)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $category->title }}</strong></td>
                        <td>{{ $category->slug ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info-focus text-info-main px-10 py-4 radius-4">
                                {{ $blogCount }} Blog
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @can('edit kategori-blog')
                                    <a href="{{ route('dashboard-admin.categorys-blog.edit', $category->id) }}"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                        aria-label="Edit {{ $category->title }}">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </a>
                                @endcan
                                @can('hapus kategori-blog')
                                    @if ($blogCount < 1)
                                        <form id="delete-form-category-{{ $category->id }}"
                                            action="{{ route('dashboard-admin.categorys-blog.destroy', $category->id) }}"
                                            method="POST" class="d-inline-flex m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn-delete-id w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center p-0"
                                                data-id="category-{{ $category->id }}"
                                                aria-label="Hapus {{ $category->title }}">
                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-neutral-200 text-neutral-600">Dipakai {{ $blogCount }} blog</span>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
