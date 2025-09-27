<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // "Login" aqui corresponde ao campo de email do Laravel Auth.
        // Vamos criar um usuário com email = 'Admin' para você entrar digitando exatamente Admin no campo de email.
        $email = 'Admin';
        $name = 'Administrador';
        $password = 'Admin';

        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => true,
                'public_profile' => false,
            ]);
        } else {
            $user->update([
                'name' => $name,
                'password' => Hash::make($password),
                'is_admin' => true,
            ]);
        }
    }
}
