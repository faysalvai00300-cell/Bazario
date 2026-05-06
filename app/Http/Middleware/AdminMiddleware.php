<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if authenticated via the new isolated .env login
        $envAdmin = session('admin_authenticated');
        
        // Backup: Check if authenticated via standard Auth as admin (for backwards compatibility if desired)
        $dbAdmin = auth()->check() && auth()->user()->role === 'admin';

        if (!$envAdmin && !$dbAdmin) {
            return redirect()->route('admin.loginform');
        }

        // Auto-link to Auth if not linked yet
        if ($envAdmin && !auth()->check()) {
            $user = \App\Models\User::where('role', 'admin')->first();
            if ($user) {
                \Illuminate\Support\Facades\Auth::login($user);
            }
        }

        return $next($request);
    }
}
