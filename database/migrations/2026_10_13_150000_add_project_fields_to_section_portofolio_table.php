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
        if (Schema::hasTable('section_portofolio')) {
            Schema::table('section_portofolio', function (Blueprint $table) {
                if (! Schema::hasColumn('section_portofolio', 'project_tag')) {
                    $table->string('project_tag')->nullable()->after('description');
                }
                if (! Schema::hasColumn('section_portofolio', 'project_title')) {
                    $table->string('project_title')->nullable()->after('project_tag');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('section_portofolio')) {
            Schema::table('section_portofolio', function (Blueprint $table) {
                if (Schema::hasColumn('section_portofolio', 'project_title')) {
                    $table->dropColumn('project_title');
                }
                if (Schema::hasColumn('section_portofolio', 'project_tag')) {
                    $table->dropColumn('project_tag');
                }
            });
        }
    }
};
