<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Database\CategoryPortofolio;
use App\Models\Database\Portofolio;
use Illuminate\Support\Str;

class CategoryPortofolioSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Web Development',
            'Aplikasi Development',
            'Aplikasi Mobile Apps',
            'Digital Marketing',
            'Design Grafis',
            'Networking & Infrastruktur',
            'IT Consulting & Digital Transformation',
            'AI Automation & Integration',
            'System Integration',
            'IT Outsourcing',
            'Cloud Solutions',
        ];

        foreach ($categories as $catName) {
            CategoryPortofolio::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName]
            );
        }

        // Jika portofolio masih kosong, masukkan beberapa sample data portofolio dari template/screenshot
        if (Portofolio::count() === 0) {
            $catWeb = CategoryPortofolio::where('slug', 'web-development')->first();
            $catApp = CategoryPortofolio::where('slug', 'aplikasi-development')->first();
            $catMobile = CategoryPortofolio::where('slug', 'aplikasi-mobile-apps')->first();
            $catAi = CategoryPortofolio::where('slug', 'ai-automation-and-integration')->first() 
                ?? CategoryPortofolio::where('slug', 'ai-automation-integration')->first();

            $samples = [
                [
                    'name' => 'APPMBGI Kota Bogor',
                    'id_category' => $catWeb?->id,
                    'year' => 2024,
                    'images' => ['assets/frontend/images/portfolio-placeholder.webp'],
                ],
                [
                    'name' => 'Ujian Online',
                    'id_category' => $catApp?->id,
                    'year' => 2024,
                    'images' => ['assets/frontend/images/portfolio-placeholder.webp'],
                ],
                [
                    'name' => 'Yudhistira',
                    'id_category' => $catWeb?->id,
                    'year' => 2023,
                    'images' => ['assets/frontend/images/portfolio-placeholder.webp'],
                ],
                [
                    'name' => 'Yudhistira Teacher Hub',
                    'id_category' => $catApp?->id,
                    'year' => 2024,
                    'images' => ['assets/frontend/images/portfolio-placeholder.webp'],
                ],
                [
                    'name' => 'Sistem Monitoring Inventaris',
                    'id_category' => $catMobile?->id,
                    'year' => 2023,
                    'images' => ['assets/frontend/images/portfolio-placeholder.webp'],
                ],
                [
                    'name' => 'Smart AI Document Workflow',
                    'id_category' => $catAi?->id ?? $catApp?->id,
                    'year' => 2024,
                    'images' => ['assets/frontend/images/portfolio-placeholder.webp'],
                ],
            ];

            foreach ($samples as $sample) {
                Portofolio::create($sample);
            }
        }
    }
}
