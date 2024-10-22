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
        Schema::create('practice_date_change_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('practice_id'); // Foreign key to prakteks
            $table->date('date_before');
            $table->date('date_after');
            $table->timestamps();

            $table->foreign('practice_id')->references('id')->on('practices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_date_change_histories');
    }
};
