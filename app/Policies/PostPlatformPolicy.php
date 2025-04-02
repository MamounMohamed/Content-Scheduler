<?php

namespace App\Policies;

use App\Models\PostPlatform;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPlatformPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user != null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PostPlatform $postPlatform): bool
    {
        return $postPlatform->post->user_id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PostPlatform $postPlatform): bool
    {
        return $postPlatform->post->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PostPlatform $postPlatform): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PostPlatform $postPlatform): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PostPlatform $postPlatform): bool
    {
        return false;
    }
}
