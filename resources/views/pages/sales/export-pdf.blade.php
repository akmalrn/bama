<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sales & BD - {{ $periodLabel }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #eff6ff;
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

        /* Top Bar - No Print */
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
            color: #fff;
            font-weight: 600;
        }

        /* Report Container */
        .report-wrapper {
            max-width: 1100px;
            margin: 24px auto;
            background: #ffffff;
            padding: 36px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }

        /* Header */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--dark);
            margin-bottom: 24px;
        }

        .company-info h2 {
            font-size: 20px;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -0.3px;
            margin-bottom: 4px;
        }

        .company-info p {
            color: var(--secondary);
            font-size: 12px;
            margin-bottom: 2px;
        }

        .report-meta {
            text-align: right;
        }

        .report-meta .report-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .badge-filter {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            font-weight: 700;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 8px;
            border: 1px solid #bfdbfe;
        }

        .meta-text {
            font-size: 11px;
            color: var(--secondary);
            line-height: 1.4;
        }

        /* KPI Cards Grid */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .kpi-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 14px 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            position: relative;
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 8px 0 0 8px;
        }

        .kpi-blue::before { background: var(--primary); }
        .kpi-purple::before { background: #8b5cf6; }
        .kpi-green::before { background: var(--success); }
        .kpi-orange::before { background: var(--warning); }

        .kpi-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .kpi-value {
            font-size: 18px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .kpi-sub {
            font-size: 11px;
            color: #64748b;
        }

        /* Section Titles */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid var(--border);
        }

        .section-header h3 {
            font-size: 13px;
            font-weight: 700;
            color: var(--dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-header .section-count {
            font-size: 11px;
            color: var(--secondary);
            font-weight: 500;
        }

        /* Tables */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            text-align: left;
        }

        table th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
        }

        table td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
        }

        table tbody tr:nth-child(even) {
            background-color: #fbfcfe;
        }

        .badge-stage {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .stage-qualification { background: #e0e7ff; color: #3730a3; }
        .stage-need_analysis { background: #e0f2fe; color: #0369a1; }
        .stage-tender_rfq { background: #fef3c7; color: #92400e; }
        .stage-proposal_submitted { background: #ffedd5; color: #9a3412; }
        .stage-negotiation { background: #f3e8ff; color: #6b21a8; }
        .stage-closed_won { background: #dcfce7; color: #166534; }
        .stage-closed_lost { background: #fee2e2; color: #991b1b; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: 700; }

        /* Signatures block */
        .signatures {
            margin-top: 36px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            page-break-inside: avoid;
        }

        .sign-box {
            border-top: 1px dashed #cbd5e1;
            padding-top: 12px;
            text-align: center;
        }

        .sign-role {
            font-size: 11px;
            color: var(--secondary);
            margin-bottom: 50px;
        }

        .sign-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--dark);
            border-top: 1px solid #94a3b8;
            display: inline-block;
            min-width: 180px;
            padding-top: 4px;
        }

        .footer-note {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            font-size: 10px;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
        }

        /* Print Media Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #ffffff;
                font-size: 10px;
            }

            .report-wrapper {
                max-width: 100%;
                margin: 0;
                padding: 0;
                border: none;
                box-shadow: none;
            }

            @page {
                size: A4 landscape;
                margin: 12mm 15mm;
            }

            table {
                font-size: 9.5px;
            }

            table th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .badge-stage, .kpi-card, .badge-filter {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    {{-- Toolbar for Screen View --}}
    <div class="action-bar no-print">
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('dashboard-admin.sales.dashboard', ['period' => $period]) }}" class="btn btn-outline">
                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
                Kembali ke Dashboard
            </a>
            <div style="display: flex; align-items: center; gap: 4px; background: #f1f5f9; padding: 3px; border-radius: 8px;">
                <span style="font-size: 11px; font-weight: 600; color: #64748b; padding: 0 8px;">Filter Periode:</span>
                <a href="{{ route('dashboard-admin.sales.dashboard.export', ['type' => 'pdf', 'period' => 'bulan_ini']) }}" 
                   class="btn-period {{ $period === 'bulan_ini' ? 'active' : '' }}">Bulan Ini</a>
                <a href="{{ route('dashboard-admin.sales.dashboard.export', ['type' => 'pdf', 'period' => 'kuartal_ini']) }}" 
                   class="btn-period {{ $period === 'kuartal_ini' ? 'active' : '' }}">Kuartal Ini</a>
                <a href="{{ route('dashboard-admin.sales.dashboard.export', ['type' => 'pdf', 'period' => 'tahun_ini']) }}" 
                   class="btn-period {{ $period === 'tahun_ini' ? 'active' : '' }}">Tahun Ini</a>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route('dashboard-admin.sales.dashboard.export', ['type' => 'excel', 'period' => $period]) }}" class="btn btn-outline" style="border-color: #16a34a; color: #16a34a;">
                <iconify-icon icon="vscode-icons:file-type-excel"></iconify-icon>
                Unduh Excel (.csv)
            </a>
            <button type="button" onclick="downloadPdfFile()" class="btn btn-primary" id="btnDownloadPdf" style="background-color: #2563eb;">
                <iconify-icon icon="solar:file-download-bold"></iconify-icon>
                Unduh File PDF
            </button>
            <button type="button" onclick="window.print()" class="btn btn-outline">
                <iconify-icon icon="solar:printer-bold"></iconify-icon>
                Cetak (Print)
            </button>
        </div>
    </div>

    {{-- Main Printable Document --}}
    <div class="report-wrapper">
        {{-- Report Header --}}
        <div class="report-header">
            <div class="company-info">
                <h2>PT ELBEMA INTEGRA INDONESIA</h2>
                <p>Enterprise Resource Planning &amp; Commercial Solutions</p>
                <p style="color: #64748b; font-size: 11px;">BAMA+ Integrated Business Management Suite</p>
            </div>
            <div class="report-meta">
                <div class="report-title">Laporan Sales &amp; BD</div>
                <div class="badge-filter">
                    <iconify-icon icon="solar:calendar-bold" style="vertical-align: -1px; margin-right: 3px;"></iconify-icon>
                    Filter: {{ $periodLabel }}
                </div>
                <div class="meta-text">
                    <strong>Rentang:</strong> {{ $startDate->format('d/m/Y') }} — {{ $endDate->format('d/m/Y') }}<br>
                    <strong>Waktu Cetak:</strong> {{ $now->translatedFormat('d F Y, H:i') }} WIB<br>
                    <strong>Dicetak Oleh:</strong> {{ $user->name ?? 'Admin Sales' }}
                </div>
            </div>
        </div>

        {{-- Executive KPI Metrics --}}
        <div class="kpi-grid">
            <div class="kpi-card kpi-blue">
                <div class="kpi-label">Pipeline Aktif</div>
                <div class="kpi-value">Rp {{ number_format($dispActiveVal, 0, ',', '.') }}</div>
                <div class="kpi-sub">{{ $dispActiveCount }} Opportunity Aktif</div>
            </div>
            <div class="kpi-card kpi-purple">
                <div class="kpi-label">Proposal Terkirim</div>
                <div class="kpi-value">Rp {{ number_format($dispPropVal, 0, ',', '.') }}</div>
                <div class="kpi-sub">{{ $dispPropCount }} Proposal pada periode ini</div>
            </div>
            <div class="kpi-card kpi-green">
                <div class="kpi-label">Closed Won</div>
                <div class="kpi-value">Rp {{ number_format($dispWonVal, 0, ',', '.') }}</div>
                <div class="kpi-sub">{{ $dispWonCount }} Kontrak berhasil</div>
            </div>
            <div class="kpi-card kpi-orange">
                <div class="kpi-label">Win Rate Penjualan</div>
                <div class="kpi-value">{{ $dispWinRate }}%</div>
                <div class="kpi-sub">{{ $closedLostCount }} Deal lost tercatat</div>
            </div>
        </div>

        {{-- Section 1: Opportunities / Deals --}}
        <div class="section-header">
            <h3>1. Daftar Opportunity &amp; Pipeline Penjualan</h3>
            <span class="section-count">Total: {{ $opportunities->count() }} Data</span>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th class="text-center" style="width: 30px;">No</th>
                        <th>Judul Opportunity</th>
                        <th>Klien / Perusahaan</th>
                        <th>Sales Owner</th>
                        <th class="text-center">Tahapan</th>
                        <th class="text-right">Nilai Estimasi (IDR)</th>
                        <th class="text-center">Target Closing</th>
                        <th>Langkah Berikutnya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($opportunities as $index => $opp)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $opp->title }}</td>
                            <td>{{ $opp->customer?->company_name ?? ($opp->customer?->name ?? '-') }}</td>
                            <td>{{ $opp->owner?->name ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge-stage stage-{{ $opp->stage }}">
                                    {{ $stageNames[$opp->stage] ?? ucfirst(str_replace('_', ' ', (string) $opp->stage)) }}
                                </span>
                            </td>
                            <td class="text-right fw-bold">Rp {{ number_format((float) $opp->estimated_value, 0, ',', '.') }}</td>
                            <td class="text-center">
                                {{ $opp->target_close_date ? \Carbon\Carbon::parse($opp->target_close_date)->format('d/m/Y') : '-' }}
                            </td>
                            <td>{{ $opp->next_step ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding: 16px; color: #94a3b8;">
                                Tidak ada data opportunity pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Section 2: Proposals --}}
        <div class="section-header">
            <h3>2. Daftar Proposal Penjualan</h3>
            <span class="section-count">Total: {{ $proposals->count() }} Data</span>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th class="text-center" style="width: 30px;">No</th>
                        <th>Nomor Proposal</th>
                        <th>Klien / Perusahaan</th>
                        <th>Opportunity Terkait</th>
                        <th class="text-right">Nilai Proposal (IDR)</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Tanggal Kirim</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $pIndex => $prop)
                        <tr>
                            <td class="text-center">{{ $pIndex + 1 }}</td>
                            <td class="fw-bold">{{ $prop->proposal_number }}</td>
                            <td>{{ $prop->opportunity?->customer?->company_name ?? ($prop->opportunity?->customer?->name ?? '-') }}</td>
                            <td>{{ $prop->opportunity?->title ?? '-' }}</td>
                            <td class="text-right fw-bold">Rp {{ number_format((float) $prop->amount, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge-stage {{ $prop->status === 'accepted' ? 'stage-closed_won' : ($prop->status === 'rejected' ? 'stage-closed_lost' : 'stage-qualification') }}">
                                    {{ ucfirst((string) $prop->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $prop->submitted_at ? \Carbon\Carbon::parse($prop->submitted_at)->format('d/m/Y') : ($prop->created_at ? \Carbon\Carbon::parse($prop->created_at)->format('d/m/Y') : '-') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding: 16px; color: #94a3b8;">
                                Tidak ada data proposal pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Signatures / Approval --}}
        <div class="signatures">
            <div class="sign-box">
                <div class="sign-role">Disiapkan Oleh:</div>
                <div class="sign-name">{{ $user->name ?? 'Sales / BD Team' }}</div>
            </div>
            <div class="sign-box">
                <div class="sign-role">Mengetahui &amp; Menyetujui:</div>
                <div class="sign-name">Head of Sales &amp; Commercial</div>
            </div>
        </div>

        {{-- Footer Note --}}
        <div class="footer-note">
            <span>Dokumen ini digenerate secara otomatis oleh Sistem BAMA+ ERP. Kerahasiaan data dilindungi.</span>
            <span>Halaman 1 dari 1</span>
        </div>
    </div>

    <script>
        function downloadPdfFile() {
            const btn = document.getElementById('btnDownloadPdf');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<iconify-icon icon="line-md:loading-loop"></iconify-icon> <span>Menyiapkan PDF...</span>';

            const element = document.querySelector('.report-wrapper');
            const periodSlug = '{{ str_replace([" ", "(", ")"], ["-", "", ""], strtolower($periodLabel)) }}';
            const filename = 'Laporan-Sales-' + periodSlug + '.pdf';

            const opt = {
                margin:       [8, 10, 8, 10],
                filename:     filename,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).save().then(function() {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }).catch(function(err) {
                console.error('Error generating PDF:', err);
                btn.disabled = false;
                btn.innerHTML = originalContent;
                // Fallback to native print dialog
                window.print();
            });
        }
    </script>
</body>
</html>
