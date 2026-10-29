# BAMA+ (CRM & Project Control v1.0) — Implementation Checklist & Progress Tracker
**PT ELBEMA Integra Indonesia**  
*Benchmark Reference: https://elbema-sales-crm-dashboard.juliyantost.chatgpt.site/*

Dokumen ini memantau seluruh modul dan fitur yang sudah selesai dikerjakan serta daftar antrean fitur yang akan dibangun berikutnya.

---

## Ringkasan Progres Keseluruhan

| No | Modul / Departemen | Status | Halaman / Fitur Utama |
|---|---|---|---|
| 0 | **HO Dashboard (Executive Control Tower)** | 🟢 *Completed (100%)* | Ringkasan Eksekutif Lintas Departemen, 4 Macro Corporate KPI, 8 Department Overview Cards |
| 1 | **Sales (CRM)** | 🟢 *Completed (100%)* | Dashboard (✓), Pipeline (✓), Proposal Register (✓), Activity Register (✓), Customer Register (✓), Customer Contact (✓), Performance (✓), AI Opportunities (✓) |
| 2 | **PMO (Project Management)** | 🟢 *Completed (100%)* | PMO Dashboard (✓), Project Register (✓), Cost Management (✓), Planning & Scheduling (✓), Project Documents & Handover (✓) |
| 3 | **Finance & Tax** | 🟢 *Completed (100%)* | Dashboard (✓), Invoice Monitoring (✓), Project Cost Management (✓), Corporate Tax (✓) |
| 4 | **HCD (Human Capital)** | 🟢 *Completed (100%)* | Dashboard (✓), Task Monitoring (✓), Employee Register (✓), Training & Development (✓), Professional Database (✓) |
| 5 | **HSSE** | 🟢 *Completed (100%)* | Safety Dashboard (✓), Permit to Work & JSA (✓), Inspeksi Alat & APD (✓), Laporan Insiden & CAPA (✓) |
| 6 | **Procurement & Logistics** | 🟢 *Completed (100%)* | Dashboard Procurement (✓), Vendor Management (✓), Purchase Request & Items (✓), Purchase Order & Print A4 (✓), Ekspedisi & Logistik Site (✓) |
| 7 | **Asset Management** | 🟢 *Completed (100%)* | Dashboard Asset (✓), Inventaris Mesin & Alat (✓), Alokasi & Mobilisasi Project (✓), Jadwal Maintenance & Kalibrasi (✓) |
| 8 | **My Workspace** | 🟢 *Completed (100%)* | Personal Workbench Karyawan, Timesheet Harian & Log Jam Kerja Mingguan, Quick Status Task Update, Akses Cepat Dokumen & Proyek Lapangan |

---

## Rincian Status per Departemen

### 1. DEPARTEMEN SALES (CRM) — 100% SELESAI
- [x] **Database & Migration CRM**: Tabel `customers`, `opportunities`, `proposals`, `sales_activities`, `follow_ups`
- [x] **Role & Permissions**: Spatie permissions untuk `sales-director`, `sales-executive`
- [x] **1. Sales Dashboard**: 
  - 4 KPI Cards (Pipeline Aktif, Proposal Terkirim, Closed Won, Win Rate %)
  - Filter Waktu (Bulan ini, Kuartal ini, Tahun ini)
  - Sales Funnel Chart (Stage 1 s/d 5)
  - Prioritas Opportunity & Log Aktivitas Terbaru
  - Support Dark Mode & Light Mode
- [x] **2. Sales Pipeline (Kanban & Table)**:
  - Kanban Board 5 Stage (`Qualification` -> `Need Analysis` -> `Tender / RFQ` -> `Proposal Submitted` -> `Negotiation` -> `Closed Won/Lost`)
  - Table View dengan sorting nilai deal & filter PIC
  - Quick Stage Update (AJAX dropdown langsung dari card)
  - Modal Popup Cepat untuk Input Opportunity Baru (tanpa perlu reload halaman penuh)
  - Detail 360 Opportunity (Progres stage banner, Proposal history, Aktivitas, Follow-up)
- [x] **3. Proposal Register & Quotation**:
  - Tracking nomor penawaran, nilai proposal, estimasi direct cost
  - Kalkulasi otomatis Gross Margin %: `((Selling Price - Direct Cost) / Selling Price) * 100` dengan indikator badge warna
  - Upload file proposal/quotation (PDF/DOCX/XLSX/ZIP)
  - Modal Popup Cepat untuk Buat Proposal Baru dengan kalkulator gross margin langsung
  - Badge counter proposal aktif di sidebar
