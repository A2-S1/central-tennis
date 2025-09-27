<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenger_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('opponent_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('scheduled_at')->nullable();
            $table->string('location')->nullable();
            // pending, accepted, rejected, completed, cancelled
            $table->string('status')->default('pending');
            // Placar simples por sets (0-3)
            $table->unsignedTinyInteger('challenger_sets')->nullable();
            $table->unsignedTinyInteger('opponent_sets')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['challenger_id', 'opponent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
