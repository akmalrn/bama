<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('testimonials', 'title')) {
                $table->string('title')->nullable()->after('id');
            }
            if (!Schema::hasColumn('testimonials', 'image')) {
                $table->string('image')->nullable()->after('foto');
            }
            if (!Schema::hasColumn('testimonials', 'logo_image')) {
                $table->string('logo_image')->nullable()->after('image');
            }
        });

        // Pindahkan data dari superioritys ke testimonials jika ada
        if (Schema::hasTable('superioritys')) {
            $superiorities = DB::table('superioritys')->get();
            foreach ($superiorities as $item) {
                // Cek agar tidak duplikat
                $exists = DB::table('testimonials')
                    ->where('title', $item->title)
                    ->orWhere('name', $item->title)
                    ->exists();

                if (!$exists) {
                    DB::table('testimonials')->insert([
                        'title' => $item->title,
                        'name' => $item->title,
                        'description' => $item->description,
                        'image' => $item->image,
                        'foto' => $item->image,
                        'logo_image' => $item->logo_image,
                        'star' => 5,
                        'created_at' => $item->created_at ?? now(),
                        'updated_at' => $item->updated_at ?? now(),
                    ]);
                }
            }
        }

        // Pindahkan data label_superiority ke section_testimonial
        if (Schema::hasTable('label_superiority') && Schema::hasTable('section_testimonial')) {
            $label = DB::table('label_superiority')->first();
            if ($label) {
                DB::table('section_testimonial')->updateOrInsert(
                    ['id' => 1],
                    [
                        'title' => $label->title,
                        'subTitle' => $label->subTitle,
                        'description' => $label->description,
                        'span' => $label->span,
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('testimonials', 'title')) {
                $columnsToDrop[] = 'title';
            }
            if (Schema::hasColumn('testimonials', 'image')) {
                $columnsToDrop[] = 'image';
            }
            if (Schema::hasColumn('testimonials', 'logo_image')) {
                $columnsToDrop[] = 'logo_image';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
