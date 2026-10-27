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
        Schema::table('abouts', function (Blueprint $table) {
            $table->string('sejarah_title')->nullable()->after('so_description');
            $table->string('sejarah_image')->nullable()->after('sejarah_title');
            $table->text('sejarah_description')->nullable()->after('sejarah_image');

            $table->string('budaya_title')->nullable()->after('sejarah_description');
            $table->string('budaya_image')->nullable()->after('budaya_title');
            $table->text('budaya_description')->nullable()->after('budaya_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropColumn([
                'sejarah_title',
                'sejarah_image',
                'sejarah_description',
                'budaya_title',
                'budaya_image',
                'budaya_description',
            ]);
        });
    }
};
