@extends('layout.layout')

@php
    $title = 'Laporan Insiden & Near Miss (Incident Register & CAPA)';
    $subTitle = 'Pencatatan Insiden K3, Analisis Akar Masalah (RCA) & Pelacakan Tindakan Perbaikan';
    $script = '<script>
        // Incident scripts
    </script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0 text-primary-light">{{ $title }}</h6>
        <p class="text-xs text-secondary-light mb-0">{{ $subTitle }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        @can('hsse.incident.report')
            <button type="button" class="btn btn-sm btn-danger radius-8 px-14 py-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#createIncidentModal">
                <iconify-icon icon="solar:danger-triangle-bold-duotone" class="text-lg"></iconify-icon>
                Lapor Insiden / Near Miss
            </button>
        @endcan
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show radius-8 mb-24" role="alert">
        <div class="d-flex align-items-center gap-2">
            <iconify-icon icon="solar:check-circle-bold-duotone" class="text-xl"></iconify-icon>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="card radius-12 border-0 shadow-sm mb-20">
    <div class="card-body p-12">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('dashboard-admin.hsse.incidents.index', array_merge(request()->query(), ['status' => 'all'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Semua Kejadian ({{ $counts['all'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.incidents.index', array_merge(request()->query(), ['status' => 'reported'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $statusFilter === 'reported' ? 'btn-danger' : 'btn-outline-secondary' }}">
                Baru Dilaporkan ({{ $counts['reported'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.incidents.index', array_merge(request()->query(), ['status' => 'investigating'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $statusFilter === 'investigating' ? 'btn-info' : 'btn-outline-secondary' }}">
                Investigasi RCA ({{ $counts['investigating'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.incidents.index', array_merge(request()->query(), ['status' => 'action_pending'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $statusFilter === 'action_pending' ? 'btn-warning' : 'btn-outline-secondary' }}">
                Tindakan CAPA Berjalan ({{ $counts['action_pending'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.incidents.index', array_merge(request()->query(), ['status' => 'closed'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $statusFilter === 'closed' ? 'btn-success' : 'btn-outline-secondary' }}">
                Selesai / Closed ({{ $counts['closed'] }})
            </a>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.hsse.incidents.index') }}" class="row g-2 align-items-center">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <div class="col-lg-4 col-md-6">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm radius-8" placeholder="Cari No. Laporan, Judul, Lokasi, Uraian...">
            </div>
            <div class="col-lg-4 col-md-6">
                <select name="incident_type" class="form-select form-select-sm radius-8">
                    <option value="all">Semua Jenis / Klasifikasi Insiden</option>
                    <option value="Near Miss" {{ $typeFilter === 'Near Miss' ? 'selected' : '' }}>Near Miss (Hampir Celaka)</option>
                    <option value="First Aid Case (FAC)" {{ $typeFilter === 'First Aid Case (FAC)' ? 'selected' : '' }}>First Aid Case (FAC - Pertolongan Pertama)</option>
                    <option value="Medical Treatment Case (MTC)" {{ $typeFilter === 'Medical Treatment Case (MTC)' ? 'selected' : '' }}>Medical Treatment Case (MTC - Medis)</option>
                    <option value="Lost Time Injury (LTI)" {{ $typeFilter === 'Lost Time Injury (LTI)' ? 'selected' : '' }}>Lost Time Injury (LTI - Kehilangan Hari Kerja)</option>
                    <option value="Restricted Work Day (RWC)" {{ $typeFilter === 'Restricted Work Day (RWC)' ? 'selected' : '' }}>Restricted Work Day (RWC)</option>
                    <option value="Property Damage" {{ $typeFilter === 'Property Damage' ? 'selected' : '' }}>Property Damage (Kerusakan Aset/Alat)</option>
                    <option value="Environmental Spill" {{ $typeFilter === 'Environmental Spill' ? 'selected' : '' }}>Environmental Spill (Pencemaran Lingkungan)</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-8">
                <select name="project_id" class="form-select form-select-sm radius-8">
                    <option value="all">Semua Site / Proyek</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $projectFilter == $p->id ? 'selected' : '' }}>{{ Str::limit($p->title, 30) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-1 col-md-4 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary radius-8 w-100 py-8">Filter</button>
            </div>
        </form>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-xs text-secondary-light text-uppercase">
                        <th class="py-12 px-20">No. Laporan & Insiden</th>
                        <th class="py-12 px-16">Klasifikasi</th>
                        <th class="py-12 px-16">Site / Lokasi</th>
                        <th class="py-12 px-16">Tanggal & Pelapor</th>
                        <th class="py-12 px-16">Akar Masalah (RCA) & CAPA</th>
                        <th class="py-12 px-16">Status</th>
                        <th class="py-12 px-20 text-center" width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($incidents as $incident)
                        <tr>
                            <td class="py-12 px-20">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-primary-light">{{ $incident->incident_number }}</span>
                                    <span class="text-xs text-secondary-light fw-medium">{{ Str::limit($incident->title, 40) }}</span>
                                    @if($incident->lost_workdays > 0)
                                        <span class="badge bg-danger-main text-white w-auto mt-1 align-self-start text-2xs">{{ $incident->lost_workdays }} Hari Kerja Hilang</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-12 px-16">
                                <span class="badge {{ $incident->type_badge_class }} text-xs px-8 py-4 radius-6">
                                    {{ $incident->incident_type }}
                                </span>
                            </td>
                            <td class="py-12 px-16 text-xs">
                                <span class="d-block fw-medium text-primary-light">{{ $incident->project ? Str::limit($incident->project->title, 25) : 'General Plant' }}</span>
                                <span class="text-secondary-light"><i class="ri-map-pin-line me-1"></i>{{ $incident->location }}</span>
                            </td>
                            <td class="py-12 px-16 text-xs text-secondary-light">
                                <div>{{ $incident->incident_date ? $incident->incident_date->format('d M Y H:i') : '-' }}</div>
                                <div class="text-primary-light">Pelapor: {{ $incident->reporter ? ($incident->reporter->user ? $incident->reporter->user->name : $incident->reporter->employee_number) : '-' }}</div>
                                @if($incident->injuredPerson)
                                    <div class="text-danger-600 text-2xs">Korban: {{ $incident->injuredPerson->user ? $incident->injuredPerson->user->name : $incident->injuredPerson->employee_number }}</div>
                                @endif
                            </td>
                            <td class="py-12 px-16 text-xs" style="max-width: 280px;">
                                <div class="text-secondary-light text-truncate" title="{{ $incident->root_cause }}">
                                    <strong class="text-primary-light">RCA:</strong> {{ $incident->root_cause ?? 'Dalam investigasi...' }}
                                </div>
                                @if($incident->capa_action)
                                    <div class="text-primary-600 text-2xs mt-1 text-truncate" title="{{ $incident->capa_action }}">
                                        <strong>CAPA:</strong> {{ $incident->capa_action }}
                                    </div>
                                @endif
                                @if($incident->target_date)
                                    <div class="text-2xs text-secondary-light mt-1">
                                        Target: {{ $incident->target_date->format('d M Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-12 px-16">
                                <span class="badge {{ $incident->status_badge_class }} text-xs px-10 py-6 radius-6">
                                    {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                </span>
                            </td>
                            <td class="py-12 px-20 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    @can('hsse.incident.report')
                                        <button type="button" class="w-32-px h-32-px rounded-circle bg-warning-focus text-warning-main d-inline-flex justify-content-center align-items-center border-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#updateCapaModal{{ $incident->id }}"
                                                title="Update RCA & CAPA">
                                            <iconify-icon icon="solar:checklist-minimalistic-bold-duotone"></iconify-icon>
                                        </button>
                                        <form action="{{ route('dashboard-admin.hsse.incidents.destroy', $incident) }}" method="POST" onsubmit="return confirm('Hapus laporan insiden {{ $incident->incident_number }}?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus Laporan">
                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>
                                        </form>
                                    @endcan
                                </div>

                                
                                <div class="modal fade" id="updateCapaModal{{ $incident->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content radius-12 border-0 text-start">
                                            <div class="modal-header border-bottom py-16 px-20">
                                                <h6 class="modal-title fs-6 fw-semibold text-primary-light">Update Investigasi & CAPA: {{ $incident->incident_number }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('dashboard-admin.hsse.incidents.capa', $incident) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body p-20">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label text-xs fw-semibold text-secondary-light">Status Penyelesaian <span class="text-danger">*</span></label>
                                                            <select name="status" class="form-select form-select-sm radius-8" required>
                                                                <option value="reported" {{ $incident->status === 'reported' ? 'selected' : '' }}>Reported (Baru Dilaporkan)</option>
                                                                <option value="investigating" {{ $incident->status === 'investigating' ? 'selected' : '' }}>Investigating (Investigasi Lapangan)</option>
                                                                <option value="action_pending" {{ $incident->status === 'action_pending' ? 'selected' : '' }}>Action Pending (Tindakan Korektif Sedang Berjalan)</option>
                                                                <option value="closed" {{ $incident->status === 'closed' ? 'selected' : '' }}>Closed (Tuntas & Diverifikasi)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label text-xs fw-semibold text-secondary-light">Target Tanggal Selesai</label>
                                                            <input type="date" name="target_date" class="form-control form-control-sm radius-8" value="{{ $incident->target_date ? $incident->target_date->format('Y-m-d') : '' }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label text-xs fw-semibold text-secondary-light">Lost Workdays (Hari)</label>
                                                            <input type="number" name="lost_workdays" class="form-control form-control-sm radius-8" value="{{ $incident->lost_workdays ?? 0 }}" min="0">
                                                        </div>

                                                        <div class="col-12">
                                                            <label class="form-label text-xs fw-semibold text-secondary-light">Analisis Akar Masalah (Root Cause Analysis - RCA)</label>
                                                            <textarea name="root_cause" rows="3" class="form-control form-control-sm radius-8" placeholder="Identifikasi faktor manusia, alat, prosedur, atau lingkungan...">{{ $incident->root_cause }}</textarea>
                                                        </div>

                                                        <div class="col-12">
                                                            <label class="form-label text-xs fw-semibold text-secondary-light">Tindakan Perbaikan & Pencegahan (CAPA Action Plan)</label>
                                                            <textarea name="capa_action" rows="3" class="form-control form-control-sm radius-8" placeholder="Rencana aksi konkret untuk mencegah kejadian berulang...">{{ $incident->capa_action }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top py-12 px-20">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-sm btn-primary radius-8">Simpan Pembaruan CAPA</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-32 text-secondary-light">
                                Tidak ada data laporan insiden yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-16 border-top">
            {{ $incidents->links() }}
        </div>
    </div>
</div>


<div class="modal fade" id="createIncidentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-12 border-0">
            <div class="modal-header border-bottom py-16 px-20">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-32-px h-32-px radius-6 bg-danger-50 text-danger-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:danger-triangle-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title fs-6 fw-semibold text-primary-light">Lapor Kejadian Insiden / Near Miss Baru</h6>
                        <span class="text-xs text-secondary-light">Formulir notifikasi insiden keselamatan kerja, lingkungan & properti</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.hsse.incidents.store') }}" method="POST">
                @csrf
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Nomor Insiden <span class="text-danger">*</span></label>
                            <input type="text" name="incident_number" class="form-control form-control-sm radius-8 bg-light" value="{{ $nextIncidentNumber }}" required readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Judul / Ringkasan Insiden <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm radius-8" placeholder="Contoh: Sambungan Selang Waterjet Lepas Saat UHP Hydrojetting" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Jenis / Klasifikasi <span class="text-danger">*</span></label>
                            <select name="incident_type" class="form-select form-select-sm radius-8" required>
                                <option value="Near Miss" selected>Near Miss (Hampir Celaka)</option>
                                <option value="First Aid Case (FAC)">First Aid Case (FAC)</option>
                                <option value="Medical Treatment Case (MTC)">Medical Treatment Case (MTC)</option>
                                <option value="Lost Time Injury (LTI)">Lost Time Injury (LTI)</option>
                                <option value="Restricted Work Day (RWC)">Restricted Work Day (RWC)</option>
                                <option value="Property Damage">Property Damage</option>
                                <option value="Environmental Spill">Environmental Spill</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Waktu Terjadinya Insiden <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="incident_date" class="form-control form-control-sm radius-8" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Status Awal <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm radius-8" required>
                                <option value="reported" selected>Reported (Baru Dilaporkan)</option>
                                <option value="investigating">Investigating (Langsung Investigasi)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Proyek / Kontrak Terkait</label>
                            <select name="project_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Non-Proyek / Area Fasilitas Umum --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Lokasi Kejadian <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control form-control-sm radius-8" placeholder="Contoh: Unit 40 Ammonia Plant PKT Bontang" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Pelapor Kejadian (Reporter)</label>
                            <select name="reporter_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Pilih Karyawan Pelapor --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->user ? $emp->user->name : $emp->employee_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Korban / Pihak Terluka (Jika ada)</label>
                            <select name="injured_person_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Tidak Ada Korban Jiwa / Nihil --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->user ? $emp->user->name : $emp->employee_number }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Kronologi & Uraian Lengkap Insiden <span class="text-danger">*</span></label>
                            <textarea name="description" rows="3" class="form-control form-control-sm radius-8" placeholder="Deskripsikan urutan kejadian, kondisi sebelum dan sesaat insiden..." required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Tindakan Langsung di Tempat (Immediate Action)</label>
                            <textarea name="immediate_action" rows="2" class="form-control form-control-sm radius-8" placeholder="Contoh: Matikan mesin, isolasi area barikade 10 meter, beri P3K..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Rencana Tindakan Korektif (CAPA)</label>
                            <textarea name="capa_action" rows="2" class="form-control form-control-sm radius-8" placeholder="Rekomendasi perbaikan sistem, SOP, atau peralatan..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Analisis Sementara Akar Masalah (RCA)</label>
                            <textarea name="root_cause" rows="2" class="form-control form-control-sm radius-8" placeholder="Dugaan penyebab utama kegagalan..."></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Target Tanggal Selesai CAPA</label>
                            <input type="date" name="target_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Lost Workdays (Hari)</label>
                            <input type="number" name="lost_workdays" class="form-control form-control-sm radius-8" value="0" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-12 px-20">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger radius-8">Kirim Laporan Insiden</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
