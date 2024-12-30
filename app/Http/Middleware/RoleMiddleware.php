<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        // Ensure the user is authenticated and has the correct role
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect()->route('login')->withErrors('Access Denied');
        }

        return $next($request);
    }
}
