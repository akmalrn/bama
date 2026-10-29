@extends('layout.layout')

@php
    $title = 'Customer Register';
    $subTitle = 'Sales CRM — Registrasi Klien & PIC Industri PT ELBEMA Integra Indonesia';
    $script = '';
@endphp

@section('content')
<div class="mb-24">

    
    <style>
        .customer-metric-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .customer-metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }
        .sector-progress-bg {
            background-color: var(--neutral-100, #f1f5f9);
        }
        [data-theme="dark"] .sector-progress-bg {
            background-color: var(--neutral-200, #323D4E) !important;
        }
        .customer-table tbody tr:hover td {
            background-color: var(--neutral-50, #f8fafc) !important;
        }
        [data-theme="dark"] .customer-table tbody tr:hover td {
            background-color: var(--neutral-100, #273142) !important;
        }
    </style>

    
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-20">
        <div>
            <div class="d-flex align-items-center gap-2 mb-4">
                <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">
                    Customer Register
                </span>
                <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-8 py-2 text-xs fw-semibold d-inline-flex align-items-center gap-1">
                    <span class="w-6-px h-6-px radius-circle bg-warning-main d-inline-block"></span>
                    Portfolio &amp; Pipeline
                </span>
            </div>
            <h5 class="mb-2 fw-bold text-primary-light">Customer Register</h5>
            <p class="text-secondary-light mb-0 text-xs">
                Pantau portofolio customer dan aktivitas komersial berdasarkan sektor industri, order, serta opportunity.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-secondary btn-sm radius-8 px-12 py-6 text-xs d-flex align-items-center gap-1" onclick="window.print()">
                <iconify-icon icon="solar:download-linear"></iconify-icon>
                <span>Export</span>
            </button>
            @can('sales.customer.create')
                <button type="button" class="btn btn-primary btn-sm radius-8 px-14 py-6 text-xs fw-semibold d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
                    <iconify-icon icon="solar:add-circle-bold-duotone" class="text-sm"></iconify-icon>
                    <span>+ Tambah Customer</span>
                </button>
            @endcan
        </div>
    </div>

    
    <div class="row g-3 mb-24">
        
        <div class="col-xl-3 col-sm-6">
            <div class="card radius-12 border border-neutral-200 shadow-sm p-16 customer-metric-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Total Customer</span>
                        <h4 class="fw-bold text-primary-light mb-0">{{ $totalCustomersCount }}</h4>
                    </div>
                    <div class="w-32-px h-32-px radius-8 bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center flex-shrink-0">
                        <iconify-icon icon="solar:user-bold-duotone" class="text-base"></iconify-icon>
                    </div>
                </div>
                <div class="pt-8 border-top">
                    <span class="text-2xs text-secondary-light">Akun customer terdaftar</span>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-sm-6">
            <div class="card radius-12 border border-neutral-200 shadow-sm p-16 customer-metric-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Sektor</span>
                        <h4 class="fw-bold text-primary-light mb-0">{{ $totalSectorsCount }}</h4>
                    </div>
                    <div class="w-32-px h-32-px radius-8 bg-info-50 text-info-600 d-flex justify-content-center align-items-center flex-shrink-0">
                        <iconify-icon icon="solar:widget-bold-duotone" class="text-base"></iconify-icon>
                    </div>
                </div>
                <div class="pt-8 border-top">
                    <span class="text-2xs text-secondary-light">Cakupan sektor industri</span>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-sm-6">
            <div class="card radius-12 border border-neutral-200 shadow-sm p-16 customer-metric-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Total Order</span>
                        <h4 class="fw-bold text-primary-light mb-0">{{ $totalOrdersCount }}</h4>
                    </div>
                    <div class="w-32-px h-32-px radius-8 bg-warning-50 text-warning-600 d-flex justify-content-center align-items-center flex-shrink-0">
                        <iconify-icon icon="solar:document-text-bold-duotone" class="text-base"></iconify-icon>
                    </div>
                </div>
                <div class="pt-8 border-top">
                    <span class="text-2xs text-secondary-light font-monospace">
                        Nilai portofolio: <strong class="text-primary-light">Rp {{ number_format($totalOrdersValue, 0, ',', '.') }}</strong>
                    </span>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-sm-6">
            <div class="card radius-12 border border-neutral-200 shadow-sm p-16 customer-metric-card h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-semibold text-secondary-light d-block mb-4">Total Opportunity</span>
                        <h4 class="fw-bold text-primary-light mb-0">{{ $totalOpportunitiesCount }}</h4>
                    </div>
                    <div class="w-32-px h-32-px radius-8 bg-success-50 text-success-600 d-flex justify-content-center align-items-center flex-shrink-0">
                        <iconify-icon icon="solar:fire-bold-duotone" class="text-base"></iconify-icon>
                    </div>
                </div>
                <div class="pt-8 border-top">
                    <span class="text-2xs text-secondary-light font-monospace">
                        Nilai pipeline: <strong class="text-primary-light">Rp {{ number_format($totalOpportunitiesValue, 0, ',', '.') }}</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-24">
        
        <div class="col-lg-4 col-md-12">
            <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="text-xs fw-bold text-primary-light mb-0">Customer by Sector</h6>
                    <span class="badge bg-neutral-100 text-secondary-light border border-neutral-200 text-2xs px-6 py-2 radius-4 fw-bold">
                        SECTOR
                    </span>
                </div>
                <span class="text-2xs text-secondary-light d-block mb-16">Jumlah customer pada tiap sektor</span>

                <div class="d-flex flex-column gap-12">
                    @foreach ($customerBySector as $item)
                        <div>
                            <div class="d-flex justify-content-between align-items-center text-xs mb-4">
                                <span class="text-secondary-light text-2xs fw-medium">{{ $item['name'] }}</span>
                                <span class="fw-bold text-primary-light text-2xs font-monospace">{{ $item['count'] }}</span>
                            </div>
                            <div class="progress sector-progress-bg" style="height: 6px; border-radius: 3px;">
                                <div class="progress-bar radius-3" style="width: {{ $item['pct'] }}%; background-color: #f97316;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        
        <div class="col-lg-4 col-md-12">
            <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="text-xs fw-bold text-primary-light mb-0">Orders by Sector</h6>
                    <span class="badge bg-neutral-100 text-secondary-light border border-neutral-200 text-2xs px-6 py-2 radius-4 fw-bold">
                        ORDER
                    </span>
                </div>
                <span class="text-2xs text-secondary-light d-block mb-16">Akumulasi order per sektor</span>

                <div class="d-flex flex-column gap-12">
                    @foreach ($ordersBySector as $item)
                        <div>
                            <div class="d-flex justify-content-between align-items-center text-xs mb-4">
                                <span class="text-secondary-light text-2xs fw-medium">{{ $item['name'] }}</span>
                                <span class="fw-bold text-primary-light text-2xs font-monospace">{{ $item['count'] }}</span>
                            </div>
                            <div class="progress sector-progress-bg" style="height: 6px; border-radius: 3px;">
                                <div class="progress-bar radius-3" style="width: {{ $item['pct'] }}%; background-color: #0284c7;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        
        <div class="col-lg-4 col-md-12">
            <div class="card radius-12 border border-neutral-200 shadow-sm p-16 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="text-xs fw-bold text-primary-light mb-0">Opportunities by Sector</h6>
                    <span class="badge bg-neutral-100 text-secondary-light border border-neutral-200 text-2xs px-6 py-2 radius-4 fw-bold">
                        OPPORTUNITY
                    </span>
                </div>
                <span class="text-2xs text-secondary-light d-block mb-16">Opportunity aktif per sektor</span>

                <div class="d-flex flex-column gap-12">
                    @foreach ($opportunitiesBySector as $item)
                        <div>
                            <div class="d-flex justify-content-between align-items-center text-xs mb-4">
                                <span class="text-secondary-light text-2xs fw-medium">{{ $item['name'] }}</span>
                                <span class="fw-bold text-primary-light text-2xs font-monospace">{{ $item['count'] }}</span>
                            </div>
                            <div class="progress sector-progress-bg" style="height: 6px; border-radius: 3px;">
                                <div class="progress-bar radius-3" style="width: {{ $item['pct'] }}%; background-color: #10b981;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>    
    <div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
        
        <div class="card-header border-bottom py-16 px-24 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="fw-bold text-primary-light mb-0 text-sm">Daftar Customer</h6>
                <span class="text-2xs text-secondary-light">Informasi sektor, cakupan order, dan opportunity per customer.</span>
            </div>
            <div>
                <span class="badge bg-neutral-100 text-secondary-light border border-neutral-200 text-2xs px-8 py-3 radius-4 fw-medium">
                    • {{ $customers->total() }} customers
                </span>
            </div>
        </div>

        
        <div class="card-body p-16 border-bottom bg-neutral-50-light">
            <form action="{{ route('dashboard-admin.sales.customers.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-7 col-sm-6">
                    <div class="position-relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama customer, sektor, kota, atau owner..."
                               class="form-control form-control-sm radius-8 ps-36 text-xs">
                        <iconify-icon icon="ion:search-outline" class="position-absolute start-0 top-50 translate-middle-y ms-12 text-secondary-light text-sm"></iconify-icon>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <select name="industry" class="form-select form-select-sm radius-8 text-xs" onchange="this.form.submit()">
                        <option value="">Semua sektor</option>
                        @foreach ($industries as $ind)
                            <option value="{{ $ind }}" {{ request('industry') === $ind ? 'selected' : '' }}>
                                {{ $ind }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-sm-12 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary btn-sm radius-8 px-12 py-6 w-100 d-flex align-items-center justify-content-center gap-1 text-xs">
                        <iconify-icon icon="solar:filter-bold-duotone"></iconify-icon>
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'industry']))
                        <a href="{{ route('dashboard-admin.sales.customers.index') }}" class="btn btn-outline-secondary btn-sm radius-8 px-10 py-6" title="Reset filter">
                            <iconify-icon icon="solar:restart-bold"></iconify-icon>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bordered-table customer-table mb-0 align-middle">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">CUSTOMER</th>
                            <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">SEKTOR</th>
                            <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">LOKASI</th>
                            <th class="text-xs fw-bold text-uppercase py-14 px-16 text-center text-primary-light">TOTAL ORDER</th>
                            <th class="text-xs fw-bold text-uppercase py-14 px-16 text-end text-primary-light">NILAI TOTAL ORDER</th>
                            <th class="text-xs fw-bold text-uppercase py-14 px-16 text-center text-primary-light">OPPORTUNITY</th>
                            <th class="text-xs fw-bold text-uppercase py-14 px-16 text-end text-primary-light">NILAI TOTAL OPP.</th>
                            <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">SALES OWNER</th>
                            <th class="text-xs fw-bold text-uppercase py-14 px-16 text-center text-primary-light" width="130">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $index => $customer)
                            <tr>
                                
                                <td class="px-16 py-12">
                                    <a href="{{ route('dashboard-admin.sales.customers.show', $customer) }}" class="fw-bold text-primary-light hover-text-primary text-sm d-block mb-1">
                                        {{ $customer->company_name }}
                                    </a>
                                    <span class="text-2xs font-monospace text-secondary-light">
                                        CUS-2026-{{ str_pad((string)$customer->id, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                
                                <td class="px-16 py-12">
                                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-8 py-4 radius-6 text-2xs fw-semibold text-nowrap">
                                        {{ $customer->sector_label }}
                                    </span>
                                </td>

                                
                                <td class="px-16 py-12">
                                    <span class="text-xs text-secondary-light text-nowrap">
                                        {{ $customer->city }}
                                    </span>
                                </td>

                                
                                <td class="px-16 py-12 text-center">
                                    <span class="fw-bold text-xs text-primary-light font-monospace">
                                        {{ $customer->computed_orders_count }}
                                    </span>
                                </td>

                                
                                <td class="px-16 py-12 text-end text-nowrap">
                                    <strong class="text-primary-600 text-xs font-monospace">
                                        Rp {{ number_format($customer->computed_orders_val, 0, ',', '.') }}
                                    </strong>
                                </td>

                                
                                <td class="px-16 py-12 text-center">
                                    <span class="fw-bold text-xs text-primary-light font-monospace">
                                        {{ $customer->computed_opp_count }}
                                    </span>
                                </td>

                                
                                <td class="px-16 py-12 text-end text-nowrap">
                                    <strong class="text-primary-600 text-xs font-monospace">
                                        Rp {{ number_format($customer->computed_opp_val, 0, ',', '.') }}
                                    </strong>
                                </td>

                                
                                <td class="px-16 py-12">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="w-24-px h-24-px radius-circle text-white fw-bold d-flex justify-content-center align-items-center text-2xs flex-shrink-0" style="background-color: {{ $customer->sales_owner['color'] ?? '#0284c7' }};">
                                            {{ $customer->sales_owner['initial'] ?? 'U' }}
                                        </div>
                                        <span class="text-xs text-secondary-light fw-medium text-nowrap">
                                            {{ $customer->sales_owner['name'] ?? '-' }}
                                        </span>
                                    </div>
                                </td>

                                
                                <td class="px-16 py-12 text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('dashboard-admin.sales.customers.show', $customer) }}"
                                           class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center"
                                           title="Detail Customer">
                                            <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                        </a>

                                        @can('sales.customer.edit')
                                            <button type="button"
                                                    class="w-32-px h-32-px rounded-circle bg-success-focus text-success-main d-inline-flex justify-content-center align-items-center border-0 btn-edit-customer"
                                                    data-id="{{ $customer->id }}"
                                                    data-company="{{ $customer->company_name }}"
                                                    data-industry="{{ $customer->industry }}"
                                                    data-npwp="{{ $customer->npwp }}"
                                                    data-location="{{ $customer->location }}"
                                                    data-pic="{{ $customer->pic_name }}"
                                                    data-phone="{{ $customer->pic_phone }}"
                                                    data-email="{{ $customer->pic_email }}"
                                                    data-bs-toggle="modal" data-bs-target="#editCustomerModal"
                                                    title="Edit Customer">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </button>
                                        @endcan

                                        @can('sales.customer.delete')
                                            <form action="{{ route('dashboard-admin.sales.customers.destroy', $customer) }}" method="POST"
                                                  onsubmit="return confirm('Hapus customer ini beserta relasinya?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0"
                                                        title="Hapus Customer">
                                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-40 text-secondary-light text-xs">
                                    <iconify-icon icon="solar:inbox-line" class="text-3xl text-neutral-400 d-block mb-2"></iconify-icon>
                                    <span>Tidak ada customer yang sesuai dengan filter pencarian.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        
        @if ($customers->hasPages())
            <div class="p-16 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-2xs text-secondary-light">
                    Menampilkan {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} dari {{ $customers->total() }} customer
                </span>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>
</div>


<datalist id="customerIndustryList">
    <option value="Oil & Gas">
    <option value="Petrochemical">
    <option value="Industrial & Manufacturing">
    <option value="Mining & Metals">
    <option value="Energy & Utilities">
</datalist>


<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 radius-12 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-36-px h-36-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-lg">
                        <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title mb-0 fw-bold" id="createCustomerModalLabel">Tambah Customer Baru</h6>
                        <p class="text-secondary-light text-xs mb-0">Isi profil perusahaan dan kontak PIC yang dapat dihubungi</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('dashboard-admin.sales.customers.store') }}" method="POST">
                @csrf
                <div class="modal-body p-24">
                    @if (isset($errors) && $errors->any() && old('_method') !== 'PUT')
                        <div class="alert alert-danger alert-dismissible fade show border-0 radius-8 mb-20" role="alert">
                            <div class="fw-semibold text-xs mb-4">Terdapat kesalahan pengisian data:</div>
                            <ul class="mb-0 text-xs ps-20">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    
                    <div class="border-bottom pb-12 mb-16">
                        <h6 class="fw-bold text-primary-600 mb-1 text-sm d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                            Informasi Perusahaan Klien
                        </h6>
                        <span class="text-xs text-secondary-light">Identitas badan usaha atau instansi customer B2B</span>
                    </div>

                    <div class="row g-3 mb-20">
                        <div class="col-md-8">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nama Perusahaan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="company_name" value="{{ old('_method') !== 'PUT' ? old('company_name') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="Contoh: PT Kaltim Parna Industri" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Sektor Industri
                            </label>
                            <input type="text" name="industry" value="{{ old('_method') !== 'PUT' ? old('industry') : '' }}"
                                   list="customerIndustryList"
                                   class="form-control radius-8 text-sm"
                                   placeholder="Contoh: Petrochemical">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nomor NPWP Perusahaan
                            </label>
                            <input type="text" name="npwp" value="{{ old('_method') !== 'PUT' ? old('npwp') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="00.000.000.0-000.000">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Lokasi / Alamat Pabrik / Site
                            </label>
                            <input type="text" name="location" value="{{ old('_method') !== 'PUT' ? old('location') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="Contoh: Kawasan Industri Bontang, Kaltim">
                        </div>
                    </div>

                    
                    <div class="border-bottom pb-12 mb-16">
                        <h6 class="fw-bold text-primary-600 mb-1 text-sm d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:user-id-bold-duotone"></iconify-icon>
                            Kontak Person In Charge (PIC)
                        </h6>
                        <span class="text-xs text-secondary-light">Pejabat atau representatif yang mengurus tender/proyek</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nama Lengkap PIC <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="pic_name" value="{{ old('_method') !== 'PUT' ? old('pic_name') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="Contoh: Ir. Bambang S." required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nomor Telepon / HP
                            </label>
                            <input type="text" name="pic_phone" value="{{ old('_method') !== 'PUT' ? old('pic_phone') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="0812xxxxxxxx">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Alamat Email PIC
                            </label>
                            <input type="email" name="pic_email" value="{{ old('_method') !== 'PUT' ? old('pic_email') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="pic@perusahaan.co.id">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-16 px-24">
                    <button type="button" class="btn btn-outline-secondary radius-8 px-20 py-8 text-sm" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary radius-8 px-20 py-8 text-sm fw-semibold d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon>
                        Simpan Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 radius-12 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-36-px h-36-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-600 text-lg">
                        <iconify-icon icon="lucide:edit"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title mb-0 fw-bold" id="editCustomerModalTitle">Edit Customer</h6>
                        <p class="text-secondary-light text-xs mb-0">Ubah data profil perusahaan atau kontak PIC klien</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editCustomerForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="customer_id" id="edit_customer_id" value="{{ old('customer_id') }}">

                <div class="modal-body p-24">
                    @if (isset($errors) && $errors->any() && old('_method') === 'PUT')
                        <div class="alert alert-danger alert-dismissible fade show border-0 radius-8 mb-20" role="alert">
                            <div class="fw-semibold text-xs mb-4">Terdapat kesalahan pengisian data:</div>
                            <ul class="mb-0 text-xs ps-20">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    
                    <div class="border-bottom pb-12 mb-16">
                        <h6 class="fw-bold text-primary-600 mb-1 text-sm d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:buildings-bold-duotone"></iconify-icon>
                            Informasi Perusahaan Klien
                        </h6>
                        <span class="text-xs text-secondary-light">Identitas badan usaha atau instansi customer B2B</span>
                    </div>

                    <div class="row g-3 mb-20">
                        <div class="col-md-8">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nama Perusahaan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="company_name" id="edit_company_name"
                                   value="{{ old('_method') === 'PUT' ? old('company_name') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="Contoh: PT Kaltim Parna Industri" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Sektor Industri
                            </label>
                            <input type="text" name="industry" id="edit_industry"
                                   value="{{ old('_method') === 'PUT' ? old('industry') : '' }}"
                                   list="customerIndustryList"
                                   class="form-control radius-8 text-sm"
                                   placeholder="Contoh: Petrochemical">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nomor NPWP Perusahaan
                            </label>
                            <input type="text" name="npwp" id="edit_npwp"
                                   value="{{ old('_method') === 'PUT' ? old('npwp') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="00.000.000.0-000.000">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Lokasi / Alamat Pabrik / Site
                            </label>
                            <input type="text" name="location" id="edit_location"
                                   value="{{ old('_method') === 'PUT' ? old('location') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="Contoh: Kawasan Industri Bontang, Kaltim">
                        </div>
                    </div>

                    
                    <div class="border-bottom pb-12 mb-16">
                        <h6 class="fw-bold text-primary-600 mb-1 text-sm d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:user-id-bold-duotone"></iconify-icon>
                            Kontak Person In Charge (PIC)
                        </h6>
                        <span class="text-xs text-secondary-light">Pejabat atau representatif yang mengurus tender/proyek</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nama Lengkap PIC <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="pic_name" id="edit_pic_name"
                                   value="{{ old('_method') === 'PUT' ? old('pic_name') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="Contoh: Ir. Bambang S." required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Nomor Telepon / HP
                            </label>
                            <input type="text" name="pic_phone" id="edit_pic_phone"
                                   value="{{ old('_method') === 'PUT' ? old('pic_phone') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="0812xxxxxxxx">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-sm fw-semibold text-primary-light">
                                Alamat Email PIC
                            </label>
                            <input type="email" name="pic_email" id="edit_pic_email"
                                   value="{{ old('_method') === 'PUT' ? old('pic_email') : '' }}"
                                   class="form-control radius-8 text-sm"
                                   placeholder="pic@perusahaan.co.id">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-16 px-24">
                    <button type="button" class="btn btn-outline-secondary radius-8 px-20 py-8 text-sm" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" id="btnUpdate" class="btn btn-primary radius-8 px-20 py-8 text-sm fw-semibold d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".btn-edit-customer").forEach(btn => {
            btn.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                const company = this.getAttribute("data-company") || "";
                const industry = this.getAttribute("data-industry") || "";
                const npwp = this.getAttribute("data-npwp") || "";
                const location = this.getAttribute("data-location") || "";
                const pic = this.getAttribute("data-pic") || "";
                const phone = this.getAttribute("data-phone") || "";
                const email = this.getAttribute("data-email") || "";

                const form = document.getElementById("editCustomerForm");
                form.action = `/dashboard-admin/sales/customers/${id}`;
                document.getElementById("edit_customer_id").value = id;
                document.getElementById("edit_company_name").value = company;
                document.getElementById("edit_industry").value = industry;
                document.getElementById("edit_npwp").value = npwp;
                document.getElementById("edit_location").value = location;
                document.getElementById("edit_pic_name").value = pic;
                document.getElementById("edit_pic_phone").value = phone;
                document.getElementById("edit_pic_email").value = email;

                const titleEl = document.getElementById("editCustomerModalTitle");
                if (titleEl) {
                    titleEl.textContent = "Edit Customer: " + company;
                }
            });
        });

        @if(request()->query('open_create') || (isset($errors) && $errors->any() && old('_method') !== 'PUT'))
            const createModalEl = document.getElementById("createCustomerModal");
            if (createModalEl) {
                const createModal = new bootstrap.Modal(createModalEl);
                createModal.show();
            }
        @elseif(isset($errors) && $errors->any() && old('_method') === 'PUT')
            const editModalEl = document.getElementById("editCustomerModal");
            if (editModalEl) {
                @if(old('customer_id'))
                    const form = document.getElementById("editCustomerForm");
                    if (form) {
                        form.action = "/dashboard-admin/sales/customers/{{ old('customer_id') }}";
                    }
                @endif
                const editModal = new bootstrap.Modal(editModalEl);
                editModal.show();
            }
        @endif
    });
</script>
@endsection
