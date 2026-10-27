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
        Schema::create('overview', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('subTitle')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('overview')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('overview');
    }
};
