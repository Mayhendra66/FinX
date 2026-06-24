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
        Schema::table('akun', function (Blueprint $table) {
            $table->dropColumn(['balance']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Perbaikan: Mengubah Schema:: menjadi Schema::table
        Schema::table('akun', function (Blueprint $table) {
             $table->decimal('balance', 15, 2)->default(0.00);
        });
    }
};