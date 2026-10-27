<?php

namespace Database\Seeders;

use App\Models\Database\Configuration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Configuration::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Configuration::create([
            // Dashboard
            'name_dashboard' => 'WAN Teknologi',
            'title_dashboard' => 'Dashbord-Admin Wan Teknologi',
            'logo_dashboard' => 'seeders/logo.webp',
            'logo_dashboard_2' => 'seeders/logo-putih.webp',
            'favicon_dashboard' => 'seeders/fav.webp',
            'copyright' => '© 2026 WAN Teknologi. All Rights Reserved.',

            'name_page' => 'WAN Teknologi',
            'title_page' => 'WAN Teknologi | Solusi Aplikasi dan Sistem Digital untuk Bisnis & Instansi',
            'meta_title' => 'WAN Teknologi | Solusi Aplikasi dan Sistem Digital untuk Bisnis & Instansi',
            'meta_descriptions' => 'PT WAN Teknologi Internasional membangun solusi digital custom: aplikasi, sistem monitoring, dan platform untuk bisnis, instansi, pendidikan, dan industri.',
            'robots' => 'index, follow, max-image-preview:large',
            'base_url' => 'https://wanteknologi.com/',
            'site_url' => 'https://wanteknologi.com/',
            'canonical_url' => 'https://wanteknologi.com/',

            // Open Graph
            'og_type' => 'website',
            'og_site_name' => 'WAN Teknologi',
            'og_title' => 'WAN Teknologi | Solusi Aplikasi dan Sistem Digital',
            'og_description' => 'Aplikasi, sistem monitoring, dan platform digital custom untuk bisnis, instansi, pendidikan, dan industri.',
            'og_url' => 'https://wanteknologi.com/',
            'og_image' => 'seeders/logo.webp',
            'language' => 'id_ID',

            // Twitter
            'twitter_card' => 'summary_large_image',
            'twitter_title' => 'WAN Teknologi | Solusi Aplikasi dan Sistem Digital',
            'twitter_description' => 'Solusi digital custom untuk bisnis, instansi, pendidikan, dan industri.',
            'twitter_image' => 'seeders/logo.webp',

            'logo_hitam_page' => 'seeders/logo-fe.webp',
        'favicon_page' => 'seeders/fav-fe.webp',
            'footer_page' => '© WAN Teknologi. All Rights Reserved.',
            'text_footer_page' => 'PT WAN Teknologi Internasional membangun solusi digital custom: aplikasi, sistem monitoring, dan platform untuk bisnis, instansi, pendidikan, dan industri.',

            // Login
            'title_login' => 'WAN Teknologi Internasional',
            'name_login' => 'Selamat datang kembali, silakan masuk untuk mengakses dashboard Anda',
            'logo_login' => 'seeders/logo-2.webp',
            'favicon_login' => 'seeders/fav-2.webp',
            'image_login' => 'seeders/login-gambar.webp',
        ]);
    }
}
