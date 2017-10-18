<?php

namespace ArgentCrusade\Support\Menus;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

abstract class Menu
{
    /**
     * Get the menu actions.
     *
     * @return array
     */
    abstract public function actions();

    /**
     * Determines if given route is a current route.
     *
     * @param string $route
     *
     * @return bool
     */
    protected function isActiveRoute(string $route)
    {
        return Route::is($route);
    }

    /**
     * Determines current route's URI starts with given URI partial.
     * If $strict is TRUE, full equality will be checked.
     *
     * @param string $partial
     * @param bool   $strict  = false
     *
     * @return bool
     */
    protected function isActiveUrl(string $partial, bool $strict = false)
    {
        $uri = '/'.ltrim(Route::current()->uri(), '/');

        if ($strict) {
            return $uri === $partial;
        }

        return Str::startsWith($uri, $partial);
    }
}
