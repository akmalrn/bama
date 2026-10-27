<?php

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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();

            // ===== Dashboard =====
            $table->string('name_dashboard')->nullable();
            $table->string('title_dashboard')->nullable();
            $table->string('logo_dashboard')->nullable();
            $table->string('logo_dashboard_2')->nullable();
            $table->string('favicon_dashboard')->nullable();

            // ===== Page Atau Website =====
            $table->text('name_page')->nullable();
            $table->text('title_page')->nullable();
            $table->text('logo_hitam_page')->nullable();
            $table->text('logo_putih_page')->nullable();
            $table->text('logo_footer_page')->nullable();
            $table->text('favicon_page')->nullable();
            $table->text('off_canvas_page')->nullable();
            $table->text('footer_page')->nullable();
            $table->text('text_footer_page')->nullable();
             $table->text('company_profile_pdf')->nullable();

            // ===== Login =====
            $table->string('title_login')->nullable();
            $table->string('name_login')->nullable();
            $table->string('logo_login')->nullable();
            $table->string('favicon_login')->nullable();
            $table->string('image_login')->nullable();

            // ===== SEO =====
            $table->text('meta_keywords')->nullable();
            $table->text('meta_descriptions')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_author')->nullable();
            $table->text('meta_subject')->nullable();

            // ===== Content =====          
            $table->text('cta')->nullable();
            $table->text('banner_cta')->nullable();
            $table->text('image_cta')->nullable();
            $table->text('image_cta_2')->nullable();
            $table->text('description_cta')->nullable();
            $table->text('title_link_cta')->nullable();
            $table->text('link_cta')->nullable();
            $table->text('name_link_cta')->nullable();

            $table->text('banner')->nullable();

            // ===== URL =====
            $table->text('base_url')->nullable();
            $table->text('site_url')->nullable();
            $table->text('canonical_url')->nullable();

            // ===== Open Graph =====
            $table->text('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->text('og_image')->nullable();
            $table->text('og_type')->nullable();
            $table->text('og_url')->nullable();
            $table->text('og_site_name')->nullable();

            // ===== Twitter =====
            $table->string('twitter_card')->nullable();
            $table->text('twitter_site')->nullable();
            $table->text('twitter_creator')->nullable();
            $table->text('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->text('twitter_image')->nullable();

            // ===== Geo =====
            $table->text('geo_placename')->nullable();
            $table->text('geo_region')->nullable();
            $table->decimal('geo_latitude', 10, 7)->nullable();
            $table->decimal('geo_longitude', 10, 7)->nullable();

            // ===== Other =====
            $table->string('language')->nullable();
            $table->string('robots')->nullable();
            $table->string('distribution')->nullable();
            $table->text('city')->nullable();
            $table->text('country')->nullable();
            $table->string('revisit_after')->nullable();

            // ===== Copyright =====
            $table->text('copyright')->nullable();
            $table->text('designer')->nullable();
            $table->text('generator')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
