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
        // Tabel Timesheet Kerja Personal / Mandiri Karyawan
        Schema::create('user_timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('task_id')->nullable()->constrained('hcd_tasks')->nullOnDelete();
            $table->date('date');
            $table->decimal('hours', 5, 2)->default(8.00); // 8.00 jam
            $table->string('work_type', 50)->default('On-Site Project'); // On-Site Project, Workshop Prep, Engineering Design, Customer Meeting, Administration, Overtime
            $table->string('activity_summary'); // Uraian pekerjaan harian
            $table->string('location', 150)->default('Head Office'); // Bontang Site, Sorowako, Workshop Balikpapan
            $table->string('status', 30)->default('submitted'); // submitted, approved, revised
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_timesheets');
    }
};
