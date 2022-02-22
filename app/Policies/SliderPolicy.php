<?php

namespace App\Policies;

use App\Models\Slider;
use App\Models\User;

class SliderPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('view_any_slider');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param Slider $slider
     * @return mixed
     */
    public function view(User $user, Slider $slider)
    {
        return $user->hasPermission('view_single_slider');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_slider');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param Slider $slider
     * @return mixed
     */
    public function update(User $user, Slider $slider)
    {
        return $user->hasPermission('update_slider');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param Slider $slider
     * @return mixed
     */
    public function delete(User $user, Slider $slider)
    {
        return $user->hasPermission('delete_slider');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param Slider $slider
     * @return mixed
     */
    public function restore(User $user, Slider $slider)
    {
        return $user->hasPermission('restore_slider');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param Slider $slider
     * @return mixed
     */
    public function forceDelete(User $user, Slider $slider)
    {
        return $user->hasPermission('force_delete_slider');
    }
}
