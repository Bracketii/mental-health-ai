<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
  /**
     * Handle an incoming request.
     *
     * Ensures that only users with 'admin' role can proceed.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Redirect to login if not authenticated
            return redirect()->route('login');
        }

        // Check if authenticated user is admin
        if (!Auth::user()->isAdmin()) {
            // Optionally, redirect to a forbidden page or home
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
