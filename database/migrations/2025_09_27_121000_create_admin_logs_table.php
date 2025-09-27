<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->unsignedBigInteger('target_id');
            $table->string('target_type');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['action','target_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
