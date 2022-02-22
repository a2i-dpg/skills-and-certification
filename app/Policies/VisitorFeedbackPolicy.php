<?php

namespace App\Policies;

use App\Models\VisitorFeedback;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitorFeedbackPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_visitor_feedback');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param VisitorFeedback $visitorFeedback
     * @return mixed
     */
    public function view(User $user, VisitorFeedback $visitorFeedback)
    {
        return $user->hasPermission('view_single_visitor_feedback');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_visitor_feedback');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param VisitorFeedback $visitorFeedback
     * @return mixed
     */
    public function update(User $user, VisitorFeedback $visitorFeedback)
    {
        return $user->hasPermission('update_visitor_feedback');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param VisitorFeedback $visitorFeedback
     * @return mixed
     */
    public function delete(User $user, VisitorFeedback $visitorFeedback)
    {
        return $user->hasPermission('delete_visitor_feedback');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param VisitorFeedback $visitorFeedback
     * @return mixed
     */
    public function restore(User $user, VisitorFeedback $visitorFeedback)
    {
        return $user->hasPermission('restore_visitor_feedback');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param VisitorFeedback $visitorFeedback
     * @return mixed
     */
    public function forceDelete(User $user, VisitorFeedback $visitorFeedback)
    {
        return $user->hasPermission('forse_delete_visitor_feedback');
    }
}