- [x] **4. Sales Activity Register**:
  - 4 KPI Cards (Total Activities, Customer Touchpoints, Recent 7 Days, Hari Ini)
  - Visual breakdown persentase tipe aktivitas (Outside Meeting, Proposal Submitted, Online Presentation, Customer Visit)
  - Filter interaktif: Tipe aktivitas, Opportunity, rentang tanggal, dan search
  - Log interaksi, ringkasan, notes, dan PIC penginput
  - Modal Popup Cepat Tambah & Edit Log Aktivitas
- [x] **5. Customer Register**:
  - Table View dengan filter nama perusahaan, PIC, dan sektor industri
  - Form Tambah & Edit Customer (Profil Perusahaan & Kontak PIC)
  - Customer 360 Detail (Profil, Metrik Deal Aktif, Tab Opportunity & Project)
  - Proteksi integritas relasi sebelum hapus
- [x] **6. Customer Contact**:
  - Direktori contact person PIC kunci seluruh perusahaan klien
  - Breakdown metrik contact by sector dan status engagement
  - Fitur pencarian kontak, no HP/WhatsApp, dan email
  - Modal Tambah & Edit Customer Contact
- [x] **7. Sales Performance**:
  - Filter rentang tanggal fleksibel dan filter Sales Person
  - KPI Cards: Total Closed Won Value, Total Pipeline Value, Proposal Register Value, Activities Logged
  - Leaderboard Top Ranking (Top Closed Won Value, Top Proposal Submissions, Top Activity & Visits)
  - Tabel komparasi kinerja komprehensif (Opportunities, Proposals, Deals, Won Value, Win Rate %)
- [x] **8. Sales AI Opportunities**:
  - Market Intelligence & Public Leads Discovery feed
  - Filter pencarian berbasis kata kunci layanan, sektor industri, dan wilayah
  - AI Match Score indicator (94%, 91%, 89%, dll)
  - Tautan penelusuran web sumber pengadaan / tender
  - Modal Cepat untuk langsung mengonversi peluang AI ke Sales Pipeline aktif

---

### 2. DEPARTEMEN PMO (PROJECT MANAGEMENT) — 100% SELESAI
- [x] **1. PMO Dashboard**:
  - Metrik Earned Value Management (CPI, SPI, BAC, EAC, VAC)
  - S-Curve trend progres fisik vs target (Qty & Value)
  - Breakdown status project (Ongoing, Completed, Closed)
  - Cash collection vs invoice submitted & total man-hours
- [x] **2. Project Register**:
  - Database project eksekusi (Nomor Kontrak/SO, Client, Project Manager, Tanggal Kickoff, Nilai Kontrak)
  - Project Detail 360 (Timeline, team allocation, milestones, documents)
  - Quick Add Project Modal & column customization
- [x] **3. Cost Management**:
  - Approved Budget (BAC), Committed Cost, Actual Cost, Cost Forecast, Variance
  - 10 Kategori Biaya (Personnel, Equipment, Material, Subcontractor, Transport, dll.)
  - Transaction Ledger / Cost Journal & Modal Input Transaksi Biaya
  - Invoice & Cash Collection Monitoring
- [x] **4. Planning & Scheduling**:
  - Breakdown milestone pengerjaan lapangan & persentase progres aktual vs plan
  - Baseline Finish vs Forecast Finish & Day Variance (+/- Hari)
  - Status alert (ON TRACK, WATCH, DELAYED)
  - Modal Tambah & Edit Milestone WBS
- [x] **5. Project Documents & Handover**:
  - Central Document Archive across all portfolio projects
  - Turnover Dossier (Draft, In Review, Approved) & Handover Status (BAST)
  - Punch List Open Tracker & Certificate Status
  - Upload & Download Dokumen Proyek (PDF/DOCX/XLSX/ZIP)

---

### 3. DEPARTEMEN FINANCE & TAX — 🟢 Completed (100%)
- [x] **1. Invoice Monitoring & Account Receivable (AR)**:
  - Database & Migrations: Tabel `invoices` & `invoice_payments` dengan relasi ke `customers`, `projects`, dan `users`
  - 4 KPI Summary Cards (Total Invoiced Gross, Cash Collected/Paid, Outstanding AR Piutang, Overdue Count)
  - Tab status filter (Semua, Draft, Terkirim/Unpaid, Partial, Lunas, Overdue) & pencarian interaktif
  - Modal Buat Invoice Baru dengan kalkulator PPN 11% & pemotongan PPh (PPh 23 / PPh Final 4(2)) otomatis secara live
  - Modal Catat Pembayaran / Kas Masuk (update otomatis saldo piutang & status lunas/partial)
  - Modal Detail 360 & Riwayat Pembayaran (tax breakdown, customer info, project info, ledger kas masuk)
  - Upload lampiran invoice/BAST dan bukti transfer
  - Seeder data realistis proyek migas (`FinanceInvoiceSeeder`)
