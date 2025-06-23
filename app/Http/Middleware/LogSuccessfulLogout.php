<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogout
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && $request->getPathInfo() === '/logout') {
            Auth::logout();
            event(new \Illuminate\Auth\Events\Logout(Auth::user()));
        }

        return $response;
    }
}
