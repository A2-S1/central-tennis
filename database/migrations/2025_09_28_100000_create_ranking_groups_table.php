<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ranking_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('category', 50)->nullable(); // Juvenil, Adulto, etc
            $table->string('gender', 20)->nullable(); // M, F, Misto
            $table->string('badge_url')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ranking_groups');
    }
};
