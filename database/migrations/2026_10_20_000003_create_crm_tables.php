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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('location')->nullable();
            $table->string('industry')->nullable();
            $table->string('npwp', 50)->nullable();
            $table->string('pic_name');
            $table->string('pic_phone')->nullable();
            $table->string('pic_email')->nullable();
            $table->timestamps();
        });

        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('estimated_value', 15, 2)->default(0.00);
            $table->enum('stage', [
                'qualification',
                'need_analysis',
                'tender_rfq',
                'proposal_submitted',
                'negotiation',
                'closed_won',
                'closed_lost',
            ])->default('qualification');
            $table->text('next_step')->nullable();
            $table->date('target_close_date')->nullable();
            $table->timestamps();
        });

        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('opportunities')->cascadeOnDelete();
            $table->string('proposal_number')->unique();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->enum('status', [
                'draft',
                'submitted',
                'revised',
                'accepted',
                'rejected',
            ])->default('draft');
            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('opportunities')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', [
                'outside_meeting',
                'proposal_submitted',
                'online_presentation',
                'customer_visit',
            ]);
            $table->string('title');
            $table->text('notes')->nullable();
            $table->dateTime('activity_at');
            $table->timestamps();
        });

        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('opportunities')->cascadeOnDelete();
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->string('task_title');
            $table->date('due_date');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
        Schema::dropIfExists('sales_activities');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('customers');
    }
};
