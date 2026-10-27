@extends('layout.layout')

@php
    $title = 'Edit Data Customer';
    $subTitle = 'Perbarui Informasi Klien — ' . $customer->company_name;
@endphp

@section('content')
<div class="row gy-4 mb-24 justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden">
            <div class="card-header border-bottom py-16 px-24 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('dashboard-admin.sales.customers.index') }}" class="w-36-px h-36-px radius-8 bg-neutral-100 text-secondary-light d-flex justify-content-center align-items-center hover-bg-primary-50 hover-text-primary">
                        <iconify-icon icon="solar:arrow-left-outline" class="text-xl"></iconify-icon>
                    </a>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Edit Customer: {{ $customer->company_name }}</h5>
                        <p class="text-secondary-light text-xs mb-0">Ubah data profil perusahaan atau kontak PIC klien</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-24">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 radius-8 mb-20" role="alert">
                        <div class="fw-semibold mb-4">Terdapat kesalahan pengisian data:</div>
                        <ul class="mb-0 text-sm ps-20">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('dashboard-admin.sales.customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Section 1: Profil Perusahaan --}}
                    <div class="border-bottom pb-16 mb-20">
                        <h6 class="fw-bold text-primary-600 mb-1 d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                            Informasi Perusahaan Klien
                        </h6>
                        <span class="text-xs text-secondary-light">Identitas badan usaha atau instansi customer B2B</span>
                    </div>

                    <div class="row g-3 mb-24">
                        <div class="col-md-8">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nama Perusahaan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="company_name" value="{{ old('company_name', $customer->company_name) }}"
                                   class="form-control radius-8 @error('company_name') is-invalid @enderror"
                                   placeholder="Contoh: PT Kaltim Parna Industri" required>
                            @error('company_name')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Sektor Industri
                            </label>
                            <input type="text" name="industry" value="{{ old('industry', $customer->industry) }}"
                                   list="industryList"
                                   class="form-control radius-8 @error('industry') is-invalid @enderror"
                                   placeholder="Contoh: Petrochemical / Mining / Oil & Gas">
                            <datalist id="industryList">
                                <option value="Oil & Gas Exploration & Production">
                                <option value="Petrochemical & Refinery">
                                <option value="Fertilizer & Chemical Manufacturing">
                                <option value="Nickel & Coal Mining">
                                <option value="Power Plant / Energy">
                                <option value="Oleochemicals & Palm Oil">
                            </datalist>
                            @error('industry')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nomor NPWP Perusahaan
                            </label>
                            <input type="text" name="npwp" value="{{ old('npwp', $customer->npwp) }}"
                                   class="form-control radius-8 @error('npwp') is-invalid @enderror"
                                   placeholder="00.000.000.0-000.000">
                            @error('npwp')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Lokasi / Alamat Pabrik / Site
                            </label>
                            <input type="text" name="location" value="{{ old('location', $customer->location) }}"
                                   class="form-control radius-8 @error('location') is-invalid @enderror"
                                   placeholder="Contoh: Kawasan Industri Bontang, Kalimantan Timur">
                            @error('location')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Section 2: Person In Charge (PIC) --}}
                    <div class="border-bottom pb-16 mb-20">
                        <h6 class="fw-bold text-primary-600 mb-1 d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:user-id-bold-duotone"></iconify-icon>
                            Kontak Person In Charge (PIC)
                        </h6>
                        <span class="text-xs text-secondary-light">Pejabat atau representatif yang mengurus tender/proyek</span>
                    </div>

                    <div class="row g-3 mb-24">
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nama Lengkap PIC <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="pic_name" value="{{ old('pic_name', $customer->pic_name) }}"
                                   class="form-control radius-8 @error('pic_name') is-invalid @enderror"
                                   placeholder="Contoh: Ir. Bambang Suryono" required>
                            @error('pic_name')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nomor Telepon / HP
                            </label>
                            <input type="text" name="pic_phone" value="{{ old('pic_phone', $customer->pic_phone) }}"
                                   class="form-control radius-8 @error('pic_phone') is-invalid @enderror"
                                   placeholder="0812xxxxxxxx">
                            @error('pic_phone')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Alamat Email PIC
                            </label>
                            <input type="email" name="pic_email" value="{{ old('pic_email', $customer->pic_email) }}"
                                   class="form-control radius-8 @error('pic_email') is-invalid @enderror"
                                   placeholder="pic@perusahaan.co.id">
                            @error('pic_email')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-end align-items-center gap-2 pt-16 border-top">
                        <a href="{{ route('dashboard-admin.sales.customers.index') }}" class="btn btn-outline-secondary radius-8 px-20 py-10">
                            Batal
                        </a>
                        <button type="submit" id="btnUpdate" class="btn btn-primary radius-8 px-24 py-10 d-flex align-items-center gap-2 fw-semibold">
                            <iconify-icon icon="solar:diskette-bold-duotone" class="text-lg"></iconify-icon>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
