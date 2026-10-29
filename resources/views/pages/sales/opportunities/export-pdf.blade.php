<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sales Pipeline & Opportunities - {{ $periodTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        :root {
            --primary: #ea580c;
            --primary-dark: #c2410c;
            --primary-light: #fff7ed;
            --secondary: #475569;
            --dark: #0f172a;
            --border: #cbd5e1;
            --bg-light: #f8fafc;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Top Action Bar - Hidden during print / PDF generation */
        .action-bar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }

        .action-bar .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-outline {
            background: #ffffff;
            border-color: #cbd5e1;
            color: var(--secondary);
        }
        .btn-outline:hover {
            background: #f8fafc;
            color: var(--dark);
        }

        .btn-period {
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            color: #475569;
            text-decoration: none;
            background: #fff;
        }
        .btn-period.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
            font-weight: 700;
        }

        /* Paper sheet simulation */
        .report-page-wrapper {
            max-width: 1050px;
            margin: 24px auto;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            padding: 40px 48px;
        }

        /* Header */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .company-logo {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--dark);
            line-height: 1.2;
        }
        .company-logo span {
            color: var(--primary);
        }

        .company-meta {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }

        .report-title-box {
            text-align: right;
        }
        .report-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-badge {
            display: inline-block;
            background: var(--primary-light);
            color: var(--primary);
            border: 1px solid #fed7aa;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 6px;
        }

        /* Info meta strip */
        .info-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            background: var(--bg-light);
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 24px;
        }
        .info-item .label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 2px;
        }
        .info-item .val {
            font-size: 12px;
            font-weight: 700;
            color: var(--dark);
        }

        /* Metric Cards */
        .metric-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 28px;
        }
        .metric-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px;
            background: #ffffff;
            border-top: 3px solid var(--primary);
        }
        .metric-card.accent-success { border-top-color: var(--success); }
        .metric-card.accent-warning { border-top-color: var(--warning); }
        .metric-card.accent-info { border-top-color: #0284c7; }

        .metric-card .card-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 4px;
        }
        .metric-card .card-value {
            font-size: 18px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 4px;
        }
        .metric-card .card-note {
            font-size: 10px;
            color: #94a3b8;
        }

        /* Section Titles */
        .section-title {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--dark);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title::before {
            content: "";
            display: inline-block;
            width: 4px;
            height: 14px;
            background: var(--primary);
            border-radius: 2px;
        }

        /* Tables */
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 11px;
        }
        .table-custom th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        .table-custom td {
            padding: 9px 12px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
        }
        .table-custom tr:nth-child(even) td {
            background-color: #fafbfc;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-align: center;
        }
        .badge-stage { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .badge-won { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .badge-lost { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
        .badge-active { background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }

        /* Signature block */
        .signatures-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px dashed #cbd5e1;
        }
        .sig-box {
            text-align: center;
        }
        .sig-title {
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 60px;
        }
        .sig-name {
            font-size: 12px;
            font-weight: 800;
            color: var(--dark);
            text-decoration: underline;
        }
        .sig-role {
            font-size: 11px;
            color: #64748b;
        }

        /* Print media styles */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .action-bar {
                display: none !important;
            }
            .report-page-wrapper {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
            @page {
                size: A4 landscape;
                margin: 12mm;
            }
        }
    </style>
</head>
<body>

    {{-- Top Action Bar --}}
    <div class="action-bar no-print">
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('dashboard-admin.sales.opportunities.index', ['year' => $selectedYear]) }}" class="btn btn-outline">
                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon> Kembali ke Pipeline
            </a>
            <span style="color: #cbd5e1;">|</span>
            <span style="font-size: 12px; font-weight: 600; color: #64748b;">Tahun Filter:</span>
            <div style="display: flex; gap: 6px;">
                <a href="{{ route('dashboard-admin.sales.opportunities.export', ['type' => 'pdf', 'year' => '2026']) }}"
                   class="btn-period {{ $selectedYear === '2026' ? 'active' : '' }}">2026</a>
                <a href="{{ route('dashboard-admin.sales.opportunities.export', ['type' => 'pdf', 'year' => '2025']) }}"
                   class="btn-period {{ $selectedYear === '2025' ? 'active' : '' }}">2025</a>
                <a href="{{ route('dashboard-admin.sales.opportunities.export', ['type' => 'pdf', 'year' => 'all']) }}"
                   class="btn-period {{ $selectedYear === 'all' ? 'active' : '' }}">Semua</a>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route('dashboard-admin.sales.opportunities.export', ['type' => 'excel', 'year' => $selectedYear]) }}" class="btn btn-outline">
                <iconify-icon icon="vscode-icons:file-type-excel"></iconify-icon> Unduh Excel (.csv)
            </a>
            <button type="button" class="btn btn-outline" onclick="window.print()">
                <iconify-icon icon="solar:printer-linear"></iconify-icon> Cetak Laporan
            </button>
            <button type="button" class="btn btn-primary" id="btnDownloadPdf" onclick="downloadPdfDocument()">
                <iconify-icon icon="solar:document-download-bold"></iconify-icon> Unduh File PDF
            </button>
        </div>
    </div>

    {{-- Report Content Page --}}
    <div class="report-page-wrapper" id="reportContentToExport">

        {{-- Header --}}
        <div class="report-header">
            <div>
                <div class="company-logo">WAN<span>Teknologi</span></div>
                <div style="font-weight: 700; color: #1e293b; font-size: 13px; margin-top: 2px;">PT ELBEMA INTEGRA INDONESIA</div>
                <div class="company-meta">
                    Business Unit: Sales &amp; Business Development (CRM)<br>
                    Website: elbema.co.id &bull; Hotline: +62 811-9238-120
                </div>
            </div>
            <div class="report-title-box">
                <div class="report-title">Sales Pipeline Overview</div>
                <div class="report-badge">{{ $periodTitle }}</div>
                <div style="font-size: 11px; color: #64748b; margin-top: 6px;">
                    Tanggal Export: {{ $now->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </div>
        </div>

        {{-- Info Strip --}}
        <div class="info-strip">
            <div class="info-item">
                <div class="label">Periode Analisis</div>
                <div class="val">{{ $periodTitle }}</div>
            </div>
            <div class="info-item">
                <div class="label">Diekspor Oleh</div>
                <div class="val">{{ $user->name ?? 'User' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Total Opportunity</div>
                <div class="val">{{ $totalOpportunitiesCount }} Deals Terdaftar</div>
            </div>
            <div class="info-item">
                <div class="label">Aktivitas Follow-up</div>
                <div class="val">{{ $completedFollowUps }}/{{ $totalFollowUps }} Selesai ({{ $followUpCompletion }}%)</div>
            </div>
        </div>

        {{-- Metric Cards (Section 1) --}}
        <div class="metric-cards-grid">
            <div class="metric-card">
                <div class="card-label">Weighted Pipeline Value</div>
                <div class="card-value">
                    @if ($weightedPipelineVal >= 1000000000)
                        Rp {{ number_format($weightedPipelineVal / 1000000000, 2, ',', '.') }} M
                    @elseif ($weightedPipelineVal >= 1000000)
                        Rp {{ number_format($weightedPipelineVal / 1000000, 1, ',', '.') }} Jt
                    @else
                        Rp {{ number_format($weightedPipelineVal, 0, ',', '.') }}
                    @endif
                </div>
                <div class="card-note">Probabilitas dari total Rp {{ number_format($totalActiveValue / 1000000000, 2, ',', '.') }} M</div>
            </div>

            <div class="metric-card accent-success">
                <div class="card-label">Active Project Leads</div>
                <div class="card-value">{{ $activeLeadsCount }}</div>
                <div class="card-note">Peluang aktif dalam tahapan sales</div>
            </div>

            <div class="metric-card accent-warning">
                <div class="card-label">Qualification Rate</div>
                <div class="card-value">{{ number_format($qualificationRate, 1, ',', '.') }}%</div>
                <div class="card-note">{{ $qualifiedLeadsCount }} dari {{ $totalOpportunitiesCount }} deals ter-kualifikasi</div>
            </div>

            <div class="metric-card accent-info">
                <div class="card-label">Qualified Project Leads</div>
                <div class="card-value">{{ $qualifiedLeadsCount }}</div>
                <div class="card-note">Tender / Proposal / Negosiasi / Won</div>
            </div>
        </div>

        {{-- Detailed Opportunities Table --}}
        <div class="section-title">Daftar Opportunity &amp; Pipeline Project</div>
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">No</th>
                    <th>Judul Proyek / Paket</th>
                    <th>Perusahaan Customer</th>
                    <th>Sales Owner</th>
                    <th style="width: 120px;">Tahapan (Stage)</th>
                    <th style="width: 120px; text-align: right;">Estimasi Nilai</th>
                    <th style="width: 60px; text-align: center;">Prob.</th>
                    <th style="width: 120px; text-align: right;">Weighted Value</th>
                    <th style="width: 90px; text-align: center;">Target Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($allOpportunities as $index => $opp)
                    @php
                        $val = (float) ($opp->estimated_value ?? 0);
                        $prob = $opp->probability ?? (($stageWeights[$opp->stage] ?? 0.2) * 100);
                        $weighted = $val * ($prob / 100);
                        $badgeClass = 'badge-stage';
                        if ($opp->stage === 'closed_won') $badgeClass = 'badge-won';
                        elseif ($opp->stage === 'closed_lost') $badgeClass = 'badge-lost';
                        else $badgeClass = 'badge-active';
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: 700; color: #0f172a;">{{ $opp->title }}</div>
                            <small style="color: #64748b; font-size: 10px;">ID: #OPP-{{ str_pad($opp->id, 4, '0', STR_PAD_LEFT) }}</small>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $opp->customer?->company_name ?? ($opp->customer?->name ?? '-') }}</div>
                            <small style="color: #64748b; font-size: 10px;">{{ $opp->customer?->industry ?? 'Umum' }}</small>
                        </td>
                        <td>{{ $opp->owner?->name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $badgeClass }}">
                                {{ $stageLabels[$opp->stage] ?? ucfirst((string) $opp->stage) }}
                            </span>
                        </td>
                        <td style="text-align: right; font-weight: 600;">
                            Rp {{ number_format($val, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center; font-weight: 700;">
                            {{ $prob }}%
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #ea580c;">
                            Rp {{ number_format($weighted, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            {{ $opp->target_deal_date ? \Carbon\Carbon::parse($opp->target_deal_date)->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 24px; color: #94a3b8;">
                            Tidak ada data opportunity pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background: #f8fafc; font-weight: 800;">
                    <td colspan="5" style="text-align: right; text-transform: uppercase;">Total Kumulatif:</td>
                    <td style="text-align: right;">Rp {{ number_format($totalActiveValue, 0, ',', '.') }}</td>
                    <td style="text-align: center;">-</td>
                    <td style="text-align: right; color: #ea580c;">Rp {{ number_format($weightedPipelineVal, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        {{-- Signatures --}}
        <div class="signatures-row">
            <div class="sig-box">
                <div class="sig-title">Dipersiapkan Oleh,</div>
                <div class="sig-name">{{ $user->name ?? 'Sales & Marketing Team' }}</div>
                <div class="sig-role">Sales &amp; Business Development</div>
            </div>
            <div class="sig-box">
                <div class="sig-title">Disetujui Oleh,</div>
                <div class="sig-name">Management PT ELBEMA Integra Indonesia</div>
                <div class="sig-role">Head of Sales &amp; Operation</div>
            </div>
        </div>

    </div>

    {{-- Script for HTML2PDF Download --}}
    <script>
        function downloadPdfDocument() {
            const btn = document.getElementById('btnDownloadPdf');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<iconify-icon icon="line-md:loading-loop"></iconify-icon> Memproses PDF...';
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';

            const element = document.getElementById('reportContentToExport');
            const opt = {
                margin:       [10, 10, 10, 10],
                filename:     'Laporan-Sales-Pipeline-{{ $selectedYear }}-{{ date("Ymd_His") }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).save().then(function() {
                btn.innerHTML = originalText;
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            }).catch(function(err) {
                console.error('PDF error:', err);
                alert('Gagal membuat PDF otomatis. Anda dapat menggunakan opsi "Cetak Laporan" lalu pilih "Save as PDF".');
                btn.innerHTML = originalText;
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            });
        }
    </script>
</body>
</html>
