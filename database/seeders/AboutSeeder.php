<?php

namespace Database\Seeders;

use App\Models\Database\About;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        About::updateOrCreate(
            ['title' => 'Mengenal Lebih Dekat'],
            [
                'label_title' => 'Tentang Renne Rent Car',
                'label_subTitle' => 'Perjalanan Nyaman Dimulai Bersama Kami',
                'label_description' => 'Mengenal layanan, komitmen, serta nilai yang menjadi dasar Renne Rent Car dalam melayani setiap perjalanan pelanggan.',
                'span' => 'Renne Rent Car',
                'subTitle' => 'Tentang Kami',
                'description' => '<p>Renne Rent Car hadir sebagai solusi transportasi yang aman, nyaman, dan terpercaya untuk kebutuhan pribadi, keluarga, bisnis, maupun perjalanan rombongan.</p><p>Dengan pilihan armada yang terawat dan pengemudi profesional, kami berkomitmen memberikan pelayanan yang responsif serta pengalaman perjalanan terbaik bagi setiap pelanggan.</p>',
                'image' => 'seeders/tentang-kami.webp',

                'title_visi_misi' => 'Langkah Kami untuk',
                'subTitle_visi_misi' => 'Visi & Misi',
                'span_visi_misi' => 'Perjalanan yang Lebih Baik',
                'description_visi_misi' => 'Visi dan misi kami menjadi pedoman dalam memberikan layanan rental mobil yang profesional, aman, dan dapat diandalkan.',
                'title_visi' => 'Menjadi Mitra Transportasi Terpercaya',
                'title_misi' => 'Memberikan Pelayanan Terbaik',
                'visi' => 'Menjadi penyedia layanan rental mobil pilihan utama yang dikenal melalui keamanan, kenyamanan, kualitas armada, dan pelayanan yang terpercaya.',
                'misi' => "Menyediakan armada yang bersih, aman, dan terawat.\nMemberikan pelayanan yang ramah, cepat, dan profesional.\nMenghadirkan solusi perjalanan yang fleksibel sesuai kebutuhan pelanggan.\nMenjaga kepercayaan pelanggan melalui pelayanan yang konsisten.",
            ]
        );
    }
}
