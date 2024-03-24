<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class CheckDepartmentCode
{
    public function handle($request, Closure $next, $expectedCode)
    {
        $user = Auth::user();
        // dd($user->departments);
        if ($user->hasDepartment($expectedCode)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
