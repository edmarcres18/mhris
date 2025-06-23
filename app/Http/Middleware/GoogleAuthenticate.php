<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('google')->check()) {
            // Get the current route name
            $currentRoute = $request->route()->getName();

            // Determine the redirect route based on the current route
            $redirectRoute = $currentRoute === 'careers' ? 'careers' : 'welcome';

            return redirect()->route($redirectRoute);
        }

        return $next($request);
    }
}
