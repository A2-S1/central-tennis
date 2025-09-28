<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ranking_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranking_group_id')->constrained('ranking_groups')->cascadeOnDelete();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ranking_tables');
    }
};
