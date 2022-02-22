<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_branch');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param Branch  $branch
     * @return mixed
     */
    public function view(User $user, Branch $branch)
    {
        return $user->hasPermission('view_single_branch');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_branch');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Branch  $branch
     * @return mixed
     */
    public function update(User $user, Branch $branch)
    {
        return $user->hasPermission('update_branch');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param Branch  $branch
     * @return mixed
     */
    public function delete(User $user, Branch $branch)
    {
        return $user->hasPermission('delete_branch');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param Branch  $branch
     * @return mixed
     */
    public function restore(User $user, Branch $branch)
    {
        return $user->hasPermission('restore_branch');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param Branch  $branch
     * @return mixed
     */
    public function forceDelete(User $user, Branch $branch)
    {
        return $user->hasPermission('force_delete_branch');
    }
}
