<?php

namespace App\Policies;

use App\Models\TraineeFeedback;
use App\Models\User;

class TraineeFeedbackPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_trainee_feedback');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param TraineeFeedback $trainee_feedback
     * @return mixed
     */
    public function view(User $user, TraineeFeedback $trainee_feedback)
    {
        return $user->hasPermission('view_single_trainee_feedback');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_trainee_feedback');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param TraineeFeedback $trainee_feedback
     * @return mixed
     */
    public function update(User $user, TraineeFeedback $trainee_feedback)
    {
        return $user->hasPermission('update_trainee_feedback');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param TraineeFeedback $trainee_feedback
     * @return mixed
     */
    public function delete(User $user, TraineeFeedback $trainee_feedback)
    {
        return $user->hasPermission('delete_trainee_feedback');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param TraineeFeedback $trainee_feedback
     * @return mixed
     */
    public function restore(User $user, TraineeFeedback $trainee_feedback)
    {
        return $user->hasPermission('restore_trainee_feedback');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param TraineeFeedback $trainee_feedback
     * @return mixed
     */
    public function forceDelete(User $user, TraineeFeedback $trainee_feedback)
    {
        return $user->hasPermission('force_delete_trainee_feedback');
    }
}
