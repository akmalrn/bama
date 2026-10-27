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
        Schema::create('business_fields', function (Blueprint $table) {
            $table->id();

            // Informasi utama
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('overview')->nullable();
            $table->longText('description')->nullable();

            // Media
            $table->text('icon')->nullable();
            $table->text('image')->nullable();

            // SEO
            $table->text('meta_keywords')->nullable();
            $table->text('meta_descriptions')->nullable();

            // Pengaturan tampilan
            $table->boolean('status')->default(true);
            $table->unsignedInteger('order')->default(0);

            $table->timestamps();
        });

        // Pindahkan data dari tabel services ke business_fields
        if (Schema::hasTable('services')) {
            $services = DB::table('services')->get();

            foreach ($services as $svc) {
                DB::table('business_fields')->insert([
                    'id' => $svc->id,
                    'slug' => $svc->slug,
                    'title' => $svc->title,
                    'overview' => $svc->overview,
                    'description' => $svc->description,
                    'icon' => $svc->icon,
                    'image' => $svc->image,
                    'meta_keywords' => $svc->meta_keywords,
                    'meta_descriptions' => $svc->meta_descriptions,
                    'status' => $svc->status ?? true,
                    'order' => $svc->order ?? 0,
                    'created_at' => $svc->created_at ?? now(),
                    'updated_at' => $svc->updated_at ?? now(),
                ]);
            }

            // Kosongkan tabel services sesuai instruksi
            DB::table('services')->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_fields');
    }
};
