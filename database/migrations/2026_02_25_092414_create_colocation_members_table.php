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
        Schema::create('colocation_members', function (Blueprint $table) {
            $table->foreignId('colocation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['owner', 'member']);
            $table->timestamp('left_at')->nullable();
            $table->primary(['colocation_id', 'user_id']);
            $table->unique(['colocation_id', 'user_id', 'left_at']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colocation_members');
    }
};
