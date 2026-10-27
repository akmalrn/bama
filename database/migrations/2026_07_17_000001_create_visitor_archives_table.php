<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_archives', function (Blueprint $table) {
            $table->id();
            $table->date('period_start');
            $table->date('period_end');
            $table->string('archive_interval', 20)->default('custom');
            $table->unsignedBigInteger('total_visits')->default(0);
            $table->unsignedBigInteger('unique_visitors')->default(0);
            $table->unsignedBigInteger('total_pages_viewed')->default(0);
            $table->json('daily_breakdown')->nullable();
            $table->string('status', 20)->default('completed');
            $table->timestamp('archived_at');
            $table->timestamps();

            $table->unique(['period_start', 'period_end']);
            $table->index(['archive_interval', 'period_start']);
            $table->index('archived_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_archives');
    }
};
