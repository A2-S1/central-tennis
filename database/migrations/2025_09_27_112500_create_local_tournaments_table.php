<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('local_tournaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('city');
            $table->string('state', 20)->nullable();
            $table->string('venue')->nullable();
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable(); // imagem do torneio
            $table->string('bracket_path')->nullable(); // chaveamento (pdf/imagem) quando houver
            $table->decimal('registration_fee', 10, 2)->nullable();
            $table->boolean('registration_is_free')->default(false);
            $table->decimal('ticket_price', 10, 2)->nullable();
            $table->boolean('ticket_is_free')->default(true);
            $table->timestamps();
            $table->index(['city', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('local_tournaments');
    }
};
