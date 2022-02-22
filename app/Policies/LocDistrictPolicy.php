<?php

namespace App\Policies;

use App\Models\LocDistrict;
use App\Models\User;

class LocDistrictPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('view_any_loc_district');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param LocDistrict $locDistrict
     * @return mixed
     */
    public function view(User $user, LocDistrict $locDistrict)
    {
        return $user->hasPermission('view_single_loc_district');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_loc_district');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param LocDistrict $locDistrict
     * @return mixed
     */
    public function update(User $user, LocDistrict $locDistrict)
    {
        return $user->hasPermission('update_loc_district');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param LocDistrict $locDistrict
     * @return mixed
     */
    public function delete(User $user, LocDistrict $locDistrict)
    {
        return $user->hasPermission('delete_loc_district');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param LocDistrict $locDistrict
     * @return mixed
     */
    public function restore(User $user, LocDistrict $locDistrict)
    {
        return $user->hasPermission('restore_loc_district');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param LocDistrict $locDistrict
     * @return mixed
     */
    public function forceDelete(User $user, LocDistrict $locDistrict)
    {
        return $user->hasPermission('force_delete_loc_district');
    }
}
