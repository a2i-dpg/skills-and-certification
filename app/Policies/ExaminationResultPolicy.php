<?php

namespace App\Policies;

use App\Models\ExaminationResult;
use App\Models\User;

class ExaminationResultPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_examination_result');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param ExaminationResult  $examination_result
     * @return mixed
     */
    public function view(User $user, ExaminationResult $examination_result)
    {
        return $user->hasPermission('view_single_examination_result');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_examination_result');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param ExaminationResult  $examination_result
     * @return mixed
     */
    public function update(User $user, ExaminationResult $examination_result)
    {
        return $user->hasPermission('update_examination_result');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param ExaminationResult  $examination_result
     * @return mixed
     */
    public function delete(User $user, ExaminationResult $examination_result)
    {
        return $user->hasPermission('delete_examination_result');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param ExaminationResult  $examination_result
     * @return mixed
     */
    public function restore(User $user, ExaminationResult $examination_result)
    {
        return $user->hasPermission('restore_examination_result');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param ExaminationResult  $examination_result
     * @return mixed
     */
    public function forceDelete(User $user, ExaminationResult $examination_result)
    {
        return $user->hasPermission('force_delete_examination_result');
    }
}
