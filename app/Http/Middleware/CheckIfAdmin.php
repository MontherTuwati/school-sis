<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIfAdmin
{
    /**
     * Check that the logged-in user is an administrator.
     *
     * --------------
     * VERY IMPORTANT
     * --------------
     * If you have both regular users and admins inside the same table,
     * change the contents of this method to check that the logged-in user
     * is an admin, and not a regular user.
     *
     * @param \App\User $user The authenticated user.
     * @return bool Whether the user is an admin or not.
     */
    private function checkIfUserIsAdmin($user): bool
    {
        // Assuming $user is an object with a 'role' property
        // Use '->' to access properties in an object
        if ($user->role == 'Admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Respond to an unauthorized access request.
     *
     * @param Request $request The incoming request.
     * @return mixed The response to an unauthorized request.
     */
    private function respondToUnauthorizedRequest($request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response(trans('backpack::base.unauthorized'), 401);
        } else {
            return redirect()->guest(backpack_url('login'));
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request The incoming request.
     * @param Closure $next The next middleware in the pipeline.
     * @return mixed The response to the request.
     */
    public function handle($request, Closure $next)
    {
        // Use backpack_auth() to check if the user is authenticated
        if (backpack_auth()->guest()) {
            return $this->respondToUnauthorizedRequest($request);
        }

        // Pass the authenticated user to the checkIfUserIsAdmin method
        if (! $this->checkIfUserIsAdmin(backpack_auth()->user())) {
            return $this->respondToUnauthorizedRequest($request);
        }

        return $next($request);
    }
}
