<?php

namespace App\Policies;

use App\Models\Short;
use App\Models\User;

class ShortPolicy
{
    /**
     * Determine whether the user can view any shorts.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the short.
     */
    public function view(User $user, Short $short): bool
    {
        // Allow owners to view their shorts, or allow viewing public shorts (user_id is null)
        return $short->user_id === null || $user->id === $short->user_id;
    }

    /**
     * Determine whether the user can create shorts.
     */
    public function create(User $user): bool
    {
        // Only authenticated users can create shorts
        return true;
    }

    /**
     * Determine whether the user can update the short.
     */
    public function update(User $user, Short $short): bool
    {
        // Only the owner can update
        return $user->id === $short->user_id;
    }

    /**
     * Determine whether the user can delete the short.
     */
    public function delete(User $user, Short $short): bool
    {
        // Only the owner can delete their shorts
        return $user->id === $short->user_id;
    }

    /**
     * Determine whether the user can restore the short.
     */
    public function restore(User $user, Short $short): bool
    {
        return $user->id === $short->user_id;
    }

    /**
     * Determine whether the user can permanently delete the short.
     */
    public function forceDelete(User $user, Short $short): bool
    {
        return $user->id === $short->user_id;
    }
}
