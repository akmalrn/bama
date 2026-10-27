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
            $table->string('projects_badge')->nullable()->after('projects_data');
            $table->string('projects_title')->nullable()->after('projects_badge');

            $table->string('workflow_title')->nullable()->after('workflow_data');
            $table->text('workflow_subtitle')->nullable()->after('workflow_title');

            $table->string('portfolios_badge')->nullable()->after('portfolios_data');
            $table->string('portfolios_title')->nullable()->after('portfolios_badge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'projects_badge',
                'projects_title',
                'workflow_title',
                'workflow_subtitle',
                'portfolios_badge',
                'portfolios_title',
            ]);
        });
    }
};
