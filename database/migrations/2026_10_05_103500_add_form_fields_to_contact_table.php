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
        Schema::table('contact', function (Blueprint $table) {
            $table->string('form_brand')->nullable()->after('image_general');
            $table->text('form_title')->nullable()->after('form_brand');
            $table->text('form_subtitle')->nullable()->after('form_title');
            $table->string('form_button_text')->nullable()->after('form_subtitle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact', function (Blueprint $table) {
            $table->dropColumn([
                'form_brand',
                'form_title',
                'form_subtitle',
                'form_button_text',
            ]);
        });
    }
};
