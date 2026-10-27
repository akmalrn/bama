<?php

declare(strict_types=1);

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
        Schema::table('proposals', function (Blueprint $table) {
            $table->decimal('estimated_cost', 15, 2)->default(0.00)->after('amount');
            $table->decimal('gross_margin', 5, 2)->default(0.00)->after('estimated_cost');
            $table->string('file_path')->nullable()->after('status');
            $table->text('notes')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['estimated_cost', 'gross_margin', 'file_path', 'notes']);
        });
    }
};
