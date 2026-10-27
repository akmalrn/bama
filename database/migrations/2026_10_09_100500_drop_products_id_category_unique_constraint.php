<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_id_category_foreign');
            $table->dropUnique('products_id_category_unique');
            $table->foreign('id_category', 'products_id_category_foreign')
                ->references('id')
                ->on('categories_product')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unique('id_category', 'products_id_category_unique');
        });
    }
};
