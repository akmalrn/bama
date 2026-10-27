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
        if (Schema::hasTable('services_section')) {
            Schema::table('services_section', function (Blueprint $table) {
                if (! Schema::hasColumn('services_section', 'image')) {
                    $table->string('image')->nullable()->after('description');
                }
            });
        }

        if (Schema::hasTable('section_portofolio')) {
            Schema::table('section_portofolio', function (Blueprint $table) {
                if (! Schema::hasColumn('section_portofolio', 'span')) {
                    $table->string('span')->nullable()->after('title');
                }
                if (! Schema::hasColumn('section_portofolio', 'image')) {
                    $table->string('image')->nullable()->after('description');
                }
            });
        }

        if (Schema::hasTable('section_product')) {
            Schema::table('section_product', function (Blueprint $table) {
                if (! Schema::hasColumn('section_product', 'image')) {
                    $table->string('image')->nullable()->after('description');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('services_section')) {
            Schema::table('services_section', function (Blueprint $table) {
                if (Schema::hasColumn('services_section', 'image')) {
                    $table->dropColumn('image');
                }
            });
        }

        if (Schema::hasTable('section_portofolio')) {
            Schema::table('section_portofolio', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('section_portofolio', 'span')) {
                    $columns[] = 'span';
                }
                if (Schema::hasColumn('section_portofolio', 'image')) {
                    $columns[] = 'image';
                }
                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('section_product')) {
            Schema::table('section_product', function (Blueprint $table) {
                if (Schema::hasColumn('section_product', 'image')) {
                    $table->dropColumn('image');
                }
            });
        }
    }
};
