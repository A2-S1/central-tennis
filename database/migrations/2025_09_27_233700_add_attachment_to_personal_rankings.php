<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('personal_rankings', function (Blueprint $table) {
            $table->string('attachment_path')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('personal_rankings', function (Blueprint $table) {
            $table->dropColumn('attachment_path');
        });
    }
};
