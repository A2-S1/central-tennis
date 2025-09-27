<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class PromoteUserA2S1Seeder extends Seeder
{
    public function run(): void
    {
        $email = 'A2S1@outlook.com.br';
        $u = User::where('email', $email)->first();
        if ($u) {
            $u->is_admin = true;
            $u->save();
        }
    }
}
