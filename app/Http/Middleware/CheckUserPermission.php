<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return redirect('/');
        }

        $permission = (int) $user->permissions;
        $routeName = $request->route()->getName();
        $routeId = $request->route('id');

        /*
        |--------------------------------------------------------------------------
        | Permission 7 → NEVER allowed anywhere
        |--------------------------------------------------------------------------
        */
        if ($permission === 7) {
            return redirect('/dashboard')
                ->with('error', 'Access denied: You are not authorized to view this page.');
        }

        /*
        |--------------------------------------------------------------------------
        | Routes: /user & /user/register
        | Only permission 1 & 2 allowed
        |--------------------------------------------------------------------------
        */
        if (in_array($routeName, ['user.index', 'user.register', 'user.store', 'action.schedules.index', 'action.schedules.register', 'action.schedules.store', 'action.schedules.show', 'action.schedules.edit', 'action.schedules.update'])) {

            if (in_array($permission, [1, 2])) {
                return $next($request);
            }

            return redirect('/dashboard')
                ->with('error', 'Access denied: You are not authorized to view this page.');
        }

        /*
        |--------------------------------------------------------------------------
        | Route: /user/{id}
        |--------------------------------------------------------------------------
        */
        if ($routeName === 'user.show') {

            // Permission 1 & 2 → Full access
            if (in_array($permission, [1, 2])) {
                return $next($request);
            }

            // Permission 3–6 → Only own profile
            if (in_array($permission, [1, 2, 3, 4, 5, 6])) {

                if ((int) $routeId === (int) $user->id) {
                    return $next($request);
                }

                return redirect('/dashboard')
                    ->with('error', 'Access denied: You are not authorized to view this page.');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback
        |--------------------------------------------------------------------------
        */
        return redirect('/dashboard')
            ->with('error', 'Access denied: You are not authorized to view this page.');
    }
}
