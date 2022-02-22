<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('view_any_user');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        return $user->id == $model->id || $user->hasPermission('view_single_user');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_user') && !$user->isTrainer();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        return $user->id == $model->id || $user->hasPermission('update_user');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        return $user->id != $model->id && $user->hasPermission('delete_user');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function restore(User $user, User $model): bool
    {
        return $user->id != $model->id && $user->hasPermission('restore_user');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->id != $model->id && $user->hasPermission('force_delete_user');
    }

    public function changePassword(User $user, User $model): bool
    {
        return $user->id == $model->id || $user->hasPermission('change_user_password');
    }

    public function viewUserPermission(User $user, User $model): bool
    {
        return $user->id != $model->id && $user->hasPermission('view_user_permission');
    }

    public function changeUserPermission(User $user, User $model): bool
    {
        return $this->viewUserPermission($user, $model) && $user->hasPermission('change_user_permission');
    }

    public function changeUserRole(User $user, User $model): bool
    {
        return $this->viewUserPermission($user, $model) && $user->hasPermission('change_user_role');
    }

    public function changeUserType(User $user, User $model): bool
    {
        return !($user->isUserBelongsToInstitute()) && !($user->id == $model->id);
    }

    public function editTrainerInformation(User $user, User $model): bool
    {
        return $model->isTrainer();
    }

    public function updateTrainer(User $user, User $model): bool
    {
        return $user->hasPermission('update_trainer') && !$user->isSuperUser();
    }
}
