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
        Schema::create('tax_filings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('tax_period_month'); // 1 - 12
            $table->unsignedSmallInteger('tax_period_year'); // 2026
            $table->string('tax_type'); // ppn, pph_21, pph_23, pph_4_2, pph_badan
            $table->decimal('gross_base_amount', 15, 2)->default(0.00); // DPP (Dasar Pengenaan Pajak)
            $table->decimal('output_tax', 15, 2)->default(0.00); // Pajak Keluaran
            $table->decimal('input_tax', 15, 2)->default(0.00); // Pajak Masukan
            $table->decimal('tax_due', 15, 2)->default(0.00); // Pajak Terutang / Kurang Bayar
            $table->string('ntpn')->nullable(); // Nomor Transaksi Penerimaan Negara
            $table->date('payment_date')->nullable();
            $table->date('reporting_date')->nullable();
            $table->string('status')->default('draft'); // draft, paid, reported
            $table->string('bpe_number')->nullable(); // Bukti Penerimaan Elektronik DJP
            $table->string('bpe_file')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_filings');
    }
};
