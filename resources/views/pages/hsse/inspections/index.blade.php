@extends('layout.layout')

@php
    $title = 'Inspeksi Alat & APD (Equipment & PPE Inspection)';
    $subTitle = 'Pemeriksaan Rutin Pra-Operasi, Sertifikasi Kelayakan & Karantina Tagging';
    $script = '<script>
        function updateTagColorByResult(select) {
            const tagSelect = document.getElementById("tagColorSelect");
            if (select.value === "passed") {
                tagSelect.value = "Green";
            } else if (select.value === "passed_with_notes") {
                tagSelect.value = "Yellow";
            } else if (select.value === "failed_quarantined") {
                tagSelect.value = "Red";
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
        @can('hsse.inspection.manage')
            <button type="button" class="btn btn-sm btn-primary radius-8 px-14 py-8 d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#createInspectionModal">
                <iconify-icon icon="solar:add-circle-bold-duotone" class="text-lg"></iconify-icon>
                Catat Inspeksi Baru
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
            <a href="{{ route('dashboard-admin.hsse.inspections.index', array_merge(request()->query(), ['result' => 'all'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $resultFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Semua Unit ({{ $counts['all'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.inspections.index', array_merge(request()->query(), ['result' => 'passed'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $resultFilter === 'passed' ? 'btn-success' : 'btn-outline-secondary' }}">
                Lolos Layak Pakai ({{ $counts['passed'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.inspections.index', array_merge(request()->query(), ['result' => 'passed_with_notes'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $resultFilter === 'passed_with_notes' ? 'btn-warning' : 'btn-outline-secondary' }}">
                Lolos Bersyarat ({{ $counts['passed_notes'] }})
            </a>
            <a href="{{ route('dashboard-admin.hsse.inspections.index', array_merge(request()->query(), ['result' => 'failed_quarantined'])) }}"
               class="btn btn-sm radius-8 px-14 py-6 {{ $resultFilter === 'failed_quarantined' ? 'btn-danger' : 'btn-outline-secondary' }}">
                Gagal / Karantina ({{ $counts['quarantined'] }})
            </a>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24">
    <div class="card-body p-16">
        <form method="GET" action="{{ route('dashboard-admin.hsse.inspections.index') }}" class="row g-2 align-items-center">
            <input type="hidden" name="result" value="{{ $resultFilter }}">
            <div class="col-lg-3 col-md-6">
                <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm radius-8" placeholder="Cari No. Inspeksi, Nama Alat, S/N...">
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="category" class="form-select form-select-sm radius-8">
                    <option value="all">Semua Kategori Peralatan</option>
                    <option value="Peralatan Berat" {{ $categoryFilter === 'Peralatan Berat' ? 'selected' : '' }}>Peralatan Berat (Heavy Equipment)</option>
                    <option value="Perkakas Listrik" {{ $categoryFilter === 'Perkakas Listrik' ? 'selected' : '' }}>Perkakas Listrik (Power Tools)</option>
                    <option value="Alat Ukur & Instrumentasi" {{ $categoryFilter === 'Alat Ukur & Instrumentasi' ? 'selected' : '' }}>Alat Ukur & Instrumentasi</option>
                    <option value="APD & Lifeline" {{ $categoryFilter === 'APD & Lifeline' ? 'selected' : '' }}>APD & Lifeline (Personal Protection)</option>
                    <option value="Tabung Bertekanan" {{ $categoryFilter === 'Tabung Bertekanan' ? 'selected' : '' }}>Tabung Bertekanan & Pressure Vessel</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="tag_color" class="form-select form-select-sm radius-8">
                    <option value="all">Semua Tag Warna</option>
                    <option value="Green" {{ $tagFilter === 'Green' ? 'selected' : '' }}>Green (Hijau - Aman)</option>
                    <option value="Yellow" {{ $tagFilter === 'Yellow' ? 'selected' : '' }}>Yellow (Kuning - Catatan)</option>
                    <option value="Red" {{ $tagFilter === 'Red' ? 'selected' : '' }}>Red (Merah - Dilarang)</option>
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


<div class="card radius-12 border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-xs text-secondary-light text-uppercase">
                        <th class="py-12 px-20">No. Inspeksi & Alat</th>
                        <th class="py-12 px-16">Kategori & S/N</th>
                        <th class="py-12 px-16">Site / Lokasi</th>
                        <th class="py-12 px-16">Jadwal & Inspektor</th>
                        <th class="py-12 px-16">Hasil & Tagging</th>
                        <th class="py-12 px-16">Temuan / Catatan</th>
                        <th class="py-12 px-20 text-center" width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($inspections as $insp)
                        <tr>
                            <td class="py-12 px-20">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-primary-light">{{ $insp->inspection_number }}</span>
                                    <span class="text-xs text-secondary-light fw-medium">{{ $insp->item_name }}</span>
                                </div>
                            </td>
                            <td class="py-12 px-16 text-xs">
                                <span class="d-block text-primary-light">{{ $insp->item_category }}</span>
                                <span class="text-secondary-light">S/N: {{ $insp->serial_number ?? 'N/A' }}</span>
                            </td>
                            <td class="py-12 px-16 text-xs">
                                <span class="d-block fw-medium text-primary-light">{{ $insp->project ? Str::limit($insp->project->title, 25) : 'General Facility' }}</span>
                                <span class="text-secondary-light"><i class="ri-map-pin-line me-1"></i>{{ $insp->location }}</span>
                            </td>
                            <td class="py-12 px-16 text-xs text-secondary-light">
                                <div>Inspeksi: {{ $insp->inspection_date ? $insp->inspection_date->format('d M Y') : '-' }}</div>
                                <div class="text-primary-600">Next: {{ $insp->next_inspection_date ? $insp->next_inspection_date->format('d M Y') : '-' }}</div>
                                <div class="text-2xs text-secondary-light">Oleh: {{ $insp->inspector ? ($insp->inspector->user ? $insp->inspector->user->name : $insp->inspector->employee_number) : '-' }}</div>
                            </td>
                            <td class="py-12 px-16">
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge {{ $insp->result_badge_class }} text-xs px-8 py-4 radius-6 text-capitalize">
                                        {{ str_replace('_', ' ', $insp->result) }}
                                    </span>
                                    @php
                                        $tagBadge = match($insp->tag_color) {
                                            'Green' => 'bg-success-main text-white',
                                            'Yellow' => 'bg-warning-main text-white',
                                            'Red' => 'bg-danger-main text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                    @endphp
                                    <span class="badge {{ $tagBadge }} text-2xs px-6 py-2 radius-4 w-auto align-self-start">
                                        {{ strtoupper($insp->tag_color) }} TAG
                                    </span>
                                </div>
                            </td>
                            <td class="py-12 px-16 text-xs" style="max-width: 250px;">
                                <div class="text-secondary-light text-truncate" title="{{ $insp->checklist_findings }}">{{ $insp->checklist_findings ?? '-' }}</div>
                                @if($insp->corrective_action)
                                    <div class="text-danger-600 text-2xs mt-1 text-truncate" title="{{ $insp->corrective_action }}">
                                        <strong>Action:</strong> {{ $insp->corrective_action }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-12 px-20 text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    @can('hsse.inspection.manage')
                                        <form action="{{ route('dashboard-admin.hsse.inspections.destroy', $insp) }}" method="POST" onsubmit="return confirm('Hapus riwayat inspeksi {{ $insp->inspection_number }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-32-px h-32-px rounded-circle bg-danger-focus text-danger-main d-inline-flex justify-content-center align-items-center border-0" title="Hapus Inspeksi">
                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-32 text-secondary-light">
                                Tidak ada data inspeksi yang sesuai kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-16 border-top">
            {{ $inspections->links() }}
        </div>
    </div>
</div>


<div class="modal fade" id="createInspectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content radius-12 border-0">
            <div class="modal-header border-bottom py-16 px-20">
                <div class="d-flex align-items-center gap-2">
                    <div class="w-32-px h-32-px radius-6 bg-primary-50 text-primary-600 d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:checklist-minimalistic-bold-duotone" class="text-lg"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="modal-title fs-6 fw-semibold text-primary-light">Catat Hasil Inspeksi Peralatan & APD</h6>
                        <span class="text-xs text-secondary-light">Verifikasi kelayakan fisik, fungsi keselamatan & pemasangan color code tag</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.hsse.inspections.store') }}" method="POST">
                @csrf
                <div class="modal-body p-20">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Nomor Inspeksi <span class="text-danger">*</span></label>
                            <input type="text" name="inspection_number" class="form-control form-control-sm radius-8 bg-light" value="{{ $nextInspectionNumber }}" required readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Nama Alat / Peralatan / APD <span class="text-danger">*</span></label>
                            <input type="text" name="item_name" class="form-control form-control-sm radius-8" placeholder="Contoh: Mesin Las Inverter Miller 400A / Full Body Harness Double Lanyard" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Kategori Alat <span class="text-danger">*</span></label>
                            <select name="item_category" class="form-select form-select-sm radius-8" required>
                                <option value="Peralatan Berat">Peralatan Berat (Heavy Equipment)</option>
                                <option value="Perkakas Listrik" selected>Perkakas Listrik (Power Tools)</option>
                                <option value="Alat Ukur & Instrumentasi">Alat Ukur & Instrumentasi</option>
                                <option value="APD & Lifeline">APD & Lifeline (Personal Protection)</option>
                                <option value="Tabung Bertekanan">Tabung Bertekanan & Pressure Vessel</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Serial Number / Asset Tag</label>
                            <input type="text" name="serial_number" class="form-control form-control-sm radius-8" placeholder="Contoh: SN-MLR-2024-88">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Proyek / Kontrak Terkait</label>
                            <select name="project_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Non-Proyek / Workshop Pusat --</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Lokasi Fisik Alat <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control form-control-sm radius-8" placeholder="Contoh: Workshop BAMA Bontang / Site PKT" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Petugas Inspeksi (Inspector)</label>
                            <select name="inspector_id" class="form-select form-select-sm radius-8">
                                <option value="">-- Pilih Inspector --</option>
                                @foreach($inspectors as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->user ? $emp->user->name : $emp->employee_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Tanggal Inspeksi <span class="text-danger">*</span></label>
                            <input type="date" name="inspection_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Jadwal Kalibrasi Berikutnya</label>
                            <input type="date" name="next_inspection_date" class="form-control form-control-sm radius-8" value="{{ date('Y-m-d', strtotime('+3 months')) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Hasil Pemeriksaan <span class="text-danger">*</span></label>
                            <select name="result" class="form-select form-select-sm radius-8" onchange="updateTagColorByResult(this)" required>
                                <option value="passed">Passed (Lolos Layak Operasi)</option>
                                <option value="passed_with_notes">Passed with Notes (Lolos Bersyarat)</option>
                                <option value="failed_quarantined">Failed / Quarantined (Gagal / Karantina)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Warna Tagging (Color Code) <span class="text-danger">*</span></label>
                            <select name="tag_color" id="tagColorSelect" class="form-select form-select-sm radius-8" required>
                                <option value="Green">Green (Hijau - Aman & Valid)</option>
                                <option value="Yellow">Yellow (Kuning - Perhatian)</option>
                                <option value="Red">Red (Merah - Dilarang Beroperasi)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Temuan Fisik / Checklist Findings</label>
                            <textarea name="checklist_findings" rows="3" class="form-control form-control-sm radius-8" placeholder="Contoh: Kabel power terkelupas 5cm dekat colokan; insulasi body aman; grounding normal..."></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-xs fw-semibold text-secondary-light">Tindakan Korektif (Corrective Action)</label>
                            <textarea name="corrective_action" rows="2" class="form-control form-control-sm radius-8" placeholder="Contoh: Pasang Red Tag, tarik ke ruang karantina workshop untuk penggantian steker kabel..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-12 px-20">
                    <button type="button" class="btn btn-sm btn-outline-secondary radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary radius-8">Simpan Hasil Inspeksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
