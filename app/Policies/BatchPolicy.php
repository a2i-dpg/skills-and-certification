<?php

namespace App\Policies;

use App\Models\Batch;
use App\Models\User;

class BatchPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_batch');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param  \App\Models\Batch  $batch
     * @return mixed
     */
    public function view(User $user, Batch $batch): bool
    {
        return $user->hasPermission('view_single_batch');
    }

    
    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_batch');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param  \App\Models\Batch  $batch
     * @return mixed
     */
    public function update(User $user): bool
    {
        return $user->hasPermission('update_batch');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param Batch  $batch
     * @return mixed
     */
    public function delete(User $user, Batch $batch)
    {
        return $user->hasPermission('delete_batch');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param Batch  $batch
     * @return mixed
     */
    public function restore(User $user, Batch $batch)
    {
        return $user->hasPermission('restore_batch');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param Batch  $batch
     * @return mixed
     */
    public function forceDelete(User $user, Batch $batch)
    {
        return $user->hasPermission('force_delete_batch');
    }

    public function viewBachTrainee(User $user, Batch $batch): bool
    {
        return $user->hasPermission('view_batch_trainee');
    }

    public function viewBachTrainerMapping(User $user, Batch $batch): bool
    {
        return $user->hasPermission('view_batch_trainer_mapping');
    }

    public function viewFinalResult(User $user, Batch $batch): bool
    {
        return $user->hasPermission('view_final_result');
    }
}
