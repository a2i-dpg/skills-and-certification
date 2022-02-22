<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VideoCategory;

class VideoCategoryPolicy extends BasePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_video_category');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param VideoCategory  $videoCategory
     * @return mixed
     */
    public function view(User $user, VideoCategory $videoCategory)
    {
        return $user->hasPermission('view_single_video_category');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_video_category');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param VideoCategory  $videoCategory
     * @return mixed
     */
    public function update(User $user, VideoCategory $videoCategory)
    {
        return $user->hasPermission('update_video_category');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param VideoCategory  $videoCategory
     * @return mixed
     */
    public function delete(User $user, VideoCategory $videoCategory)
    {
        return $user->hasPermission('delete_video_category');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param VideoCategory  $videoCategory
     * @return mixed
     */
    public function restore(User $user, VideoCategory $videoCategory)
    {
        return $user->hasPermission('restore_video_category');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param VideoCategory  $videoCategory
     * @return mixed
     */
    public function forceDelete(User $user, VideoCategory $videoCategory)
    {
        return $user->hasPermission('force_delete_video_category');
    }
}
