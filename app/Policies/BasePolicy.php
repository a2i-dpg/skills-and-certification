<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class BasePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        /** @var User $user */
        if ($user->row_status != User::ROW_STATUS_ACTIVE) {
            return false;
        }

        if ($user->isSuperUser()) {
            return true;
        }
    }

}
