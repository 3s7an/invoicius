<?php

namespace App\Policies;

use App\Models\Automatization;
use App\Models\User;

class AutomatizationPolicy
{
    public function view(User $user, Automatization $automatization): bool
    {
        return $user->id === $automatization->user_id;
    }

    public function update(User $user, Automatization $automatization): bool
    {
        return $user->id === $automatization->user_id;
    }

    public function delete(User $user, Automatization $automatization): bool
    {
        return $user->id === $automatization->user_id;
    }
}
