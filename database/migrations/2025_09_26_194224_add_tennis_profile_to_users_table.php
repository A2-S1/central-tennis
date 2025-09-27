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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('plays_tennis')->default(false);
            $table->enum('tennis_level', ['iniciante', 'intermediario', 'avancado', 'especial'])->nullable();
            $table->string('usual_playing_location')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('public_profile')->default(false);
            $table->index('city');
            $table->index('tennis_level');
            $table->index('usual_playing_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'plays_tennis',
                'tennis_level',
                'usual_playing_location',
                'city',
                'state',
                'bio',
                'public_profile'
            ]);
        });
    }
};

