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
        Schema::table('services', function (Blueprint $table) {
            $table->json('projects_data')->nullable()->after('images');
            $table->json('workflow_data')->nullable()->after('projects_data');
            $table->json('portfolios_data')->nullable()->after('workflow_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['projects_data', 'workflow_data', 'portfolios_data']);
        });
    }
};
