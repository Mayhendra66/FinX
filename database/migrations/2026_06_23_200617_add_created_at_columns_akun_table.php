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
        schema::table('akun', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::table('akun', function (Blueprint $table) {
            $table->dropColumn('created_at');
        });
    }
};
