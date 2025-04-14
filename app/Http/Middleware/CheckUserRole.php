<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user || !$this->userHasAnyRole($user, $roles)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }

    /**
     * Check if user has any of the specified roles.
     *
     * @param  \App\Models\User  $user
     * @param  array  $roles
     * @return bool
     */
    protected function userHasAnyRole($user, $roles)
    {
        $allowedRoles = Role::whereIn('name', $roles)->pluck('id')->toArray();
        $userRoles = $user->roles()->pluck('role_id')->toArray();
        return ! empty(array_intersect($allowedRoles, $userRoles));
    }
}
