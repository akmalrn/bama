@extends('layout.layout')

@php
    $title = 'Project Documents & Handover';
    $subTitle = 'Project Management Office — Turnover Dossiers, Certificates, Punch Lists & BAST Handover';
@endphp

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-24">
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="badge bg-primary-50 text-primary-600 border border-primary-200 px-8 py-2 text-xs fw-bold text-uppercase">PMO Controls</span>
            <span class="badge bg-success-50 text-success-700 border border-success-200 px-8 py-2 text-xs fw-semibold">Turnover Dossier &amp; BAST</span>
        </div>
        <h4 class="mb-4 fw-bold text-primary-light">Project Documents &amp; Handover</h4>
        <p class="text-secondary-light mb-0 text-sm">
            Kelola kelengkapan dokumen proyek, turnover dossier, sertifikat kelaikan, punch list, dan status serah terima (BAST).
        </p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
            <iconify-icon icon="solar:upload-track-2-bold-duotone" class="text-base"></iconify-icon>
            + Upload Dokumen
        </button>
        <a href="{{ route('dashboard-admin.pmo.dashboard') }}" class="btn btn-outline-secondary btn-sm px-16 py-8 radius-8 d-inline-flex align-items-center gap-2 fw-semibold">
            <iconify-icon icon="solar:arrow-left-outline" class="text-base"></iconify-icon>
            PMO Dashboard
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show text-sm radius-8 mb-20 d-flex align-items-center gap-2" role="alert">
        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-xl"></iconify-icon>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="row g-3 mb-24">
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 p-16 transition-all">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-primary-600">Project Dossiers</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 text-lg flex-shrink-0">
                    <iconify-icon icon="solar:folder-with-files-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h4 class="fw-bold text-primary-light mb-0 text-nowrap" style="letter-spacing: -0.3px;">
                    {{ $totalDossiers }} <span class="fs-6 fw-normal text-secondary-light">Portfolio</span>
                </h4>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>Proyek Terpantau</span>
                <span class="badge bg-primary-50 text-primary-700 px-6 py-2 radius-4 text-xs fw-semibold">Tracking</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 p-16 transition-all">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-success-main">Documents Complete</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-success-50 text-success-600 text-lg flex-shrink-0">
                    <iconify-icon icon="solar:file-check-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h4 class="fw-bold text-success-main mb-0 text-nowrap" style="letter-spacing: -0.3px;">
                    {{ $docsCompletePercentage }}%
                </h4>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>Document Readiness</span>
                <span class="badge bg-success-50 text-success-700 px-6 py-2 radius-4 text-xs fw-semibold">Avg Rate</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 p-16 transition-all">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-warning-main">Punch Items Open</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-warning-50 text-warning-main text-lg flex-shrink-0">
                    <iconify-icon icon="solar:danger-triangle-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h4 class="fw-bold text-warning-main mb-0 text-nowrap" style="letter-spacing: -0.3px;">
                    {{ $totalPunchOpen }} <span class="fs-6 fw-normal text-secondary-light">Items</span>
                </h4>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>Perbaikan Sisa</span>
                <span class="badge bg-warning-50 text-warning-700 px-6 py-2 radius-4 text-xs fw-semibold">Open Punch</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card radius-12 border-0 shadow-sm h-100 p-16 transition-all">
            <div class="d-flex align-items-center justify-content-between mb-8">
                <span class="text-xs fw-bold text-uppercase tracking-wider text-info-main">Handover Ready</span>
                <div class="w-32-px h-32-px radius-8 d-flex justify-content-center align-items-center bg-info-50 text-info-main text-lg flex-shrink-0">
                    <iconify-icon icon="solar:diploma-verified-bold-duotone"></iconify-icon>
                </div>
            </div>
            <div class="mb-8">
                <h4 class="fw-bold text-info-main mb-0 text-nowrap" style="letter-spacing: -0.3px;">
                    {{ $handoverReady }} <span class="fs-6 fw-normal text-secondary-light">Proyek</span>
                </h4>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-8 border-top text-xs text-secondary-light">
                <span>Siap / Telah BAST</span>
                <span class="badge bg-info-50 text-info-700 px-6 py-2 radius-4 text-xs fw-semibold">Handover</span>
            </div>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-header bg-transparent py-16 px-24 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h6 class="text-lg fw-bold text-primary-light mb-1">Turnover Dossier &amp; Handover Status per Project</h6>
            <p class="text-xs text-secondary-light mb-0">Status kelengkapan berkas Berita Acara Serah Terima (BAST), sertifikat, dan punch list penutupan proyek.</p>
        </div>

        <form method="GET" action="{{ route('dashboard-admin.pmo.documents-handover.index') }}" class="d-flex align-items-center gap-2">
            <select name="status" class="form-select form-select-sm radius-8" onchange="this.form.submit()">
                <option value="all">Semua Status Handover</option>
                <option value="In Progress" {{ $statusFilter === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Action Required" {{ $statusFilter === 'Action Required' ? 'selected' : '' }}>Action Required</option>
                <option value="Handed Over" {{ $statusFilter === 'Handed Over' ? 'selected' : '' }}>Handed Over</option>
            </select>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-100">
                    <tr>
                        <th class="ps-24 py-16 text-xs fw-bold text-uppercase">Project / Package</th>
                        <th class="py-16 text-xs fw-bold text-uppercase">Customer &amp; Lokasi</th>
                        <th class="text-center py-16 text-xs fw-bold text-uppercase">Document Register</th>
                        <th class="text-center py-16 text-xs fw-bold text-uppercase">Punch List</th>
                        <th class="py-16 text-xs fw-bold text-uppercase">Certificates</th>
                        <th class="text-center py-16 text-xs fw-bold text-uppercase">Turnover Dossier</th>
                        <th class="text-center py-16 text-xs fw-bold text-uppercase">Handover Status</th>
                        <th class="text-center pe-24 py-16 text-xs fw-bold text-uppercase" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        @php
                            $docCount = $project->documents->count();
                            $handoverStatusLower = strtolower((string) $project->handover_status);
                            $badgeClass = match(true) {
                                str_contains($handoverStatusLower, 'handed') || str_contains($handoverStatusLower, 'ready') => 'bg-success-50 text-success-600 border border-success-200',
                                str_contains($handoverStatusLower, 'action') || str_contains($handoverStatusLower, 'risk') => 'bg-danger-50 text-danger-600 border border-danger-200',
                                default => 'bg-warning-50 text-warning-700 border border-warning-200',
                            };
                        @endphp
                        <tr>
                            <td class="ps-20 py-14">
                                <a href="{{ route('dashboard-admin.pmo.projects.show', $project->id) }}" class="fw-bold text-primary-600 text-decoration-none d-block">
                                    {{ $project->title }}
                                </a>
                                <span class="text-xs text-secondary-light">{{ $project->project_code }}</span>
                            </td>
                            <td class="py-14">
                                <span class="fw-medium text-primary-light">{{ $project->customer->name ?? '-' }}</span>
                                <span class="d-block text-xs text-secondary-light">{{ $project->location ?? '-' }}</span>
                            </td>
                            <td class="text-center py-14">
                                <span class="badge bg-neutral-100 text-primary-light px-8 py-4 text-xs fw-semibold">
                                    {{ $docCount }} Dokumen
                                </span>
                            </td>
                            <td class="text-center py-14">
                                @if ($project->punch_list_open_count > 0)
                                    <span class="badge bg-warning-50 text-warning-700 px-8 py-2 text-xs">
                                        {{ $project->punch_list_open_count }} Open
                                    </span>
                                @else
                                    <span class="badge bg-success-50 text-success-600 px-8 py-2 text-xs">Closed</span>
                                @endif
                            </td>
                            <td class="py-14 text-xs">
                                <span class="text-primary-light fw-medium">{{ $project->certificates_status ?? 'Pending' }}</span>
                            </td>
                            <td class="text-center py-14">
                                <span class="badge bg-neutral-100 text-secondary-light px-8 py-2 text-xs">
                                    {{ $project->turnover_dossier_status ?? 'Draft' }}
                                </span>
                            </td>
                            <td class="text-center py-14">
                                <span class="badge {{ $badgeClass }} px-10 py-4 text-xs fw-bold text-uppercase">
                                    {{ $project->handover_status ?? 'In Progress' }}
                                </span>
                            </td>
                            <td class="text-center pe-20 py-14">
                                <button type="button" class="btn btn-sm btn-outline-primary px-10 py-4 radius-6 text-xs" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editHandoverStatusModal{{ $project->id }}">
                                    Update Status
                                </button>

                                
                                <div class="modal fade" id="editHandoverStatusModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content radius-16 border-0 shadow text-start">
                                            <div class="modal-header border-bottom py-16 px-24">
                                                <h6 class="modal-title fw-bold text-primary-light">Update Handover: {{ $project->project_code }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('dashboard-admin.pmo.documents-handover.status', $project->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body p-24">
                                                    <div class="mb-16">
                                                        <label class="form-label text-xs fw-bold text-secondary-light">Status Turnover Dossier</label>
                                                        <select name="turnover_dossier_status" class="form-select radius-8" required>
                                                            <option value="Draft" {{ $project->turnover_dossier_status === 'Draft' ? 'selected' : '' }}>Draft</option>
                                                            <option value="In Review" {{ $project->turnover_dossier_status === 'In Review' ? 'selected' : '' }}>In Review (Verifikasi Klien)</option>
                                                            <option value="Approved" {{ $project->turnover_dossier_status === 'Approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-16">
                                                        <label class="form-label text-xs fw-bold text-secondary-light">Handover Status (BAST)</label>
                                                        <select name="handover_status" class="form-select radius-8" required>
                                                            <option value="In Progress" {{ $project->handover_status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                            <option value="Action Required" {{ $project->handover_status === 'Action Required' ? 'selected' : '' }}>Action Required</option>
                                                            <option value="Handed Over" {{ $project->handover_status === 'Handed Over' ? 'selected' : '' }}>Handed Over (Selesai BAST)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-16">
                                                        <label class="form-label text-xs fw-bold text-secondary-light">Open Punch Items (Jumlah)</label>
                                                        <input type="number" name="punch_list_open_count" min="0" value="{{ $project->punch_list_open_count }}" class="form-control radius-8" required>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label text-xs fw-bold text-secondary-light">Status Sertifikat &amp; Laporan Uji</label>
                                                        <input type="text" name="certificates_status" value="{{ $project->certificates_status }}" class="form-control radius-8" placeholder="Contoh: PCC issued / Pending inspection" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top py-12 px-24">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary px-16 radius-8" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-sm btn-primary px-20 radius-8">Simpan Status</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-32 text-secondary-light">Belum ada data proyek pada filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card radius-12 border-0 shadow-sm mb-24 overflow-hidden">
    <div class="card-header bg-transparent py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h6 class="text-lg fw-bold text-primary-light mb-1">Central Document Archive (All Projects)</h6>
            <p class="text-xs text-secondary-light mb-0">Seluruh dokumen legalitas, kontrak, BAST, dan gambar teknis yang tersimpan dalam sistem.</p>
        </div>
        <span class="badge bg-neutral-100 text-secondary-light text-xs px-10 py-4 radius-6">{{ $documents->total() }} File Tersimpan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0 align-middle">
                <thead class="bg-neutral-100">
                    <tr>
                        <th class="ps-24 py-16 text-xs fw-bold text-uppercase">Nama Dokumen</th>
                        <th class="py-16 text-xs fw-bold text-uppercase">Proyek Terkait</th>
                        <th class="py-16 text-xs fw-bold text-uppercase">Kategori</th>
                        <th class="py-16 text-xs fw-bold text-uppercase">Ukuran</th>
                        <th class="py-16 text-xs fw-bold text-uppercase">Tanggal Unggah</th>
                        <th class="text-center pe-24 py-16 text-xs fw-bold text-uppercase" width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $doc)
                        <tr>
                            <td class="ps-20 py-12">
                                <div class="d-flex align-items-center gap-2">
                                    <iconify-icon icon="solar:document-bold-duotone" class="text-primary-600 text-xl"></iconify-icon>
                                    <div>
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="fw-semibold text-primary-600 text-decoration-none">
                                            {{ $doc->name }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="py-12">
                                <span class="fw-medium text-primary-light">{{ $doc->project->title ?? '-' }}</span>
                                <span class="d-block text-xs text-secondary-light">{{ $doc->project->project_code ?? '' }}</span>
                            </td>
                            <td class="py-12">
                                <span class="badge bg-neutral-100 text-secondary-light px-8 py-2 text-xs">{{ $doc->category }}</span>
                            </td>
                            <td class="py-12 text-xs text-secondary-light">{{ $doc->file_size ?? '-' }}</td>
                            <td class="py-12 text-xs text-secondary-light">{{ \Carbon\Carbon::parse($doc->uploaded_date)->format('d M Y') }}</td>
                            <td class="text-center pe-20 py-12">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary px-8 py-4 radius-6 text-xs" title="Lihat Dokumen">
                                        Unduh
                                    </a>
                                    <form action="{{ route('dashboard-admin.pmo.documents-handover.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger p-4 radius-6" title="Hapus">
                                            <iconify-icon icon="solar:trash-bin-trash-outline" class="text-base"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-32 text-secondary-light">Belum ada dokumen yang diunggah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($documents->hasPages())
            <div class="p-16 border-top">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>


<div class="modal fade" id="uploadDocModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 border-0 shadow">
            <div class="modal-header border-bottom py-16 px-24">
                <h6 class="modal-title fw-bold text-primary-light">Upload Dokumen / Dossier Proyek</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard-admin.pmo.documents-handover.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-24">
                    <div class="mb-16">
                        <label class="form-label text-xs fw-bold text-secondary-light">Pilih Proyek <span class="text-danger">*</span></label>
                        <select name="project_id" class="form-select radius-8" required>
                            <option value="">-- Pilih Proyek Terkait --</option>
                            @foreach ($allProjects as $p)
                                <option value="{{ $p->id }}">{{ $p->project_code }} — {{ $p->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-16">
                        <label class="form-label text-xs fw-bold text-secondary-light">Nama / Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control radius-8" placeholder="Contoh: BAST Fisik Tahap 1" required>
                    </div>
                    <div class="mb-16">
                        <label class="form-label text-xs fw-bold text-secondary-light">Kategori Dokumen <span class="text-danger">*</span></label>
                        <select name="category" class="form-select radius-8" required>
                            <option value="BAST / Handover">BAST / Handover</option>
                            <option value="Contract & SO">Contract &amp; SO</option>
                            <option value="Technical Drawing & Spec">Technical Drawing &amp; Spec</option>
                            <option value="Inspection & QA/QC">Inspection &amp; QA/QC</option>
                            <option value="HSSE Permit & Safety">HSSE Permit &amp; Safety</option>
                            <option value="Commercial & Invoice">Commercial &amp; Invoice</option>
                            <option value="Other">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-16">
                        <label class="form-label text-xs fw-bold text-secondary-light">Tanggal Dokumen <span class="text-danger">*</span></label>
                        <input type="date" name="uploaded_date" value="{{ date('Y-m-d') }}" class="form-control radius-8" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-xs fw-bold text-secondary-light">Pilih File (PDF, DOCX, XLSX, ZIP maks 20MB) <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control radius-8" accept=".pdf,.docx,.xlsx,.zip" required>
                    </div>
                </div>
                <div class="modal-footer border-top py-12 px-24">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-16 radius-8" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-20 radius-8">Upload Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
