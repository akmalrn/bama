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
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('span')->nullable();
            $table->text('subTitle')->nullable();
            $table->text('description')->nullable();
            $table->text('location')->nullable();
            $table->text('working_hours')->nullable();
            $table->text('working_hours_2')->nullable();
            $table->text('phone')->nullable();
            $table->text('phone_2')->nullable();
            $table->text('whatsapp')->nullable();
            $table->text('whatsapp_2')->nullable();
            $table->text('email')->nullable();
            $table->text('email_2')->nullable();

            $table->text('map')->nullable();

            //sosmed
            $table->text('link')->nullable();
            $table->text('name')->nullable();
            $table->text('image')->nullable();

            $table->text('link_2')->nullable();
            $table->text('name_2')->nullable();
            $table->text('image_2')->nullable();

            $table->text('link_3')->nullable();
            $table->text('name_3')->nullable();
            $table->text('image_3')->nullable();

            $table->text('link_4')->nullable();
            $table->text('name_4')->nullable();
            $table->text('image_4')->nullable();

            $table->text('link_5')->nullable();
            $table->text('name_5')->nullable();
            $table->text('image_5')->nullable();

            // label sosmed
            $table->text('title_sosmed')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
