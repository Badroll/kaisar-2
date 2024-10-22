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
        Schema::create('ms_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('day_duration'); // Max duration of the course in days
            $table->integer('num_of_praktek'); // 7, 12, 18 days
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_models');
    }
};