- [x] **2. Project Cost Management (RAB & Realisasi AP)**:
  - Database & Migrations: Field `payment_status` (paid/unpaid AP), `tax_amount` (PPN Masukan), `pph_amount`, dan `attachment_path` pada `project_cost_entries`
  - 4 KPI Cards: Total Approved Budget (RAB), Realisasi Beban (Actual Cost), Hutang Vendor (AP Unpaid), Gross Profit Portfolio %
  - Tab 1: Analisis Anggaran & Margin per Proyek (Nilai Kontrak, RAB, Beban Aktual, Sisa Budget, Burn Rate progress bar, Gross Margin %, Status Healthy/Warning/Overrun)
  - Tab 2: Alokasi 10 Kategori Biaya Standar Industri Migas (Manpower, Equipment, Material, Subcon, Transport, dll.)
  - Tab 3: Jurnal Beban & Tagihan Vendor AP lengkap dengan filter status bayar dan aksi sekali klik pelunasan hutang vendor
  - Modal Input & Upload Bukti Pembayaran Beban Proyek
- [x] **3. Corporate Finance Tax Management**:
  - Database & Migrations: Tabel `tax_filings` untuk pengarsipan bukti setor NTPN & tanda terima BPE DJP
  - 4 KPI Cards: Pajak Keluaran (PK), Pajak Masukan (PM), Net Kurang/Lebih Bayar, dan Total Kredit Pajak PPh 23
  - Tab 1: Rekonsiliasi SPT Masa PPN 11% Bulanan (Januari s/d Desember) otomatis mengagregasi DPP & PPN dari Invoice vs Pengeluaran Proyek
  - Tab 2: Rekapitulasi Withholding Tax (PPh 23/4(2) yang dipotong klien vs PPh yang dipotong ke rekanan vendor)
  - Tab 3: Buku Arsip Kode NTPN, Nomor BPE DJP, status bayar/lapor, dan download lampiran tanda terima elektronik
  - Modal Rekam Setoran Pajak & NTPN dengan integrasi tombol Lapor cepat dari baris rekonsiliasi bulanan
  - Seeder data pelaporan pajak historis (`FinanceTaxSeeder`)
- [x] **4. Finance & Taxes Executive Dashboard**:
  - 4 Executive Metric Cards: Net Cash Flow (Surplus/Defisit), Outstanding Receivables (AR), Outstanding Payables (AP), dan Net Working Capital
  - Visual Chart: Trend Arus Kas Bulanan 12 Bulan (Cash In vs Cash Out vs Net Flow via ApexCharts)
  - AR Aging Schedule: Breakdown usia piutang (Current 0-30 hari, 31-60 hari, 61-90 hari, > 60 hari kritis)
  - Top 5 Proyek dengan Efisiensi & Gross Margin Tertinggi
  - Daftar Penagihan Jatuh Tempo Terdekat (Upcoming Due Invoices dalam 14 hari)
  - Feed Transaksi Kas Masuk & Kas Keluar Operasional Terbaru secara real-time

---

### 4. DEPARTEMEN HCD (HUMAN CAPITAL DEVELOPMENT) — 100% SELESAI
- [x] **Database & Migration HCD**:
  - Kolom biodata lanjutan pada `employees`: `nik`, `employment_status` (permanent, contract, freelance), `work_location`, `status`, `daily_rate`, `skills`, `education`, `emergency_contact_name`, `emergency_contact_phone`.
  - Tabel `hcd_tasks`: Task monitoring penugasan kerja per personel, proyek terkait, departemen, prioritas (low/medium/high/urgent), estimasi vs actual man-hours, status pengerjaan, dan due date.
  - Tabel `hcd_trainings`: Katalog kurikulum sertifikasi teknis & K3 (Kemenaker, BNSP, TUV Rheinland, dll.).
  - Tabel `hcd_employee_trainings`: Catatan sertifikat tiap karyawan dengan pelacak masa kadaluarsa (otomatis mendeteksi status valid, expiring soon < 30 hari, dan expired).
  - Tabel `hcd_professionals`: Talent pool database tenaga ahli eksternal, spesialis lapangan (Hot Tapping, UHP Hydrojetting, Controlled Bolting, NDT Level III, CSMS Assessor) lengkap dengan ketersediaan (available, mobilized, standby), rating, dan penugasan proyek.
