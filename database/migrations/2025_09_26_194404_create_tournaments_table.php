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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tour', 10)->default('ATP'); // ATP/WTA
            $table->string('level')->nullable(); // 250, 500, 1000, Grand Slam, WTA 250 etc
            $table->string('surface')->nullable(); // clay, hard, grass
            $table->string('city')->nullable();
            $table->string('country', 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('upcoming'); // ongoing, completed, upcoming
            $table->string('source')->nullable();
            $table->timestamps();
            $table->index(['tour', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
