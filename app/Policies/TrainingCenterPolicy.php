<?php

namespace App\Policies;

use App\Models\TrainingCenter;
use App\Models\User;

class TrainingCenterPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_training_center');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param TrainingCenter $trainingCenter
     * @return mixed
     */
    public function view(User $user, TrainingCenter $trainingCenter): bool
    {
        return $user->hasPermission('view_single_training_center');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_training_center');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param TrainingCenter $trainingCenter
     * @return mixed
     */
    public function update(User $user, TrainingCenter $trainingCenter): bool
    {
        return $user->hasPermission('update_training_center');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param TrainingCenter $trainingCenter
     * @return mixed
     */
    public function delete(User $user, TrainingCenter $trainingCenter): bool
    {
        return $user->hasPermission('delete_training_center');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param TrainingCenter  $trainingCenter
     * @return mixed
     */
    public function restore(User $user, TrainingCenter $trainingCenter): bool
    {
        return $user->hasPermission('restore_training_center');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param TrainingCenter  $trainingCenter
     * @return mixed
     */
    public function forceDelete(User $user, TrainingCenter $trainingCenter): bool
    {
        return $user->hasPermission('force_delete_training_center');
    }
}
