@extends('layout.layout')

@php
    $title = 'Blogs';
    $subTitle = 'Blogs';
    $script = '';
@endphp

@section('content')
    @php($activeTab = session('activeTab', 'tab-data'))

    
    <div class="card mb-3" id="blog-tab-container-card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'tab-data' ? 'active' : '' }}" data-bs-toggle="tab"
                        data-bs-target="#tab-blog">
                        Data Blog
                    </button>
                </li>
                @can('lihat kategori-blog')
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'tab-category' ? 'active' : '' }}" data-bs-toggle="tab"
                            data-bs-target="#tab-category">
                            Kategori Blog
                        </button>
                    </li>
                @endcan
                @can('edit label blog')
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'tab-label' ? 'active' : '' }}" data-bs-toggle="tab"
                            data-bs-target="#tab-label">
                            Label Blog
                        </button>
                    </li>
                @endcan
            </ul>
        </div>

        <div class="card-body tab-content {{ $activeTab === 'tab-label' ? '' : 'd-none' }}" id="module-label-card-body">
            <div class="tab-pane fade {{ $activeTab === 'tab-data' ? 'show active' : '' }}" id="tab-blog"></div>
            @can('lihat kategori-blog')
                <div class="tab-pane fade {{ $activeTab === 'tab-category' ? 'show active' : '' }}" id="tab-category"></div>
            @endcan
            @can('edit label blog')
                <div class="tab-pane fade {{ $activeTab === 'tab-label' ? 'show active' : '' }}" id="tab-label">
                    <form action="{{ route('dashboard-admin.blogs.storeOrUpdate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="active_tab" value="tab-label">

                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Judul</label>
                                <input type="text" name="title" value="{{ $label->title ?? '' }}" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sub Judul</label>
                                <input type="text" name="subTitle" value="{{ $label->subTitle ?? '' }}" class="form-control">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3">{{ $label->description ?? '' }}</textarea>
                            </div>

                            <div class="col-12 text-end">
                                <button id="btnSubmit" class="btn btn-primary">
                                    Simpan Label
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endcan
        </div>
    </div>

    <div class="service-admin-page">
        
        <div class="card" id="module-data-card">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <h5 class="card-title mb-0">Blogs</h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-sm text-secondary-light mb-0">Show</span>
                        <form method="GET" action="{{ route('dashboard-admin.blogs.index') }}" id="perPageForm">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-8 h-36-px" onchange="document.getElementById('perPageForm').submit()">
                                @foreach ([10, 25, 50, 100] as $option)
                                    <option value="{{ $option }}" @selected((int) request('per_page', 10) === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                <div class="d-flex align-items-center flex-wrap gap-3">
                    <form class="navbar-search d-inline-block position-relative" method="GET" action="{{ route('dashboard-admin.blogs.index') }}">
                        @if(request('per_page'))
                            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                        @endif
                        <input type="text" class="bg-base h-40-px w-auto pe-40" name="search" value="{{ request('search') }}" placeholder="Cari blog...">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                        @if(request('search'))
                            <a href="{{ route('dashboard-admin.blogs.index', request('per_page') ? ['per_page' => request('per_page')] : []) }}"
                               class="position-absolute end-0 top-50 translate-middle-y me-12 text-secondary-light text-hover-danger d-flex align-items-center"
                               title="Reset pencarian">
                                <iconify-icon icon="ic:round-close" class="text-lg"></iconify-icon>
                            </a>
                        @endif
                    </form>

                    @can('buat blog')
                        <a href="{{ route('dashboard-admin.blogs.create') }}"
                            class="btn btn-primary d-flex align-items-center gap-2">
                            <iconify-icon icon="ic:round-add"></iconify-icon> Tambah Blog
                        </a>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0" id="blogTable">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Tanggal Publish</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($blogs as $index => $blog)
                                <tr>
                                    <td>{{ $blogs->firstItem() + $index }}</td>
                                    <td>
                                        @if ($blog->image)
                                            <img src="{{ asset($blog->image) }}" width="100" class="rounded"
                                                alt="{{ $blog->title }}">
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Illuminate\Support\Str::limit($blog->title, 40, '...') }}</strong>
                                    </td>
                                    <td>
                                        {{ $blog->categoryBlog->title ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('d M Y') : '-' }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @can('edit blog')
                                                <a href="{{ route('dashboard-admin.blogs.edit', $blog->id) }}"
                                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    aria-label="Edit {{ $blog->title }}">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </a>
                                            @endcan
                                            @can('hapus blog')
                                                <form id="delete-form-{{ $blog->id }}"
                                                    action="{{ route('dashboard-admin.blogs.destroy', $blog->id) }}" method="POST"
                                                    class="d-inline-flex m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn-delete-id w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center p-0"
                                                        data-id="{{ $blog->id }}"
                                                        aria-label="Hapus {{ $blog->title }}">
                                                        <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-secondary-light">
                                        Tidak ada data blog yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <span class="text-secondary-light text-sm">
                        Showing {{ $blogs->firstItem() ?? 0 }} to {{ $blogs->lastItem() ?? 0 }} of {{ $blogs->total() }} entries
                    </span>
                    @if ($blogs->hasPages())
                        {{ $blogs->links('pagination.wowdash') }}
                    @endif
                </div>
            </div>
        </div>

        
        @can('lihat kategori-blog')
            <div class="card basic-data-table {{ $activeTab === 'tab-category' ? '' : 'd-none' }}" id="module-category-card">
                @include('pages.blogs.partials.category-tab')
            </div>
        @endcan
    </div>

    @include('pages.partials.module-tabs-script')
@endsection
