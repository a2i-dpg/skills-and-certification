<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_event');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @return mixed
     */
    public function view(User $user): bool
    {
        return $user->hasPermission('view_single_event');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_event');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Event $event
     * @return mixed
     */
    public function update(User $user, Event $event): bool
    {
        return $user->hasPermission('update_event');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param Event $event
     * @return mixed
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->hasPermission('delete_event');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param Event $event
     * @return mixed
     */
    public function restore(User $user, Event $event): bool
    {
        return $user->hasPermission('restore_event');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param Event $event
     * @return mixed
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return $user->hasPermission('force_delete_event');
    }
}
