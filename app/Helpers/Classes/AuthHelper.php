<?php

namespace App\Helpers\Classes;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthHelper
{
    /**
     * @param string $guard
     * @return Authenticatable|null|User
     */
    public static function getAuthUser(string $guard = 'web'): ?Authenticatable
    {
        /**
         * If User pass empty string then it will return default auth, if not found default guard then return null value
         */

        if (empty($guard)) {
            if (Auth::check()) {
                return Auth::user();
            } else {
                return null;
            }
        }

        if (Auth::guard($guard)->check()) {
            return Auth::guard($guard)->user();
        }

        return null;
    }

    public static function checkAuthUser(string $guard = 'web'): bool
    {
        if (empty($guard)) return false;

        return Auth::guard($guard)->check();
    }
}
