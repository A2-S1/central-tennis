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
        Schema::create('wta_rankings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('rank')->index();
            $table->string('player_name');
            $table->string('country', 3)->nullable();
            $table->unsignedInteger('points')->default(0);
            $table->unsignedInteger('tournaments_played')->default(0);
            $table->date('as_of_date')->index();
            $table->string('source')->nullable();
            $table->timestamps();
            $table->unique(['rank', 'as_of_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wta_rankings');
    }
};
