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
        Schema::disableForeignKeyConstraints();

        // Drop old CMS project sections if they exist
        Schema::dropIfExists('project_cta_sections');
        Schema::dropIfExists('project_why_us_items');
        Schema::dropIfExists('project_why_us_sections');
        Schema::dropIfExists('project_features');
        Schema::dropIfExists('project_feature_sections');
        Schema::dropIfExists('project_superiorities');
        Schema::dropIfExists('project_superiority_sections');
        Schema::dropIfExists('project_partners');
        Schema::dropIfExists('project_partner_sections');

        // Drop previous showcase projects table
        Schema::dropIfExists('projects');

        Schema::enableForeignKeyConstraints();

        // Create new PMO projects table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->nullable()->constrained('opportunities')->nullOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('project_manager_id')->constrained('employees')->restrictOnDelete();
            $table->string('project_code')->unique();
            $table->string('title');
            $table->decimal('contract_value', 15, 2)->default(0.00);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', [
                'planning',
                'on_progress',
                'on_hold',
                'completed',
                'cancelled',
            ])->default('planning');
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
