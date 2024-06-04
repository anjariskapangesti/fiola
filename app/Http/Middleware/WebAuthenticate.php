<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class WebAuthenticate extends Authenticate
{
    /**
    * Get the path the user should be redirected to when they are not authenticated.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return string|null
    */
    protected function redirectTo($request)
    {
        if (!Auth::check()) {
            return route('website.auth.login');
        }

        return $next($request);
    }
}
