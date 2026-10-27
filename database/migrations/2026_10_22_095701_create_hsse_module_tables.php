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
        // 1. Safety Permit & JSA (Permit to Work / PTW)
        Schema::create('hsse_permits', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number', 50)->unique();
            $table->string('title');
            $table->string('permit_type', 50)->default('Cold Work'); // Hot Work, Confined Space Entry, Working at Height, Cold Work, Lifting & Rigging, Radiography / NDT, Electrical / LOTO
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('location');
            $table->foreignId('applicant_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('safety_officer_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('risk_level', 20)->default('Medium'); // Low, Medium, High, Critical
            $table->text('jsa_content')->nullable();
            $table->boolean('gas_test_required')->default(false);
            $table->string('gas_test_o2', 20)->nullable();
            $table->string('gas_test_lel', 20)->nullable();
            $table->string('gas_test_co', 20)->nullable();
            $table->string('gas_test_h2s', 20)->nullable();
            $table->text('ppe_requirements')->nullable();
            $table->string('status', 30)->default('draft'); // draft, under_review, approved, active, closed, rejected
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Inspeksi Alat & APD
        Schema::create('hsse_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_number', 50)->unique();
            $table->string('item_name');
            $table->string('item_category', 60)->default('PPE & Fall Protection'); // Heavy Machinery / Pump, PPE & Fall Protection, Gas Detection & Monitoring, Lifting Gear & Slings, Electrical & LOTO, Fire Protection / APAR
            $table->string('serial_number', 100)->nullable();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('location')->nullable();
            $table->foreignId('inspector_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('inspection_date');
            $table->date('next_inspection_date')->nullable();
            $table->string('result', 30)->default('passed'); // passed, passed_with_notes, failed_quarantined
            $table->text('checklist_findings')->nullable();
            $table->text('corrective_action')->nullable();
            $table->string('tag_color', 20)->default('green'); // green, yellow, red
            $table->timestamps();
        });

        // 3. Laporan Insiden & Investigasi (Incident Log & Near Miss)
        Schema::create('hsse_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number', 50)->unique();
            $table->string('title');
            $table->string('incident_type', 60)->default('Near Miss'); // Near Miss, First Aid Case (FAC), Medical Treatment Case (MTC), Restricted Work Day (RWC), Lost Time Injury (LTI), Property Damage, Environmental Spill
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->dateTime('incident_date');
            $table->string('location');
            $table->foreignId('reporter_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('injured_person_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->text('description');
            $table->text('root_cause')->nullable();
            $table->text('immediate_action')->nullable();
            $table->text('capa_action')->nullable();
            $table->date('target_date')->nullable();
            $table->string('status', 30)->default('reported'); // reported, investigating, action_pending, closed
            $table->integer('lost_workdays')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsse_incidents');
        Schema::dropIfExists('hsse_inspections');
        Schema::dropIfExists('hsse_permits');
    }
};
