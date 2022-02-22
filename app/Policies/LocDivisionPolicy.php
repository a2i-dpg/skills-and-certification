<?php

namespace App\Policies;

use App\Models\LocDivision;
use App\Models\User;

class LocDivisionPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('view_any_loc_division');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param LocDivision $locDivision
     * @return mixed
     */
    public function view(User $user, LocDivision $locDivision)
    {
        return $user->hasPermission('view_single_loc_division');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_loc_division');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param LocDivision $locDivision
     * @return mixed
     */
    public function update(User $user, LocDivision $locDivision)
    {
        return $user->hasPermission('update_loc_division');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param LocDivision $locDivision
     * @return mixed
     */
    public function delete(User $user, LocDivision $locDivision)
    {
        return $user->hasPermission('delete_loc_division');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param LocDivision $locDivision
     * @return mixed
     */
    public function restore(User $user, LocDivision $locDivision)
    {
        return $user->hasPermission('restore_loc_division');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param LocDivision $locDivision
     * @return mixed
     */
    public function forceDelete(User $user, LocDivision $locDivision)
    {
        return $user->hasPermission('force_delete_loc_division');
    }
}
