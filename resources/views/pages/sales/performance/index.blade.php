@extends('layout.layout')

@php
    $title = 'Sales Performance';
    $subTitle = 'Sales CRM';
    $script = '';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0">Sales Performance Leaderboard</h6>
        <p class="text-neutral-500 text-sm mb-0">Pantau performa, pencapaian target kontrak (Closed Won), volume proposal, dan aktivitas setiap Sales Person.</p>
    </div>
</div>


<div class="card border-0 shadow-sm radius-12 mb-24">
    <div class="card-body p-20">
        <form method="GET" action="{{ route('dashboard-admin.sales.performance.index') }}" class="row g-3">
            <div class="col-lg-3 col-md-4">
                <label class="form-label text-sm fw-medium">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control text-sm" value="{{ $startDate }}">
            </div>
            <div class="col-lg-3 col-md-4">
                <label class="form-label text-sm fw-medium">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control text-sm" value="{{ $endDate }}">
            </div>
            <div class="col-lg-4 col-md-4">
                <label class="form-label text-sm fw-medium">Sales Person</label>
                <select name="owner_id" class="form-select text-sm">
                    <option value="">Semua Sales Person</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ $ownerFilter == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-12 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100 py-10">Terapkan</button>
                @if(request()->anyFilled(['start_date', 'end_date', 'owner_id']))
                    <a href="{{ route('dashboard-admin.sales.performance.index') }}" class="btn btn-light btn-sm py-10" title="Reset">
                        <iconify-icon icon="solar:restart-outline"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>


