<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DepartmentManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (auth()->check()) {
            // Check if the user has the role of "Department Manager"
            if (auth()->user()->role == 'Department Manager') {
                // Check if the user's department ID matches the requested resource's department ID
                $userDepartmentId = auth()->user()->department_id;
                
                // Retrieve the department ID from the route or request parameters
                $requestedDepartmentId = $request->route('department_id'); // Adjust as per your route configuration

                if ($userDepartmentId == $requestedDepartmentId) {
                    return $next($request);
                }
            }
        }

        // If not authorized, redirect or respond accordingly
        return redirect()->route('unauthorized'); // You can customize this route or response
    }
}
