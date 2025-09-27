<?php

namespace App\Policies;

use App\Models\TennisCourt;
use App\Models\User;

class TennisCourtPolicy
{
    public function update(User $user, TennisCourt $court): bool
    {
        return $court->user_id === $user->id;
    }

    public function delete(User $user, TennisCourt $court): bool
    {
        return $court->user_id === $user->id;
    }
}
