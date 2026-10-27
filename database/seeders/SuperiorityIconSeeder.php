<?php

namespace Database\Seeders;

use App\Models\Database\LabelSuperiorityIcon;
use App\Models\Database\SuperiorityIcon;
use Illuminate\Database\Seeder;

class SuperiorityIconSeeder extends Seeder
{
    public function run(): void
    {
        LabelSuperiorityIcon::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Kenapa Memilih',
                'span' => 'Renne Rent Car?',
                'subTitle' => 'Keunggulan Kami',
                'description' => 'Kami memberikan layanan rental mobil yang aman, nyaman, dan profesional untuk mendukung setiap kebutuhan perjalanan Anda.',
            ]
        );

        $superiorities = [
            [
                'title' => 'Armada Lengkap & Terawat',
                'description' => 'Pilihan kendaraan yang beragam, bersih, dan diperiksa secara berkala untuk memastikan perjalanan tetap nyaman.',
                'icon' => '<iconify-icon icon="solar:wheel-bold-duotone"></iconify-icon>',
            ],
            [
                'title' => 'Aman & Terpercaya',
                'description' => 'Proses pemesanan transparan dengan pelayanan profesional untuk memberikan rasa aman kepada setiap pelanggan.',
                'icon' => '<iconify-icon icon="solar:shield-check-bold-duotone"></iconify-icon>',
            ],
            [
                'title' => 'Layanan 24/7',
                'description' => 'Tim kami siap membantu kebutuhan informasi dan perjalanan Anda kapan saja dengan respons yang cepat.',
                'icon' => '<iconify-icon icon="solar:headphones-round-bold-duotone"></iconify-icon>',
            ],
            [
                'title' => 'Pengemudi Profesional',
                'description' => 'Pengemudi berpengalaman, ramah, dan memahami rute untuk membuat perjalanan lebih aman dan efisien.',
                'icon' => '<iconify-icon icon="solar:user-id-bold-duotone"></iconify-icon>',
            ],
            [
                'title' => 'Harga Transparan',
                'description' => 'Informasi biaya disampaikan secara jelas sejak awal tanpa biaya tersembunyi yang membingungkan pelanggan.',
                'icon' => '<iconify-icon icon="solar:tag-price-bold-duotone"></iconify-icon>',
            ],
            [
                'title' => 'Jangkauan Luas',
                'description' => 'Melayani berbagai tujuan perjalanan dalam kota, luar kota, hingga kebutuhan perjalanan antardaerah.',
                'icon' => '<iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon>',
            ],
        ];

        foreach ($superiorities as $superiority) {
            SuperiorityIcon::updateOrCreate(
                ['title' => $superiority['title']],
                $superiority
            );
        }
    }
}
