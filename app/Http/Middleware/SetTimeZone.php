<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Date;

class SetTimeZone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Date::setLocale(config('app.locale'));
        date_default_timezone_set(config('app.timezone'));

        return $next($request);
    }
}
