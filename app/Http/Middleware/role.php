<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class role
{
    public function handle($request, Closure $next)
{
    // Check if the user is authenticated
    if (!$request->user()) {
        return redirect('/')->with('error', 'Unauthorized');
    }
    
    

    // Check if the user has the "administrator" role and is trying to access the "departments.dashboard" route
    if ($request->user()->role === 1 && $request->routeIs('departments.dashboard')) {
        // Prepare the toast notification data
        $notification = [
            'status' => 'warning',
            'message' => 'Your not Authorized to visit the departments dashboard!',
        ];

        // Convert the notification to JSON
        $notificationJson = json_encode($notification);
        return redirect()->route('administrator.dashboard')->with('notification', $notificationJson);
    }

    // Check if the user has the "department" role and is trying to access the "administrator.dashboard" route
    if ($request->user()->role === 0 && $request->routeIs('administrator.dashboard')) {
        $notification = [
            'status' => 'warning',
            'message' => 'Your not Authorized to visit the administrator dashboard!',
        ];

        // Convert the notification to JSON
        $notificationJson = json_encode($notification);
        return redirect()->route('departments.dashboard')->with('notification', $notificationJson);
    }

    return $next($request);
}


    /**
     * Redirect to the appropriate dashboard based on the user's role.
     *
     * @param int $role
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToDashboard($role)
    {
        switch ($role) {
            case 1:
                return redirect()->route('administrator.dashboard');
                // break;
            case 0:
                return redirect()->route('departments.dashboard');
                // break;
            // Add more cases for other roles if needed
            default:
                return redirect('/');
                // break;
        }
    }
}
