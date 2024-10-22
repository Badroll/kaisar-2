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
        Schema::create('practice_talent', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('practice_id'); // Foreign key to practices
            $table->uuid('user_id'); // Foreign key to users (for talent)
            $table->enum('session_time', ['1', '2', '3']);
            $table->timestamps();

            $table->foreign('practice_id')->references('id')->on('practices')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_talent');
    }
};
