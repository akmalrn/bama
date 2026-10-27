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
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('budget', 15, 2)->default(0.00)->after('contract_value');
            $table->decimal('actual_cost', 15, 2)->default(0.00)->after('budget');
            $table->unsignedTinyInteger('planned_progress_percentage')->default(0)->after('progress_percentage');
            $table->string('location')->nullable()->after('title');
            $table->string('category')->nullable()->after('location');
            $table->string('sector')->nullable()->after('category');
            $table->string('procurement_type')->nullable()->after('sector');
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low')->after('status');
            $table->unsignedTinyInteger('readiness_percentage')->default(100)->after('risk_level');
            $table->unsignedTinyInteger('quality_percentage')->default(100)->after('readiness_percentage');
            $table->unsignedTinyInteger('hsse_percentage')->default(100)->after('quality_percentage');
            $table->unsignedInteger('man_hours')->default(0)->after('hsse_percentage');
            $table->decimal('invoice_submitted', 15, 2)->default(0.00)->after('man_hours');
            $table->decimal('invoice_paid', 15, 2)->default(0.00)->after('invoice_submitted');
            $table->text('notes')->nullable()->after('invoice_paid');
        });

        Schema::create('project_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('milestone_code')->nullable();
            $table->string('title');
            $table->date('planned_start_date')->nullable();
            $table->date('target_date');
            $table->date('actual_date')->nullable();
            $table->decimal('weight_percentage', 5, 2)->default(0.00);
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue'])->default('pending');
            $table->string('deliverable')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('role');
            $table->string('discipline')->nullable();
            $table->date('assigned_date')->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->text('performance_notes')->nullable();
            $table->enum('status', ['active', 'completed', 'released'])->default('active');
            $table->timestamps();
        });

        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('name');
            $table->string('category')->default('Contract');
            $table->string('file_path');
            $table->date('uploaded_date');
            $table->string('file_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_documents');
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('project_milestones');

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'budget',
                'actual_cost',
                'planned_progress_percentage',
                'location',
                'category',
                'sector',
                'procurement_type',
                'risk_level',
                'readiness_percentage',
                'quality_percentage',
                'hsse_percentage',
                'man_hours',
                'invoice_submitted',
                'invoice_paid',
                'notes',
            ]);
        });
    }
};
