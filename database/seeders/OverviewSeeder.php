<?php

namespace Database\Seeders;

use App\Models\Database\Overview;
use Illuminate\Database\Seeder;

class OverviewSeeder extends Seeder
{
    public function run(): void
    {
        Overview::updateOrCreate(
            ['title' => 'Solusi Perjalanan Nyaman'],
            [
                'subTitle' => 'Sekilas Renne Rent Car',
                'description' => 'Renne Rent Car menyediakan layanan rental mobil untuk kebutuhan pribadi, keluarga, bisnis, hingga perjalanan rombongan.',
                'image' => 'seeders/ringkasan.webp',
                'overview' => 'Didukung pilihan armada yang lengkap dan terawat, kami siap menemani perjalanan dalam kota maupun luar kota dengan pelayanan yang aman, praktis, dan profesional.',
                'note' => 'Armada terawat, pengemudi profesional, dan layanan pelanggan yang siap membantu Anda.',
                'name_link' => 'Lihat Armada',
                'link' => '#armada',
            ]
        );
    }
}
