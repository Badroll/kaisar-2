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
        Schema::create('practices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id'); // Foreign key to courses
            $table->date('session_date');
            $table->uuid('talent_1')->nullable();
            $table->uuid('talent_2')->nullable();
            $table->uuid('talent_3')->nullable();
            $table->enum('session_time', ['siang', 'malam']);
            $table->enum('status', ['scheduled', 'done', 'canceled'])->default('scheduled');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('talent_1')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('talent_2')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('talent_3')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practices');
    }
};
