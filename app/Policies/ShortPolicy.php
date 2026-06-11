<?php

namespace App\Policies;

use App\Models\Short;
use App\Models\User;

class ShortPolicy
{
    public function view(User $user, Short $short): bool
    {
        return $short->user_id === null || $user->id === $short->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Short $short): bool
    {
        return $user->id === $short->user_id;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Short $short): bool
    {
        return $short->user_id === null || $user->id === $short->user_id;
    }
}
