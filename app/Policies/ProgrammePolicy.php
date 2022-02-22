<?php

namespace App\Policies;

use App\Models\Programme;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgrammePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_programme');

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param Programme $programme
     * @return mixed
     */
    public function view(User $user, Programme $programme): bool
    {
        return $user->hasPermission('view_single_programme');

    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_programme');

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Programme $programme
     * @return mixed
     */
    public function update(User $user, Programme $programme): bool
    {
        return $user->hasPermission('update_programme');

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param Programme  $programme
     * @return mixed
     */
    public function delete(User $user, Programme $programme)
    {
        return $user->hasPermission('delete_programme');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param Programme  $programme
     * @return mixed
     */
    public function restore(User $user, Programme $programme)
    {
        return $user->hasPermission('restore_programme');
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param Programme  $programme
     * @return mixed
     */
    public function forceDelete(User $user, Programme  $programme)
    {
        return $user->hasPermission('force_delete_programme');
        //
    }
}