- [x] **1. HCD Executive Dashboard**:
  - 4 Executive KPI Cards: Total Headcount (Tetap vs Kontrak), Mobilisasi Kru Lapangan (On-Site vs Head Office), Rasio Sertifikasi Personel (Valid vs Expired), dan Task Completion Rate %.
  - Distribusi Tenaga Kerja per Departemen (PMO, Operations, HSSE, Sales, HCD).
  - Integrasi Kru Lapangan pada Proyek Aktif PMO.
  - Radar Kepatuhan Sertifikasi Kritis (Alert sertifikasi yang kadaluarsa atau mendekati jatuh tempo < 30 hari).
  - Log Tugas & Penugasan Terbaru secara real-time.
- [x] **2. Task Monitoring**:
  - 4 KPI Metrik: Total Tugas, Sedang Dikerjakan (In Progress), Menunggu & Review, Selesai (Completed).
  - Status Tab Pills: Semua, Pending, In Progress, Review, Completed, Blocked.
  - Filter lengkap berdasarkan Prioritas, Proyek Terkait, dan Pencarian teks.
  - Tracking estimasi jam kerja vs realisasi actual man-hours.
  - Quick Modal Update Status & Realisasi Jam Kerja.
  - Modal Buat Tugas Baru dengan kode otomatis `TSK-YYYY-xxx`.
- [x] **3. Employee Register**:
  - 4 KPI Metrik: Total Headcount, Karyawan Tetap (PKWTT), Karyawan Kontrak (PKWT), Kru Mobilisasi Site.
  - Filter terpadu: Departemen, Status Kerja, Lokasi Kerja (Head Office, Site Bontang, Palembang, Sorowako), dan pencarian NIK/NIP/Telepon.
  - Profil 360 Karyawan (`/hcd/employees/{id}`): Hubungan kerja, masa kerja, rate harian, riwayat sertifikasi K3, daftar tugas, dan keahlian teknis.
  - Modal Daftarkan Karyawan Baru (Otomatis membuat user akun login ERP).
  - Modal Edit Karyawan & update status aktif.
- [x] **4. Training & Development**:
  - 4 KPI Metrik: Total Sertifikat, Sertifikasi Valid, Mendekati Expired (<30 Hari), Expired (Perlu Renewal).
  - 2 Tab Navigasi: Pelacak Sertifikasi Personel vs Katalog Program Pelatihan.
  - Filter status validitas sertifikat & pencarian nama personel/no. sertifikat.
  - Modal Catat Sertifikat Karyawan baru (upload scan file sertifikat, nomor registrasi, biaya pelatihan).
  - Modal Tambah Program Pelatihan baru ke katalog.
- [x] **5. Professional Database (Talent Pool)**:
  - 4 KPI Metrik: Total Talent Pool, Siap Mobilisasi (Available), Sedang Bertugas (Mobilized), Standby / Cadangan.
  - Filter Kategori Bidang: Mechanical & Piping, Surface Preparation, Inspection & QA/QC, Machining, HSSE Field, Electrical.
  - Filter Ketersediaan & pencarian keahlian / kota asal.
  - Informasi detail: Pengalaman tahun, rate harian, rating bintang (1-5), sertifikasi kunci, dan proyek yang sedang ditugaskan.
  - Modal Tambah & Edit Tenaga Ahli beserta penugasan langsung ke proyek PMO.

---

### 5. DEPARTEMEN HSSE (HEALTH, SAFETY, SECURITY & ENVIRONMENT) — 100% SELESAI
- [x] **Database & Migration HSSE**:
  - Tabel `hsse_permits`: Permit to Work (PTW) & Job Safety Analysis (JSA), multi-gas readings (O₂, LEL, CO, H₂S), tingkat risiko (Low, Medium, High, Critical), persyaratan APD, workflow status (Draft, Under Review, Approved, Active, Closed, Rejected), relasi ke Proyek PMO dan Karyawan (Pemohon & Safety Officer).
  - Tabel `hsse_inspections`: Log inspeksi pra-operasi peralatan berat, power tools, APD & lifeline, tabung bertekanan, hasil uji (Passed, Passed with notes, Failed / Quarantined), sistem color tag (Green, Yellow, Red Tagging), dan jadwal kalibrasi ulang.
  - Tabel `hsse_incidents`: Buku register insiden & near-miss, klasifikasi tingkat keparahan (Near Miss, FAC, MTC, LTI, RWC, Property Damage, Environmental Spill), Root Cause Analysis (RCA), Corrective & Preventive Action (CAPA), target due date, dan perhitungan hari kerja hilang (Lost Workdays).
