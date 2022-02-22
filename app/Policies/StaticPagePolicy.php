<?php

namespace App\Policies;

use App\Models\StaticPage;
use App\Models\User;

class StaticPagePolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_any_static_page');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param StaticPage $staticPage
     * @return mixed
     */
    public function view(User $user, StaticPage $staticPage)
    {
        return $user->hasPermission('view_single_static_page');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_static_page');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param StaticPage $staticPage
     * @return mixed
     */
    public function update(User $user, StaticPage $staticPage)
    {
        return $user->hasPermission('update_static_page');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param StaticPage $staticPage
     * @return mixed
     */
    public function delete(User $user, StaticPage $staticPage)
    {
        return $user->hasPermission('delete_static_page');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param StaticPage $staticPage
     * @return mixed
     */
    public function restore(User $user, StaticPage $staticPage)
    {
        return $user->hasPermission('restore_static_page');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param StaticPage $staticPage
     * @return mixed
     */
    public function forceDelete(User $user, StaticPage $staticPage)
    {
        return $user->hasPermission('force_delete_static_page');
    }
}
