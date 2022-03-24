<?php

namespace App\Http\Middleware;

use App\Helpers\Classes\AuthHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::guard('web')->check() || AuthHelper::isAuthTrainee()) {
            return redirect()->route('admin.login-form');
        }

        return $next($request);
    }
}
