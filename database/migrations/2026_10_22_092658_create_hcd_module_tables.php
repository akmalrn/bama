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
        // 1. Add extra fields to employees table if not exists
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'nik')) {
                $table->string('nik', 30)->nullable()->after('employee_number');
            }
            if (!Schema::hasColumn('employees', 'employment_status')) {
                $table->string('employment_status', 30)->default('contract')->after('nik'); // permanent, contract, freelance, internship
            }
            if (!Schema::hasColumn('employees', 'work_location')) {
                $table->string('work_location', 100)->default('Head Office')->after('employment_status'); // Head Office, Site Bontang, Site Palembang, etc.
            }
            if (!Schema::hasColumn('employees', 'status')) {
                $table->string('status', 30)->default('active')->after('work_location'); // active, inactive, on_leave, mobilized
            }
            if (!Schema::hasColumn('employees', 'daily_rate')) {
                $table->decimal('daily_rate', 15, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('employees', 'skills')) {
                $table->text('skills')->nullable()->after('daily_rate');
            }
            if (!Schema::hasColumn('employees', 'education')) {
                $table->string('education', 100)->nullable()->after('skills');
            }
            if (!Schema::hasColumn('employees', 'emergency_contact_name')) {
                $table->string('emergency_contact_name', 100)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('employees', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone', 50)->nullable()->after('emergency_contact_name');
            }
        });

        // 2. Task Monitoring (Penugasan Harian & Proyek)
        Schema::create('hcd_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_code', 50)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
            $table->string('status', 20)->default('pending'); // pending, in_progress, review, completed, blocked
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('estimated_hours', 6, 2)->default(0);
            $table->decimal('actual_hours', 6, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Training & Certification Master Catalog
        Schema::create('hcd_trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 50)->default('Technical Specialist'); // Safety & K3, Technical Specialist, Project Management, Soft Skills, Quality Assurance
            $table->string('institution', 150)->nullable(); // Kemenaker RI, BNSP, TUV Rheinland, etc.
            $table->integer('validity_years')->default(3);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Employee Training & Certification Records (Tracking Masa Berlaku)
        Schema::create('hcd_employee_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('training_id')->constrained('hcd_trainings')->cascadeOnDelete();
            $table->string('certificate_number', 100)->nullable();
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('status', 30)->default('valid'); // valid, expiring_soon, expired, in_training
            $table->string('certificate_file')->nullable();
            $table->decimal('cost', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 5. Professional Database (Talent Pool Spesialisasi Lapangan & Freelancer Terverifikasi)
        Schema::create('hcd_professionals', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('specialization'); // UHP Water Jetting Specialist, Hot Tapping, Flange Management, etc.
            $table->string('category', 60)->default('Mechanical & Piping'); // Mechanical & Piping, Surface Preparation, Inspection & QA/QC, Machining, HSSE Field
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 50);
            $table->string('city', 100)->nullable();
            $table->integer('experience_years')->default(5);
            $table->decimal('daily_rate', 15, 2)->default(0);
            $table->string('availability_status', 30)->default('available'); // available, mobilized, standby, blacklisted
            $table->foreignId('current_project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->text('certifications_summary')->nullable();
            $table->string('cv_file')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hcd_professionals');
        Schema::dropIfExists('hcd_employee_trainings');
        Schema::dropIfExists('hcd_trainings');
        Schema::dropIfExists('hcd_tasks');

        Schema::table('employees', function (Blueprint $table) {
            $cols = [
                'nik', 'employment_status', 'work_location', 'status', 'daily_rate',
                'skills', 'education', 'emergency_contact_name', 'emergency_contact_phone'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('employees', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
