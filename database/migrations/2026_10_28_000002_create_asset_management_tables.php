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
        // 1. Master Asset (Peralatan, Mesin & Kendaraan Operasional)
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code', 50)->unique(); // AST-2026-001
            $table->string('name');                      // UHP Water Jetting Unit 40.000 PSI
            $table->string('category', 80);              // High Pressure Pump, Flange & Bolting, Generator, Testing Equipment, Vehicle
            $table->string('brand_model', 150)->nullable(); // Hammelmann HDP 170 / WOMA
            $table->string('serial_number', 100)->nullable();
            $table->year('year_acquired')->nullable();
            $table->decimal('purchase_price', 15, 2)->default(0.00);
            $table->string('condition', 30)->default('good'); // good, fair, maintenance_needed, damaged
            $table->string('status', 30)->default('available'); // available, mobilized, under_maintenance, decommissioned
            $table->string('current_location', 150)->default('Workshop Balikpapan'); // Workshop Balikpapan, Yard Bontang, Site Sorowako
            $table->foreignId('assigned_project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('pic_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->date('last_calibration_date')->nullable();
            $table->date('next_calibration_date')->nullable();
            $table->text('technical_specifications')->nullable(); // Tekanan kerja, debit, engine spec
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Alokasi Asset ke Proyek Lapangan (Asset Mobilization / Allocation)
        Schema::create('asset_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('allocation_number', 50)->unique(); // ALC-2026-001
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('start_date');
            $table->date('expected_return_date');
            $table->date('actual_return_date')->nullable();
            $table->string('status', 30)->default('active'); // pending, active, returned, cancelled
            $table->string('destination_site', 150); // Pupuk Kaltim Bontang, PT Vale Sorowako
            $table->string('courier_vehicle', 100)->nullable(); // Self Drive, Vendor Ekspedisi
            $table->string('letter_of_handover_number', 80)->nullable(); // No BAST / Surat Jalan
            $table->text('condition_out')->nullable(); // Kondisi saat keluar
            $table->text('condition_in')->nullable();  // Kondisi saat dikembalikan
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Jadwal & Riwayat Perawatan / Kalibrasi Mesin (Maintenance & Calibration Logs)
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('maintenance_number', 50)->unique(); // MTC-2026-001
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->string('type', 40)->default('preventive'); // preventive, corrective, calibration, overhaul, certification
            $table->string('title'); // Ganti Seal High Pressure Plunger, Kalibrasi Pressure Gauge
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->decimal('cost', 15, 2)->default(0.00);
            $table->string('status', 30)->default('scheduled'); // scheduled, in_progress, completed, overdue, cancelled
            $table->string('vendor_name', 150)->nullable(); // Bengkel Resmi / Vendor Kalibrasi
            $table->foreignId('technician_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->text('action_taken')->nullable();
            $table->text('parts_replaced')->nullable();
            $table->string('certificate_number', 100)->nullable(); // Sertifikat Kalibrasi / Disnaker
            $table->date('next_due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
        Schema::dropIfExists('asset_allocations');
        Schema::dropIfExists('assets');
    }
};
