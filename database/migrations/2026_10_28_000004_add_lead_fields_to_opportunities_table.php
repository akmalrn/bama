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
        Schema::table('opportunities', function (Blueprint $table) {
            $table->string('service_line')->nullable()->after('title');
            $table->string('project_location')->nullable()->after('service_line');
            $table->string('lead_source')->nullable()->after('project_location');
            $table->date('next_follow_up_date')->nullable()->after('target_close_date');
            $table->text('notes')->nullable()->after('next_step');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'service_line',
                'project_location',
                'lead_source',
                'next_follow_up_date',
                'notes',
            ]);
        });
    }
};
