<?php

namespace App\Helpers\Classes;

use App\Models\UserType;
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

    /**
     * check if authenticated user is a user except trainee.
     *
     * @param string $guard
     * @return bool
     */
    public static function checkAuthUser(string $guard = 'web'): bool
    {
        if (empty($guard)) {
            return false;
        }

        return Auth::guard($guard)->check() && !self::isAuthTrainee();
    }

    /**
     * check authenticated trainee
     *
     * @param string $guard
     * @return bool
     */
    public static function isAuthTrainee(string $guard = 'web'): bool
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        if (empty($guard)) {
            return false;
        }

        return Auth::guard($guard)->check() && $authUser->user_type_id == UserType::USER_TYPE_TRAINEE_USER_CODE;
    }
}
