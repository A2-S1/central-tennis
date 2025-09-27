<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tennis_courts', function (Blueprint $table) {
            $table->string('access_type')->default('publica')->after('court_type'); // publica, paga, condominio
        });
    }

    public function down(): void
    {
        Schema::table('tennis_courts', function (Blueprint $table) {
            $table->dropColumn('access_type');
        });
    }
};
