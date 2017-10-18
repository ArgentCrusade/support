<?php

namespace ArgentCrusade\Support\Http\Middleware;

use Closure;

class SetDefaultTimezone
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
        $defaultTimezone = config('support.timezones.default');

        app()->instance('user.timezone', $request->input('timezone', $defaultTimezone));

        return $next($request);
    }
}
