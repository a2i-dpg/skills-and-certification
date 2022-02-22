<?php

namespace App\Policies;

use App\Models\Gallery;
use App\Models\User;

class GalleryPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {

        return $user->hasPermission('view_any_gallery');
    }

    /**
     * determine where the user can view models.
     *
     * @param User $user
     * @param Gallery $gallery
     * @return bool
     */
    public function view(User $user, Gallery $gallery): bool
    {
        return $user->hasPermission('view_single_gallery');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_gallery');
    }


    /**
     * @param User $user
     * @param Gallery $gallery
     * @return mixed
     */
    public function update(User $user): bool
    {
        return $user->hasPermission('update_gallery');
    }

    /**
     * @param User $user
     * @param Gallery $gallery
     * @return mixed
     */
    public function delete(User $user, Gallery $gallery)
    {
        return $user->hasPermission('delete_gallery');

    }

    /**
     * @param User $user
     * @param Gallery $gallery
     * @return mixed
     *
     */
    public function restore(User $user, Gallery $gallery)
    {
        return $user->hasPermission('restore_gallery');
    }

    /**
     * @param User $user
     * @param Gallery $gallery
     * @return mixed
     */
    public function forceDelete(User $user, Gallery $gallery)
    {
        return $user->hasPermission('force_delete_gallery');
    }
}
