=<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cicilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('akun')->onDelete('cascade');
            $table->string('name', 100);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('monthly_amount', 15, 2);
            $table->integer('total_months');
            $table->integer('paid_months')->default(0);
            $table->date('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cicilan');
    }
};