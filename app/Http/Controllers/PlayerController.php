<?php

namespace App\Http\Controllers;

use App\Models\User;

class PlayerController extends Controller
{
    public function show(User $user)
    {
        if (!$user->public_profile) {
            abort(404);
        }
        return view('players.show', ['player' => $user]);
    }
}
