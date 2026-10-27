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
        Schema::table('project_cost_entries', function (Blueprint $table) {
            if (! Schema::hasColumn('project_cost_entries', 'payment_status')) {
                $table->string('payment_status')->default('paid')->after('amount'); // paid, unpaid (AP)
            }
            if (! Schema::hasColumn('project_cost_entries', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->default(0.00)->after('payment_status'); // PPN Masukan
            }
            if (! Schema::hasColumn('project_cost_entries', 'pph_amount')) {
                $table->decimal('pph_amount', 15, 2)->default(0.00)->after('tax_amount'); // PPh Potongan
            }
            if (! Schema::hasColumn('project_cost_entries', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_cost_entries', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'tax_amount', 'pph_amount', 'attachment_path']);
        });
    }
};
