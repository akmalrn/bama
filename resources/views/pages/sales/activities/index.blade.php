@extends('layout.layout')

@php
    $title = 'Sales Activity Register';
    $subTitle = 'Sales CRM';
    $script = '<script>
        document.addEventListener("DOMContentLoaded", function () {
            // Edit modal population
            document.querySelectorAll(".btn-edit-activity").forEach(btn => {
                btn.addEventListener("click", function() {
                    const id = this.getAttribute("data-id");
                    const opp = this.getAttribute("data-opp");
                    const type = this.getAttribute("data-type");
                    const title = this.getAttribute("data-title");
                    const date = this.getAttribute("data-date");
                    const notes = this.getAttribute("data-notes");

                    const form = document.getElementById("editActivityForm");
                    form.action = `/dashboard-admin/sales/activities/${id}`;
                    document.getElementById("edit_opportunity_id").value = opp;
                    document.getElementById("edit_type").value = type;
                    document.getElementById("edit_title").value = title;
                    document.getElementById("edit_activity_at").value = date;
                    document.getElementById("edit_notes").value = notes;
                });
            });

            @if(request()->query("open_create") || (isset($errors) && $errors->any()))
                const createModalEl = document.getElementById("createActivityModal");
                if (createModalEl) {
                    const createModal = new bootstrap.Modal(createModalEl);
                    createModal.show();
                }
            @endif
        });
    </script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <p class="text-secondary-light text-sm mb-0">Log seluruh interaksi, meeting, kunjungan customer, dan follow-up tim Sales.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary text-sm px-16 py-10 radius-8 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createActivityModal">
            <iconify-icon icon="solar:calendar-add-outline" class="icon text-lg"></iconify-icon>
            <span class="fw-semibold">Log Activity</span>
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="text-xl"></iconify-icon>
        <ul class="mb-0 ps-12">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="row g-3 mb-20">
    
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-primary">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Activities</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light">
                            {{ number_format($totalActivities) }}
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:checklist-minimalistic-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Riwayat interaksi sales</span>
                    <span class="badge bg-primary-50 text-primary-700 border border-primary-200">Semua Waktu</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-success">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Customer Touchpoints</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light">
                            {{ number_format($customerTouchpoints) }}
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-main text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:buildings-2-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Customer unik dihubungi</span>
                    <span class="badge bg-success-50 text-success-700 border border-success-200">Customer Aktif</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-purple">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-purple-main">7 Hari Terakhir</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light">
                            {{ number_format($recentCount) }}
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-purple-100 text-purple-main text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Follow-up sepekan ini</span>
                    <span class="badge bg-purple-50 text-purple-700 border border-purple-200">Mingguan</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-warning">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Aktivitas Hari Ini</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light">
                            {{ number_format($todayCount) }}
                        </h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:calendar-date-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Touchpoint tercatat hari ini</span>
                    <span class="badge bg-warning-50 text-warning-main border border-warning-200">Hari Ini</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-20">
    <div class="card-header border-bottom py-14 px-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="card-title mb-0 fw-semibold fs-6">Distribusi Tipe Aktivitas</h6>
            <span class="text-xs text-secondary-light">Sebaran interaksi berdasarkan kategori kegiatan sales</span>
        </div>
        <span class="badge bg-neutral-100 text-secondary-light border px-12 py-6 radius-6 text-xs fw-semibold">
            {{ $totalActivities }} Total Log Tercatat
        </span>
    </div>
    <div class="card-body p-16">
        <div class="row g-3">
            @php
                $typeConfigs = [
                    'outside_meeting' => [
                        'label' => 'Outside Meeting',
                        'icon' => 'solar:users-group-rounded-bold-duotone',
                        'color' => 'primary',
                        'count' => $typeCounts['outside_meeting'] ?? 0,
                    ],
                    'proposal_submitted' => [
                        'label' => 'Proposal Submitted',
                        'icon' => 'solar:document-text-bold-duotone',
                        'color' => 'info',
                        'count' => $typeCounts['proposal_submitted'] ?? 0,
                    ],
                    'online_presentation' => [
                        'label' => 'Online Presentation',
                        'icon' => 'solar:videocamera-record-bold-duotone',
                        'color' => 'warning',
                        'count' => $typeCounts['online_presentation'] ?? 0,
                    ],
                    'customer_visit' => [
                        'label' => 'Customer Visit',
                        'icon' => 'solar:map-point-bold-duotone',
                        'color' => 'purple',
                        'count' => $typeCounts['customer_visit'] ?? 0,
                    ],
                ];
            @endphp
            @foreach($typeConfigs as $cfg)
                @php
                    $pct = $totalActivities > 0 ? round(($cfg['count'] / $totalActivities) * 100, 1) : 0;
                @endphp
                <div class="col-md-3 col-sm-6">
                    <div class="p-12 radius-10 border bg-base h-100 transition-all">
                        <div class="d-flex align-items-center justify-content-between mb-8">
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-{{ $cfg['color'] }}-100 text-{{ $cfg['color'] }}-main text-base">
                                    <iconify-icon icon="{{ $cfg['icon'] }}"></iconify-icon>
                                </div>
                                <span class="text-xs fw-semibold text-primary-light">{{ $cfg['label'] }}</span>
                            </div>
                            <span class="fw-bold text-sm text-{{ $cfg['color'] }}-main">{{ $cfg['count'] }}</span>
                        </div>
                        <div class="progress h-6-px bg-neutral-100 radius-4 mb-6">
                            <div class="progress-bar bg-{{ $cfg['color'] }}-main" role="progressbar" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xxs text-secondary-light d-block text-end">{{ $pct }}% dari total</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-20">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.sales.activities.index') }}" class="row g-3">
            <div class="col-lg-3 col-md-6">
                <label class="form-label text-sm fw-medium">Pencarian</label>
                <div class="position-relative">
                    <input type="text" name="search" class="form-control text-sm pe-40" placeholder="Cari judul, catatan, customer..." value="{{ request('search') }}">
                    <iconify-icon icon="solar:magnifer-outline" class="position-absolute end-0 top-50 translate-middle-y me-12 text-neutral-400"></iconify-icon>
                </div>
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label text-sm fw-medium">Tipe Aktivitas</label>
                <select name="type" class="form-select text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="outside_meeting" {{ request('type') == 'outside_meeting' ? 'selected' : '' }}>Outside Meeting</option>
                    <option value="proposal_submitted" {{ request('type') == 'proposal_submitted' ? 'selected' : '' }}>Proposal Submitted</option>
                    <option value="online_presentation" {{ request('type') == 'online_presentation' ? 'selected' : '' }}>Online Presentation</option>
                    <option value="customer_visit" {{ request('type') == 'customer_visit' ? 'selected' : '' }}>Customer Visit</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label text-sm fw-medium">Opportunity</label>
                <select name="opportunity_id" class="form-select text-sm">
                    <option value="">Semua Opportunity</option>
                    @foreach($opportunities as $opp)
                        <option value="{{ $opp->id }}" {{ request('opportunity_id') == $opp->id ? 'selected' : '' }}>
                            {{ Str::limit($opp->title, 25) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label text-sm fw-medium">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control text-sm" value="{{ request('start_date') }}">
            </div>
            <div class="col-lg-2 col-md-3">
                <label class="form-label text-sm fw-medium">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control text-sm" value="{{ request('end_date') }}">
            </div>
            <div class="col-lg-1 col-md-12 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100 py-10 radius-8">Filter</button>
                @if(request()->anyFilled(['search', 'type', 'opportunity_id', 'start_date', 'end_date']))
                    <a href="{{ route('dashboard-admin.sales.activities.index') }}" class="btn btn-light btn-sm py-10 radius-8" title="Reset">
                        <iconify-icon icon="solar:restart-outline"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm overflow-hidden mb-20">
    <div class="card-header border-bottom py-12 px-20 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="card-title mb-0 fw-semibold fs-6">Daftar Log Aktivitas</h6>
        <span class="badge bg-neutral-100 text-secondary-light border px-10 py-4 radius-6 text-xs fw-semibold">{{ $activities->total() }} Records</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table crm-table-compact basic-border-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-20" style="width: 140px;">Tanggal & Waktu</th>
                        <th style="width: 150px;">Tipe Aktivitas</th>
                        <th>Judul & Catatan</th>
                        <th>Opportunity / Customer</th>
                        <th style="width: 130px;">Logged By</th>
                        <th class="text-end pe-20" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $act)
                        @php
                            $badgeClass = match($act->type) {
                                'outside_meeting' => 'bg-primary-subtle text-primary-main',
                                'proposal_submitted' => 'bg-info-subtle text-info-main',
                                'online_presentation' => 'bg-warning-subtle text-warning-main',
                                'customer_visit' => 'bg-purple-subtle text-purple-main',
                                default => 'bg-neutral-100 text-neutral-700'
                            };
                            $typeLabel = match($act->type) {
                                'outside_meeting' => 'Outside Meeting',
                                'proposal_submitted' => 'Proposal Submitted',
                                'online_presentation' => 'Online Presentation',
                                'customer_visit' => 'Customer Visit',
                                default => ucwords(str_replace('_', ' ', $act->type))
                            };
                        @endphp
                        <tr>
                            <td class="ps-20">
                                <div class="d-flex align-items-center gap-2">
                                    <iconify-icon icon="solar:calendar-date-outline" class="text-neutral-400 text-lg flex-shrink-0"></iconify-icon>
                                    <div class="lh-sm">
                                        <span class="d-block fw-semibold text-xs text-primary-light">{{ $act->activity_at ? $act->activity_at->translatedFormat('d M Y') : '—' }}</span>
                                        <small class="text-secondary-light text-xxs">{{ $act->activity_at ? $act->activity_at->format('H:i') : '' }} WIB</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $badgeClass }} px-8 py-4 radius-4 text-xs fw-semibold">
                                    {{ $typeLabel }}
                                </span>
                            </td>
                            <td>
                                <div class="lh-sm" style="max-width: 320px;">
                                    <span class="fw-semibold text-xs d-block text-primary-light">{{ $act->title }}</span>
                                    @if($act->notes)
                                        <p class="text-secondary-light text-xxs mb-0 text-truncate" title="{{ $act->notes }}">{{ $act->notes }}</p>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($act->opportunity)
                                    <div class="lh-sm" style="max-width: 280px;">
                                        <span class="fw-semibold text-xs d-block text-primary-light text-truncate" title="{{ $act->opportunity->title }}">{{ $act->opportunity->title }}</span>
                                        <small class="text-secondary-light text-xxs d-block text-truncate">{{ $act->opportunity->customer->company_name ?? '—' }}</small>
                                    </div>
                                @else
                                    <span class="text-secondary-light text-xs">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="w-24-px h-24-px rounded-circle bg-neutral-200 text-neutral-700 fw-bold d-flex align-items-center justify-content-center text-xxs flex-shrink-0">
                                        {{ substr($act->user->name ?? 'U', 0, 2) }}
                                    </span>
                                    <span class="text-xs text-primary-light text-truncate" style="max-width: 90px;" title="{{ $act->user->name ?? 'System' }}">{{ $act->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="text-end pe-20">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <button type="button" class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center btn-edit-activity"
                                        data-bs-toggle="modal" data-bs-target="#editActivityModal"
                                        data-id="{{ $act->id }}"
                                        data-opp="{{ $act->opportunity_id }}"
                                        data-type="{{ $act->type }}"
                                        data-title="{{ $act->title }}"
                                        data-date="{{ $act->activity_at ? $act->activity_at->format('Y-m-d\TH:i') : '' }}"
                                        data-notes="{{ $act->notes }}"
                                        title="Edit Aktivitas">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </button>
                                    <form action="{{ route('dashboard-admin.sales.activities.destroy', $act->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Hapus Aktivitas">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-32 text-neutral-400">
                                <iconify-icon icon="solar:clipboard-remove-outline" class="text-4xl mb-8 d-block mx-auto"></iconify-icon>
                                Belum ada aktivitas yang dicatat sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-20 border-top">
            {{ $activities->links() }}
        </div>
    </div>
</div>


<div class="modal fade" id="createActivityModal" tabindex="-1" aria-labelledby="createActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 radius-12 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title" id="createActivityModalLabel">Log Aktivitas Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.sales.activities.store') }}" method="POST">
                @csrf
                <div class="modal-body p-20">
                    <div class="mb-16">
                        <label for="opportunity_id" class="form-label text-sm fw-medium">Opportunity / Customer <span class="text-danger-main">*</span></label>
                        <select name="opportunity_id" id="opportunity_id" class="form-select text-sm" required>
                            <option value="">-- Pilih Opportunity --</option>
                            @foreach($opportunities as $opp)
                                <option value="{{ $opp->id }}" {{ old('opportunity_id') == $opp->id ? 'selected' : '' }}>
                                    {{ $opp->title }} ({{ $opp->customer->company_name ?? '—' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-16">
                        <div class="col-md-6">
                            <label for="type" class="form-label text-sm fw-medium">Tipe Aktivitas <span class="text-danger-main">*</span></label>
                            <select name="type" id="type" class="form-select text-sm" required>
                                <option value="outside_meeting" {{ old('type') == 'outside_meeting' ? 'selected' : '' }}>Outside Meeting</option>
                                <option value="proposal_submitted" {{ old('type') == 'proposal_submitted' ? 'selected' : '' }}>Proposal Submitted</option>
                                <option value="online_presentation" {{ old('type') == 'online_presentation' ? 'selected' : '' }}>Online Presentation</option>
                                <option value="customer_visit" {{ old('type') == 'customer_visit' ? 'selected' : '' }}>Customer Visit</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="activity_at" class="form-label text-sm fw-medium">Tanggal & Waktu <span class="text-danger-main">*</span></label>
                            <input type="datetime-local" name="activity_at" id="activity_at" class="form-control text-sm" value="{{ old('activity_at', now()->format('Y-m-d\TH:i')) }}" required>
                        </div>
                    </div>

                    <div class="mb-16">
                        <label for="title" class="form-label text-sm fw-medium">Ringkasan / Judul Aktivitas <span class="text-danger-main">*</span></label>
                        <input type="text" name="title" id="title" class="form-control text-sm" placeholder="Contoh: Diskusi teknis scope tank cleaning" value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-0">
                        <label for="notes" class="form-label text-sm fw-medium">Catatan / Rencana Tindak Lanjut</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control text-sm" placeholder="Tuliskan catatan hasil meeting, kesepakatan, atau next action...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-20">
                    <button type="button" class="btn btn-secondary text-sm btn-sm px-16 py-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary text-sm btn-sm px-16 py-8">Simpan Aktivitas</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editActivityModal" tabindex="-1" aria-labelledby="editActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 radius-12 shadow">
            <div class="modal-header border-bottom p-20">
                <h6 class="modal-title" id="editActivityModalLabel">Edit Log Aktivitas</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editActivityForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-20">
                    <div class="mb-16">
                        <label for="edit_opportunity_id" class="form-label text-sm fw-medium">Opportunity / Customer <span class="text-danger-main">*</span></label>
                        <select name="opportunity_id" id="edit_opportunity_id" class="form-select text-sm" required>
                            @foreach($opportunities as $opp)
                                <option value="{{ $opp->id }}">
                                    {{ $opp->title }} ({{ $opp->customer->company_name ?? '—' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-16">
                        <div class="col-md-6">
                            <label for="edit_type" class="form-label text-sm fw-medium">Tipe Aktivitas <span class="text-danger-main">*</span></label>
                            <select name="type" id="edit_type" class="form-select text-sm" required>
                                <option value="outside_meeting">Outside Meeting</option>
                                <option value="proposal_submitted">Proposal Submitted</option>
                                <option value="online_presentation">Online Presentation</option>
                                <option value="customer_visit">Customer Visit</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_activity_at" class="form-label text-sm fw-medium">Tanggal & Waktu <span class="text-danger-main">*</span></label>
                            <input type="datetime-local" name="activity_at" id="edit_activity_at" class="form-control text-sm" required>
                        </div>
                    </div>

                    <div class="mb-16">
                        <label for="edit_title" class="form-label text-sm fw-medium">Ringkasan / Judul Aktivitas <span class="text-danger-main">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control text-sm" required>
                    </div>

                    <div class="mb-0">
                        <label for="edit_notes" class="form-label text-sm fw-medium">Catatan / Rencana Tindak Lanjut</label>
                        <textarea name="notes" id="edit_notes" rows="3" class="form-control text-sm"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-20">
                    <button type="button" class="btn btn-secondary text-sm btn-sm px-16 py-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnUpdate" class="btn btn-primary text-sm btn-sm px-16 py-8">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
