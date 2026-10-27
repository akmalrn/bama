@extends('layout.layout')

@php
    $title = 'Perbandingan';
    $subTitle = 'Perbandingan';
    $script = '<script>
        let table = new DataTable("#dataTable");
    </script>';
@endphp

@section('content')
    {{-- @php($activeTab = session('activeTab', 'tab-data'))
    <div class="card mb-3">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="serviceTab" role="tablist">

                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'tab-data' ? 'active' : '' }}" data-bs-toggle="tab"
                        data-bs-target="#tab-project">
                        Data Perbandingan
                    </button>
                </li>
                @can('lihat kategori-projek')
                    <li class="nav-item"><button class="nav-link {{ $activeTab === 'tab-category' ? 'active' : '' }}"
                            data-bs-toggle="tab" data-bs-target="#tab-category">Kategori Projek</button></li>
                @endcan
                @can('edit label project')
                    <li class="nav-item">
                        <button class="nav-link {{ $activeTab === 'tab-label' ? 'active' : '' }}" data-bs-toggle="tab"
                            data-bs-target="#tab-label">
                            Label Perbandingan
                        </button>
                    </li>
                @endcan
            </ul>
        </div>

        <div class="card-body tab-content">
            <div class="tab-pane fade 
        {{ $activeTab === 'tab-data' ? 'show active' : '' }}" id="tab-project">

            </div>
            @can('lihat kategori-projek')
                <div class="tab-pane fade {{ $activeTab === 'tab-category' ? 'show active' : '' }}" id="tab-category">
                    @include('pages.projects.partials.category-tab')
                </div>
            @endcan
            <div class="tab-pane fade 
            {{ $activeTab === 'tab-label' ? 'show active' : '' }}" id="tab-label">

                <form action="{{ route('dashboard-admin.projects.storeOrUpdate') }}" method="POST">
                    @csrf

                    <div class="row gy-3">

                        <div class="col-md-6">
                            <label class="form-label">Judul</label>
                            <input type="text" name="title" value="{{ $label->title ?? '' }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Span</label>
                            <input type="text" name="span" value="{{ $label->span ?? '' }}" class="form-control">
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

        </div>
    </div> --}}
    <div class="card basic-data-table" id="module-data-card">

        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">Perbandingan</h5>

            @can('buat projek')
                <a href="{{ route('dashboard-admin.projects.create') }}"
                    class="btn btn-primary d-flex align-items-center gap-2">
                    <iconify-icon icon="ic:round-add"></iconify-icon>
                    Tambah Perbandingan
                </a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length="10">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            {{-- <th>Icon</th> --}}
                            {{-- <th>Kategori</th> --}}
                            <th>Judul</th>
                            <th>Ringkasan</th>
                            {{-- <th>Link</th> --}}
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($datas as $index => $item)
                            <tr>

                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($item->image)
                                        <img src="{{ asset($item->image) }}" width="80" class="rounded">
                                    @else
                                        -
                                    @endif
                                </td>
                                {{-- <td>
                                    {!! $item->icon ?? '-' !!}
                                </td> --}}
                                {{-- <td>
                                    {{ $item->category->name ?? '-' }}
                                </td> --}}
                                <td>
                                    <strong>{{ $item->title }}</strong>
                                </td>
                                <td>
                                    {{ Str::limit($item->overview, 40) }}
                                </td>
                                {{-- <td>
                                    @if ($item->link)
                                        <a href="{{ $item->link }}" target="_blank">
                                            {{ $item->name_link ?? 'Lihat' }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td> --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('comparison.show', $item->slug) }}" target="_blank"
                                            class="w-32-px h-32-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                            title="Lihat Halaman Publik">
                                            <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                        </a>

                                        @can('edit projek')
                                            <a href="{{ route('dashboard-admin.projects.edit', $item->id) }}"
                                                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                aria-label="Edit {{ $item->title }}">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </a>
                                        @endcan

                                        @can('hapus projek')
                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('dashboard-admin.projects.destroy', $item->id) }}"
                                                method="POST" class="d-inline-flex m-0">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                    class="btn-delete-id w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center p-0"
                                                    data-id="{{ $item->id }}" aria-label="Hapus {{ $item->title }}">
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
        </div>

    </div>
    @include('pages.partials.module-tabs-script')
@endsection
