<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('products')->select('id_category')->whereNotNull('id_category')->groupBy('id_category')->havingRaw('COUNT(*) > 1')->exists()) {
            throw new \RuntimeException('Ada kategori yang digunakan lebih dari satu produk. Rapikan duplikasi sebelum migration dilanjutkan.');
        }
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('id_category')->nullable()->change();
            $table->unique('id_category', 'products_id_category_unique');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_id_category_unique');
            $table->foreignId('id_category')->nullable(false)->change();
        });
    }
};
