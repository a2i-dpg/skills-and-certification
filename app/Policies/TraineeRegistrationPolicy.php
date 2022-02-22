<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TraineeRegistration;

class TraineeRegistrationPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param TraineeRegistration $traineeRegistration
     * @return mixed
     */
    public function view(User $user, TraineeRegistration $traineeRegistration)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param TraineeRegistration $traineeRegistration
     * @return mixed
     */
    public function update(User $user, TraineeRegistration $traineeRegistration)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param TraineeRegistration $traineeRegistration
     * @return mixed
     */
    public function delete(User $user, TraineeRegistration $traineeRegistration)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param TraineeRegistration $traineeRegistration
     * @return mixed
     */
    public function restore(User $user, TraineeRegistration $traineeRegistration)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param TraineeRegistration $traineeRegistration
     * @return mixed
     */
    public function forceDelete(User $user, TraineeRegistration $traineeRegistration)
    {
        //
    }
}
