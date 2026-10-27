<?php

namespace Database\Seeders;

use App\Models\Database\Blog;
use App\Models\Database\CategoryBlog;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'GodAdmin@example.com'],
            ['name' => 'God Admin', 'password' => bcrypt('123456')]
        );

        $blogs = [
            ['Tips Berkendara', 'Lorem Ipsum Dolor Sit Amet', 'blog-1.webp'],
            ['Wisata', 'Consectetur Adipiscing Elit', 'blog-2.webp'],
            ['Berita Otomotif', 'Sed Do Eiusmod Tempor Incididunt', 'blog-3.webp'],
            ['Acara & Promosi', 'Ut Labore Et Dolore Magna Aliqua', 'blog-4.webp'],
        ];

        foreach ($blogs as $index => [$categoryTitle, $title, $image]) {
            $category = CategoryBlog::firstOrCreate(['title' => $categoryTitle]);

            Blog::updateOrCreate(
                ['title' => $title],
                [
                    'id_category' => $category->id,
                    'description' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>',
                    'image' => 'seeders/'.$image,
                    'overview' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.',
                    'meta_keywords' => 'lorem ipsum, rental mobil, tips perjalanan',
                    'meta_descriptions' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'user_id' => $user->id,
                    'published_at' => now()->subDays($index),
                ]
            );
        }
    }
}
