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
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colocation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payer_id')->constrained('users');
            $table->foreignId('receiver_id')->constrained('users');
            $table->decimal('amount');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['colocation_id', 'paid_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
