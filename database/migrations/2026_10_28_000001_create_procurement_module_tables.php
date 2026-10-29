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
        // 1. Vendor & Rekanan Pengadaan (Procurement Vendors)
        Schema::create('procurement_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_code', 50)->unique();
            $table->string('name');
            $table->string('category', 80)->default('Piping, Flange & Fittings');
            $table->string('contact_person')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('npwp', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_number', 60)->nullable();
            $table->string('bank_account_holder', 150)->nullable();
            $table->string('payment_terms', 60)->default('Net 30 Days');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('status', 30)->default('active'); // active, under_review, blacklisted
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Purchase Requests (PR)
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number', 50)->unique();
            $table->string('title');
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('request_date');
            $table->date('required_date');
            $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
            $table->string('category', 80)->default('Piping & Valve');
            $table->decimal('estimated_total', 15, 2)->default(0.00);
            $table->string('status', 30)->default('draft'); // draft, pending_approval, approved, rejected, converted_to_po
            $table->text('justification')->nullable();
            $table->text('approval_notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Purchase Request Items
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained('purchase_requests')->cascadeOnDelete();
            $table->string('item_name');
            $table->text('specifications')->nullable();
            $table->decimal('qty', 10, 2)->default(1.00);
            $table->string('unit', 30)->default('Pcs');
            $table->decimal('estimated_unit_price', 15, 2)->default(0.00);
            $table->decimal('estimated_total_price', 15, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Purchase Orders (PO)
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number', 50)->unique();
            $table->foreignId('purchase_request_id')->nullable()->constrained('purchase_requests')->nullOnDelete();
            $table->foreignId('vendor_id')->constrained('procurement_vendors')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->date('po_date');
            $table->date('delivery_deadline');
            $table->decimal('subtotal', 15, 2)->default(0.00);
            $table->decimal('tax_rate', 5, 2)->default(11.00);
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->decimal('discount_amount', 15, 2)->default(0.00);
            $table->decimal('grand_total', 15, 2)->default(0.00);
            $table->string('payment_terms', 60)->default('Net 30 Days');
            $table->text('delivery_address')->nullable();
            $table->string('status', 30)->default('draft'); // draft, issued, acknowledged, in_delivery, partially_received, completed, cancelled
            $table->text('terms_conditions')->nullable();
            $table->string('attachment_path')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 5. Purchase Order Items
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->string('item_name');
            $table->text('specifications')->nullable();
            $table->decimal('qty', 10, 2)->default(1.00);
            $table->string('unit', 30)->default('Pcs');
            $table->decimal('unit_price', 15, 2)->default(0.00);
            $table->decimal('total_price', 15, 2)->default(0.00);
            $table->decimal('qty_received', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Logistics & Expedition Tracking (Pengiriman Barang ke Site)
        Schema::create('logistics_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_number', 50)->unique();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('expedition_name');
            $table->string('tracking_number', 100)->nullable();
            $table->string('origin');
            $table->string('destination');
            $table->date('shipment_date');
            $table->date('estimated_arrival_date');
            $table->date('actual_arrival_date')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone', 50)->nullable();
            $table->string('status', 30)->default('preparing'); // preparing, in_transit, arrived_at_site, inspected_and_received, issue_damaged
            $table->string('driver_name')->nullable();
            $table->string('driver_phone', 50)->nullable();
            $table->string('vehicle_plate', 30)->nullable();
            $table->string('bap_attachment_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics_deliveries');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('purchase_request_items');
        Schema::dropIfExists('purchase_requests');
        Schema::dropIfExists('procurement_vendors');
    }
};
