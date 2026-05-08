<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if authenticated via the 'admin' guard
        $isAdmin = Auth::guard('admin')->check();
        
        if (!$isAdmin) {
            // Fallback: Check if they have the admin session variable but aren't logged in yet
            if (session('admin_authenticated')) {
                $user = \App\Models\User::where('role', 'admin')->first();
                if ($user) {
                    Auth::guard('admin')->login($user);
                    return $next($request);
                }
            }
            return redirect()->route('admin.loginform');
        }

        return $next($request);
    }
}
