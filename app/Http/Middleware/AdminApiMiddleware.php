<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated as admin
        if (!auth('admin')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Admin authentication required.',
                'code' => 401
            ], 401);
        }

        return $next($request);
    }
}
