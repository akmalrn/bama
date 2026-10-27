<?php

namespace Database\Seeders;

use App\Models\Database\CategoryProduct;
use App\Models\Database\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'ERP',
                'title' => 'Software ERP Terintegrasi',
                'overview' => 'Integrasikan semua departemen dalam satu sistem data',
                'description' => 'Ubah sistem manajemen bisnis yang kompleks & kaku menjadi lebih simpel, fleksibel, kompetitif, dan adaptif terhadap perubahan pasar.',
                'icon' => '<iconify-icon icon="hugeicons:layout-table-02" style="color: #ec4899;"></iconify-icon>',
                'order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Manufaktur',
                'title' => 'Software ERP Manufaktur & Pabrikasi',
                'overview' => 'Visibilitas penuh dari material hingga produk jadi',
                'description' => 'Kontrol menyeluruh proses produksi, bill of materials (BOM), kapasitas mesin, dan jadwal kerja secara akurat.',
                'icon' => '<iconify-icon icon="solar:buildings-2-linear" style="color: #0284c7;"></iconify-icon>',
                'order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Construction',
                'title' => 'Software ERP Manajemen Konstruksi',
                'overview' => 'Pantau RAB vs realisasi biaya proyek secara langsung',
                'description' => 'Monitoring progres fisik lapangan, kurva S, pengadaan material proyek, dan profitabilitas tiap proyek secara real-time.',
                'icon' => '<iconify-icon icon="mdi:crane" style="color: #f43f5e;"></iconify-icon>',
                'order' => 3,
                'status' => true,
            ],
            [
                'name' => 'Mining',
                'title' => 'Software ERP Industri Pertambangan',
                'overview' => 'Kelola seluruh aspek dalam bisnis pertambangan',
                'description' => 'Otomasi manajemen eksplorasi, penimbangan, pemeliharaan alat berat, logistik tambang, dan kepatuhan regulasi.',
                'icon' => '<iconify-icon icon="mdi:pickaxe" style="color: #0284c7;"></iconify-icon>',
                'order' => 4,
                'status' => true,
            ],
            [
                'name' => 'Food Production',
                'title' => 'Software ERP Produksi Makanan & Minuman',
                'overview' => 'Kelola stok bahan & penjualan dari semua outlet F&B',
                'description' => 'Manajemen resep standar, pelacakan lot kedaluwarsa, standardisasi mutu, dan kepatuhan sertifikasi pangan.',
                'icon' => '<iconify-icon icon="mdi:food-drumstick-outline" style="color: #f43f5e;"></iconify-icon>',
                'order' => 5,
                'status' => true,
            ],
            [
                'name' => 'Supermarket',
                'title' => 'Software ERP Supermarket & Minimarket',
                'overview' => 'Kelola gudang, supply chain, & supplier di satu sistem',
                'description' => 'Integrasi barcode scanning, replenishment otomatis, promosi berkala, dan sinkronisasi kasir multi-kasir.',
                'icon' => '<iconify-icon icon="solar:cart-large-2-linear" style="color: #a855f7;"></iconify-icon>',
                'order' => 6,
                'status' => true,
            ],
            [
                'name' => 'F&B',
                'title' => 'Software ERP Restoran & Kuliner (F&B)',
                'overview' => 'Kelola stok bahan & penjualan dari semua outlet F&B',
                'description' => 'Sistem kitchen display, reservasi meja, split bill, perhitungan food cost akurat, dan program loyalitas pelanggan.',
                'icon' => '<iconify-icon icon="solar:cup-hot-linear" style="color: #f59e0b;"></iconify-icon>',
                'order' => 7,
                'status' => true,
            ],
            [
                'name' => 'Retail',
                'title' => 'Software ERP Retail & Multi-Cabang',
                'overview' => 'Kelola seluruh outlet dalam satu platform dengan mudah',
                'description' => 'Manajemen harga grosir/eceran, transfer stok antar toko, program membership, dan rekonsiliasi kasir harian.',
                'icon' => '<iconify-icon icon="solar:shop-2-linear" style="color: #a855f7;"></iconify-icon>',
                'order' => 8,
                'status' => true,
            ],
            [
                'name' => 'Akuntansi',
                'title' => 'Software Akuntansi & Manajemen Keuangan',
                'overview' => 'Laporan neraca, L/R & cash flow otomatis real-time',
                'description' => 'Penjurnalan otomatis, manajemen multi-mata uang, integrasi e-faktur pajak, dan analisis rasio keuangan mendalam.',
                'icon' => '<iconify-icon icon="solar:calculator-linear" style="color: #f59e0b;"></iconify-icon>',
                'order' => 9,
                'status' => true,
            ],
            [
                'name' => 'Inventaris',
                'title' => 'Software Manajemen Inventaris & Stok',
                'overview' => 'Lacak pergerakan stok secara real-time dan akurat',
                'description' => 'Metode FIFO/LIFO/Average, notifikasi stok minimum, barcode tracking, dan stok opname tanpa menghentikan operasional.',
                'icon' => '<iconify-icon icon="solar:box-minimalistic-linear" style="color: #06b6d4;"></iconify-icon>',
                'order' => 10,
                'status' => true,
            ],
            [
                'name' => 'Procurement',
                'title' => 'Software Pengadaan & Procurement',
                'overview' => 'Kontrol budget & percepat approval PO pengadaan',
                'description' => 'Alur persetujuan bertingkat, perbandingan penawaran vendor, integrasi purchase order (PO), dan monitoring delivery.',
                'icon' => '<iconify-icon icon="solar:bag-check-linear" style="color: #a855f7;"></iconify-icon>',
                'order' => 11,
                'status' => true,
            ],
            [
                'name' => 'Warehouse',
                'title' => 'Software Warehouse Management System (WMS)',
                'overview' => 'Sinkronkan stok semua gudang secara real-time',
                'description' => 'Optimalisasi tata letak bin, batch picking, serial number tracking, dan integrasi pengiriman pihak ketiga.',
                'icon' => '<iconify-icon icon="solar:buildings-linear" style="color: #0284c7;"></iconify-icon>',
                'order' => 12,
                'status' => true,
            ],
            [
                'name' => 'Point of Sales',
                'title' => 'Software Kasir POS Cloud & Offline',
                'overview' => 'Kelola Stok, transaksi, dan pembukuan dalam satu sistem online',
                'description' => 'Transaksi kasir kilat, dukungan pembayaran QRIS/kartu, cetak struk bluetooth, dan laporan kasir otomatis.',
                'icon' => '<iconify-icon icon="solar:card-2-linear" style="color: #10b981;"></iconify-icon>',
                'order' => 13,
                'status' => true,
            ],
            [
                'name' => 'Sales',
                'title' => 'Software Manajemen Penjualan & Distribusi',
                'overview' => 'Maksimalkan potensi closing deals untuk tingkatkan revenue',
                'description' => 'Target penjualan sales force, rute kunjungan, quotation kilat, komisi penjualan, dan pemantauan performa tim.',
                'icon' => '<iconify-icon icon="solar:cart-check-linear" style="color: #10b981;"></iconify-icon>',
                'order' => 14,
                'status' => true,
            ],
            [
                'name' => 'CRM - Leads',
                'title' => 'Software CRM & Pengelolaan Prospek',
                'overview' => 'Lacak setiap leads & pantau pipeline penjualan real-time',
                'description' => 'Pencatatan interaksi prospek, otomatisasi follow-up whatsapp/email, lead scoring, dan analitik konversi penjualan.',
                'icon' => '<iconify-icon icon="solar:users-group-rounded-linear" style="color: #0284c7;"></iconify-icon>',
                'order' => 15,
                'status' => true,
            ],
            [
                'name' => 'Facilities',
                'title' => 'Software Manajemen Fasilitas & Pemeliharaan',
                'overview' => 'Maksimalkan kegunaan fasilitas untuk dorong produktivitas bisnis',
                'description' => 'Jadwal preventive maintenance, penugasan work order teknisi, inventaris suku cadang, dan pencatatan riwayat aset.',
                'icon' => '<iconify-icon icon="solar:city-linear" style="color: #0284c7;"></iconify-icon>',
                'order' => 16,
                'status' => true,
            ],
            [
                'name' => 'Agriculture',
                'title' => 'Software ERP Agribisnis & Perkebunan',
                'overview' => 'Digitalisasi manajemen agribisnis dengan fitur terlengkap',
                'description' => 'Perencanaan masa tanam, distribusi pupuk & bibit, kalkulasi upah harian pekerja kebun, dan hasil panen per blok.',
                'icon' => '<iconify-icon icon="solar:leaf-linear" style="color: #10b981;"></iconify-icon>',
                'order' => 17,
                'status' => true,
            ],
            [
                'name' => 'Trading',
                'title' => 'Software ERP Perdagangan Besar & Wholesale',
                'overview' => 'Sederhanakan proses bisnis wholesale dan distribution',
                'description' => 'Katalog harga khusus distributor, pengaturan limit kredit pelanggan, penagihan piutang, dan manajemen ekspedisi.',
                'icon' => '<iconify-icon icon="solar:dollar-minimalistic-linear" style="color: #a855f7;"></iconify-icon>',
                'order' => 18,
                'status' => true,
            ],
            [
                'name' => 'HRM',
                'title' => 'Software HR Management & Payroll Terpadu',
                'overview' => 'Otomatiskan tugas administrasi HRD dan tingkatkan kualitas SDM perusahaan',
                'description' => 'Perhitungan gaji PPh 21 & BPJS akurat, absensi GPS mobile, pengajuan cuti & klaim online, serta evaluasi KPI berkala.',
                'icon' => '<iconify-icon icon="solar:user-id-linear" style="color: #f43f5e;"></iconify-icon>',
                'order' => 19,
                'status' => true,
            ],
        ];

        foreach ($categories as $data) {
            $category = CategoryProduct::updateOrCreate(
                ['name' => $data['name']],
                [
                    'title' => $data['title'],
                    'slug' => Str::slug($data['name']),
                    'overview' => $data['overview'],
                    'description' => $data['description'],
                    'icon' => $data['icon'],
                    'order' => $data['order'],
                    'status' => $data['status'],
                ]
            );

            // Pastikan setiap kategori mempunyai 1 produk untuk halaman detail
            Product::firstOrCreate(
                ['id_category' => $category->id],
                [
                    'title' => $data['title'],
                    'slug' => Str::slug($data['name']),
                    'overview' => $data['overview'],
                    'description' => '<p>' . e($data['description']) . '</p>',
                    'icon' => $data['icon'],
                    'order' => $data['order'],
                    'is_featured' => true,
                    'status' => true,
                ]
            );
        }
    }
}
