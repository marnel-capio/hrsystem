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

        if (!$user) {
            return redirect('/');
        }

        $permission = (int) $user->permissions;
        $routeName = $request->route()->getName();
        $routeId = $request->route('id');

        /*
        |----------------------------------------------------------------------
        | Permission WALK-IN → NEVER allowed anywhere
        |----------------------------------------------------------------------
        */
        if ($permission === config('constants.WALKIN_PERMISSION.value')) {
            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }

        /*
        |----------------------------------------------------------------------
        | Routes: /user & /user/register
        | Only HR Admin & HR Manager allowed
        |----------------------------------------------------------------------
        */
        if (in_array($routeName, ['user.index', 'user.register', 'user.store'])) {
            if (in_array($permission, [
                config('constants.HR_ADMIN_PERMISSION.value'),
                config('constants.HR_MANAGER_PERMISSION.value'),
            ])) {
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

            // Full access
            if (in_array($permission, [
                config('constants.HR_ADMIN_PERMISSION.value'),
                config('constants.HR_MANAGER_PERMISSION.value'),
            ])) {
                return $next($request);
            }

            // Limited access (own profile only)
            if (in_array($permission, [
                config('constants.HR_RECRUITER_PERMISSION.value'),
                config('constants.HR_PERMISSION.value'),
                config('constants.BU_MANAGER_PERMISSION.value'),
                config('constants.INTERVIEWER_PERMISSION.value'),
            ])) {

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

            // Full access
            if (in_array($permission, [
                config('constants.HR_ADMIN_PERMISSION.value'),
                config('constants.HR_MANAGER_PERMISSION.value'),
            ])) {
                return $next($request);
            }

            // Limited access (own profile only)
            if (in_array($permission, [
                config('constants.HR_RECRUITER_PERMISSION.value'),
                config('constants.HR_PERMISSION.value'),
                config('constants.BU_MANAGER_PERMISSION.value'),
                config('constants.INTERVIEWER_PERMISSION.value'),
            ])) {

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