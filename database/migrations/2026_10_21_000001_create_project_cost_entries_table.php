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
        Schema::create('project_cost_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->date('entry_date');
            $table->string('cost_category'); // Personnel, Equipment, Material, Subcontractor, Transport, etc.
            $table->string('cost_subcategory')->nullable();
            $table->enum('type', ['actual', 'commitment'])->default('actual');
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->string('vendor')->nullable();
            $table->string('reference_no')->nullable(); // PO No / Invoice No
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Add turnover / handover status fields to projects if not present
        if (! Schema::hasColumn('projects', 'turnover_dossier_status')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('turnover_dossier_status')->default('Draft')->after('status');
                $table->string('handover_status')->default('In Progress')->after('turnover_dossier_status');
                $table->unsignedInteger('punch_list_open_count')->default(0)->after('handover_status');
                $table->string('certificates_status')->default('Pending')->after('punch_list_open_count');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_cost_entries');

        if (Schema::hasColumn('projects', 'turnover_dossier_status')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn([
                    'turnover_dossier_status',
                    'handover_status',
                    'punch_list_open_count',
                    'certificates_status',
                ]);
            });
        }
    }
};
