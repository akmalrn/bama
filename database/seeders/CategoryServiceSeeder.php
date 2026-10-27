<?php

namespace Database\Seeders;

use App\Models\Database\CategoryService;
use Illuminate\Database\Seeder;

class CategoryServiceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Solusi Industri',
            'Solusi Bisnis & Finansial',
            'Operasional & Distribusi',
            'Layanan Publik & Sosial',
        ];

        foreach ($categories as $title) {
            CategoryService::updateOrCreate(['title' => $title]);
        }
    }
}
