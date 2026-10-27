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
            $table->string('cta_title')->nullable()->after('description');
            $table->text('cta_description')->nullable()->after('cta_title');
            $table->string('cta_button_text')->nullable()->after('cta_description');
            $table->string('cta_button_link')->nullable()->after('cta_button_text');
            $table->string('cta_button_icon')->nullable()->after('cta_button_link');
        });

        Schema::table('collaborations', function (Blueprint $table) {
            $table->string('cta_title')->nullable()->after('description');
            $table->text('cta_description')->nullable()->after('cta_title');
            $table->string('cta_button_text')->nullable()->after('cta_description');
            $table->string('cta_button_link')->nullable()->after('cta_button_text');
            $table->string('cta_button_icon')->nullable()->after('cta_button_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn([
                'cta_title',
                'cta_description',
                'cta_button_text',
                'cta_button_link',
                'cta_button_icon',
            ]);
        });

        Schema::table('collaborations', function (Blueprint $table) {
            $table->dropColumn([
                'cta_title',
                'cta_description',
                'cta_button_text',
                'cta_button_link',
                'cta_button_icon',
            ]);
        });
    }
};
