<?php

namespace App\Policies;

use App\Models\Reference;
use App\Models\User;

class ReferencePolicy
{
    /**
     * Determine whether the user can view the reference.
     */
    public function view(User $user, Reference $reference): bool
    {
        return $user->id === $reference->user_id;
    }

    /**
     * Determine whether the user can update the reference.
     */
    public function update(User $user, Reference $reference): bool
    {
        return $user->id === $reference->user_id;
    }

    /**
     * Determine whether the user can delete the reference.
     */
    public function delete(User $user, Reference $reference): bool
    {
        return $user->id === $reference->user_id;
    }
}
