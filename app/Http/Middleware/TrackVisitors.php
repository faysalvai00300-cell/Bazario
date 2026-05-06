<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && !$request->ajax()) {
            // Track everyone including admins for real-time experience
            try {
                \App\Models\Visitor::updateOrCreate(
                    [
                        'ip_address' => $request->ip(),
                        'visit_date' => now()->toDateString(),
                        'user_agent' => substr($request->userAgent(), 0, 500)
                    ],
                    [
                        'path' => $request->path(),
                        'last_active_at' => now(),
                    ]
                );
            } catch (\Exception $e) {
                // Fail silently to keep application running
                \Log::warning('Visitor tracking failed: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
