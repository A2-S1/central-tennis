<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->unsignedTinyInteger('set1_challenger')->nullable()->after('opponent_sets');
            $table->unsignedTinyInteger('set1_opponent')->nullable()->after('set1_challenger');
            $table->unsignedTinyInteger('set2_challenger')->nullable()->after('set1_opponent');
            $table->unsignedTinyInteger('set2_opponent')->nullable()->after('set2_challenger');
            $table->unsignedTinyInteger('set3_challenger')->nullable()->after('set2_opponent');
            $table->unsignedTinyInteger('set3_opponent')->nullable()->after('set3_challenger');
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn([
                'set1_challenger','set1_opponent',
                'set2_challenger','set2_opponent',
                'set3_challenger','set3_opponent',
            ]);
        });
    }
};
