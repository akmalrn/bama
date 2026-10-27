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
        if (Schema::hasTable('services')) {
            Schema::table('services', function (Blueprint $table) {
                if (! Schema::hasColumn('services', 'show_projects')) {
                    $table->boolean('show_projects')->default(true)->after('projects_title');
                }
                if (! Schema::hasColumn('services', 'show_workflow')) {
                    $table->boolean('show_workflow')->default(true)->after('workflow_subtitle');
                }
                if (! Schema::hasColumn('services', 'show_portfolios')) {
                    $table->boolean('show_portfolios')->default(true)->after('portfolios_title');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('services')) {
            Schema::table('services', function (Blueprint $table) {
                $columnsToDrop = [];
                foreach (['show_projects', 'show_workflow', 'show_portfolios'] as $col) {
                    if (Schema::hasColumn('services', $col)) {
                        $columnsToDrop[] = $col;
                    }
                }
                if (! empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
