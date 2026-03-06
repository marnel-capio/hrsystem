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
        |----------------------------------------------------------------------
        | Permission 7 → NEVER allowed anywhere
        |---------------------------------------------------------------------- 
        */
        if ($permission === 7) {
            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }

        /*
        |----------------------------------------------------------------------
        | Routes: /user & /user/register
        | Only permission 1 & 2 allowed
        |----------------------------------------------------------------------
        */
        if (in_array($routeName, ['user.index', 'user.register', 'user.store'])) {
            if (in_array($permission, [1, 2])) {
                return $next($request);
            }
            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }

        /*
        |----------------------------------------------------------------------
        | Route: /user/{id} → show user profile
        |----------------------------------------------------------------------
        */
        if ($routeName === 'user.show') {

            // Permission 1 & 2 → Full access
            if (in_array($permission, [1, 2])) {
                return $next($request);
            }

            // Permission 3–6 → Only own profile
            if (in_array($permission, [3, 4, 5, 6])) {
                if ((int) $routeId === (int) $user->id) {
                    return $next($request);
                }
                return redirect('/dashboard')
                    ->with('error', config('errors.unauthorized.errorMessage'));
            }
        }

        /*
        |----------------------------------------------------------------------
        | Route: /user/{id}/edit → edit user profile
        |----------------------------------------------------------------------
        */
        if ($routeName === 'user.edit') {

            // Permission 1 & 2 → Full access
            if (in_array($permission, [1, 2])) {
                return $next($request);
            }

            // Permission 3–6 → Only own profile
            if (in_array($permission, [3, 4, 5, 6])) {
                if ((int) $routeId === (int) $user->id) {
                    return $next($request);
                }
                return redirect('/dashboard')
                    ->with('error', config('errors.unauthorized.errorMessage'));
            }
        }

        /*
        |----------------------------------------------------------------------
        | Fallback
        |----------------------------------------------------------------------
        */
        return redirect('/dashboard')
            ->with('error', config('errors.unauthorized.errorMessage'));
    }
}