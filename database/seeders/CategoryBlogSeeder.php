<?php

namespace Database\Seeders;

use App\Models\Database\CategoryBlog;
use Illuminate\Database\Seeder;

class CategoryBlogSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Tips Berkendara', 'Wisata', 'Berita Otomotif', 'Acara & Promosi'] as $title) {
            CategoryBlog::updateOrCreate(['title' => $title]);
        }
    }
}
