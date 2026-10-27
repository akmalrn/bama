<?php

namespace Database\Seeders;

use App\Models\Database\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            ['Lorem Ipsum Dolor Sit Amet', 'Perjalanan nyaman dimulai dari sini', 'slider-1.webp'],
            ['Consectetur Adipiscing Elit', 'Pilihan kendaraan untuk setiap kebutuhan', 'slider-2.webp'],
            ['Sed Do Eiusmod Tempor', 'Layanan mudah, aman, dan terpercaya', 'slider-3.webp'],
        ];

        foreach ($sliders as [$title, $subTitle, $image]) {
            Slider::updateOrCreate(
                ['title' => $title],
                [
                    'sub_title' => $subTitle,
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'image' => 'seeders/'.$image,
                    'name_link' => 'Lihat Layanan',
                    'link' => '/services',
                    'name_link_2' => 'Hubungi Kami',
                    'link_2' => '/contact',
                ]
            );
        }
    }
}