<div class="row g-3 mb-20">
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-success">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Total Closed Won</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light fs-5">Rp {{ number_format($grandWonValue, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-success-100 text-success-main text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:cup-star-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Total kontrak dimenangkan</span>
                    <span class="badge bg-success-50 text-success-700 border border-success-200">Revenue Won</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-primary">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Total Pipeline Value</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light fs-5">Rp {{ number_format($grandOppValue, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-primary-100 text-primary-600 text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:chart-2-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Potensi opportunity aktif</span>
                    <span class="badge bg-primary-50 text-primary-700 border border-primary-200">Pipeline</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-warning">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Nilai Proposal Register</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light fs-5">Rp {{ number_format($grandProposalValue, 0, ',', '.') }}</h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-warning-100 text-warning-main text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Proposal resmi diterbitkan</span>
                    <span class="badge bg-warning-50 text-warning-main border border-warning-200">Komersial</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 overflow-hidden position-relative crm-kpi-card crm-kpi-purple">
            <div class="card-body p-16 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-8">
                    <div>
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-purple-main">Activities Logged</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary-light fs-5">{{ number_format($grandActivities) }}</h4>
                    </div>
                    <div class="w-40-px h-40-px radius-8 d-flex justify-content-center align-items-center bg-purple-100 text-purple-main text-xl shadow-xs flex-shrink-0">
                        <iconify-icon icon="solar:calendar-mark-bold-duotone"></iconify-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                    <span>Meeting, survey, & visit customer</span>
                    <span class="badge bg-purple-50 text-purple-700 border border-purple-200">Aktivitas</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-24">
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
            <div class="card-header border-bottom py-14 px-16">
                <div class="d-flex align-items-center gap-2">
                    <span class="w-28-px h-28-px rounded-circle bg-success-subtle text-success-main d-flex align-items-center justify-content-center text-sm">
                        <iconify-icon icon="solar:medal-ribbons-star-outline"></iconify-icon>
                    </span>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Top Closed Won Value</h6>
                </div>
            </div>
            <div class="card-body p-16">
                <div class="d-flex flex-column gap-3">
                    @forelse($rankedByWonValue->take(4) as $idx => $r)
                        @php
                            $maxWon = $rankedByWonValue->first()['won_value'] ?? 1;
                            $pct = $maxWon > 0 ? ($r['won_value'] / $maxWon * 100) : 0;
                        @endphp
                        <div>
                            <div class="d-flex justify-content-between text-xs mb-1">
                                <span class="fw-semibold text-primary-light">#{{ $idx + 1 }} {{ $r['user']->name }}</span>
                                <span class="text-success-main fw-bold font-monospace">Rp {{ number_format($r['won_value'], 0, ',', '.') }}</span>
                            </div>
                            <div class="progress h-4-px bg-neutral-100">
                                <div class="progress-bar bg-success-main" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <span class="text-xs text-secondary-light">Belum ada kontrak won</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
            <div class="card-header border-bottom py-14 px-16">
                <div class="d-flex align-items-center gap-2">
                    <span class="w-28-px h-28-px rounded-circle bg-warning-subtle text-warning-main d-flex align-items-center justify-content-center text-sm">
                        <iconify-icon icon="solar:document-add-outline"></iconify-icon>
                    </span>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Top Proposal Submissions</h6>
                </div>
            </div>
            <div class="card-body p-16">
                <div class="d-flex flex-column gap-3">
                    @forelse($rankedByProposals->take(4) as $idx => $r)
                        @php
                            $maxProps = $rankedByProposals->first()['proposal_count'] ?? 1;
                            $pct = $maxProps > 0 ? ($r['proposal_count'] / $maxProps * 100) : 0;
                        @endphp
                        <div>
                            <div class="d-flex justify-content-between text-xs mb-1">
                                <span class="fw-semibold text-primary-light">#{{ $idx + 1 }} {{ $r['user']->name }}</span>
                                <span class="text-warning-main fw-bold">{{ $r['proposal_count'] }} Proposal</span>
                            </div>
                            <div class="progress h-4-px bg-neutral-100">
                                <div class="progress-bar bg-warning-main" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <span class="text-xs text-secondary-light">Belum ada data proposal</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="card border-0 shadow-sm radius-12 overflow-hidden h-100">
            <div class="card-header border-bottom py-14 px-16">
                <div class="d-flex align-items-center gap-2">
                    <span class="w-28-px h-28-px rounded-circle bg-info-subtle text-info-main d-flex align-items-center justify-content-center text-sm">
                        <iconify-icon icon="solar:walking-outline"></iconify-icon>
                    </span>
                    <h6 class="text-sm fw-semibold mb-0 text-primary-light">Top Activity & Customer Visit</h6>
                </div>
            </div>
            <div class="card-body p-16">
                <div class="d-flex flex-column gap-3">
                    @forelse($rankedByActivities->take(4) as $idx => $r)
                        @php
                            $maxActs = $rankedByActivities->first()['activity_count'] ?? 1;
                            $pct = $maxActs > 0 ? ($r['activity_count'] / $maxActs * 100) : 0;
                        @endphp
                        <div>
                            <div class="d-flex justify-content-between text-xs mb-1">
                                <span class="fw-semibold text-primary-light">#{{ $idx + 1 }} {{ $r['user']->name }}</span>
                                <span class="text-info-main fw-bold">{{ $r['activity_count'] }} Log ({{ $r['customer_visits'] }} Visit)</span>
                            </div>
                            <div class="progress h-4-px bg-neutral-100">
                                <div class="progress-bar bg-info-main" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <span class="text-xs text-secondary-light">Belum ada data aktivitas</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm radius-12 overflow-hidden mb-24">
    <div class="card-header border-bottom py-16 px-24 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="fw-bold text-primary-light mb-0 text-sm">Rincian Kinerja Sales Person</h6>
            <span class="text-2xs text-secondary-light">Komparasi komprehensif aktivitas, opportunity, proposal, deals won, dan win rate per sales person.</span>
        </div>
        <span class="badge bg-neutral-100 text-secondary-light border border-neutral-200 text-2xs px-10 py-4 radius-4 fw-medium">
            • {{ $performances->count() }} orang
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-100">
                    <tr>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-primary-light">SALES PERSON</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-center text-primary-light">ACTIVITIES</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-center text-primary-light">OPPORTUNITIES</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-end text-primary-light">PIPELINE VALUE</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-center text-primary-light">PROPOSALS</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-end text-primary-light">PROPOSAL VALUE</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-center text-primary-light">CLOSED WON</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-end text-primary-light">WON VALUE</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-center text-primary-light">WIN RATE</th>
                        <th class="text-xs fw-bold text-uppercase py-14 px-16 text-center text-primary-light" width="70">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($performances as $p)
                        <tr>
                            <td class="px-16 py-12">
                                <div class="d-flex align-items-center gap-12">
                                    <span class="w-36-px h-36-px rounded-circle bg-primary-50 text-primary-600 fw-bold d-flex align-items-center justify-content-center text-xs flex-shrink-0 border border-primary-100">
                                        {{ strtoupper(substr($p['user']->name, 0, 2)) }}
                                    </span>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-sm text-primary-light text-nowrap">{{ $p['user']->name }}</span>
                                        <small class="text-secondary-light text-2xs text-nowrap">{{ $p['user']->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-16 py-12 text-center">
                                <span class="fw-bold text-sm text-primary-light d-block">{{ $p['activity_count'] }}</span>
                                <span class="text-2xs text-secondary-light text-nowrap">{{ $p['customer_visits'] }} visits</span>
                            </td>
                            <td class="px-16 py-12 text-center">
                                <span class="badge bg-neutral-100 text-primary-light border border-neutral-200 px-10 py-4 radius-4 text-xs fw-semibold">
                                    {{ $p['opportunities_count'] }}
                                </span>
                            </td>
                            <td class="px-16 py-12 text-end">
                                <span class="fw-semibold text-sm font-monospace text-nowrap text-primary-light">
                                    Rp {{ number_format($p['opportunities_value'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-16 py-12 text-center">
                                <span class="badge bg-neutral-100 text-primary-light border border-neutral-200 px-10 py-4 radius-4 text-xs fw-semibold">
                                    {{ $p['proposal_count'] }}
                                </span>
                            </td>
                            <td class="px-16 py-12 text-end">
                                <span class="fw-semibold text-sm font-monospace text-nowrap text-primary-light">
                                    Rp {{ number_format($p['proposal_value'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-16 py-12 text-center">
                                <span class="badge {{ $p['won_count'] > 0 ? 'bg-success-focus text-success-600 border border-success-subtle' : 'bg-neutral-100 text-secondary-light border border-neutral-200' }} px-10 py-4 radius-4 text-xs fw-semibold text-nowrap">
                                    {{ $p['won_count'] }} Deals
                                </span>
                            </td>
                            <td class="px-16 py-12 text-end">
                                <span class="fw-bold text-sm font-monospace text-nowrap {{ $p['won_value'] > 0 ? 'text-success-600' : 'text-secondary-light' }}">
                                    Rp {{ number_format($p['won_value'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-16 py-12 text-center">
                                @php
                                    $rate = $p['win_rate'];
                                    $rateBadge = 'bg-neutral-100 text-secondary-light border border-neutral-200';
                                    if ($rate >= 50) {
                                        $rateBadge = 'bg-success-focus text-success-600 border border-success-subtle';
                                    } elseif ($rate >= 25) {
                                        $rateBadge = 'bg-info-focus text-info-600 border border-info-subtle';
                                    } elseif ($rate > 0) {
                                        $rateBadge = 'bg-warning-focus text-warning-600 border border-warning-subtle';
                                    }
                                @endphp
                                <span class="badge {{ $rateBadge }} px-10 py-4 radius-4 text-xs fw-bold text-nowrap">
                                    {{ $rate }}%
                                </span>
                            </td>
                            <td class="px-16 py-12 text-center">
                                <a href="{{ route('dashboard-admin.sales.performance.index', ['owner_id' => $p['user']->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="w-32-px h-32-px bg-primary-50 text-primary-600 hover-bg-primary-600 hover-text-white rounded-circle d-inline-flex align-items-center justify-content-center transition-2" 
                                   title="Filter dashboard untuk {{ $p['user']->name }}">
                                    <iconify-icon icon="solar:filter-bold-duotone" class="text-base"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-32 text-secondary-light">
                                Belum ada data sales person pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
