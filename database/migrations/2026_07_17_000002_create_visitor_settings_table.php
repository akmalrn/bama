<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('singleton_key')->default(1)->unique();
            $table->boolean('archive_enabled')->default(true);
            $table->string('archive_interval', 20)->default('weekly');
            $table->unsignedTinyInteger('archive_day')->default(1);
            $table->unsignedTinyInteger('archive_month_day')->default(1);
            $table->time('archive_time')->default('00:10:00');
            $table->unsignedInteger('retention_value')->default(30);
            $table->string('retention_unit', 20)->default('days');
            $table->boolean('auto_delete_enabled')->default(false);
            $table->boolean('delete_after_archive')->default(false);
            $table->string('timezone', 64)->default(config('app.timezone', 'Asia/Jakarta'));
            $table->timestamp('last_archived_at')->nullable();
            $table->timestamp('last_cleanup_at')->nullable();
            $table->timestamps();
        });

        DB::table('visitor_settings')->insert([
            'singleton_key' => 1,
            'archive_enabled' => true,
            'archive_interval' => 'weekly',
            'archive_day' => 1,
            'archive_month_day' => 1,
            'archive_time' => '00:10:00',
            'retention_value' => 30,
            'retention_unit' => 'days',
            'auto_delete_enabled' => false,
            'delete_after_archive' => false,
            'timezone' => config('app.timezone', 'Asia/Jakarta'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_settings');
    }
};
