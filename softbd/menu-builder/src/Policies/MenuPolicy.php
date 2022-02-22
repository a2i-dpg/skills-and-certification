<?php

namespace Softbd\MenuBuilder\Policies;

use App\Models\User;
use App\Policies\BasePolicy;
use Softbd\MenuBuilder\Models\Menu;

class MenuPolicy extends BasePolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermission('view_any_menu');
    }

    public function create(User $user)
    {
        return $user->hasPermission('create_menu');
    }

    public function view(User $user, Menu $menu)
    {
        return $user->hasPermission('view_single_menu');
    }

    public function update(User $user, Menu $menu)
    {
        return $user->hasPermission('update_menu');
    }

    public function delete(User $user, Menu $menu)
    {
        return $user->hasPermission('delete_menu');
    }


}
