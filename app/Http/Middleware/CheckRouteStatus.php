<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RouteManagement;
use Symfony\Component\HttpFoundation\Response;

class CheckRouteStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();
        
        if ($routeName) {
            $route = RouteManagement::where('route_name', $routeName)->first();
            
            if ($route && !$route->is_active) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'This route is currently disabled.'], 503);
                }
                abort(503, 'This route is currently disabled.');
            }
        }

        return $next($request);
    }
} 