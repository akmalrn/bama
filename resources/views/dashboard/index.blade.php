@extends('layout.layout')

@php
    $title = 'HO Dashboard — PT ELBEMA Integra Indonesia';
    $subTitle = 'Ringkasan Eksekutif Lintas Departemen';
@endphp

@section('content')
<div class="ho-dashboard-wrapper">
    
    <div class="card radius-8 border shadow-none p-16 mb-20 bg-base">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-2xs fw-bold text-uppercase">
                        CORPORATE · HEAD OFFICE
                    </span>
                    <span class="badge bg-warning-50 text-warning-700 border border-warning-200 px-8 py-2 text-2xs fw-semibold d-inline-flex align-items-center gap-1">
                        <span class="w-6-px h-6-px radius-circle bg-warning-main d-inline-block"></span>
                        DATA TERINTEGRASI
                    </span>
                </div>
                <h5 class="mb-1 fw-bold text-primary-light">HO Dashboard</h5>
                <p class="text-secondary-light mb-0 text-xs">
                    Ringkasan eksekutif lintas departemen ELBEMA. Pilih kartu untuk membuka detail unit kerja.
                </p>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('dashboard-admin.workspace.index') }}" class="btn btn-outline-primary btn-sm radius-8 px-12 py-6 text-xs fw-semibold d-flex align-items-center gap-1">
                    <iconify-icon icon="solar:laptop-minimalistic-bold-duotone" class="text-sm"></iconify-icon>
                    My Workbench
                </a>
                <span class="badge bg-neutral-100 text-neutral-700 border border-neutral-200 px-10 py-6 text-xs fw-normal radius-8 d-flex align-items-center gap-1">
                    <iconify-icon icon="solar:calendar-date-bold-duotone" class="text-primary-600"></iconify-icon>
                    Update · {{ now()->format('d.m.Y') }}
                </span>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-24 align-items-stretch">
        
        <div class="col-xl-3 col-sm-6 d-flex">
            <a href="{{ route('dashboard-admin.sales.opportunities.index') }}" class="text-decoration-none w-100 d-flex">
                <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between hover-shadow-sm transition-2">
                    <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-10">
                                <div>
                                    <p class="fw-medium text-secondary-light mb-1 text-sm text-truncate">Sales Pipeline</p>
                                    <h6 class="mb-0 fw-bold text-primary-light text-nowrap">
                                        Rp {{ $salesPipelineValue >= 1000000000 ? number_format($salesPipelineValue / 1000000000, 2, ',', '.') . ' M' : number_format($salesPipelineValue / 1000000, 1, ',', '.') . ' Jt' }}
                                    </h6>
                                </div>
                                <div class="w-48-px h-48-px bg-warning-focus text-warning-main rounded-circle d-flex justify-content-center align-items-center text-xl flex-shrink-0">
                                    <iconify-icon icon="solar:chart-square-bold"></iconify-icon>
                                </div>
                            </div>
                        </div>
                        <div class="pt-10 border-top border-neutral-100 mt-auto">
                            <p class="fw-medium text-xs text-secondary-light mb-0 text-truncate" style="min-height: 20px;">
                                <span class="text-warning-main fw-semibold">
                                    <iconify-icon icon="solar:round-alt-arrow-right-bold" class="text-xs"></iconify-icon> {{ $activeOpportunitiesCount }} peluang
                                </span>
                                aktif pada pipeline
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        
        <div class="col-xl-3 col-sm-6 d-flex">
            <a href="{{ route('dashboard-admin.pmo.projects.index') }}" class="text-decoration-none w-100 d-flex">
                <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between hover-shadow-sm transition-2">
                    <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-10">
                                <div>
                                    <p class="fw-medium text-secondary-light mb-1 text-sm text-truncate">Nilai Portfolio Proyek</p>
                                    <h6 class="mb-0 fw-bold text-primary-light text-nowrap">
                                        Rp {{ $projectPortfolioValue >= 1000000000 ? number_format($projectPortfolioValue / 1000000000, 2, ',', '.') . ' M' : number_format($projectPortfolioValue / 1000000, 1, ',', '.') . ' Jt' }}
                                    </h6>
                                </div>
                                <div class="w-48-px h-48-px bg-primary-focus text-primary-600 rounded-circle d-flex justify-content-center align-items-center text-xl flex-shrink-0">
                                    <i class="ri-folder-5-fill text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="pt-10 border-top border-neutral-100 mt-auto">
                            <p class="fw-medium text-xs text-secondary-light mb-0 text-truncate" style="min-height: 20px;">
                                <span class="text-primary-600 fw-semibold">
                                    <iconify-icon icon="solar:round-alt-arrow-right-bold" class="text-xs"></iconify-icon> {{ $projectsCount }} proyek
                                </span>
                                terdaftar di register
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        
        <div class="col-xl-3 col-sm-6 d-flex">
            <a href="{{ route('dashboard-admin.finance.invoices.index') }}" class="text-decoration-none w-100 d-flex">
                <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between hover-shadow-sm transition-2">
                    <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-10">
                                <div>
                                    <p class="fw-medium text-secondary-light mb-1 text-sm text-truncate">Invoice Outstanding</p>
                                    <h6 class="mb-0 fw-bold text-primary-light text-nowrap">
                                        Rp {{ $invoiceOutstanding >= 1000000000 ? number_format($invoiceOutstanding / 1000000000, 2, ',', '.') . ' M' : number_format($invoiceOutstanding / 1000000, 1, ',', '.') . ' Jt' }}
                                    </h6>
                                </div>
                                <div class="w-48-px h-48-px bg-danger-focus text-danger-main rounded-circle d-flex justify-content-center align-items-center text-xl flex-shrink-0">
                                    <iconify-icon icon="solar:bill-list-bold"></iconify-icon>
                                </div>
                            </div>
                        </div>
                        <div class="pt-10 border-top border-neutral-100 mt-auto">
                            <p class="fw-medium text-xs text-secondary-light mb-0 text-truncate" style="min-height: 20px;">
                                <span class="text-danger-main fw-semibold">Submitted</span> dikurangi paid
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        
        <div class="col-xl-3 col-sm-6 d-flex">
            <a href="{{ route('dashboard-admin.procurement.purchase-orders.index') }}" class="text-decoration-none w-100 d-flex">
                <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between hover-shadow-sm transition-2">
                    <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-10">
                                <div>
                                    <p class="fw-medium text-secondary-light mb-1 text-sm text-truncate">Komitmen Procurement</p>
                                    <h6 class="mb-0 fw-bold text-primary-light text-nowrap">
                                        Rp {{ $procurementCommitment >= 1000000000 ? number_format($procurementCommitment / 1000000000, 2, ',', '.') . ' M' : number_format($procurementCommitment / 1000000, 1, ',', '.') . ' Jt' }}
                                    </h6>
                                </div>
                                <div class="w-48-px h-48-px bg-success-focus text-success-main rounded-circle d-flex justify-content-center align-items-center text-xl flex-shrink-0">
                                    <iconify-icon icon="solar:transfer-horizontal-bold"></iconify-icon>
                                </div>
                            </div>
                        </div>
                        <div class="pt-10 border-top border-neutral-100 mt-auto">
                            <p class="fw-medium text-xs text-secondary-light mb-0 text-truncate" style="min-height: 20px;">
                                <span class="text-success-main fw-semibold">PO Terbit</span> pada cost register
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-14">
        <div>
            <h6 class="mb-1 fw-bold text-primary-light text-md">Department Overview</h6>
            <p class="text-secondary-light mb-0 text-xs">Indikator utama per unit kerja</p>
        </div>
        <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-3 text-2xs fw-semibold d-inline-flex align-items-center gap-1">
            <span class="w-6-px h-6-px radius-circle bg-primary-600 d-inline-block"></span>
            LIVE SYSTEM DATA
        </span>
    </div>

    <div class="row g-3 mb-24 align-items-stretch">
        
        <div class="col-xl-4 col-md-6 d-flex">
            <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-36-px h-36-px radius-8 bg-warning-focus text-warning-main d-flex justify-content-center align-items-center text-lg flex-shrink-0">
                                    <iconify-icon icon="solar:chart-square-bold"></iconify-icon>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary-light text-sm text-truncate">Sales</h6>
                            </div>
                            <span class="badge bg-neutral-100 text-neutral-600 text-2xs px-6 py-2 radius-4">RINGKASAN</span>
                        </div>
                        <p class="text-secondary-light text-xs mb-12 d-flex align-items-center" style="min-height: 38px;">
                            Pipeline, proposal, sales order, dan aktivitas customer.
                        </p>

                        <div class="p-10 radius-8 bg-neutral-100 border border-neutral-200 mb-12 d-flex flex-column justify-content-center" style="min-height: 108px;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Opportunity aktif</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $activeOpportunitiesCount }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Pipeline value</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">Rp {{ number_format($salesPipelineValue / 1000000000, 2, ',', '.') }} M</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Proposal terkirim</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $salesSubmittedProposals->count() }} · Rp {{ number_format($salesSubmittedProposals->sum('selling_price') / 1000000000, 2, ',', '.') }} M</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Sales order</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $salesWonOpportunities->count() }} · Rp {{ number_format($salesWonOpportunities->sum('estimated_value') / 1000000000, 2, ',', '.') }} M</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-top border-neutral-200 mt-auto">
                        <a href="{{ route('dashboard-admin.sales.dashboard') }}" class="text-xs fw-semibold text-primary-600 text-decoration-none d-flex align-items-center gap-1 hover-text-primary-800">
                            Buka Sales &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4 col-md-6 d-flex">
            <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-36-px h-36-px radius-8 bg-success-focus text-success-main d-flex justify-content-center align-items-center text-lg flex-shrink-0">
                                    <iconify-icon icon="solar:wallet-money-bold"></iconify-icon>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary-light text-sm text-truncate">Finance &amp; Tax</h6>
                            </div>
                            <span class="badge bg-neutral-100 text-neutral-600 text-2xs px-6 py-2 radius-4">RINGKASAN</span>
                        </div>
                        <p class="text-secondary-light text-xs mb-12 d-flex align-items-center" style="min-height: 38px;">
                            Invoice proyek, penerimaan pembayaran, dan tagihan berjalan.
                        </p>

                        <div class="p-10 radius-8 bg-neutral-100 border border-neutral-200 mb-12 d-flex flex-column justify-content-center" style="min-height: 108px;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Total Invoice</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">Rp {{ number_format($totalInvoiceAmount / 1000000000, 2, ',', '.') }} M</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Submitted</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">Rp {{ number_format($invoiceSubmittedAmount / 1000000000, 2, ',', '.') }} M</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Paid</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">Rp {{ number_format($totalPaidAmount / 1000000000, 2, ',', '.') }} M</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Outstanding</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">Rp {{ number_format($invoiceOutstanding / 1000000000, 2, ',', '.') }} M</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-top border-neutral-200 mt-auto">
                        <a href="{{ route('dashboard-admin.finance.dashboard') }}" class="text-xs fw-semibold text-primary-600 text-decoration-none d-flex align-items-center gap-1 hover-text-primary-800">
                            Buka Finance &amp; Tax &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4 col-md-6 d-flex">
            <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-36-px h-36-px radius-8 bg-info-focus text-info-main d-flex justify-content-center align-items-center text-lg flex-shrink-0">
                                    <iconify-icon icon="solar:users-group-two-rounded-bold"></iconify-icon>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary-light text-sm text-truncate">HCD</h6>
                            </div>
                            <span class="badge bg-neutral-100 text-neutral-600 text-2xs px-6 py-2 radius-4">RINGKASAN</span>
                        </div>
                        <p class="text-secondary-light text-xs mb-12 d-flex align-items-center" style="min-height: 38px;">
                            Direktori karyawan, pengembangan, dan sertifikasi tim.
                        </p>

                        <div class="p-10 radius-8 bg-neutral-100 border border-neutral-200 mb-12 d-flex flex-column justify-content-center" style="min-height: 108px;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Karyawan</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $totalEmployees }} terdaftar</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Training aktif</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $activeTrainings }} program</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Sertifikat ditinjau</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">2 lisensi</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Employee register</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">Tersedia</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-top border-neutral-200 mt-auto">
                        <a href="{{ route('dashboard-admin.hcd.dashboard') }}" class="text-xs fw-semibold text-primary-600 text-decoration-none d-flex align-items-center gap-1 hover-text-primary-800">
                            Buka HCD &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4 col-md-6 d-flex">
            <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-36-px h-36-px radius-8 bg-primary-focus text-primary-600 d-flex justify-content-center align-items-center text-lg flex-shrink-0">
                                    <i class="ri-folder-5-fill text-xl"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary-light text-sm text-truncate">PMO (Project Control)</h6>
                            </div>
                            <span class="badge bg-neutral-100 text-neutral-600 text-2xs px-6 py-2 radius-4">RINGKASAN</span>
                        </div>
                        <p class="text-secondary-light text-xs mb-12 d-flex align-items-center" style="min-height: 38px;">
                            Earned Value Management (EVM), milestone WBS, dan serah terima BAST.
                        </p>

                        <div class="p-10 radius-8 bg-neutral-100 border border-neutral-200 mb-12 d-flex flex-column justify-content-center" style="min-height: 108px;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Portfolio Proyek</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $projectsCount }} terdaftar</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">On Progress</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $onProgressProjectsCount }} berjalan</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Rata-rata Progres</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $avgProgress }}% selesai</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Status CPI / SPI</div>
                                    <div class="fw-semibold text-xs text-success-main text-truncate">1.04 / 0.98 (Sehat)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-top border-neutral-200 mt-auto">
                        <a href="{{ route('dashboard-admin.pmo.dashboard') }}" class="text-xs fw-semibold text-primary-600 text-decoration-none d-flex align-items-center gap-1 hover-text-primary-800">
                            Buka PMO &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4 col-md-6 d-flex">
            <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-36-px h-36-px radius-8 bg-danger-focus text-danger-main d-flex justify-content-center align-items-center text-lg flex-shrink-0">
                                    <iconify-icon icon="solar:shield-warning-bold"></iconify-icon>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary-light text-sm text-truncate">HSSE</h6>
                            </div>
                            <span class="badge bg-neutral-100 text-neutral-600 text-2xs px-6 py-2 radius-4">RINGKASAN</span>
                        </div>
                        <p class="text-secondary-light text-xs mb-12 d-flex align-items-center" style="min-height: 38px;">
                            Zero accident man-hours, permit to work (PTW), dan inspeksi keselamatan.
                        </p>

                        <div class="p-10 radius-8 bg-neutral-100 border border-neutral-200 mb-12 d-flex flex-column justify-content-center" style="min-height: 108px;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Safe Man-Hours</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ number_format($safeManHours, 0, ',', '.') }} Jam</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Izin Kerja (PTW)</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $activePtwCount }} aktif</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Inspeksi Alat &amp; APD</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $inspectedEquipmentCount }} unit</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Open Incident</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $openIncidentsCount }} laporan</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-top border-neutral-200 mt-auto">
                        <a href="{{ route('dashboard-admin.hsse.dashboard') }}" class="text-xs fw-semibold text-primary-600 text-decoration-none d-flex align-items-center gap-1 hover-text-primary-800">
                            Buka HSSE &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4 col-md-6 d-flex">
            <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-36-px h-36-px radius-8 bg-purple-focus text-purple-main d-flex justify-content-center align-items-center text-lg flex-shrink-0">
                                    <iconify-icon icon="solar:cart-large-minimalistic-bold"></iconify-icon>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary-light text-sm text-truncate">Procurement &amp; Logistics</h6>
                            </div>
                            <span class="badge bg-neutral-100 text-neutral-600 text-2xs px-6 py-2 radius-4">RINGKASAN</span>
                        </div>
                        <p class="text-secondary-light text-xs mb-12 d-flex align-items-center" style="min-height: 38px;">
                            Pengadaan barang &amp; jasa, vendor terverifikasi, dan ekspedisi site.
                        </p>

                        <div class="p-10 radius-8 bg-neutral-100 border border-neutral-200 mb-12 d-flex flex-column justify-content-center" style="min-height: 108px;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">PO Diterbitkan</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">Rp {{ number_format($procurementCommitment / 1000000, 1, ',', '.') }} Jt</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">PR Menunggu Review</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $pendingPrCount }} pengajuan</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Rekanan Vendor</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $activeVendorsCount }} mitra</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Ekspedisi Site</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $inTransitDeliveriesCount }} in transit</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-top border-neutral-200 mt-auto">
                        <a href="{{ route('dashboard-admin.procurement.dashboard') }}" class="text-xs fw-semibold text-primary-600 text-decoration-none d-flex align-items-center gap-1 hover-text-primary-800">
                            Buka Procurement &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4 col-md-6 d-flex">
            <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-36-px h-36-px radius-8 bg-primary-focus text-primary-600 d-flex justify-content-center align-items-center text-lg flex-shrink-0">
                                    <i class="ri-tools-fill text-xl"></i>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary-light text-sm text-truncate">Asset Management</h6>
                            </div>
                            <span class="badge bg-neutral-100 text-neutral-600 text-2xs px-6 py-2 radius-4">RINGKASAN</span>
                        </div>
                        <p class="text-secondary-light text-xs mb-12 d-flex align-items-center" style="min-height: 38px;">
                            Inventaris mesin spesialisasi UHP, mobilisasi site, &amp; kalibrasi.
                        </p>

                        <div class="p-10 radius-8 bg-neutral-100 border border-neutral-200 mb-12 d-flex flex-column justify-content-center" style="min-height: 108px;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Total Valuasi</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">Rp {{ number_format($totalAssetValuation / 1000000000, 2, ',', '.') }} M</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Unit Tersedia</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $availableAssetsCount }} di workshop</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Dimobilisasi</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $mobilizedAssetsCount }} di site</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Kalibrasi / Servis</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $scheduledMaintenanceCount }} jadwal</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-top border-neutral-200 mt-auto">
                        <a href="{{ route('dashboard-admin.assets.dashboard') }}" class="text-xs fw-semibold text-primary-600 text-decoration-none d-flex align-items-center gap-1 hover-text-primary-800">
                            Buka Asset &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4 col-md-6 d-flex">
            <div class="card radius-8 border shadow-none bg-base w-100 h-100 d-flex flex-column justify-content-between">
                <div class="card-body p-20 d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-10">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-36-px h-36-px radius-8 bg-info-focus text-info-main d-flex justify-content-center align-items-center text-lg flex-shrink-0">
                                    <iconify-icon icon="solar:global-bold"></iconify-icon>
                                </div>
                                <h6 class="mb-0 fw-bold text-primary-light text-sm text-truncate">Portal &amp; Web Traffic</h6>
                            </div>
                            <span class="badge bg-neutral-100 text-neutral-600 text-2xs px-6 py-2 radius-4">TRAFFIC</span>
                        </div>
                        <p class="text-secondary-light text-xs mb-12 d-flex align-items-center" style="min-height: 38px;">
                            Statistik pengunjung web publik, artikel blog, dan layanan publik.
                        </p>

                        <div class="p-10 radius-8 bg-neutral-100 border border-neutral-200 mb-12 d-flex flex-column justify-content-center" style="min-height: 108px;">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Total Kunjungan</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ number_format($totalVisitors ?? 0) }} visits</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Artikel Blog</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $totalBlogs ?? 0 }} konten</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Layanan Terbit</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $totalServices ?? 0 }} service</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-2xs text-secondary-light text-truncate">Testimoni Klien</div>
                                    <div class="fw-semibold text-xs text-primary-light text-truncate">{{ $totalTestimonials ?? 0 }} ulasan</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-top border-neutral-200 mt-auto">
                        <a href="{{ route('dashboard-admin.visitors.history') }}" class="text-xs fw-semibold text-primary-600 text-decoration-none d-flex align-items-center gap-1 hover-text-primary-800">
                            Buka Monitoring Visitor &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
