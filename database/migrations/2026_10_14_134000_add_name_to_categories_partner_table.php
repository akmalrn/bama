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
        Schema::table('categories_partner', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        // Salin nilai title ke name untuk data lama
        DB::table('categories_partner')->whereNull('name')->orWhere('name', '')->update([
            'name' => DB::raw('title'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories_partner', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
