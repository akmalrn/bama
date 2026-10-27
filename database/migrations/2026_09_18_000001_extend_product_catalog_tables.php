<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories_product', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('title');
            $table->string('highlight')->nullable()->after('subtitle');
            $table->string('banner_image')->nullable()->after('image');
            $table->string('thumbnail')->nullable()->after('banner_image');
            $table->string('meta_title')->nullable()->after('meta_keywords');
            $table->string('og_image')->nullable()->after('meta_descriptions');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'highlight', 'banner_image', 'thumbnail', 'meta_title', 'og_image']);
        });
        Schema::table('categories_product', function (Blueprint $table) {
            $table->dropColumn(['description', 'icon', 'image', 'order', 'status']);
        });
    }
};
