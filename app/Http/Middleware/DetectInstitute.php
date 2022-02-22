<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetectInstitute
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        preg_match("/\/([a-z0-9-]{2,50})\/?/", $request->getRequestUri(), $matches);
        $request->offsetSet('current_institute_slug', $matches[1] ?? '');

        return $next($request);
    }
}
