<?php

namespace App\Policies;

use App\Models\TrainerFeedback;
use App\Models\User;

class TrainerFeedbackPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_trainer_feedback');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param TrainerFeedback $trainer_feedback
     * @return mixed
     */
    public function view(User $user, TrainerFeedback $trainer_feedback)
    {
        return $user->hasPermission('view_single_trainer_feedback');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_trainer_feedback');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param TrainerFeedback $trainer_feedback
     * @return mixed
     */
    public function update(User $user, TrainerFeedback $trainer_feedback)
    {
        return $user->hasPermission('update_trainer_feedback');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param TrainerFeedback $trainer_feedback
     * @return mixed
     */
    public function delete(User $user, TrainerFeedback $trainer_feedback)
    {
        return $user->hasPermission('delete_trainer_feedback');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param TrainerFeedback $trainer_feedback
     * @return mixed
     */
    public function restore(User $user, TrainerFeedback $trainer_feedback)
    {
        return $user->hasPermission('restore_trainer_feedback');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param TrainerFeedback $trainer_feedback
     * @return mixed
     */
    public function forceDelete(User $user, TrainerFeedback $trainer_feedback)
    {
        return $user->hasPermission('force_delete_trainer_feedback');
    }
}
