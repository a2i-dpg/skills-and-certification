<?php

namespace App\Policies;

use App\Models\User;
use App\Models\IntroVideo;

class IntroVideoPolicy extends BasePolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermission('view_any_intro_video');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param IntroVideo  $introVideo
     * @return mixed
     */
    public function view(User $user, IntroVideo $introVideo)
    {
        return $user->hasPermission('view_single_intro_video');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_intro_video');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param IntroVideo  $introVideo
     * @return mixed
     */
    public function update(User $user, IntroVideo $introVideo)
    {
        return $user->hasPermission('update_intro_video');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param IntroVideo  $introVideo
     * @return mixed
     */
    public function delete(User $user, IntroVideo $introVideo)
    {
        return$user->hasPermission('delete_intro_video');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param IntroVideo  $introVideo
     * @return mixed
     */
    public function restore(User $user, IntroVideo $introVideo)
    {
        return $user->hasPermission('restore_intro_video');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param IntroVideo  $introVideo
     * @return mixed
     */
    public function forceDelete(User $user, IntroVideo $introVideo)
    {
        return $user->hasPermission('force_delete_intro_video');
    }
}