- [x] **1. HSSE Executive Dashboard**:
  - 4 Safety KPI Cards: Zero Accident Days (Hari Kerja Selamat tanpa LTI), Akumulasi Safe Man-Hours seluruh turnaround plant, Active Permits to Work, dan Inspection Pass Rate % dengan alert tag merah aktif.
  - Radar Permit to Work & JSA Aktif di site operasional (Plant Fertilizer & Refinery).
  - Incident & Near Miss Monitor: Breakdown keparahan (Near Miss, First Aid, LTI) & status investigasi CAPA.
  - Karantina Alat & Tag Merah: Pengawasan unit dilarang beroperasi sampai perbaikan tuntas.
  - Jadwal Kalibrasi & Inspeksi Ulang dalam 14 hari ke depan.
- [x] **2. Safety Permit to Work (PTW) & JSA**:
  - Tab Status Filter: Semua PTW, Aktif Lapangan, Disetujui, Under Review, Selesai / Ditutup.
  - Filter Kategori Pekerjaan (Hot Work, Confined Space, Working at Height, Electrical Isolation/LOTO, Lifting & Rigging) & Tingkat Risiko.
  - Panel Pembacaan Gas Atmosfer Multi-Gas Detector (O₂, LEL, CO, H₂S).
  - Modal Terbitkan PTW & JSA Baru dengan nomor registrasi otomatis `PTW-YYYY-xxx`.
  - Modal Alur Persetujuan & Penutupan Izin Kerja (Approval Workflow & Safety Officer sign-off).
- [x] **3. Inspeksi Alat & APD (Equipment & PPE Inspection)**:
  - Tab Filter Hasil Uji: Semua Unit, Lolos Layak Pakai, Lolos Bersyarat, Gagal / Karantina.
  - Filter Kategori Alat & Warna Color Tag (Green, Yellow, Red Tag).
  - Pelacak tanggal inspeksi berkala & jadwal kalibrasi berikutnya.
  - Modal Catat Hasil Inspeksi Alat & APD Baru dengan nomor otomatis `INSP-YYYY-xxx` dan auto-switch warna tag.
- [x] **4. Laporan Insiden & Near Miss (Incident Register & CAPA)**:
  - Tab Status Investigasi: Semua Kejadian, Baru Dilaporkan, Investigasi RCA, Tindakan CAPA Berjalan, Selesai / Closed.
  - Filter Klasifikasi Insiden & Proyek Terkait.
  - Indikator Lost Workdays (Hari Kerja Hilang).
  - Modal Pelaporan Insiden Baru dengan nomor otomatis `INC-YYYY-xxx`.
  - Modal Investigasi Lapangan: Analisis Akar Masalah (RCA) & Rencana Tindakan Korektif (CAPA) dengan target tanggal selesai.

---

### 6. DEPARTEMEN PROCUREMENT & LOGISTICS — 🟢 Completed (100%)
- [x] **Database & Migration Procurement**:
  - Tabel `procurement_vendors`: Master database rekanan vendor terverifikasi, spesialisasi, rating kinerja, NPWP, dan rekening bank untuk integrasi Finance AP.
  - Tabel `purchase_requests` & `purchase_request_items`: Alur pengajuan kebutuhan material/alat proyek PMO, persetujuan bertingkat, dan estimasi anggaran.
  - Tabel `purchase_orders` & `purchase_order_items`: Kontrak pemesanan barang/jasa resmi, perhitungan subtotal, PPN 11%, diskon, grand total, syarat pembayaran, dan pencatatan komitmen biaya proyek (PMO & Finance AP).
  - Tabel `logistics_deliveries`: Pelacakan ekspedisi surat jalan, nomor resi AWB, armada kendaraan, rute pengiriman, dan tanda terima berita acara (BAP) di site proyek.
- [x] **1. Procurement Executive Dashboard**:
  - 4 Executive KPI Cards: Total Nilai PO Diterbitkan, Total PR Menunggu Review/Approval, Mitra Rekanan Aktif, dan Ekspedisi Material Dalam Perjalanan.
  - Alur Pengadaan Interaktif: Siklus status 5 langkah (Draft -> Review -> PO Issued -> In Transit -> Selesai di Site).
  - Donut Chart Analisis Belanja per Kategori Spesialisasi Vendor (Piping, Heavy Equipment, Consumables, Safety, dll.).
  - Monitoring PR Prioritas Mendesak (Urgent Shutdown / Turnaround) & Top 5 Rekanan Vendor Terbesar.
  - Radar Logistik Aktif & Tracking Pergerakan Kargo ke Lapangan.
