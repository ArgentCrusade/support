<?php

namespace ArgentCrusade\Support\Tests\Stubs;

use ArgentCrusade\Support\Http\Middleware\SetDefaultTimezone;
use ArgentCrusade\Support\Http\Middleware\SetUserTimezone;
use Orchestra\Testbench\Http\Kernel;

class HttpKernel extends Kernel
{
    /**
     * The application's middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Orchestra\Testbench\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        SetDefaultTimezone::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Orchestra\Testbench\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            SetUserTimezone::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];
}
