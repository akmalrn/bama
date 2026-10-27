# BAMA+ (CRM & Project Control v1.0) — Implementation Checklist & Progress Tracker
**PT ELBEMA Integra Indonesia**  
*Benchmark Reference: https://elbema-sales-crm-dashboard.juliyantost.chatgpt.site/*

Dokumen ini memantau seluruh modul dan fitur yang sudah selesai dikerjakan serta daftar antrean fitur yang akan dibangun berikutnya.

---

## Ringkasan Progres Keseluruhan

| No | Modul / Departemen | Status | Halaman / Fitur Utama |
|---|---|---|---|
| 1 | **Sales (CRM)** | 🟢 *Completed (100%)* | Dashboard (✓), Pipeline (✓), Proposal Register (✓), Activity Register (✓), Customer Register (✓), Customer Contact (✓), Performance (✓), AI Opportunities (✓) |
| 2 | **PMO (Project Management)** | 🟢 *Completed (100%)* | PMO Dashboard (✓), Project Register (✓), Cost Management (✓), Planning & Scheduling (✓), Project Documents & Handover (✓) |
| 3 | **Finance & Tax** | 🟢 *Completed (100%)* | Dashboard (✓), Invoice Monitoring (✓), Project Cost Management (✓), Corporate Tax (✓) |
| 4 | **HCD (Human Capital)** | 🟢 *Completed (100%)* | Dashboard (✓), Task Monitoring (✓), Employee Register (✓), Training & Development (✓), Professional Database (✓) |
| 5 | **HSSE** | ⚪ *Pending (0%)* | Safety Metrics (Zero Accident Counter), Permit to Work (PTW), Incident Log |
| 6 | **Procurement & Logistics** | ⚪ *Pending (0%)* | PR/PO Register, Approval Flow, Partner Register |
| 7 | **Asset Management** | ⚪ *Pending (0%)* | Asset Register (UHP Water Jet, Torque Wrench), Maintenance, MRO Release Flow |
| 8 | **My Workspace** | ⚪ *Pending (0%)* | Quick Quotation/PO Print A4 Layout, Personal Workbench, Timesheet |

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

### 6. DEPARTEMEN PROCUREMENT & LOGISTICS
- [ ] **Procurement Register**: Purchase Request (PR) & Purchase Order (PO)
- [ ] **Vendor Management**: Database supplier & subkontraktor migas terverifikasi
- [ ] **Logistics Tracking**: Pengiriman barang/sparepart ke site

---

### 7. DEPARTEMEN ASSET MANAGEMENT
- [ ] **Asset Register**: Mesin dan peralatan kerja khusus (UHP Water Jet Pump, Torque Wrench, Gas Detector)
- [ ] **Maintenance & Kalibrasi**: Jadwal inspeksi dan sertifikasi kelaikan alat
- [ ] **Material & Consumable Release**: Approval alokasi consumable gudang