- [x] **2. Vendor Management (Mitra Rekanan)**:
  - 4 Status KPI Metrik: Total Vendor, Aktif Terverifikasi, Dalam Penilaian (Under Review), dan Blacklisted.
  - Filter interaktif Kategori Spesialisasi, Status Kualifikasi, dan pencarian teks.
  - Modal Daftarkan Rekanan Baru & Modal Edit Vendor.
  - Modal Detail 360 Rekanan: Profil perusahaan, PIC, alamat kantor/workshop, rekening bank AP, rating bintang, dan catatan kualifikasi.
- [x] **3. Purchase Request (PR) Register**:
  - 4 Metrik Status: Total PR, Menunggu Review, Disetujui, dan Converted to PO.
  - Filter Tingkat Prioritas (Urgent, High, Medium, Low), Proyek Terkait, dan Kategori.
  - Form Pembuatan PR Baru Dinamis: Tambah/hapus baris item material dengan kalkulasi otomatis subtotal dan total anggaran.
  - Modal Alur Persetujuan & Tinjau Ulang PR (Approval Workflow & Catatan Evaluasi).
  - Modal Detail 360 PR & Tombol Cepat Konversi ke PO (Convert to PO) saat disetujui.
- [x] **4. Purchase Order (PO) Register & Cetak A4**:
  - 4 Metrik Finansial: Total PO Terbit, PO Aktif Berjalan, Dalam Pengiriman, dan Selesai di Site.
  - Filter Mitra Vendor, Proyek PMO, Status, dan Pencarian.
  - Form Pembuatan PO Baru dengan fitur **Impor Otomatis dari PR yang Disetujui** atau input manual.
  - Kalkulator Finansial Otomatis: Subtotal (DPP), Potongan Diskon, PPN 11%, dan Grand Total PO.
  - Integrasi Otomatis ke Komitmen Biaya Proyek PMO & Jurnal Beban Finance AP.
  - Halaman Cetak Resmi / Download PDF (`/procurement/purchase-orders/{id}`): Format kop surat standar PT ELBEMA Integra Indonesia, ship-to proyek, terms & conditions, dan blok tanda tangan tiga pihak (Buyer, Direktur, Vendor).
- [x] **5. Ekspedisi & Logistik Lapangan**:
  - 4 Metrik Logistik: Total Pengiriman, Dalam Perjalanan (In Transit), Tiba di Site, dan Diterima Lengkap (BAP).
  - Filter Status Pengiriman, Proyek Lapangan Tujuan, dan Pencarian No. Resi/Surat Jalan.
  - Modal Catat Pengiriman Logistik Baru terhubung ke PO dan Proyek.
  - Modal Update Status Pengiriman (In Transit -> Arrived at Site -> Inspected & Received di Site) dengan pencatatan tanggal tiba aktual dan auto-complete status PO.
  - Modal Detail 360 Pengiriman & Catatan BAP Lapangan.
- [x] **Seeder Data Industri Migas Realistis (`ProcurementSeeder`)**:
  - 5 Rekanan vendor migas tersertifikasi (ASME B16.5, Hydrojet 40k psi, APD K3, Gas Industri, Stud Bolt B7).
  - 4 Dokumen PR realistis lengkap dengan rincian item material turnaround.
  - 3 Dokumen PO lengkap dengan perhitungan pajak PPN 11% dan status delivery.
  - 3 Pengiriman kargo logistik ekspedisi laut & darat (Balikpapan, Bontang, Palembang).

---

### 7. DEPARTEMEN ASSET MANAGEMENT — 🟢 Completed (100%)
- [x] **Database & Migration Asset Management**:
  - Tabel `assets`: Master register mesin & instrumen khusus (UHP Hydrojet 40k psi, Torque Wrench, Machining Split Frame, Compressor 900 CFM, Hydrotest Skid), nomor seri, valuasi perolehan, kondisi fisik (good, fair, maintenance_needed, damaged), status operasional (available, mobilized, under_maintenance, decommissioned), lokasi standby, dan relasi PIC & Proyek.
  - Tabel `asset_allocations`: Mobilisasi alat ke site klien (Pupuk Kaltim Bontang, PT Vale Sorowako), nomor surat jalan BAST, armada pengangkut, pelacak tanggal estimasi kembali vs realisasi, dan pencatatan kondisi fisik saat diterima kembali di workshop.
  - Tabel `asset_maintenances`: Buku jadwal servis & kalibrasi berkala (Preventive, Corrective, Calibration, Overhaul, Certification), estimasi vs realisasi biaya, nomor sertifikat akreditasi KAN/Disnaker, dan pembaharuan otomatis tanggal next service.
