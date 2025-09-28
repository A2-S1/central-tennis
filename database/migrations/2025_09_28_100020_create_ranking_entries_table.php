<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ranking_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranking_table_id')->constrained('ranking_tables')->cascadeOnDelete();
            $table->unsignedInteger('position');
            $table->string('player_name');
            $table->string('club')->nullable();
            $table->string('state', 10)->nullable();
            $table->unsignedInteger('points')->default(0);
            $table->unsignedInteger('matches')->nullable();
            $table->string('obs')->nullable();
            $table->timestamps();
            $table->unique(['ranking_table_id','position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ranking_entries');
    }
};
