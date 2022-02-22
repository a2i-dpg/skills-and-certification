<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;

class VideoPolicy extends BasePolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermission('view_any_video');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param Video  $video
     * @return mixed
     */
    public function view(User $user, Video $video)
    {
        return $user->hasPermission('view_single_video');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_video');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Video  $video
     * @return mixed
     */
    public function update(User $user, Video $video)
    {
        return $user->hasPermission('update_video');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param Video  $video
     * @return mixed
     */
    public function delete(User $user, Video $video)
    {
        return $user->hasPermission('delete_video');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param Video  $video
     * @return mixed
     */
    public function restore(User $user, Video $video)
    {
        return $user->hasPermission('restore_video');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param Video  $video
     * @return mixed
     */
    public function forceDelete(User $user, Video $video)
    {
        return $user->hasPermission('force_delete_video');
    }
}
