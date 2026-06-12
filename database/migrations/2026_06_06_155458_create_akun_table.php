<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name', 100);
            $table->enum('type', ['cash', 'bank', 'e_wallet']);
            $table->decimal('balance', 15, 2)->default(0.00);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun');
    }
};