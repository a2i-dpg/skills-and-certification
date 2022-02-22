<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuestionAnswer;

class QuestionAnswerPolicy extends BasePolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermission('view_any_question_answer');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param QuestionAnswer  $questionAnswer
     * @return mixed
     */
    public function view(User $user, QuestionAnswer $questionAnswer)
    {
        return $user->hasPermission('view_single_question_answer');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_question_answer');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param QuestionAnswer  $questionAnswer
     * @return mixed
     */
    public function update(User $user, QuestionAnswer $questionAnswer)
    {
        return $user->hasPermission('update_question_answer');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param QuestionAnswer  $questionAnswer
     * @return mixed
     */
    public function delete(User $user, QuestionAnswer $questionAnswer)
    {
        return $user->hasPermission('delete_question_answer');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param QuestionAnswer  $questionAnswer
     * @return mixed
     */
    public function restore(User $user, QuestionAnswer $questionAnswer)
    {
        return $user->hasPermission('restore_question_answer');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param QuestionAnswer  $questionAnswer
     * @return mixed
     */
    public function forceDelete(User $user, QuestionAnswer $questionAnswer)
    {
        return $user->hasPermission('force_delete_question_answer');
    }
}
