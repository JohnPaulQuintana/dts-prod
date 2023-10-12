<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckArchivedStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Get the authenticated user
            $user = Auth::user();

            // Check if the user is archived (adjust the condition based on your implementation)
            if ($user->status === 'archived') {
                // Log out the user
                Auth::logout();

                // Redirect to the login page or any other desired page with a message
                return redirect()->route('login')->with('error', 'Your account has been archived. Please log in again.');
            }
        }

        return $next($request);
    }
}
