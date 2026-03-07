<?php

namespace App\Policies;

use App\Models\Recipient;
use App\Models\User;

class RecipientPolicy
{
    public function view(User $user, Recipient $recipient): bool
    {
        return $user->id === $recipient->user_id;
    }

    public function update(User $user, Recipient $recipient): bool
    {
        return $user->id === $recipient->user_id;
    }

    public function delete(User $user, Recipient $recipient): bool
    {
        return $user->id === $recipient->user_id;
    }
}
