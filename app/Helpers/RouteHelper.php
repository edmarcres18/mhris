<?php

namespace App\Helpers;

class RouteHelper
{
    /**
     * Check if the current route matches a given pattern
     *
     * @param string|array $routes
     * @return bool
     */
    public static function isActive($routes)
    {
        if (is_string($routes)) {
            return request()->is($routes);
        }

        foreach ($routes as $route) {
            if (request()->is($route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the active class if route matches
     *
     * @param string|array $routes
     * @param string $class
     * @return string
     */
    public static function activeClass($routes, $class = 'active')
    {
        return self::isActive($routes) ? $class : '';
    }
}