- [x] **1. Asset Executive Dashboard**:
  - 4 Executive KPI Cards: Total Valuasi Asset (Rp Milyar), Utilisasi Alat Lapangan %, Perawatan & Kalibrasi Mendekati Jatuh Tempo, dan Kesiapan Operasional %.
  - Distribusi Valuasi & Kuantitas per Kategori (High Pressure Pump, Bolting Tool, Machining Tool, Compressor, Testing Instrument).
  - Radar Unit Kritis & Perlu Perbaikan Workshop.
  - Monitoring Alokasi Lapangan Aktif & Surat Jalan BAST.
- [x] **2. Inventaris Mesin & Peralatan (Asset Register)**:
  - 4 Status KPI Metrik: Total Unit, Tersedia di Workshop (Available), Dimobilisasi (Mobilized), dan Under Maintenance.
  - Filter Kategori, Status Operasional, Kondisi Fisik, dan Pencarian serial number / merk.
  - Modal Daftarkan Asset Baru dengan generate otomatis kode `AST-YYYY-xxx`.
  - Modal Edit Asset & Update Kondisi Fisik.
  - Modal Detail 360 Asset: Spesifikasi teknis, nomor seri, riwayat perawatan, tanggal kalibrasi, dan lokasi unit.
  - Proteksi integritas: Asset yang sedang dimobilisasi ke proyek dilarang dihapus.
- [x] **3. Alokasi Project Lapangan (Asset Mobilization)**:
  - 3 Status Metrik: Total Riwayat Alokasi, Aktif di Site, dan Sudah Dikembalikan (Returned).
  - Form Mobilisasi Cepat: Pemilihan asset yang berstatus *available*, proyek PMO tujuan, site lapangan, dan nomor surat jalan BAST.
  - Otomatisasi Status: Saat dimobilisasi, status asset otomatis berubah menjadi *mobilized* dan lokasi ter-update ke site proyek.
  - Modal Catat Pengembalian (Return Asset): Tanggal tiba di gudang, checklist kondisi penerimaan alat, dan auto-release status asset kembali menjadi *available*.
  - Modal Detail 360 Alokasi.
- [x] **4. Jadwal Maintenance & Kalibrasi (Maintenance Schedule)**:
  - 4 Status Metrik: Total Record, Terjadwal, Sedang Dikerjakan (In Progress), dan Selesai (Completed) beserta alert keterlambatan (*overdue*).
  - Filter Tipe Servis (Preventive, Corrective, Calibration, Overhaul, Certification).
  - Modal Buat Jadwal Servis & Kalibrasi Baru dengan kode otomatis `MTC-YYYY-xxx`.
  - Modal Selesaikan Perawatan: Input tanggal selesai, tindakan perbaikan, penggantian spare part, nomor sertifikat kalibrasi, dan auto-update tanggal kalibrasi berikutnya pada master asset.
- [x] **Seeder Data Industri Migas Realistis (`AssetManagementSeeder`)**:
  - 5 Asset utama bernilai miliaran rupiah (Hammelmann UHP 40k psi, Enerpac Torque Wrench, Wachs Split Frame Cutter, Atlas Copco 900 CFM, Maximator Hydrotest Skid).
  - 2 Alokasi site aktif di Bontang & Sorowako.
  - 2 Dokumen jadwal servis kompresor & kalibrasi pressure gauge berlisensi KAN.

---

### 8. MODUL PERSONAL: MY WORKSPACE — 🟢 Completed (100%)
- [x] **Database & Migration Personal Timesheet**:
  - Tabel `user_timesheets`: Catatan harian log jam kerja mandiri staf operasional/lapangan/kantor (`user_id`, `employee_id`, `project_id`, `task_id`, `date`, `hours`, `work_type`, `activity_summary`, `location`, `status`, `notes`).
- [x] **1. Posisi Menu Sidebar Paling Atas**:
  - Ditempatkan langsung pada urutan teratas di bawah header *Aplikasi* (sebelum modul departemen) dengan badge *Personal* dan icon *solar:laptop-minimalistic-bold-duotone*.
  - Dapat diakses langsung oleh seluruh user login tanpa terhalang permission departemen khusus.
- [x] **2. Executive Profile Banner & Greeting**:
  - Menyapa nama akun user aktif secara personal (`Selamat Datang, {User}`) disertai NIK dan jabatan dari profil pegawai (`employees`).
  - Indikator status akun aktif & tanggal lokal Bahasa Indonesia.
  - Tombol aksi cepat modal `+ Catat Timesheet / Jam Kerja`.
- [x] **3. 4 KPI Metrics Card Personal**:
  - Total Tugas Ditugaskan (dengan rincian counter Selesai vs Tertunda).
  - Tugas Aktif yang sedang berjalan (Pending / In Progress / Review).
  - Total Jam Kerja (Timesheet) Minggu Berjalan & Progress Bar capaian target 40 Jam/Minggu.
  - Akumulasi Man-Hours Bulan Berjalan untuk dasar payroll & pelaporan man-power proyek.
