<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Permission::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Yang Di komentari itu tidak di gunakan di settingan web

        $permissions = [

            // visitor archive and cleanup
            // 'view visitor statistics',
            // 'manage visitor settings',
            // 'run visitor archive',
            // 'delete visitor data',

            // user
            'lihat user',
            'buat user',
            'edit user',
            'hapus user',
            'impersonate',

            // role
            'lihat role',
            'buat role',
            'edit role',
            'hapus role',

            // kategori blog
            'lihat kategori-blog',
            'buat kategori-blog',
            'edit kategori-blog',
            'hapus kategori-blog',

            // blog
            'lihat blog',
            'buat blog',
            'edit blog',
            'hapus blog',
            'edit label blog',

            // configuration website
            'lihat pengaturan-website',
            'edit pengaturan-website',

            // configuration kontak
            'lihat pengaturan-kontak',
            'edit pengaturan-kontak',

            // slider
            'lihat slider',
            'buat slider',
            'edit slider',
            'hapus slider',

            // tentang kami
            'lihat tentang-kami',
            'edit tentang-kami',

            // karir
            'lihat karir',
            'edit karir',

            // kerja sama
            'lihat kerja-sama',
            'edit kerja-sama',

            // benefit tentang kami
            // 'lihat benefit-tentang-kami',
            // 'buat benefit-tentang-kami',
            // 'edit benefit-tentang-kami',
            // 'hapus benefit-tentang-kami',

            // ringkasan
            // 'lihat ringkasan',
            // 'edit ringkasan',

            // Benefit ringkasan
            // 'lihat benefit-ringkasan',
            // 'buat benefit-ringkasan',
            // 'edit benefit-ringkasan',
            // 'hapus benefit-ringkasan',

            // tim kami
            // 'lihat tim-kami',
            // 'buat tim-kami',
            // 'edit tim-kami',
            // 'hapus tim-kami',
            // 'edit label tim-kami',

            // stat perusahaan
            'lihat stat-perusahaan',
            'buat stat-perusahaan',
            'edit stat-perusahaan',
            'hapus stat-perusahaan',

            // keunggulan ikon
            'lihat keunggulan-(ikon)',
            'buat keunggulan-(ikon)',
            'edit keunggulan-(ikon)',
            'hapus keunggulan-(ikon)',
            'edit label keunggulan-(ikon)',

            // keunggulan gambar
            // 'lihat keunggulan-(gambar)',
            // 'buat keunggulan-(gambar)',
            // 'edit keunggulan-(gambar)',
            // 'hapus keunggulan-(gambar)',
            // 'edit label keunggulan-(gambar)',

            // kategori layanan
            'lihat kategori-layanan',
            'buat kategori-layanan',
            'edit kategori-layanan',
            'hapus kategori-layanan',

            // layanan
            'lihat layanan',
            'buat layanan',
            'edit layanan',
            'hapus layanan',
            'edit label layanan',

            // bidang usaha
            'lihat bidang-usaha',
            'buat bidang-usaha',
            'edit bidang-usaha',
            'hapus bidang-usaha',
            'edit label bidang-usaha',

            // kategori produk
            'lihat kategori-produk',
            'buat kategori-produk',
            'edit kategori-produk',
            'hapus kategori-produk',

            // produk
            'lihat produk',
            'buat produk',
            'edit produk',
            'hapus produk',
            'edit label produk',

            // booking
            // 'lihat booking',
            // 'buat booking',
            // 'edit booking',
            // 'hapus booking',

            // kategori projek
            // 'lihat kategori-projek',
            // 'buat kategori-projek',
            // 'edit kategori-projek',
            // 'hapus kategori-projek',

            // projek
            // 'lihat projek',
            // 'buat projek',
            // 'edit projek',
            // 'hapus projek',
            // 'edit label projek',

            // portofolio
            // 'lihat portofolio',
            // 'buat portofolio',
            // 'edit portofolio',
            // 'hapus portofolio',
            // 'edit label portofolio',

            // langkah pemesanan
            // 'lihat langkah-pemesanan',
            // 'buat langkah-pemesanan',
            // 'edit langkah-pemesanan',
            // 'hapus langkah-pemesanan',
            // 'edit label langkah-pemesanan',

            // galeri
            'lihat galeri',
            'buat galeri',
            'edit galeri',
            'hapus galeri',
            'edit label galeri',

            // galeri kategori
            // 'lihat galeri-(kategori)',
            // 'buat galeri-(kategori)',
            // 'edit galeri-(kategori)',
            // 'hapus galeri-(kategori)',
            // 'edit label galeri-(kategori)',

            // category galeri
            // 'lihat kategori-galeri',
            // 'buat kategori-galeri',
            // 'edit kategori-galeri',
            // 'hapus kategori-galeri',

            // testimonial
            'lihat testimoni',
            'buat testimoni',
            'edit testimoni',
            'hapus testimoni',
            'edit label testimoni',

            // kategori partner
            'lihat kategori-partner',
            'buat kategori-partner',
            'edit kategori-partner',
            'hapus kategori-partner',

            // partner
            'lihat partner',
            'buat partner',
            'edit partner',
            'hapus partner',
            'edit label partner',

            // faq
            'lihat faq',
            'buat faq',
            'edit faq',
            'hapus faq',
            'edit label faq',

            // Workshop
            // 'lihat workshop',
            // 'buat workshop',
            // 'edit workshop',
            // 'hapus wworkshop',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
