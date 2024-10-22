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
        Schema::create('teori', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id'); // Foreign key to courses
            $table->date('session_date');
            $table->enum('status', ['scheduled', 'done', 'canceled'])->default('scheduled');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teori');
    }
};
