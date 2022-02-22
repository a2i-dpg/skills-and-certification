<?php

namespace App\Policies;

use App\Models\Examination;
use App\Models\User;

class ExaminationPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_examination');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param Examination  $examination
     * @return mixed
     */
    public function view(User $user, Examination $examination)
    {
        return $user->hasPermission('view_single_examination');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_examination');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Examination  $examination
     * @return mixed
     */
    public function update(User $user, Examination $examination)
    {
        return $user->hasPermission('update_examination');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param Examination  $examination
     * @return mixed
     */
    public function delete(User $user, Examination $examination)
    {
        return $user->hasPermission('delete_examination');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param Examination  $examination
     * @return mixed
     */
    public function restore(User $user, Examination $examination)
    {
        return $user->hasPermission('restore_examination');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param Examination  $examination
     * @return mixed
     */
    public function forceDelete(User $user, Examination $examination)
    {
        return $user->hasPermission('force_delete_examination');
    }

    /**
     * @param User  $user
     * @return mixed
     */

    public function status(User $user)
    {
        return $user->hasPermission('examination_status');
    }

    /**
     * @param User  $user
     * @return mixed
     */

    public function result(User $user)
    {
        return $user->hasPermission('examination_result');
    }
}
