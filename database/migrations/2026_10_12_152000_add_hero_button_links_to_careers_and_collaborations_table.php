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
        Schema::table('careers', function (Blueprint $table) {
            $table->string('hero_button_link')->nullable()->after('hero_button_text');
            $table->string('hero_button_2_text')->nullable()->after('hero_button_link');
            $table->string('hero_button_2_link')->nullable()->after('hero_button_2_text');
        });

        Schema::table('collaborations', function (Blueprint $table) {
            $table->string('hero_button_link')->nullable()->after('hero_button_text');
            $table->string('hero_button_2_text')->nullable()->after('hero_button_link');
            $table->string('hero_button_2_link')->nullable()->after('hero_button_2_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn([
                'hero_button_link',
                'hero_button_2_text',
                'hero_button_2_link',
            ]);
        });

        Schema::table('collaborations', function (Blueprint $table) {
            $table->dropColumn([
                'hero_button_link',
                'hero_button_2_text',
                'hero_button_2_link',
            ]);
        });
    }
};
