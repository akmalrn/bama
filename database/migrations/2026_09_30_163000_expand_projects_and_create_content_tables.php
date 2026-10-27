<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('id_category')->nullable()->change();
            $table->string('subtitle')->nullable()->after('title');
            $table->string('badge')->nullable()->after('subtitle');
            $table->string('button_name')->nullable()->after('link');
            $table->string('button_url')->nullable()->after('button_name');
            $table->string('button_name_2')->nullable()->after('button_url');
            $table->string('button_url_2')->nullable()->after('button_name_2');
            $table->string('meta_title')->nullable()->after('meta_keywords');
            $table->unsignedInteger('order')->default(0)->after('meta_descriptions');
            $table->boolean('status')->default(true)->after('order');
            $table->boolean('is_featured')->default(false)->after('status');
        });

        // 1. Partner Section & Partners
        Schema::create('project_partner_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained('projects')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('project_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_partner_section_id')->constrained('project_partner_sections')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 2. Superiority Section & Superiorities
        Schema::create('project_superiority_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained('projects')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('project_superiorities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_superiority_section_id')->constrained('project_superiority_sections')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Feature Section & Features
        Schema::create('project_feature_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained('projects')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('project_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_feature_section_id')->constrained('project_feature_sections')->cascadeOnDelete();
            $table->string('tab_name')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('button_name')->nullable();
            $table->string('button_url')->nullable();
            $table->text('bullet_points')->nullable(); // JSON or line-separated list of bullets
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 4. Why Us Section & Items
        Schema::create('project_why_us_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained('projects')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('project_why_us_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_why_us_section_id')->constrained('project_why_us_sections')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('card_type')->default('small'); // 'small' or 'big'
            $table->unsignedInteger('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 5. CTA Section
        Schema::create('project_cta_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained('projects')->cascadeOnDelete();
            $table->string('banner_title')->nullable();
            $table->string('banner_subtitle')->nullable();
            $table->string('banner_button_name')->nullable();
            $table->string('banner_button_url')->nullable();
            $table->string('banner_image')->nullable();

            $table->string('solution_title')->nullable();
            $table->string('solution_subtitle')->nullable();
            $table->string('solution_button_name')->nullable();
            $table->string('solution_button_url')->nullable();
            $table->string('solution_image')->nullable();

            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_cta_sections');
        Schema::dropIfExists('project_why_us_items');
        Schema::dropIfExists('project_why_us_sections');
        Schema::dropIfExists('project_features');
        Schema::dropIfExists('project_feature_sections');
        Schema::dropIfExists('project_superiorities');
        Schema::dropIfExists('project_superiority_sections');
        Schema::dropIfExists('project_partners');
        Schema::dropIfExists('project_partner_sections');

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'subtitle',
                'badge',
                'button_name',
                'button_url',
                'button_name_2',
                'button_url_2',
                'meta_title',
                'order',
                'status',
                'is_featured'
            ]);
        });
    }
};
