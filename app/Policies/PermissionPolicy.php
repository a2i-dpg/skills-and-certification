<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_permission');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermission('view_single_permission');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_permission');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function update(User $user): bool
    {
        return $user->hasPermission('update_permission');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermission('delete_permission');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function restore(User $user, Permission $permission): bool
    {
        return $user->hasPermission('restore_permission');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->hasPermission('force_delete_permission');
    }
}
