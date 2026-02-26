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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->decimal('amount');
            $table->date('start_at');
            $table->foreignId('payer_id')->constrained('users');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('colocation_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index(['colocation_id', 'start_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
