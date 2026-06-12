<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('person_name', 100);
            $table->enum('type', ['debt', 'receivable']);
            $table->decimal('amount', 15, 2);
            $table->date('due_date')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->text('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utang');
    }
};