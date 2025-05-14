<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // if (!in_array(needle: Auth::user()->role, $roles)) {
        //     abort(403, 'Unauthorized');
        // }

        if($request->session()->get('roles') !== 'Admin'){
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

