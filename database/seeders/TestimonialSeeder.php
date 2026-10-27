<?php

namespace Database\Seeders;

use App\Models\Database\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['Andi Pratama', 'Pengusaha', 'Jakarta', 5],
            ['Siti Rahma', 'Karyawan Swasta', 'Bandung', 5],
            ['Budi Santoso', 'Wiraswasta', 'Surabaya', 4],
            ['Dewi Lestari', 'Content Creator', 'Yogyakarta', 5],
        ];

        foreach ($testimonials as $index => [$name, $position, $address, $star]) {
            Testimonial::updateOrCreate(
                ['name' => $name],
                [
                    'position' => $position,
                    'address' => $address,
                    'foto' => 'seeders/testi-'.($index + 1).'.webp',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    'star' => $star,
                ]
            );
        }
    }
}
