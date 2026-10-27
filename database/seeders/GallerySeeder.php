<?php

namespace Database\Seeders;

use App\Models\Database\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 10) as $number) {
            Gallery::updateOrCreate(
                ['title' => 'Galeri Lorem Ipsum '.$number],
                ['image' => 'seeders/galeri-'.$number.'.webp']
            );
        }
    }
}