- [x] **4. Tabel Daftar Tugas Personal (My Tasks)**:
  - Menampilkan daftar tugas dari modul HCD yang ditugaskan (*assigned*) langsung ke akun/pegawai atau dibuat oleh user.
  - Informasi lengkap: Kode & judul tugas, proyek terkait, badge prioritas, batas waktu (*due date*), dan status saat ini.
  - Tombol aksi lingkaran (*circular action button*) untuk membuka Modal Quick Update Status pengerjaan & pencatatan jam aktual.
- [x] **5. Timesheet & Log Jam Kerja Mingguan**:
  - Tabel catatan jam kerja Senin s/d Minggu pada minggu berjalan.
  - Rincian kolom: Tanggal, Jenis Pekerjaan (On-Site Project, Workshop Prep, Engineering Design, Customer Meeting, Administration, Overtime), Proyek Terkait, Uraian Ringkas Pekerjaan, Lokasi (Site/Workshop), Durasi Jam, dan Status Validasi.
  - Modal Form Pencatatan Jam Kerja Harian lengkap dengan input proyek, tipe aktivitas, lokasi, dan catatan kendala lapangan.
- [x] **6. Pintasan Cepat Proyek & Dokumen Resmi**:
  - Card daftar proyek PMO yang diikuti oleh user dengan shortcut langsung ke detail proyek.
  - Tombol pintasan langsung ke formulir operasional rutin: Buat Purchase Request (PR), Permohonan Alokasi Asset Lapangan, dan Pelaporan Insiden Keselamatan HSSE / Nearmiss.

---

### 0. EXECUTIVE CONTROL TOWER: HO DASHBOARD — 🟢 Completed (100%)
- [x] **Transformasi Dashboard Index ke HO Dashboard**:
  - Mengubah halaman default utama `/dashboard-admin` (`dashboard-admin.index`) dari template lama menjadi **HO Dashboard (Head Office Dashboard)** sesuai benchmark PT ELBEMA Integra Indonesia.
- [x] **1. Header & Tagline Korporat**:
  - Badge `CORPORATE · HEAD OFFICE` & `DATA TERINTEGRASI` (Live Data).
  - Judul `HO Dashboard` dan deskripsi: *"Ringkasan eksekutif lintas departemen ELBEMA. Pilih kartu untuk membuka detail unit kerja."*
  - Tombol navigasi cepat ke `My Workbench` & badge tanggal pembaruan sistem.
- [x] **2. 4 Kartu Metrik Eksekutif Makro**:
  - **Sales Pipeline**: Menampilkan total potensi nilai pipeline dan jumlah peluang aktif yang sedang diprospek.
  - **Nilai Portfolio Proyek**: Akumulasi total nilai kontrak seluruh proyek yang terdaftar di sistem.
  - **Invoice Outstanding**: Nilai piutang tagihan berjalan (*submitted minus paid*).
  - **Komitmen Procurement**: Total komitmen pesanan pembelian (PO) pada register pembiayaan proyek.
- [x] **3. Department Overview (8 Kartu Ringkasan Unit Kerja)**:
  - **Sales (CRM)**: Opportunity aktif, pipeline value, proposal terkirim, sales order (won), dan link langsung `Buka Sales →`.
  - **Finance & Tax**: Total invoice terbit, submitted, pembayaran diterima (paid), sisa outstanding, dan link `Buka Finance & Tax →`.
  - **HCD**: Jumlah pegawai terdaftar, program training aktif, sertifikasi kompetensi, dan link `Buka HCD →`.
  - **PMO (Project Control)**: Proyek terdaftar, proyek on progress, rata-rata progres fisik %, status CPI/SPI, dan link `Buka PMO →`.
  - **HSSE**: Akumulasi safe man-hours, izin kerja (PTW) aktif, inspeksi alat kelaikan, open incident, dan link `Buka HSSE →`.
  - **Procurement & Logistics**: Total PO terbit, PR menunggu review, mitra rekanan aktif, ekspedisi in transit, dan link `Buka Procurement →`.
  - **Asset Management**: Total valuasi asset mesin, unit standby di workshop, mobilisasi di site, jadwal servis/kalibrasi, dan link `Buka Asset →`.
  - **Portal & Web Traffic**: Total pengunjung web company profile publik, artikel blog, dan layanan publik.
- [x] **4. Struktur Navigasi Sidebar**:
  - `My Workspace` -> `My Workbench`
  - `Dashboard` -> `HO Dashboard`
  - `Functional Departments` -> Modul per departemen teknis.



