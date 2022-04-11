<?php

namespace App\Policies;

use App\Models\SiteSetting;
use App\Models\User;

class SiteSettingPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view any models.
     *
     * @param User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('view_any_site_setting');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User  $user
     * @param SiteSetting  $site_setting
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->hasPermission('view_single_site_setting');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_site_setting');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User  $user
     * @param SiteSetting  $site_setting
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->hasPermission('update_site_setting');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User  $user
     * @param SiteSetting  $site_setting
     * @return mixed
     */
    
    public function delete(User $user)
    {
        return $user->hasPermission('delete_site_setting');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User  $user
     * @param SiteSetting  $site_setting
     * @return mixed
     */
    public function restore(User $user)
    {
        return $user->hasPermission('restore_site_setting');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User  $user
     * @param SiteSetting  $site_setting
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        return $user->hasPermission('force_delete_site_setting');
    }
}
