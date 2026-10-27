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
                if (! Schema::hasColumn('services', 'hero_badge')) {
                    $table->string('hero_badge')->nullable()->after('icon');
                }
                if (! Schema::hasColumn('services', 'hero_span')) {
                    $table->string('hero_span')->nullable()->after('hero_badge');
                }
                if (! Schema::hasColumn('services', 'hero_title')) {
                    $table->string('hero_title')->nullable()->after('hero_span');
                }
                if (! Schema::hasColumn('services', 'hero_description')) {
                    $table->text('hero_description')->nullable()->after('hero_title');
                }
                if (! Schema::hasColumn('services', 'hero_image')) {
                    $table->string('hero_image')->nullable()->after('hero_description');
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
                $columns = [];
                foreach (['hero_badge', 'hero_span', 'hero_title', 'hero_description', 'hero_image'] as $col) {
                    if (Schema::hasColumn('services', $col)) {
                        $columns[] = $col;
                    }
                }
                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
