<?php

namespace ArgentCrusade\Support\Http\Middleware;

use Closure;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (is_null($user = $request->user())) {
            return $next($request);
        }

        app()->setLocale($user->locale);

        return $next($request);
    }
}
