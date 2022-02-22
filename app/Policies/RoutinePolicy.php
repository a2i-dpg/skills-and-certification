<?php

namespace App\Policies;

use App\Models\Routine;
use App\Models\User;

class RoutinePolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_routine');
    }

    public function viewDailyRoutine(User $user): bool
    {
        return $user->hasPermission('view_daily_routine');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param Routine  $routine
     * @return mixed
     */
    public function view(User $user, Routine $routine)
    {
        return $user->hasPermission('view_single_routine');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_routine');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Routine  $routine
     * @return mixed
     */
    public function update(User $user, Routine $routine)
    {
        return $user->hasPermission('update_routine');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param Routine  $routine
     * @return mixed
     */
    public function delete(User $user, Routine $routine)
    {
        return $user->hasPermission('delete_routine');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param Routine  $routine
     * @return mixed
     */
    public function restore(User $user, Routine $routine)
    {
        return $user->hasPermission('restore_routine');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param Routine  $routine
     * @return mixed
     */
    public function forceDelete(User $user, Routine $routine)
    {
        return $user->hasPermission('force_delete_routine');
    }
}
