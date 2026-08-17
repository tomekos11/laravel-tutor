<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();

        if (!$user) {
            return apiResponse(null, 'Forbidden - insufficient role', false, 403);
        }

        $user->loadMissing('userRoles.role');

        $hasRole = $user->userRoles->contains(function ($userRole) use ($role) {
            return $userRole->role && $userRole->role->name === $role;
        });

        if (!$hasRole) {
            return apiResponse(null, 'Forbidden - insufficient role', false, 403);
        }

        return $next($request);
    }
}
