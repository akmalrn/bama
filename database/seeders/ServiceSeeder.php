<?php

namespace Database\Seeders;

use App\Models\Database\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Jasa Pembuatan Website Profesional',
                'slug' => 'jasa-pembuatan-website-profesional',
                'overview' => 'Website profesional yang mengoptimalkan pertumbuhan bisnis.',
                'description' => '<p>Di era digital saat ini, website profesional menjadi kebutuhan utama bagi setiap bisnis yang ingin berkembang dan bersaing secara online. Calon pelanggan mencari produk dan layanan melalui Google, sehingga website Anda menjadi kesan pertama yang menentukan kepercayaan bisnis.</p>' .
                    '<p>Kami menyediakan jasa pembuatan website profesional yang melayani klien dari seluruh kota di Indonesia. Setiap website dirancang dengan desain modern, kecepatan optimal, dan struktur SEO-friendly agar mudah ditemukan di mesin pencari serta mampu menarik pelanggan lokal maupun nasional.</p>' .
                    '<p><strong>Dengan menggunakan jasa pembuatan website profesional kami, bisnis Anda akan mendapatkan:</strong></p>' .
                    '<ul>' .
                    '<li>Website responsif dan profesional di semua perangkat (Desktop, Tablet, & Mobile)</li>' .
                    '<li>Peningkatan kepercayaan pelanggan lokal dan nasional</li>' .
                    '<li>Konversi dan penjualan yang lebih optimal dengan integrasi formulir dan WhatsApp CTA</li>' .
                    '</ul>' .
                    '<p>Percayakan jasa pembuatan website profesional kepada tim kami dan miliki website yang bekerja 24 jam untuk membangun kredibilitas, menjangkau lebih banyak pelanggan, dan mendorong pertumbuhan bisnis secara berkelanjutan.</p>',
                'icon' => '<iconify-icon icon="solar:global-bold-duotone"></iconify-icon>',
                'image' => 'seeders/layanan-1.webp',
                'button_name' => 'Konsultasi Sekarang',
                'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+konsultasi+Jasa+Pembuatan+Website+Profesional',
                'meta_keywords' => 'jasa pembuatan website, website profesional, company profile, web developer indonesia',
                'meta_descriptions' => 'Jasa pembuatan website profesional modern, responsif, dan SEO-friendly untuk meningkatkan kredibilitas bisnis Anda.',
                'is_featured' => true,
                'status' => true,
                'order' => 1,
                'projects_data' => [
                    [
                        'title' => 'Website Company Profile',
                        'image' => 'seeders/layanan-1.webp',
                        'description' => 'Tampilkan citra profesional bisnis Anda dengan website company profile yang modern, informatif, dan kredibel. Website professional menjadi wajah digital perusahaan yang memberikan kesan pertama terbaik bagi calon pelanggan dan mitra bisnis. Dengan desain yang tepat dan struktur yang kuat, company profile Anda akan menunjukkan nilai, kualitas, dan keunggulan perusahaan secara jelas dan meyakinkan.',
                        'point_1_title' => 'Always Online',
                        'point_1_desc' => 'Website Anda hadir 24/7 tanpa batas waktu siap diakses kapan saja oleh pelanggan dari seluruh dunia.',
                        'point_2_title' => 'Grow Up Your Business',
                        'point_2_desc' => 'Website profesional membantu meningkatkan kepercayaan, membangun kredibilitas, dan mendorong lebih banyak peluang bisnis.',
                        'button_text' => 'Buat Website Anda Sekarang',
                        'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+ingin+buat+Website+Company+Profile',
                    ],
                    [
                        'title' => 'Website Toko Online (E-Commerce)',
                        'image' => 'seeders/layanan-2.webp',
                        'description' => 'Kelola transaksi penjualan, katalog produk, kalkulasi ongkos kirim otomatis, dan pembayaran digital multi-channel dalam satu sistem toko online yang terintegrasi dan aman.',
                        'point_1_title' => 'Sistem Pembayaran Otomatis',
                        'point_1_desc' => 'Mendukung Payment Gateway (QRIS, Transfer Bank, E-Wallet) dengan verifikasi pesanan instan tanpa perlu cek mutasi manual.',
                        'point_2_title' => 'Manajemen Stok & Pesanan',
                        'point_2_desc' => 'Pantau stok produk secara real-time dan kelola resi pengiriman dengan mudah dalam dashboard admin yang ramah pengguna.',
                        'button_text' => 'Konsultasikan Toko Online',
                        'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+ingin+konsultasi+Website+Toko+Online',
                    ],
                    [
                        'title' => 'Website Custom & Web App',
                        'image' => 'seeders/layanan-3.webp',
                        'description' => 'Solusi aplikasi web dengan logika bisnis khusus, integrasi API sistem pihak ketiga, portal internal perusahaan, hingga booking engine interaktif sesuai alur kerja bisnis Anda.',
                        'point_1_title' => 'Arsitektur Skalabel',
                        'point_1_desc' => 'Dibangun dengan teknologi modern yang siap menangani lonjakan data dan pengguna secara handal dan aman.',
                        'point_2_title' => 'Keamanan Data Berlapis',
                        'point_2_desc' => 'Menerapkan enkripsi data, proteksi otentikasi role-based, dan backup berkala untuk melindungi aset digital perusahaan.',
                        'button_text' => 'Mulai Projek Custom',
                        'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+ingin+konsultasi+Website+Custom',
                    ],
                    [
                        'title' => 'Jasa Maintenance Website',
                        'image' => 'seeders/layanan-4.webp',
                        'description' => 'Layanan pemeliharaan berkala, backup rutin, pembaruan keamanan, perbaikan bug, dan optimasi kecepatan server agar website selalu prima dan bebas gangguan.',
                        'point_1_title' => 'Monitoring & Backup Rutin',
                        'point_1_desc' => 'Pemantauan uptime dan performa untuk mendeteksi serta mengatasi kendala teknis sebelum mengganggu pelanggan Anda.',
                        'point_2_title' => 'Update Konten Berkala',
                        'point_2_desc' => 'Bantuan update informasi, banner promosi, dan fitur baru tanpa Anda perlu pusing urusan teknis pemrograman.',
                        'button_text' => 'Pilih Paket Maintenance',
                        'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+butuh+Jasa+Maintenance+Website',
                    ],
                    [
                        'title' => 'Jasa SEO Website',
                        'image' => 'seeders/layanan-1.webp',
                        'description' => 'Optimasi mesin pencari komprehensif mulai dari SEO On-Page, struktur schema, optimasi kecepatan, hingga strategi kata kunci agar website menduduki peringkat teratas Google.',
                        'point_1_title' => 'Trafik Organik Berkualitas',
                        'point_1_desc' => 'Mendatangkan calon pelanggan yang secara aktif mencari produk atau jasa Anda di Google tanpa biaya iklan berulang.',
                        'point_2_title' => 'Laporan Analisis Rutin',
                        'point_2_desc' => 'Dapatkan laporan perkembangan peringkat kata kunci dan pertumbuhan pengunjung secara transparan setiap bulan.',
                        'button_text' => 'Tingkatkan Peringkat SEO',
                        'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+ingin+konsultasi+Jasa+SEO',
                    ],
                ],
                'workflow_data' => [
                    [
                        'step' => '01',
                        'title' => 'Konsultasi & Identifikasi Kebutuhan',
                        'icon' => '<iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>',
                        'description' => 'Kami mulai memahami tujuan bisnis, tantangan, serta kebutuhan spesifik Anda untuk menentukan solusi terbaik.',
                    ],
                    [
                        'step' => '02',
                        'title' => 'Perencanaan & Penawaran Solusi',
                        'icon' => '<iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>',
                        'description' => 'Tim kami menyusun konsep, fitur, timeline, dan estimasi biaya secara transparan sesuai kebutuhan proyek Anda.',
                    ],
                    [
                        'step' => '03',
                        'title' => 'Pengembangan & Implementasi',
                        'icon' => '<iconify-icon icon="solar:laptop-minimalistic-bold-duotone"></iconify-icon>',
                        'description' => 'Proses development dilakukan oleh tim in-house kami dengan standar profesional, disertai update progres secara berkala.',
                    ],
                    [
                        'step' => '04',
                        'title' => 'Testing, Serah Terima & Support',
                        'icon' => '<iconify-icon icon="solar:verified-check-bold-duotone"></iconify-icon>',
                        'description' => 'Setelah siap, kami lakukan uji menyeluruh, serah terima, dan menyediakan support serta maintenance berkala.',
                    ],
                ],
                'portfolios_data' => [
                    [
                        'title' => 'Nugraha & Partners Law Firm',
                        'subtitle' => 'WEBSITE COMPANY PROFILE',
                        'image' => 'seeders/galeri-1.webp',
                        'url' => 'https://example.com/portofolio-1',
                    ],
                    [
                        'title' => 'Bluefire Homestay & Resort',
                        'subtitle' => 'BOOKING ENGINE & WEB PROFILE',
                        'image' => 'seeders/galeri-2.webp',
                        'url' => 'https://example.com/portofolio-2',
                    ],
                    [
                        'title' => 'Kulamani E-Commerce Store',
                        'subtitle' => 'TOKO ONLINE & PAYMENT GATEWAY',
                        'image' => 'seeders/galeri-3.webp',
                        'url' => 'https://example.com/portofolio-3',
                    ],
                    [
                        'title' => 'Sistem ERP Distribusi Niaga',
                        'subtitle' => 'WEB APPLICATION & ERP SYSTEM',
                        'image' => 'seeders/galeri-4.webp',
                        'url' => 'https://example.com/portofolio-4',
                    ],
                    [
                        'title' => 'Portal Layanan Publik Daerah',
                        'subtitle' => 'GOVERNMENT & PUBLIC PORTAL',
                        'image' => 'seeders/galeri-5.webp',
                        'url' => 'https://example.com/portofolio-5',
                    ],
                ],
            ],
            [
                'title' => 'Pengembangan Sistem ERP & Software Custom',
                'slug' => 'pengembangan-sistem-erp-software-custom',
                'overview' => 'Integrasikan seluruh proses bisnis mulai dari keuangan, inventaris, hingga operasional dalam satu platform terpusat.',
                'description' => '<p>Pengembangan software ERP custom dirancang khusus untuk memecahkan kerumitan operasional perusahaan Anda. Kami memadukan modul keuangan, akuntansi, supply chain, manajemen gudang, hingga HR ke dalam sistem komprehensif yang efisien dan otomatis.</p>' .
                    '<p>Dengan sistem ERP yang tepat, pimpinan perusahaan dapat melihat laporan kinerja bisnis secara real-time kapan saja dan di mana saja, serta meminimalkan human error dalam pencatatan data.</p>' .
                    '<p><strong>Keunggulan Pengembangan Software ERP Bersama Kami:</strong></p>' .
                    '<ul>' .
                    '<li>Kustomisasi penuh sesuai SOP dan alur birokrasi internal perusahaan</li>' .
                    '<li>Dashboard analitik eksekutif dengan grafik interaktif</li>' .
                    '<li>Hak akses bertingkat dengan audit log aktivitas pengguna</li>' .
                    '</ul>',
                'icon' => '<iconify-icon icon="solar:widget-bold-duotone"></iconify-icon>',
                'image' => 'seeders/layanan-2.webp',
                'button_name' => 'Konsultasikan Kebutuhan ERP',
                'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+konsultasi+Pengembangan+Sistem+ERP',
                'meta_keywords' => 'software erp custom, sistem manajemen bisnis, aplikasi erp indonesia',
                'meta_descriptions' => 'Pengembangan sistem ERP dan software custom terintegrasi untuk otomatisasi bisnis modern.',
                'is_featured' => true,
                'status' => true,
                'order' => 2,
                'projects_data' => [
                    [
                        'title' => 'ERP Modul Keuangan & Akuntansi',
                        'image' => 'seeders/solution-finance.webp',
                        'description' => 'Otomatisasi pencatatan buku besar, jurnal umum, rekonsiliasi bank, dan laporan keuangan komprehensif (Laba Rugi, Neraca, Arus Kas).',
                        'point_1_title' => 'Multi-Cabang & Multi-Mata Uang',
                        'point_1_desc' => 'Dukungan konsolidasi laporan keuangan lintas entitas anak perusahaan secara instan.',
                        'point_2_title' => 'Kepatuhan Pajak & Audit',
                        'point_2_desc' => 'Format pelaporan standar yang memudahkan proses audit keuangan dan rekonsiliasi pajak.',
                        'button_text' => 'Pelajari Modul Keuangan',
                        'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+modul+ERP+Keuangan',
                    ],
                    [
                        'title' => 'ERP Supply Chain & Warehouse',
                        'image' => 'seeders/solution-inventory.webp',
                        'description' => 'Pantau pergerakan stok antar gudang, purchase order, surat jalan, dan barcode scanning untuk efisiensi inventaris barang.',
                        'point_1_title' => 'Stok Real-Time Akurat',
                        'point_1_desc' => 'Peringatan stok menipis dan rekomendasi pemesanan ulang otomatis berdasarkan histori penjualan.',
                        'point_2_title' => 'Barcode & QR Tracking',
                        'point_2_desc' => 'Mempercepat proses stock opname dan penerimaan barang masuk serta keluar gudang.',
                        'button_text' => 'Pelajari Modul Warehouse',
                        'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+modul+ERP+Warehouse',
                    ],
                    [
                        'title' => 'HRM & Payroll System',
                        'image' => 'seeders/solution-hrm.webp',
                        'description' => 'Sistem kepegawaian terpadu meliputi absensi online berbasis GPS, pengajuan cuti, perhitungan gaji & BPJS otomatis, serta PPh 21.',
                        'point_1_title' => 'Slip Gaji Digital',
                        'point_1_desc' => 'Kirim slip gaji terenkripsi ke email atau aplikasi karyawan dalam satu kali klik.',
                        'point_2_title' => 'Manajemen KPI Karyawan',
                        'point_2_desc' => 'Evaluasi kinerja berkala karyawan secara objektif berdasarkan target pencapaian.',
                        'button_text' => 'Pelajari Modul HRM',
                        'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+modul+HRM',
                    ],
                ],
                'workflow_data' => [
                    [
                        'step' => '01',
                        'title' => 'Business Process Analysis',
                        'icon' => '<iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>',
                        'description' => 'Menganalisis alur kerja, hierarki persetujuan, dan kebutuhan data spesifik tiap divisi perusahaan.',
                    ],
                    [
                        'step' => '02',
                        'title' => 'System Architecture & Prototyping',
                        'icon' => '<iconify-icon icon="solar:layers-minimalistic-bold-duotone"></iconify-icon>',
                        'description' => 'Merancang database schema, arsitektur modul, dan wireframe antarmuka pengguna.',
                    ],
                    [
                        'step' => '03',
                        'title' => 'Agile Development & Migration',
                        'icon' => '<iconify-icon icon="solar:code-bold-duotone"></iconify-icon>',
                        'description' => 'Pengembangan sprint bertahap disertai migrasi data aman dari sistem lama ke sistem baru.',
                    ],
                    [
                        'step' => '04',
                        'title' => 'UAT & Training Karyawan',
                        'icon' => '<iconify-icon icon="solar:diploma-verified-bold-duotone"></iconify-icon>',
                        'description' => 'Pelatihan menyeluruh kepada user dan manajemen serta pendampingan masa go-live.',
                    ],
                ],
                'portfolios_data' => [
                    [
                        'title' => 'ERP Manufaktur Tekstil Cipta Indah',
                        'subtitle' => 'SISTEM ERP MANUFAKTUR & GUDANG',
                        'image' => 'seeders/galeri-6.webp',
                        'url' => 'https://example.com/portofolio-erp-1',
                    ],
                    [
                        'title' => 'Supply Chain ERP Multi-Cabang',
                        'subtitle' => 'DISTRIBUSI LOGISTIK & INVENTARIS',
                        'image' => 'seeders/galeri-7.webp',
                        'url' => 'https://example.com/portofolio-erp-2',
                    ],
                    [
                        'title' => 'Finance & Accounting System BPR',
                        'subtitle' => 'SISTEM KEUANGAN & AUDIT PERBANKAN',
                        'image' => 'seeders/galeri-8.webp',
                        'url' => 'https://example.com/portofolio-erp-3',
                    ],
                ],
            ],
            [
                'title' => 'Jasa Pembuatan Aplikasi Mobile dan Web',
                'slug' => 'jasa-pembuatan-aplikasi-mobile-android-dan-ios',
                'overview' => 'Aplikasi custom yang membantu bisnis Anda berkembang.',
                'description' => '<p>Miliki aplikasi mobile native maupun cross-platform (Flutter & React Native) dengan desain UI/UX interaktif yang memikat pengguna. Kami mengembangkan aplikasi mobile siap rilis di Google Play Store dan Apple App Store dengan standar keamanan tinggi.</p>' .
                    '<p>Mulai dari aplikasi e-commerce, kurir logistik, booking layanan, hingga sistem operasional internal lapangan, tim developer kami siap merealisasikannya secara profesional.</p>',
                'icon' => '<iconify-icon icon="solar:smartphone-bold-duotone"></iconify-icon>',
                'image' => 'seeders/layanan-3.webp',
                'button_name' => 'Konsultasikan Aplikasi Mobile',
                'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+konsultasi+Aplikasi+Mobile',
                'meta_keywords' => 'jasa pembuatan aplikasi mobile, android developer, ios app developer, flutter indonesia',
                'meta_descriptions' => 'Jasa pembuatan aplikasi mobile Android dan iOS berkualitas tinggi dengan desain modern dan performa cepat.',
                'is_featured' => true,
                'status' => true,
                'order' => 3,
                'projects_data' => [
                    [
                        'title' => 'Aplikasi Customer & Loyalty',
                        'image' => 'seeders/layanan-3.webp',
                        'description' => 'Aplikasi keanggotaan pelanggan dengan poin reward, katalog penukaran voucher, dan notifikasi promosi langsung ke layar smartphone.',
                        'point_1_title' => 'Push Notification Instan',
                        'point_1_desc' => 'Kirim broadcast pengumuman diskon dan info menarik langsung ke saku pelanggan.',
                        'point_2_title' => 'QR Member Scanner',
                        'point_2_desc' => 'Identifikasi anggota dengan scan barcode/QR instan di kasir toko fisik.',
                        'button_text' => 'Konsultasikan App Loyalty',
                        'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+App+Loyalty',
                    ],
                    [
                        'title' => 'Aplikasi Kurir & Operasional Lapangan',
                        'image' => 'seeders/layanan-4.webp',
                        'description' => 'Pantau pengantaran paket secara live GPS, tanda tangan digital penerima (Proof of Delivery), dan laporan otomatis.',
                        'point_1_title' => 'GPS Live Tracking',
                        'point_1_desc' => 'Pelacakan armada kurir secara akurat dengan rute terpendek yang hemat waktu.',
                        'point_2_title' => 'Bukti Pengiriman Foto & Tanda Tangan',
                        'point_2_desc' => 'Upload bukti pengiriman digital yang langsung tersimpan di cloud server.',
                        'button_text' => 'Konsultasikan App Kurir',
                        'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+App+Kurir',
                    ],
                ],
                'workflow_data' => [
                    [
                        'step' => '01',
                        'title' => 'User Journey & Wireframing',
                        'icon' => '<iconify-icon icon="solar:smart-home-angle-bold-duotone"></iconify-icon>',
                        'description' => 'Menyusun alur interaksi layar aplikasi agar intuitif dan mudah dipahami oleh pengguna.',
                    ],
                    [
                        'step' => '02',
                        'title' => 'UI/UX Visual Design',
                        'icon' => '<iconify-icon icon="solar:pallete-2-bold-duotone"></iconify-icon>',
                        'description' => 'Membuat desain visual antarmuka modern yang ramah mata dan sesuai identitas brand Anda.',
                    ],
                    [
                        'step' => '03',
                        'title' => 'Mobile Coding & API Integration',
                        'icon' => '<iconify-icon icon="solar:devices-bold-duotone"></iconify-icon>',
                        'description' => 'Pengkodean aplikasi dengan framework modern dan integrasi backend API yang stabil.',
                    ],
                    [
                        'step' => '04',
                        'title' => 'Publish Play Store & App Store',
                        'icon' => '<iconify-icon icon="solar:rocket-2-bold-duotone"></iconify-icon>',
                        'description' => 'Pendampingan proses review dan rilis aplikasi hingga tayang di Google Play Store & Apple App Store.',
                    ],
                ],
                'portfolios_data' => [
                    [
                        'title' => 'Express Courier Mobile Delivery',
                        'subtitle' => 'APLIKASI LOGISTIK & PELACAKAN',
                        'image' => 'seeders/galeri-9.webp',
                        'url' => 'https://example.com/portofolio-app-1',
                    ],
                    [
                        'title' => 'Boutique Coffee Member App',
                        'subtitle' => 'APLIKASI LOYALTY & ORDERING',
                        'image' => 'seeders/galeri-10.webp',
                        'url' => 'https://example.com/portofolio-app-2',
                    ],
                ],
            ],
            [
                'title' => 'Jasa Desain dan Branding Profesional',
                'slug' => 'jasa-desain-dan-branding-profesional',
                'overview' => 'Kami membantu bisnis membangun identitas visual melalui jasa desain grafis, brand identity, dan visual promosi.',
                'description' => '<p>Bangun identitas merek yang kuat dan tak terlupakan dengan layanan desain grafis dan branding profesional. Kami membantu bisnis Anda tampil percaya diri di hadapan calon klien dan mitra strategis.</p>',
                'icon' => '<iconify-icon icon="solar:pallete-2-bold-duotone"></iconify-icon>',
                'image' => 'seeders/layanan-1.webp',
                'button_name' => 'Konsultasikan Desain & Brand',
                'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+Jasa+Desain+dan+Branding+Profesional',
                'meta_keywords' => 'jasa desain grafis, branding perusahaan, logo design, identitas visual',
                'meta_descriptions' => 'Jasa desain dan branding profesional untuk meningkatkan citra visual dan nilai brand bisnis Anda.',
                'is_featured' => true,
                'status' => true,
                'order' => 3,
            ],
            [
                'title' => 'Jasa Digital Marketing dan Iklan Online',
                'slug' => 'jasa-digital-marketing-dan-iklan-online',
                'overview' => 'Perluas jangkauan bisnis Anda dengan jasa digital marketing, Google Ads, Meta Ads, dan kampanye terukur.',
                'description' => '<p>Tingkatkan prospek penjualan dan popularitas bisnis Anda di ranah online dengan manajemen kampanye digital marketing profesional yang berbasis data dan konversi tinggi.</p>',
                'icon' => '<iconify-icon icon="solar:volume-loud-bold-duotone"></iconify-icon>',
                'image' => 'seeders/layanan-2.webp',
                'button_name' => 'Konsultasikan Marketing & Ads',
                'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+Jasa+Digital+Marketing',
                'meta_keywords' => 'jasa digital marketing, google ads, meta ads, jasa iklan online, agensi marketing',
                'meta_descriptions' => 'Perluas jangkauan bisnis dengan jasa digital marketing terpercaya dan iklan online tertarget.',
                'is_featured' => true,
                'status' => true,
                'order' => 4,
            ],
            [
                'title' => 'Jasa Maintenance Website dan IT Support',
                'slug' => 'jasa-maintenance-website-dan-it-support',
                'overview' => 'Layanan maintenance website, server, cloud, infrastruktur IT, dan keamanan sistem secara berkala.',
                'description' => '<p>Fokus kembangkan bisnis Anda tanpa rasa khawatir. Tim IT Support kami siap menjaga keandalan infrastruktur server, pembaruan keamanan, dan backup rutin aset digital perusahaan Anda.</p>',
                'icon' => '<iconify-icon icon="solar:server-square-bold-duotone"></iconify-icon>',
                'image' => 'seeders/layanan-4.webp',
                'button_name' => 'Konsultasikan Maintenance & IT',
                'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+Jasa+Maintenance+Website',
                'meta_keywords' => 'jasa maintenance website, it support, kelola server cloud, perbaikan bug website',
                'meta_descriptions' => 'Layanan maintenance website dan IT support andal untuk menjaga uptime dan keamanan aset digital Anda.',
                'is_featured' => true,
                'status' => true,
                'order' => 5,
            ],
            [
                'title' => 'AI Automation & Integration',
                'slug' => 'ai-automation-and-integration',
                'overview' => 'WAN Teknologi menyediakan layanan AI Automation & Integration untuk membantu bisnis Anda semakin efisien.',
                'description' => '<p>Otomatisasi alur kerja berulang perusahaan Anda dengan integrasi kecerdasan buatan (AI) canggih. Kurangi biaya operasional dan tingkatkan efisiensi produktivitas secara signifikan.</p>',
                'icon' => '<iconify-icon icon="solar:cpu-bolt-bold-duotone"></iconify-icon>',
                'image' => 'seeders/layanan-3.webp',
                'button_name' => 'Konsultasikan Solusi AI',
                'button_url' => 'https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+Solusi+AI+Automation',
                'meta_keywords' => 'ai automation, integrasi ai bisnis, chatbot ai, otomatisasi alur kerja',
                'meta_descriptions' => 'Solusi AI Automation & Integration modern untuk meningkatkan efisiensi proses bisnis secara terpadu.',
                'is_featured' => true,
                'status' => true,
                'order' => 6,
            ],
        ];

        foreach ($services as $svc) {
            Service::updateOrCreate(
                ['slug' => $svc['slug']],
                $svc
            );
        }
    }
}
