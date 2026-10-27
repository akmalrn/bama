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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('title'); // Termin / Deskripsi penagihan
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('tax_rate', 5, 2)->default(11.00); // PPN 11%
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->string('pph_type')->default('none'); // none, pph_21, pph_23, pph_4_2
            $table->decimal('pph_rate', 5, 2)->default(0.00);
            $table->decimal('pph_amount', 15, 2)->default(0.00);
            $table->decimal('total_amount', 15, 2)->default(0.00); // subtotal + tax_amount
            $table->decimal('paid_amount', 15, 2)->default(0.00);
            $table->string('status')->default('draft'); // draft, sent, partial, paid, overdue, cancelled
            $table->string('payment_terms')->default('Net 30 Days');
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('payment_method')->default('Transfer Bank'); // Transfer Bank, Bilyet Giro, Cash, dll
            $table->string('reference_no')->nullable(); // Nomor Ref / Bukti Transfer
            $table->text('notes')->nullable();
            $table->string('receipt_file')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
        Schema::dropIfExists('invoices');
    }
};
