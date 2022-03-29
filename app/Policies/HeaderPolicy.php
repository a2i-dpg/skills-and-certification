<?php

namespace App\Policies;

use App\Models\Header;
use App\Models\User;

class HeaderPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {

        return $user->hasPermission('view_any_header');
    }

    /**
     * determine where the user can view models.
     *
     * @param User $user
     * @param Header $header
     * @return bool
     */
    public function view(User $user, Header $header): bool
    {
        return $user->hasPermission('view_single_header');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_header');
    }


    /**
     * @param User $user
     * @return mixed
     */
    public function update(User $user): bool
    {
        return $user->hasPermission('update_header');
    }

    /**
     * @param User $user
     * @param Header $header
     * @return mixed
     */
    public function delete(User $user, Header $header)
    {
        return $user->hasPermission('delete_header');

    }

    /**
     * @param User $user
     * @param Header $header
     * @return mixed
     *
     */
    public function restore(User $user, Header $header)
    {
        return $user->hasPermission('restore_header');
    }

    /**
     * @param User $user
     * @param Header $header
     * @return mixed
     */
    public function forceDelete(User $user, Header $header)
    {
        return $user->hasPermission('force_delete_header');
    }
}
