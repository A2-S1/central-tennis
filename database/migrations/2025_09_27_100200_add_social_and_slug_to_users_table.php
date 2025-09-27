<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('instagram')->nullable()->after('bio');
            $table->string('whatsapp')->nullable()->after('instagram');
        });

        // Backfill slugs para usuários existentes
        $users = DB::table('users')->select('id', 'name', 'slug')->get();
        $existing = [];
        foreach ($users as $u) {
            if ($u->slug) { continue; }
            $base = Str::slug($u->name ?: 'user-'.$u->id);
            $slug = $base ?: 'user-'.$u->id;
            $n = 1;
            while (in_array($slug, $existing, true) || DB::table('users')->where('slug', $slug)->exists()) {
                $slug = $base.'-'.(++$n);
            }
            DB::table('users')->where('id', $u->id)->update(['slug' => $slug]);
            $existing[] = $slug;
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['slug', 'instagram', 'whatsapp']);
        });
    }
};
