# Arsitektur & Keterhubungan Menu ERP BAMA+ (PT ELBEMA Integra Indonesia)

Dokumen ini memetakan interkoneksi, aliran data (*data flow*), dan ketergantungan relasional antar 6 departemen utama: **Sales**, **Finance & Tax**, **HCD**, **PMO**, **Procurement & Logistics**, dan **Asset Management**.

---

## 1. Peta Rantai Nilai Bisnis (End-to-End Enterprise Flow)

```text
[SALES] 
   Opportunity Closed Won & Proposal disetujui
        │
        ▼
[PMO (Project Management)] ─── Menugaskan Tim Lapangan ──► [HCD] (Employee Register & Sertifikasi)
   Project Register dibuat        │
        │                         ├─ Request Pembelian ──► [PROCUREMENT] (PR/PO & Partner Vendor)
        │                         │
        │                         └─ Request Alat/Bahan ─► [ASSET MANAGEMENT] (Mesin & Consumables)
        ▼
   Progres Milestone Lapangan (BAST Handover)
        │
        ▼
[FINANCE & TAX]
   1. Terbitkan Penagihan (Invoice Monitoring / AR) berdasarkan Termin & BAST PMO
   2. Catat Biaya Aktual Proyek (Project Cost Management / AP) dari PO Procurement & Pemakaian Aset
   3. Rekapitulasi Pajak PPN & PPh (Corporate Finance Tax Management)
   4. Ringkasan Eksekutif Finansial (Finance & Taxes Dashboard)
```

---

## 2. Matriks Dependensi Lintas Menu & Submenu

| Menu Utama | Submenu | Data Yang Dibutuhkan (Input Dari) | Data Yang Diberikan (Output Ke) |
|---|---|---|---|
| **1. Sales** | **Customer Register & Contact** | Input Mandiri / Sales Team | Master Client untuk **PMO** (Project) dan **Finance** (Invoice Bill-To) |
| | **Sales Pipeline & Proposal** | Customer Register | Saat `Closed Won`, memicu pembuatan **Project Register (PMO)** & Nilai Kontrak |
| | **Sales Performance** | Activity Register, Proposal, Opportunities | Penilaian KPI individu Sales (**HCD**) |
| **2. PMO** | **Project Register** | Proposal disetujui (**Sales**) | Dasar penugasan kerja (**HCD**), PR material (**Procurement**), penagihan termin (**Finance**) |
| | **Planning & Scheduling** | Project Register | Bukti capaian termin untuk **Invoice Monitoring (Finance)** |
| | **Cost Management** | Approved Budget (BAC) Project | Realisasi biaya vs RAB di **Project Cost Management (Finance)** |
| | **Documents & Handover** | Laporan Progres & BAST | Syarat lampiran resmi penagihan **Invoice Monitoring (Finance)** |
| **3. Procurement** | **Procurement (PR/PO)** | Permintaan Material/Jasa dari **PMO** | Tagihan Vendor (AP) masuk ke **Finance (Project Cost)** |
| | **Partner Register** | Input Vendor/Supplier | Database rekanan untuk **Procurement**, rekening & NPWP untuk **Finance** |
| | **Logistics** | PO disetujui (**Procurement**) | Pengiriman barang tiba di site (**PMO**) & stok gudang (**Asset Management**) |
| **4. Asset Mgmt** | **Asset Register** | Aset perusahaan / PO pembelian | Penugasan alat kerja ke **PMO (Project Register)** |
| | **Inspection & Maintenance**| Master Alat & Jadwal Kalibrasi | Bukti kelaikan alat K3/HSSE sebelum masuk site **PMO** |
| | **Material & Consumables** | Pembelian dari **Procurement** | Pembebanan biaya pemakaian ke **Project Cost (Finance)** |
| **5. HCD** | **Employee Register** | Input HRD & Rekrutmen | Sumber user login, alokasi tim di **PMO**, PIC di **Sales** |
| | **Training & Certification**| Data Sertifikat Personel | Syarat lolos sertifikasi teknis (Migas/K3) untuk personel lapangan di **PMO** |
| | **Task Monitoring** | Penugasan kerja harian | Kontrol produktivitas man-hours proyek di **PMO** |
| **6. Finance & Tax**| **Invoice Monitoring (AR)** | Kontrak (**Sales/PMO**) & BAST (**PMO Handover**)| Menghasilkan Cash-In, PPN Keluaran, & Bukti Potong PPh |
| | **Project Cost Management** | PO (**Procurement**) & Timesheet/Alat (**PMO/Asset**) | Menghasilkan Cash-Out, PPN Masukan, & Margin Laba/Rugi |
| | **Corporate Finance Tax** | Invoice AR (PPN Keluaran) & Vendor AP (PPN Masukan) | Laporan SPT Masa PPN & Bukti Potong PPh Perusahaan |
| | **Finance & Taxes Dashboard**| Seluruh data Invoice, Cost, dan Pajak | Ringkasan eksekutif likuiditas, piutang, dan laba rugi |

---

## 3. Rincian Kunci Relasi Database (Foreign Keys Kritis)

Agar menu-menu ini saling terhubung secara otomatis tanpa duplikasi data, berikut tabel master dan relasi kuncinya:

1. **`customers`** (Sales)
   - ➜ `projects.customer_id` (PMO)
   - ➜ `invoices.customer_id` (Finance)

2. **`projects`** (PMO)
   - ➜ `invoices.project_id` (Finance Invoice)
   - ➜ `project_cost_entries.project_id` (Finance Cost)
   - ➜ `purchase_requests.project_id` (Procurement)
   - ➜ `asset_allocations.project_id` (Asset Mgmt)
   - ➜ `project_members.project_id` (HCD Team)

3. **`users` / `employees`** (HCD)
   - ➜ `opportunities.owner_id` (Sales PIC)
   - ➜ `projects.project_manager_id` (PMO PM)
   - ➜ `sales_activities.user_id` (Sales Log)
   - ➜ `training_records.employee_id` (HCD Training)

4. **`partners` / `vendors`** (Procurement)
   - ➜ `purchase_orders.vendor_id` (Procurement)
   - ➜ `vendor_bills.vendor_id` (Finance AP)

5. **`invoices`** (Finance)
   - ➜ `tax_invoices.invoice_id` (Faktur Pajak PPN Keluaran)
   - ➜ `invoice_payments.invoice_id` (Penerimaan Kas)
