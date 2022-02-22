<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_role');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('view_single_role');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_role');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @return mixed
     */
    public function update(User $user): bool
    {
        return $user->hasPermission('update_role');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermission('delete_role');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasPermission('restore_role');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->hasPermission('force_delete_role');
    }

    public function rolePermission(User $user, Role $role): bool
    {
        return $user->hasPermission('role_permission');
    }
}
