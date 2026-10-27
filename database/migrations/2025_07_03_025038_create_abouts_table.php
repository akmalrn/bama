<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            // Tentang Kami
            $table->text('label_title')->nullable();
            $table->text('label_subTitle')->nullable();
            $table->text('label_description')->nullable();

            $table->text('title')->nullable();
            $table->text('span')->nullable();
            $table->text('subTitle')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();

            // Visi & Misi
            $table->text('title_visi_misi')->nullable();
            $table->text('subTitle_visi_misi')->nullable();
            $table->text('span_visi_misi')->nullable();
            $table->text('description_visi_misi')->nullable();

            $table->text('title_visi')->nullable();
            $table->text('title_misi')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();

            // Kepemilikan Saham
            $table->text('ks_image')->nullable();
            $table->text('ks_title')->nullable();
            $table->text('ks_subTitle')->nullable();
            $table->text('ks_span')->nullable();
            $table->text('ks_description')->nullable();

            // Struktur Organisasi
            $table->text('so_image')->nullable();
            $table->text('so_title')->nullable();
            $table->text('so_subTitle')->nullable();
            $table->text('so_span')->nullable();
            $table->text('so_description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
