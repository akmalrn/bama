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
        Schema::create('services_section', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('span')->nullable();
            $table->string('subTitle')->nullable();
            $table->text('description')->nullable();
            $table->text('title_cta')->nullable();
            $table->text('description_cta')->nullable();
            $table->text('subTitle_cta')->nullable();
            $table->text('name_link')->nullable();
            $table->text('link')->nullable();
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_section');
    }
};
