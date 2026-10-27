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
        Schema::create('label_superiority', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subTitle')->nullable();
            $table->text('description')->nullable();
            $table->text('span')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_superiority');
    }
};
