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
        $routeName = optional($request->route())->getName();
        $routePath = $request->path();

        if ($request->is('action/applications') && $request->isMethod('post')) {
    if (in_array($permission, [config('constants.HR_ADMIN_PERMISSION.value'), config('constants.HR_MANAGER_PERMISSION.value'), config('constants.HR_RECRUITER_PERMISSION.value')])) {
        return $next($request);
    }

    return redirect('/dashboard')
        ->with('error', config('errors.unauthorized.errorMessage'));
}

        /*
        |----------------------------------------------------------------------
        | Permission WALK-IN → NEVER allowed anywhere
        |----------------------------------------------------------------------
        */
        if ($permission === config('constants.WALKIN_PERMISSION.value')) {
            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }

        // Allow API routes based on path (since they might not have names)
        if (str_contains($routePath, 'eligible-applicants') ||
            str_contains($routePath, 'check-eligibility') ||
            str_contains($routePath, 'check-unique')) {
            // API routes should be accessible to HR Admin, HR Manager, and HR Recruiter
            if (in_array($permission, [config('constants.HR_ADMIN_PERMISSION.value'), config('constants.HR_MANAGER_PERMISSION.value'), config('constants.HR_RECRUITER_PERMISSION.value')])) {
                return $next($request);
            }
            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }

        /*
        |----------------------------------------------------------------------
        | Routes: /user & /user/register
        | Only HR Admin & HR Manager allowedintermediate.projects.lis
        |----------------------------------------------------------------------
        */
        if (in_array($routeName, ['user.index', 'user.register', 'user.store', 'action.schedules.register', 'action.schedules.store', 'action.schedules.edit', 'action.schedules.update', 'action.create', 'action.show', 'action.schedules.notify', 'action.schedules.destroy'])) {
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
        |--------------------------------------------------------------------------
        | Route: /action/schedules/ and /action/batches and /action/applications/register and /action/applications/id and applications import
        | Only permission 1, 2, and 3 allowed
        |--------------------------------------------------------------------------
        */
        if (in_array($routeName, [
            'action.schedules.index',
            'action.schedules.show',
            'action.list',
            'action.applications.import',
            'action.applications.create',
            'action.applications.show',
            'action.applications.store',
            'action.applications.eligible-applicants',
            'action.applications.check-eligibility',
            'action.applications.check-unique'
        ])) {
            if (in_array($permission, [config('constants.HR_ADMIN_PERMISSION.value'), config('constants.HR_MANAGER_PERMISSION.value'), config('constants.HR_RECRUITER_PERMISSION.value')])) {
                return $next($request);
            }

            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }

        /*
        |--------------------------------------------------------------------------
        | Route: ACTION Applications List
        | Only permission 1, 2, 3, 5, 6 allowed
        |--------------------------------------------------------------------------
        */
        if (in_array($routeName, ['action.applications.index'])) {
            if (in_array($permission, [1, 2, 3, 5, 6])) {
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

        // ACTION BATCH
        if (in_array($routeName, ['action.batches.list', 'action.batches.show'])) {
            if (in_array($permission, [1, 2, 3])) {
                return $next($request);
            }

            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }

        if (in_array($routeName, ['action.batches.register', 'action.batches.store', 'action.batches.edit', 'action.batches.update'])) {
            if (in_array($permission, [1, 2])) {
                return $next($request);
            }

            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }

        // INTERMEDIATE
        if (in_array($routeName, ['intermediate.projects.list', 'intermediate.projects.show'])) {
            if (in_array($permission, [1, 2, 3, 5])) {
                return $next($request);
            }
            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }
        if (in_array($routeName, ['intermediate.projects.register', 'intermediate.projects.store', 'intermediate.projects.edit', 'intermediate.projects.update'])) {
            // Only permission 1 or 5 are allowed for these routes
            if (in_array($permission, [1, 5])) {
                return $next($request);
            }

            // Fetch the error message from errors.php using the correct key
            $errorMessage = trans('errors.unauthorized_user.errorMessage');

            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }


        /*
        |----------------------------------------------------------------------
        | Route: /user/{id}/edit → edit user profile
        |----------------------------------------------------------------------
        */
        if ($routeName === 'user.edit') {
            if (in_array($permission, [
                config('constants.HR_ADMIN_PERMISSION.value'),
                config('constants.HR_MANAGER_PERMISSION.value'),
            ])) {
                return $next($request);
            }

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

        if (in_array($routeName, ['action.applicants.index'])) {
            if (in_array($permission, [
                config('constants.HR_ADMIN_PERMISSION.value'),
                config('constants.HR_MANAGER_PERMISSION.value'),
                config('constants.HR_RECRUITER_PERMISSION.value'),
                config('constants.BU_MANAGER_PERMISSION.value'),
                config('constants.INTERVIEWER_PERMISSION.value'),
            ])) {
                return $next($request);
            }

            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
        }

        /*
        |--------------------------------------------------------------------------
        | ACTION Applicants → only HR Admin, HR Manager, HR Recruiter
        |--------------------------------------------------------------------------
        */
        if (in_array($routeName, [
            'action.applicants.register',
            'action.applicants.store',
            'action.applicants.detail',
            'action.applicants.check-email',
        ])) {
            if (in_array($permission, [
                config('constants.HR_ADMIN_PERMISSION.value'),
                config('constants.HR_MANAGER_PERMISSION.value'),
                config('constants.HR_RECRUITER_PERMISSION.value'),
            ])) {
                return $next($request);
            }

            return redirect('/dashboard')
                ->with('error', config('errors.unauthorized.errorMessage'));
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
