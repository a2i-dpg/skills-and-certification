<?php

namespace App\Policies;

use App\Models\ExaminationRoutine;
use App\Models\User;

class ExaminationRoutinePolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_examination_routine');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param ExaminationRoutine  $examination_routine
     * @return mixed
     */
    public function view(User $user, ExaminationRoutine $examination_routine)
    {
        return $user->hasPermission('view_single_examination_routine');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_examination_routine');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param ExaminationRoutine  $examination_routine
     * @return mixed
     */
    public function update(User $user, ExaminationRoutine $examination_routine)
    {
        return $user->hasPermission('update_examination_routine');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param ExaminationRoutine  $examination_routine
     * @return mixed
     */
    public function delete(User $user, ExaminationRoutine $examination_routine)
    {
        return $user->hasPermission('delete_examination_routine');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param ExaminationRoutine  $examination_routine
     * @return mixed
     */
    public function restore(User $user, ExaminationRoutine $examination_routine)
    {
        return $user->hasPermission('restore_examination_routine');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param ExaminationRoutine  $examination_routine
     * @return mixed
     */
    public function forceDelete(User $user, ExaminationRoutine $examination_routine)
    {
        return $user->hasPermission('force_delete_examination_routine');
    }
}
