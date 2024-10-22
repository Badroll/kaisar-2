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
        Schema::table('practice_talent', function (Blueprint $table) {
            // Hapus kolom id
            $table->dropColumn('id');
            $table->primary(['practice_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practice_talent', function (Blueprint $table) {
            // Kembalikan kolom id jika rollback dilakukan
            $table->id();
        });
    }
};
