<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ProfileCompletionCheck
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->profileIncomplete()) {
            return redirect()->route('website.user.edit');
        }

        return $next($request);
    }
}
