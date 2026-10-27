@extends('layout.layout')

@php
    $title = 'Safety Permit to Work (PTW) & JSA';
    $subTitle = 'Registrasi Izin Kerja Berisiko Tinggi, Job Safety Analysis & Pengujian Gas';
    $script = '<script>
        function toggleGasInputs(checkbox) {
            const container = document.getElementById("gasInputsContainer");
            if (checkbox.checked) {
                container.classList.remove("d-none");
            } else {
                container.classList.add("d-none");
            }
        }
    </script>';
@endphp

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <div>
        <h6 class="fw-semibold mb-0 text-primary-light">{{ $title }}</h6>
        <p class="text-xs text-secondary-light mb-0">{{ $subTitle }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        @can('hsse.permit.issue')
            <button type="button" class="btn btn-sm btn-primary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#createPermitModal">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="text-lg"></iconify-icon>
                Terbitkan PTW Baru
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

{{-- FILTER TABS STATUS --}}
<div class="card radius-12 border-0 shadow-sm mb-20">
    <div class="card-body p-12">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('dashboard-admin.hsse.permits.index', array_merge(request()->query(), ['status' => 'all'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Semua PTW ({{ $counts['all'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.permits.index', array_merge(request()->query(), ['status' => 'active'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $statusFilter === 'active' ? 'btn-success' : 'btn-outline-secondary' }}">
                Aktif Lapangan ({{ $counts['active'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.permits.index', array_merge(request()->query(), ['status' => 'approved'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $statusFilter === 'approved' ? 'btn-info' : 'btn-outline-secondary' }}">
                Disetujui ({{ $counts['approved'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.permits.index', array_merge(request()->query(), ['status' => 'under_review'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $statusFilter === 'under_review' ? 'btn-warning' : 'btn-outline-secondary' }}">
                Under Review ({{ $counts['under_review'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.permits.index', array_merge(request()->query(), ['status' => 'closed'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $statusFilter === 'closed' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                Selesai / Ditutup ({{ $counts['closed'] }})
            </a>
        </div>
    </div>
</div>

{{-- SEARCH & FILTER BAR --}}
<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.hsse.permits.index') }}" class="row g-2 align-items-center">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <div class="col-lg-3 col-md-6">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm radius-8" placeholder="Cari No. PTW, Judul, Lokasi...">
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="permit_type" class="form-select form-select-sm radius-8">
                    <option value="all">Semua Jenis Pekerjaan</option>
                    <option value="Hot Work" {{ $typeFilter === 'Hot Work' ? 'selected' : '' }}>Hot Work (Pengelasan/Api)</option>
                    <option value="Confined Space" {{ $typeFilter === 'Confined Space' ? 'selected' : '' }}>Confined Space (Ruang Terbatas)</option>
                    <option value="Working at Height" {{ $typeFilter === 'Working at Height' ? 'selected' : '' }}>Working at Height (Ketinggian)</option>
                    <option value="Electrical Isolation" {{ $typeFilter === 'Electrical Isolation' ? 'selected' : '' }}>Electrical Isolation (LOTO)</option>
                    <option value="Lifting Operation" {{ $typeFilter === 'Lifting Operation' ? 'selected' : '' }}>Lifting & Rigging</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="risk_level" class="form-select form-select-sm radius-8">
                    <option value="all">Semua Tingkat Risiko</option>
                    <option value="Low" {{ $riskFilter === 'Low' ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ $riskFilter === 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High" {{ $riskFilter === 'High' ? 'selected' : '' }}>High</option>
                    <option value="Critical" {{ $riskFilter === 'Critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4">
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

{{-- PERMITS REGISTER TABLE --}}
<div class="card radius-12 border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-xs text-secondary-light text-uppercase">
                        <th class="py-12 px-20">No. PTW & Pekerjaan</th>
                        <th class="py-12 px-16">Site / Proyek</th>
                        <th class="py-12 px-16">Risk & Gas Test</th>
                        <th class="py-12 px-16">Pemohon & Safety Officer</th>
                        <th class="py-12 px-16">Validitas Waktu</th>
                        <th class="py-12 px-16">Status</th>
                        <th class="py-12 px-20 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($permits as $permit)
                        <tr>
                            <td class="py-12 px-20">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-primary-light">{{ $permit->permit_number }}</span>
                                    <span class="text-xs text-secondary-light">{{ Str::limit($permit->title, 40) }}</span>
                                    <span class="badge bg-neutral-100 text-secondary-light w-auto mt-1 align-self-start text-2xs">{{ $permit->permit_type }}</span>
                                </div>
                            </td>
                            <td class="py-12 px-16">
                                <span class="d-block fw-medium text-xs text-primary-light">{{ $permit->project ? Str::limit($permit->project->title, 25) : 'General Plant' }}</span>
                                <span class="text-xs text-secondary-light"><i class="ri-map-pin-line me-1"></i>{{ $permit->location }}</span>
                            </td>
                            <td class="py-12 px-16">
                                <span class="badge {{ $permit->risk_badge_class }} text-xs px-8 py-4 radius-6">
                                    {{ $permit->risk_level }}
                                </span>
                                @if($permit->gas_test_required)
                                    <div class="mt-1 p-6 bg-light radius-6 text-2xs">
                                        <div class="fw-bold text-secondary-light">Gas Reading:</div>
                                        <div>O₂: {{ $permit->gas_test_o2 ?? '-' }}% | LEL: {{ $permit->gas_test_lel ?? '-' }}%</div>
                                        <div>CO: {{ $permit->gas_test_co ?? '-' }} ppm | H₂S: {{ $permit->gas_test_h2s ?? '-' }} ppm</div>
                                    </div>
                                @endif
                            </td>
                            <td class="py-12 px-16 text-xs">
                                <div><span class="text-secondary-light">Applicant:</span> {{ $permit->applicant ? ($permit->applicant->user ? $permit->applicant->user->name : $permit->applicant->employee_number) : '-' }}</div>
                                <div><span class="text-secondary-light">Safety Officer:</span> {{ $permit->safetyOfficer ? ($permit->safetyOfficer->user ? $permit->safetyOfficer->user->name : $permit->safetyOfficer->employee_number) : '-' }}</div>
                            </td>
                            <td class="py-12 px-16 text-xs text-secondary-light">
                                <div>Mulai: {{ $permit->start_time ? $permit->start_time->format('d M Y H:i') : '-' }}</div>
                                <div>Selesai: {{ $permit->end_time ? $permit->end_time->format('d M Y H:i') : '-' }}</div>
                            </td>
                            <td class="py-12 px-16">
                                <span class="badge {{ $permit->status_badge_class }} text-xs px-10 py-6 radius-6">
                                    {{ ucfirst(str_replace('_', ' ', $permit->status)) }}
                                </span>
                            </td>
                            <td class="py-12 px-20 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary radius-8 px-8 py-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <iconify-icon icon="solar:menu-dots-bold"></iconify-icon>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 radius-8">
                                        @can('hsse.permit.issue')
                                            <li>
                                                <button class="dropdown-item text-xs d-flex align-items-center gap-2 py-8"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#updateStatusModal{{ $permit->id }}">
                                                    <iconify-icon icon="solar:check-read-linear" class="text-warning-main"></iconify-icon>
                                                    Ubah Status PTW
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <form action="{{ route('dashboard-admin.hsse.permits.destroy', $permit) }}" method="POST" onsubmit="return confirm('Hapus izin kerja {{ $permit->permit_number }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-xs text-danger-600 d-flex align-items-center gap-2 py-8">
                                                        <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon>
                                                        Hapus Izin
                                                    </button>
                                                </form>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>

                                {{-- Modal Update Status --}}
                                <div class="modal fade" id="updateStatusModal{{ $permit->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content radius-12 border-0 text-start">
                                            <div class="modal-header border-bottom py-16 px-20">
                                                <h6 class="modal-title fs-6 fw-semibold text-primary-light">Update Status PTW: {{ $permit->permit_number }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('dashboard-admin.hsse.permits.status', $permit) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body p-20">
                                                    <div class="mb-16">
                                                        <label class="form-label text-xs fw-semibold text-secondary-light">Status Baru</label>
                                                        <select name="status" class="form-select form-select-sm radius-8" required>
                                                            <option value="draft" {{ $permit->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                                            <option value="under_review" {{ $permit->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                                            <option value="approved" {{ $permit->status === 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                                                            <option value="active" {{ $permit->status === 'active' ? 'selected' : '' }}>Active (Pekerjaan Berlangsung)</option>
                                                            <option value="closed" {{ $permit->status === 'closed' ? 'selected' : '' }}>Closed (Selesai & Sign-off)</option>
                                                            <option value="rejected" {{ $permit->status === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak / Batal)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-16">
                                                        <label class="form-label text-xs fw-semibold text-secondary-light">Catatan Pengawas / Rekomendasi Tambahan</label>
                                                        <textarea name="notes" rows="3" class="form-control form-control-sm radius-8" placeholder="Misal: Gas test wajib diulang per 2 jam...">{{ $permit->notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top py-12 px-20">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-sm btn-primary radius-8">Simpan Perubahan</button>
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
                                Tidak ada data Permit to Work (PTW) yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-16 border-top">
            {{ $permits->links() }}
        </div>
    </div>
</div>

{{-- MODAL CREATE PERMIT TO WORK --}}
<div class="modal fade" id="createPermitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-12 border-0">
            <div class="modal-header border-bottom py-16 px-20">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-32-px h-32-px radius-6 bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:document-text-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title fs-6 fw-semibold text-primary-light">Terbitkan Permit to Work (PTW) & JSA</h6>
                        <span class="text-xs text-secondary-light">Form izin kerja aman dengan verifikasi APD dan bahaya lingkungan</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.hsse.permits.store') }}" method="POST">
                @csrf
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Nomor PTW <span class="text-danger">*</span></label>
                            <input type="text" name="permit_number" class="form-control form-control-sm radius-8 bg-light" value="{{ $nextPermitNumber }}" required readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Judul / Uraian Pekerjaan <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm radius-8" placeholder="Contoh: Hot Bolting Flange HP Separator Area C-301" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Kategori Pekerjaan <span class="text-danger">*</span></label>
                            <select name="permit_type" class="form-select form-select-sm radius-8" required>
                                <option value="Hot Work">Hot Work (Pengelasan/Api)</option>
                                <option value="Confined Space">Confined Space (Ruang Terbatas)</option>
                                <option value="Working at Height">Working at Height (Ketinggian)</option>
                                <option value="Electrical Isolation">Electrical Isolation (LOTO)</option>
                                <option value="Lifting Operation">Lifting & Rigging</option>
                                <option value="Cold Work">Cold Work (Umum)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Tingkat Risiko <span class="text-danger">*</span></label>
                            <select name="risk_level" class="form-select form-select-sm radius-8" required>
                                <option value="Low">Low Risk</option>
                                <option value="Medium">Medium Risk</option>
                                <option value="High" selected>High Risk</option>
                                <option value="Critical">Critical Risk</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Status Permohonan <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm radius-8" required>
                                <option value="draft">Draft</option>
                                <option value="under_review" selected>Under Review</option>
                                <option value="approved">Approved</option>
                                <option value="active">Active</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Proyek / Kontrak Terkait</label>
                            <select name="project_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Non-Proyek / Plant Maintenance Umum --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }} ({{ $p->customer ? $p->customer->company_name : 'No Client' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Lokasi Spesifik / Unit Tag <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control form-control-sm radius-8" placeholder="Contoh: Unit 40 Ammonia Plant, Lantai 3 Converter" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Pemohon Izin (Applicant / Supervisor)</label>
                            <select name="applicant_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->user ? $emp->user->name : $emp->employee_number }} ({{ $emp->position ? $emp->position->name : 'Staff' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Safety Officer / Pengawas K3</label>
                            <select name="safety_officer_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Pilih Safety Officer --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->user ? $emp->user->name : $emp->employee_number }} ({{ $emp->position ? $emp->position->name : 'Staff' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Waktu Mulai Pekerjaan <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_time" class="form-control form-control-sm radius-8" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Waktu Berakhir Izin <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="end_time" class="form-control form-control-sm radius-8" value="{{ now()->addHours(8)->format('Y-m-d\TH:i') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Job Safety Analysis (JSA) / Langkah Bahaya & Mitigasi</label>
                            <textarea name="jsa_content" rows="3" class="form-control form-control-sm radius-8" placeholder="Uraikan potensi bahaya (misal: percikan api, akumulasi gas beracun, kejatuhan material) dan kontrol proteksinya..."></textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="gas_test_required" value="1" id="gasTestSwitch" onchange="toggleGasInputs(this)">
                                <label class="form-check-label text-xs fw-semibold text-primary-light" for="gasTestSwitch">
                                    Wajib Pengujian Gas Atmosfer (Gas Test Required)
                                </label>
                            </div>
                            <div id="gasInputsContainer" class="p-12 radius-8 bg-light border d-none">
                                <span class="text-2xs fw-bold text-uppercase text-secondary-light d-block mb-8">Hasil Pembacaan Detektor Gas Multi-Gas</span>
                                <div class="row g-2">
                                    <div class="col-3">
                                        <label class="text-2xs text-secondary-light">Oksigen (O₂ %)</label>
                                        <input type="text" name="gas_test_o2" class="form-control form-control-sm radius-6" placeholder="20.9">
                                    </div>
                                    <div class="col-3">
                                        <label class="text-2xs text-secondary-light">Flammable (LEL %)</label>
                                        <input type="text" name="gas_test_lel" class="form-control form-control-sm radius-6" placeholder="0.0">
                                    </div>
                                    <div class="col-3">
                                        <label class="text-2xs text-secondary-light">Karbon Monoksida (CO ppm)</label>
                                        <input type="text" name="gas_test_co" class="form-control form-control-sm radius-6" placeholder="0">
                                    </div>
                                    <div class="col-3">
                                        <label class="text-2xs text-secondary-light">Hidrogen Sulfida (H₂S ppm)</label>
                                        <input type="text" name="gas_test_h2s" class="form-control form-control-sm radius-6" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Persyaratan APD Khusus</label>
                            <input type="text" name="ppe_requirements" class="form-control form-control-sm radius-8" value="Safety Helmet, Safety Shoes, Glasses, Gloves, Full Body Harness Double Lanyard" placeholder="Sebutkan APD wajib...">
                        </div>

                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Catatan & Ketentuan Izin</label>
                            <textarea name="notes" rows="2" class="form-control form-control-sm radius-8" placeholder="Ketentuan khusus dari Safety Inspector..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-12 px-20">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary radius-8">Terbitkan Permit to Work</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
