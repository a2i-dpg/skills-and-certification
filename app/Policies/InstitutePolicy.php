<?php

namespace App\Policies;

use App\Models\Institute;
use App\Models\User;

class InstitutePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_institute');
    }

    /**
     * @param User $user
     * @param Institute $institute
     * @return bool
     */
    public function view(User $user, Institute $institute): bool
    {
        return $user->hasPermission('view_single_institute');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return !$user->isUserBelongsToInstitute() && $user->hasPermission('create_institute');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Institute  $institute
     * @return mixed
     */
    public function update(User $user, Institute $institute)
    {
        return $user->hasPermission('update_institute');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param Institute  $institute
     * @return mixed
     */
    public function delete(User $user, Institute $institute)
    {
        return !$user->isUserBelongsToInstitute() &&$user->hasPermission('delete_institute');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param Institute  $institute
     * @return mixed
     */
    public function restore(User $user, Institute $institute)
    {
        return !$user->isUserBelongsToInstitute() && $user->hasPermission('restore_institute');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param Institute  $institute
     * @return mixed
     */
    public function forceDelete(User $user, Institute $institute)
    {
        return !$user->isUserBelongsToInstitute() && $user->hasPermission('force_delete_institute');
    }
}
