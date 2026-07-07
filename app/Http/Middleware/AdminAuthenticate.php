<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     * If the admin guard is not authenticated, redirect to admin login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('admin.login');
        }

        // Tell Laravel to use the admin guard as the default for this request.
        // This ensures Gate::authorize() uses the admin user, not web guard user.
        Auth::shouldUse('admin');

        return $next($request);
    }
}
