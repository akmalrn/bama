@extends('layout.layout')

@php
    $title = 'Invoice Monitoring (AR)';
    $subTitle = 'Finance & Tax';
    $script = '';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0">Invoice Monitoring & Account Receivable (AR)</h6>
        <p class="text-neutral-500 text-sm mb-0">Kelola penagihan termin proyek, PPN 11%, pemotongan PPh, pemantauan aging piutang, dan pencatatan kas masuk.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary btn-sm px-16 py-8 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
            <iconify-icon icon="solar:document-add-bold" class="text-lg"></iconify-icon>
            <span>+ Buat Invoice Baru</span>
        </button>
    </div>
</div>

<div class="row g-3 mb-24">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Invoiced (Gross)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">Rp {{ number_format($totalInvoiced, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Termin & faktur resmi</span>
                    <span class="badge bg-primary-50 text-primary-600 border border-primary-200">{{ $counts['all'] }} Dokumen</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Cash Collected (Paid)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Kas masuk diterima</span>
                    <span class="badge bg-success-50 text-success-700 border border-success-200">
                        {{ $totalInvoiced > 0 ? round(($totalPaid / $totalInvoiced) * 100, 1) : 0 }}% Terkumpul
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Outstanding AR (Piutang)</span>
                        <h4 class="fw-bold mb-0 mt-2 text-primary-light fs-5">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Menunggu pelunasan</span>
                    <span class="badge bg-warning-50 text-warning-main border border-warning-200">{{ $counts['sent'] + $counts['partial'] }} Invoice</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="card-body p-20 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-12">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-danger-main">Overdue Invoices</span>
                        <h4 class="fw-bold mb-0 mt-2 text-danger-main fs-5">{{ $totalOverdueCount }} Invoice</h4>
                    </div>
                    <div class="w-44-px h-44-px radius-8 d-flex justify-content-center align-items-center bg-danger-100 text-danger-main text-2xl flex-shrink-0 shadow-xs">
                        <iconify-icon icon="solar:danger-triangle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-10 border-top text-xs text-secondary-light">
                    <span>Melewati jatuh tempo</span>
                    <span class="badge bg-danger-50 text-danger-700 border border-danger-200">Perlu Tindak Lanjut</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm radius-12 mb-24">
    <div class="card-body p-20">
        
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom pb-16 mb-16">
            <ul class="nav nav-pills gap-2">
                <li class="nav-item">
                    <a class="nav-link py-6 px-16 text-xs radius-8 {{ empty($status) || $status === 'all' ? 'active' : '' }}" 
                       href="{{ route('dashboard-admin.finance.invoices.index', array_merge(request()->except(['status', 'page']), ['status' => 'all'])) }}">
                        Semua Status <span class="badge bg-white text-primary-600 ms-1">{{ $counts['all'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-6 px-16 text-xs radius-8 {{ $status === 'draft' ? 'active' : '' }}" 
                       href="{{ route('dashboard-admin.finance.invoices.index', array_merge(request()->except(['status', 'page']), ['status' => 'draft'])) }}">
                        Draft <span class="badge bg-neutral-200 text-secondary-light ms-1">{{ $counts['draft'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-6 px-16 text-xs radius-8 {{ $status === 'sent' ? 'active' : '' }}" 
                       href="{{ route('dashboard-admin.finance.invoices.index', array_merge(request()->except(['status', 'page']), ['status' => 'sent'])) }}">
                        Terkirim / Unpaid <span class="badge bg-info-100 text-info-main ms-1">{{ $counts['sent'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-6 px-16 text-xs radius-8 {{ $status === 'partial' ? 'active' : '' }}" 
                       href="{{ route('dashboard-admin.finance.invoices.index', array_merge(request()->except(['status', 'page']), ['status' => 'partial'])) }}">
                        Dibayar Sebagian <span class="badge bg-warning-100 text-warning-main ms-1">{{ $counts['partial'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-6 px-16 text-xs radius-8 {{ $status === 'paid' ? 'active' : '' }}" 
                       href="{{ route('dashboard-admin.finance.invoices.index', array_merge(request()->except(['status', 'page']), ['status' => 'paid'])) }}">
                        Lunas <span class="badge bg-success-100 text-success-main ms-1">{{ $counts['paid'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-6 px-16 text-xs radius-8 {{ $status === 'overdue' ? 'active text-white bg-danger-main' : 'text-danger-main' }}" 
                       href="{{ route('dashboard-admin.finance.invoices.index', array_merge(request()->except(['status', 'page']), ['status' => 'overdue'])) }}">
                        Overdue <span class="badge bg-danger-100 text-danger-main ms-1">{{ $counts['overdue'] }}</span>
                    </a>
                </li>
            </ul>
        </div>

        
        <form method="GET" action="{{ route('dashboard-admin.finance.invoices.index') }}" class="row g-2 align-items-center">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="col-lg-3 col-md-6">
                <div class="position-relative">
                    <input type="text" name="search" class="form-control form-control-sm ps-36 text-sm" placeholder="Cari nomor invoice, termin..." value="{{ $search }}">
                    <iconify-icon icon="solar:magnifer-outline" class="position-absolute top-50 start-0 translate-middle-y ms-12 text-secondary-light"></iconify-icon>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="customer_id" class="form-select form-select-sm text-sm">
                    <option value="">Semua Klien / Perusahaan</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ $customerId == $c->id ? 'selected' : '' }}>{{ $c->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="project_id" class="form-select form-select-sm text-sm">
                    <option value="">Semua Proyek (PMO)</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-16 w-100">Filter</button>
                @if(request()->anyFilled(['search', 'customer_id', 'project_id', 'date_start', 'date_end', 'status']))
                    <a href="{{ route('dashboard-admin.finance.invoices.index') }}" class="btn btn-light btn-sm px-12" title="Reset Filter">
                        <iconify-icon icon="solar:restart-outline"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>


<div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
    <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="text-sm fw-semibold mb-0 text-primary-light">Daftar Faktur Penagihan & Monitoring Piutang</h6>
        <span class="text-xs text-secondary-light">Menampilkan {{ $invoices->firstItem() ?? 0 }} - {{ $invoices->lastItem() ?? 0 }} dari {{ $invoices->total() }} faktur</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-100">
                    <tr>
                        <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">NO. INVOICE</th>
                        <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">KLIEN & PROYEK</th>
                        <th class="text-xs fw-bold text-uppercase py-12 px-16 text-primary-light">TERMIN / SUBJEK</th>
                        <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">TOTAL TAGIHAN (PPN)</th>
                        <th class="text-xs fw-bold text-uppercase py-12 px-16 text-end text-primary-light">SISA PIUTANG</th>
                        <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light">JATUH TEMPO</th>
                        <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light">STATUS</th>
                        <th class="text-xs fw-bold text-uppercase py-12 px-16 text-center text-primary-light" width="120">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                        @php
                            $isOverdue = $inv->is_overdue;
                            $statusBadge = match($inv->status) {
                                'paid' => 'bg-success-focus text-success-600 border border-success-subtle',
                                'partial' => 'bg-warning-focus text-warning-main border border-warning-subtle',
                                'sent' => 'bg-info-focus text-info-main border border-info-subtle',
                                'cancelled' => 'bg-neutral-200 text-secondary-light border border-neutral-300',
                                default => 'bg-neutral-100 text-secondary-light border border-neutral-200',
                            };
                            $statusLabel = match($inv->status) {
                                'paid' => 'Lunas',
                                'partial' => 'Sebagian',
                                'sent' => 'Terkirim',
                                'cancelled' => 'Batal',
                                default => 'Draft',
                            };
                        @endphp
                        <tr>
                            <td class="px-16 py-12">
                                <span class="fw-bold text-sm text-primary-600 font-monospace d-block">{{ $inv->invoice_number }}</span>
                                <small class="text-2xs text-secondary-light">{{ $inv->invoice_date->format('d M Y') }}</small>
                            </td>
                            <td class="px-16 py-12">
                                <span class="fw-semibold text-sm text-primary-light d-block text-truncate" style="max-width: 220px;" title="{{ $inv->customer->company_name ?? '-' }}">
                                    {{ $inv->customer->company_name ?? '-' }}
                                </span>
                                @if($inv->project)
                                    <small class="text-2xs text-info-main d-flex align-items-center gap-1 text-truncate" style="max-width: 220px;" title="{{ $inv->project->name }}">
                                        <iconify-icon icon="solar:folder-with-files-outline"></iconify-icon>
                                        {{ $inv->project->name }}
                                    </small>
                                @else
                                    <small class="text-2xs text-secondary-light">Non-Project / General</small>
                                @endif
                            </td>
                            <td class="px-16 py-12">
                                <span class="text-sm text-primary-light fw-medium d-block text-truncate" style="max-width: 240px;" title="{{ $inv->title }}">
                                    {{ $inv->title }}
                                </span>
                                <small class="text-2xs text-secondary-light">Terms: {{ $inv->payment_terms }}</small>
                            </td>
                            <td class="px-16 py-12 text-end">
                                <span class="fw-bold text-sm font-monospace text-primary-light d-block">
                                    Rp {{ number_format($inv->total_amount, 0, ',', '.') }}
                                </span>
                                <small class="text-2xs text-secondary-light">
                                    Subtotal: Rp {{ number_format($inv->subtotal, 0, ',', '.') }}
                                </small>
                            </td>
                            <td class="px-16 py-12 text-end">
                                <span class="fw-bold text-sm font-monospace {{ $inv->balance > 0 ? ($isOverdue ? 'text-danger-main' : 'text-warning-main') : 'text-success-main' }} d-block">
                                    Rp {{ number_format($inv->balance, 0, ',', '.') }}
                                </span>
                                <small class="text-2xs text-success-main">
                                    Terbayar: Rp {{ number_format($inv->paid_amount, 0, ',', '.') }}
                                </small>
                            </td>
                            <td class="px-16 py-12 text-center">
                                <span class="text-xs fw-semibold {{ $isOverdue ? 'text-danger-main fw-bold' : 'text-secondary-light' }} d-block">
                                    {{ $inv->due_date ? $inv->due_date->format('d M Y') : '-' }}
                                </span>
                                @if($isOverdue)
                                    <span class="badge bg-danger-focus text-danger-main text-3xs px-6 py-2 radius-4">Overdue</span>
                                @endif
                            </td>
                            <td class="px-16 py-12 text-center">
                                <span class="badge {{ $statusBadge }} px-10 py-4 radius-4 text-xs fw-semibold">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-16 py-12 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    
                                    <button type="button" class="w-32-px h-32-px rounded-circle bg-info-focus text-info-main d-inline-flex justify-content-center align-items-center border-0 btn-view-invoice" 
                                            data-id="{{ $inv->id }}" title="Lihat Detail & Riwayat Pembayaran">
                                        <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                    </button>

                                    
                                    @if($inv->status !== 'paid' && $inv->status !== 'cancelled')
                                        <button type="button" class="w-32-px h-32-px rounded-circle bg-success-focus text-success-main d-inline-flex justify-content-center align-items-center border-0 btn-pay-invoice" 
                                                data-id="{{ $inv->id }}"
                                                data-number="{{ $inv->invoice_number }}"
                                                data-balance="{{ $inv->balance }}"
                                                data-formatted-balance="Rp {{ number_format($inv->balance, 0, ',', '.') }}"
                                                title="Catat Kas Masuk / Pelunasan">
                                            <iconify-icon icon="solar:wallet-money-bold"></iconify-icon>
                                        </button>
                                    @endif

                                    
                                    <button type="button" class="w-32-px h-32-px rounded-circle bg-warning-focus text-warning-main d-inline-flex justify-content-center align-items-center border-0 btn-edit-invoice"
                                            data-id="{{ $inv->id }}"
                                            data-number="{{ $inv->invoice_number }}"
                                            data-customer="{{ $inv->customer_id }}"
                                            data-project="{{ $inv->project_id }}"
                                            data-title="{{ $inv->title }}"
                                            data-date="{{ $inv->invoice_date->toDateString() }}"
                                            data-due="{{ $inv->due_date ? $inv->due_date->toDateString() : '' }}"
                                            data-subtotal="{{ $inv->subtotal }}"
                                            data-tax-rate="{{ $inv->tax_rate }}"
                                            data-pph-type="{{ $inv->pph_type }}"
                                            data-pph-rate="{{ $inv->pph_rate }}"
                                            data-terms="{{ $inv->payment_terms }}"
                                            data-status="{{ $inv->status }}"
                                            data-notes="{{ $inv->notes }}"
                                            title="Edit Invoice">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </button>

                                    
                                    <form action="{{ route('dashboard-admin.finance.invoices.destroy', $inv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice {{ $inv->invoice_number }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus Invoice">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-40 text-secondary-light">
                                <iconify-icon icon="solar:bill-cross-outline" class="text-4xl text-neutral-400 mb-2"></iconify-icon>
                                <p class="mb-0 text-sm">Belum ada data invoice penagihan pada filter ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
            <div class="px-20 py-16 border-top d-flex justify-content-end">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>


<div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light d-flex align-items-center gap-2" id="createInvoiceModalLabel">
                    <iconify-icon icon="solar:document-add-bold" class="text-primary-600 text-xl"></iconify-icon>
                    Buat Faktur Penagihan Baru (Invoice AR)
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.finance.invoices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Nomor Invoice <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_number" class="form-control text-sm font-monospace" value="{{ $nextInvNumber }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Status Awal</label>
                            <select name="status" class="form-select text-sm">
                                <option value="draft">Draft (Disimpan Sementara)</option>
                                <option value="sent" selected>Terkirim ke Klien (Menunggu Pembayaran)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Klien / Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select text-sm" required>
                                <option value="">Pilih Klien</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->company_name }} ({{ $c->pic_name ?? 'No PIC' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Proyek Terkait (PMO) <span class="text-secondary-light text-2xs">(Opsional)</span></label>
                            <select name="project_id" class="form-select text-sm">
                                <option value="">-- Non-Project / Penjualan Umum --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-sm fw-medium">Deskripsi Termin / Pekerjaan <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control text-sm" placeholder="Contoh: Termin 1 (DP 30%) - Pekerjaan Tank Blasting & Coating" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Tanggal Invoice <span class="text-danger">*</span></label>
                            <input type="date" name="invoice_date" class="form-control text-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Jatuh Tempo (Due Date) <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control text-sm" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Syarat Pembayaran (Payment Terms)</label>
                            <input type="text" name="payment_terms" class="form-control text-sm" value="Net 30 Days" placeholder="Net 30 Days, COD, dll">
                        </div>

                        
                        <div class="col-12">
                            <div class="p-16 radius-12 bg-neutral-50 border border-neutral-200">
                                <h6 class="text-xs fw-bold text-uppercase tracking-wider text-secondary-light mb-12">Perhitungan Nilai & Perpajakan</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-sm fw-medium">Nilai Sebelum Pajak (Subtotal) <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" step="0.01" name="subtotal" id="create_subtotal" class="form-control text-sm font-monospace text-end" placeholder="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-sm fw-medium">Tarif PPN (%)</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" name="tax_rate" id="create_tax_rate" class="form-control text-sm text-end" value="11.00">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <small class="text-2xs text-secondary-light mt-1 d-block">Nilai PPN: <span id="create_tax_amount_preview" class="fw-bold font-monospace">Rp 0</span></small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-sm fw-medium">Potongan PPh (Komersial)</label>
                                        <select name="pph_type" id="create_pph_type" class="form-select form-select-sm text-sm">
                                            <option value="none">Tanpa PPh</option>
                                            <option value="pph_23">PPh 23 (Jasa Teknik/Manajemen - 2%)</option>
                                            <option value="pph_4_2">PPh Final 4(2) (Konstruksi - 1.75% s/d 2.65%)</option>
                                            <option value="pph_21">PPh 21 (Tenaga Ahli)</option>
                                        </select>
                                        <div class="input-group input-group-sm mt-1">
                                            <input type="number" step="0.01" name="pph_rate" id="create_pph_rate" class="form-control text-sm text-end" value="0.00">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-12 border-top pt-12 mt-12 d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold text-sm text-primary-light">Total Tagihan Bruto (Subtotal + PPN):</span>
                                        <h5 class="fw-bold text-primary-600 mb-0 font-monospace" id="create_total_preview">Rp 0</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Lampiran Dokumen (BAST / PO / Invoice PDF)</label>
                            <input type="file" name="attachment" class="form-control form-control-sm text-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Catatan / Keterangan Transfer Bank</label>
                            <input type="text" name="notes" class="form-control form-control-sm text-sm" placeholder="Contoh: Transfer ke Rekening Mandiri PT ELBEMA...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-light btn-sm px-16" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-20">Simpan & Terbitkan Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light d-flex align-items-center gap-2" id="addPaymentModalLabel">
                    <iconify-icon icon="solar:wallet-money-bold" class="text-success-main text-xl"></iconify-icon>
                    Catat Kas Masuk / Pembayaran Invoice
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPaymentForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-24">
                    <div class="alert alert-light border border-neutral-200 radius-8 p-12 mb-16">
                        <div class="d-flex justify-content-between text-xs mb-1">
                            <span class="text-secondary-light">Nomor Invoice:</span>
                            <span class="fw-bold font-monospace text-primary-light" id="pay_invoice_number">-</span>
                        </div>
                        <div class="d-flex justify-content-between text-xs">
                            <span class="text-secondary-light">Sisa Piutang:</span>
                            <span class="fw-bold font-monospace text-danger-main fs-6" id="pay_balance_preview">Rp 0</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-sm fw-medium">Tanggal Diterima <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control text-sm" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-sm fw-medium">Jumlah Pembayaran (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text text-sm">Rp</span>
                                <input type="number" step="0.01" name="amount" id="pay_amount_input" class="form-control text-sm font-monospace text-end fw-bold" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Metode Pembayaran</label>
                            <select name="payment_method" class="form-select text-sm">
                                <option value="Transfer Bank" selected>Transfer Bank</option>
                                <option value="Bilyet Giro / Cek">Bilyet Giro / Cek</option>
                                <option value="Cash / Tunai">Cash / Tunai</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">No. Referensi / Rekening</label>
                            <input type="text" name="reference_no" class="form-control text-sm" placeholder="Ref Bank / No Cek">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-sm fw-medium">Upload Bukti Transfer / Rekening Koran <span class="text-secondary-light text-2xs">(Opsional)</span></label>
                            <input type="file" name="receipt" class="form-control form-control-sm text-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-sm fw-medium">Keterangan / Notes</label>
                            <textarea name="notes" rows="2" class="form-control text-sm" placeholder="Catatan pembayaran..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-light btn-sm px-16" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm px-20">Konfirmasi Kas Masuk</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="invoiceDetailModal" tabindex="-1" aria-labelledby="invoiceDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light d-flex align-items-center gap-2" id="invoiceDetailModalLabel">
                    <iconify-icon icon="solar:bill-check-bold" class="text-primary-600 text-xl"></iconify-icon>
                    Detail Faktur & Riwayat Kas Masuk
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24" id="invoiceDetailBody">
                <div class="text-center py-32 text-secondary-light">
                    <span class="spinner-border spinner-border-sm me-2"></span> Memuat data invoice...
                </div>
            </div>
            <div class="modal-footer border-top py-14 px-24">
                <button type="button" class="btn btn-light btn-sm px-20" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 radius-16 shadow-lg">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fs-6 fw-semibold text-primary-light d-flex align-items-center gap-2" id="editInvoiceModalLabel">
                    <iconify-icon icon="solar:pen-bold" class="text-warning-main text-xl"></iconify-icon>
                    Edit Data Invoice
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editInvoiceForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-24">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Nomor Invoice <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_number" id="edit_invoice_number" class="form-control text-sm font-monospace" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Status Invoice <span class="text-danger">*</span></label>
                            <select name="status" id="edit_status" class="form-select text-sm" required>
                                <option value="draft">Draft</option>
                                <option value="sent">Terkirim / Unpaid</option>
                                <option value="partial">Dibayar Sebagian</option>
                                <option value="paid">Lunas</option>
                                <option value="overdue">Overdue</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Klien / Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" id="edit_customer_id" class="form-select text-sm" required>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Proyek Terkait</label>
                            <select name="project_id" id="edit_project_id" class="form-select text-sm">
                                <option value="">-- Non-Project / Umum --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-sm fw-medium">Deskripsi Termin / Subjek <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_title" class="form-control text-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Tanggal Invoice <span class="text-danger">*</span></label>
                            <input type="date" name="invoice_date" id="edit_invoice_date" class="form-control text-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" id="edit_due_date" class="form-control text-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Payment Terms</label>
                            <input type="text" name="payment_terms" id="edit_payment_terms" class="form-control text-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Nilai Sebelum Pajak (Subtotal) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" name="subtotal" id="edit_subtotal" class="form-control text-sm font-monospace text-end" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Tarif PPN (%)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" name="tax_rate" id="edit_tax_rate" class="form-control text-sm text-end" value="11.00">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-medium">Tipe PPh</label>
                            <select name="pph_type" id="edit_pph_type" class="form-select form-select-sm text-sm">
                                <option value="none">Tanpa PPh</option>
                                <option value="pph_23">PPh 23 (2%)</option>
                                <option value="pph_4_2">PPh Final 4(2)</option>
                                <option value="pph_21">PPh 21</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Ganti Lampiran File</label>
                            <input type="file" name="attachment" class="form-control form-control-sm text-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-medium">Catatan</label>
                            <input type="text" name="notes" id="edit_notes" class="form-control form-control-sm text-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-14 px-24">
                    <button type="button" class="btn btn-light btn-sm px-16" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm px-20">Perbarui Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Live Calculator for Create Invoice Modal
    const subtotalInput = document.getElementById('create_subtotal');
    const taxRateInput = document.getElementById('create_tax_rate');
    const pphTypeSelect = document.getElementById('create_pph_type');
    const pphRateInput = document.getElementById('create_pph_rate');
    const taxPreview = document.getElementById('create_tax_amount_preview');
    const totalPreview = document.getElementById('create_total_preview');

    function calculateCreateTotal() {
        const subtotal = parseFloat(subtotalInput.value) || 0;
        const taxRate = parseFloat(taxRateInput.value) || 0;
        const taxAmount = subtotal * (taxRate / 100);
        const total = subtotal + taxAmount;

        taxPreview.textContent = 'Rp ' + Math.round(taxAmount).toLocaleString('id-ID');
        totalPreview.textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
    }

    if (subtotalInput && taxRateInput) {
        subtotalInput.addEventListener('input', calculateCreateTotal);
        taxRateInput.addEventListener('input', calculateCreateTotal);
    }

    if (pphTypeSelect && pphRateInput) {
        pphTypeSelect.addEventListener('change', function () {
            if (this.value === 'pph_23') {
                pphRateInput.value = '2.00';
            } else if (this.value === 'pph_4_2') {
                pphRateInput.value = '2.65';
            } else {
                pphRateInput.value = '0.00';
            }
        });
    }

    // 2. Add Payment Modal Trigger
    document.querySelectorAll('.btn-pay-invoice').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const number = this.getAttribute('data-number');
            const balance = this.getAttribute('data-balance');
            const formattedBalance = this.getAttribute('data-formatted-balance');

            document.getElementById('pay_invoice_number').textContent = number;
            document.getElementById('pay_balance_preview').textContent = formattedBalance;
            document.getElementById('pay_amount_input').value = balance;

            const form = document.getElementById('addPaymentForm');
            form.action = `/dashboard-admin/finance/invoices/${id}/payments`;

            const modal = new bootstrap.Modal(document.getElementById('addPaymentModal'));
            modal.show();
        });
    });

    // 3. Edit Invoice Modal Trigger
    document.querySelectorAll('.btn-edit-invoice').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            document.getElementById('edit_invoice_number').value = this.getAttribute('data-number');
            document.getElementById('edit_customer_id').value = this.getAttribute('data-customer');
            document.getElementById('edit_project_id').value = this.getAttribute('data-project') || '';
            document.getElementById('edit_title').value = this.getAttribute('data-title');
            document.getElementById('edit_invoice_date').value = this.getAttribute('data-date');
            document.getElementById('edit_due_date').value = this.getAttribute('data-due');
            document.getElementById('edit_subtotal').value = this.getAttribute('data-subtotal');
            document.getElementById('edit_tax_rate').value = this.getAttribute('data-tax-rate');
            document.getElementById('edit_pph_type').value = this.getAttribute('data-pph-type');
            document.getElementById('edit_payment_terms').value = this.getAttribute('data-terms');
            document.getElementById('edit_status').value = this.getAttribute('data-status');
            document.getElementById('edit_notes').value = this.getAttribute('data-notes') || '';

            const form = document.getElementById('editInvoiceForm');
            form.action = `/dashboard-admin/finance/invoices/${id}`;

            const modal = new bootstrap.Modal(document.getElementById('editInvoiceModal'));
            modal.show();
        });
    });

    // 4. View Detail Invoice Modal (AJAX)
    document.querySelectorAll('.btn-view-invoice').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const detailBody = document.getElementById('invoiceDetailBody');
            detailBody.innerHTML = '<div class="text-center py-32 text-secondary-light"><span class="spinner-border spinner-border-sm me-2"></span> Memuat data invoice...</div>';

            const modal = new bootstrap.Modal(document.getElementById('invoiceDetailModal'));
            modal.show();

            fetch(`/dashboard-admin/finance/invoices/${id}`)
                .then(res => res.json())
                .then(data => {
                    const inv = data.invoice;
                    let paymentsHtml = '';
                    if (data.payments && data.payments.length > 0) {
                        paymentsHtml = `
                            <table class="table bordered-table mb-0 align-middle text-xs">
                                <thead class="bg-neutral-100">
                                    <tr>
                                        <th>TANGGAL</th>
                                        <th>METODE</th>
                                        <th>NO. REF</th>
                                        <th class="text-end">NOMINAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.payments.map(p => `
                                        <tr>
                                            <td>${new Date(p.payment_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}</td>
                                            <td>${p.payment_method}</td>
                                            <td>${p.reference_no || '-'}</td>
                                            <td class="text-end font-monospace fw-bold text-success-main">Rp ${parseFloat(p.amount).toLocaleString('id-ID')}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        `;
                    } else {
                        paymentsHtml = '<div class="text-center py-16 text-secondary-light text-xs">Belum ada riwayat pembayaran yang dicatat.</div>';
                    }

                    detailBody.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start border-bottom pb-16 mb-16">
                            <div>
                                <span class="badge bg-primary-50 text-primary-600 border border-primary-200 text-xs px-8 py-3 radius-4 mb-2">FAKTUR TAGIHAN</span>
                                <h5 class="fw-bold text-primary-light mb-0 font-monospace">${inv.invoice_number}</h5>
                                <span class="text-xs text-secondary-light">Diterbitkan: ${new Date(inv.invoice_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})} | Jatuh Tempo: ${inv.due_date ? new Date(inv.due_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge ${inv.status === 'paid' ? 'bg-success-focus text-success-main border border-success-subtle' : 'bg-warning-focus text-warning-main border border-warning-subtle'} text-xs px-12 py-6 radius-4 fw-bold text-uppercase">
                                    ${inv.status}
                                </span>
                            </div>
                        </div>

                        <div class="row g-3 mb-20 text-xs">
                            <div class="col-md-6">
                                <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                                    <span class="text-secondary-light d-block mb-1">DITAGIHKAN KEPADA:</span>
                                    <h6 class="fw-bold text-sm text-primary-light mb-1">${data.customer_name}</h6>
                                    <div class="text-secondary-light">PIC: ${data.customer_pic}</div>
                                    <div class="text-secondary-light">NPWP: ${data.customer_npwp}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-12 radius-8 bg-neutral-50 border border-neutral-200">
                                    <span class="text-secondary-light d-block mb-1">REFERENSI PROYEK & KONTRAK:</span>
                                    <h6 class="fw-bold text-sm text-info-main mb-1">${data.project_name}</h6>
                                    <div class="text-secondary-light">Deskripsi: ${inv.title}</div>
                                    <div class="text-secondary-light">Ketentuan: ${inv.payment_terms || '-'}</div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive border radius-8 mb-20">
                            <table class="table mb-0 text-xs align-middle">
                                <tbody>
                                    <tr>
                                        <td class="text-secondary-light py-10 px-16">Nilai Sebelum Pajak (Subtotal)</td>
                                        <td class="text-end font-monospace fw-semibold py-10 px-16">${data.formatted_subtotal}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-secondary-light py-10 px-16">PPN (${parseFloat(inv.tax_rate)}%)</td>
                                        <td class="text-end font-monospace fw-semibold py-10 px-16">${data.formatted_tax_amount}</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td class="fw-bold text-primary-light py-10 px-16">TOTAL TAGIHAN (BRUTO)</td>
                                        <td class="text-end font-monospace fw-bold text-primary-600 fs-6 py-10 px-16">${data.formatted_total_amount}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-success-main py-10 px-16">Total Kas Masuk Diterima</td>
                                        <td class="text-end font-monospace fw-bold text-success-main py-10 px-16">${data.formatted_paid_amount}</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td class="fw-bold text-danger-main py-10 px-16">SISA PIUTANG (BALANCE DUE)</td>
                                        <td class="text-end font-monospace fw-bold text-danger-main fs-6 py-10 px-16">${data.formatted_balance}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6 class="fw-bold text-xs text-uppercase tracking-wider text-primary-light mb-8">Riwayat Pembayaran Kas Masuk</h6>
                        <div class="table-responsive border radius-8">
                            ${paymentsHtml}
                        </div>
                    `;
                })
                .catch(err => {
                    detailBody.innerHTML = '<div class="alert alert-danger text-xs mb-0">Gagal memuat rincian invoice.</div>';
                });
        });
    });
});
</script>
@endsection
