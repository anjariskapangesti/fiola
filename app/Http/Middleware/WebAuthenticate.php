<?php

namespace App\Http\Middleware;

use Closure;

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
        return route('website.auth.login');
    }
}
