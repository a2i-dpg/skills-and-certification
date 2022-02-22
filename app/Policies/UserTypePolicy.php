<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserType;

class UserTypePolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param UserType $userType
     * @return mixed
     */
    public function view(User $user, UserType $userType)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param UserType $userType
     * @return mixed
     */
    public function update(User $user, UserType $userType)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param UserType $userType
     * @return mixed
     */
    public function delete(User $user, UserType $userType)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param UserType $userType
     * @return mixed
     */
    public function restore(User $user, UserType $userType)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param UserType $userType
     * @return mixed
     */
    public function forceDelete(User $user, UserType $userType)
    {
        return true;
    }
}
