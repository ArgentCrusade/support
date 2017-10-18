<?php

return [
    'assets' => [
        'frontend_host' => env('FRONTEND_ASSETS_HOST'),
        'backend_host' => env('BACKEND_ASSETS_HOST'),
    ],

    'timezones' => [
        'default' => env('DEFAULT_USER_TIMEZONE', 'Europe/Moscow'),
    ],

    'deployments' => [
        'hostname' => env('APP_HOSTNAME', gethostname()),

        'cache_tags' => [
            'purge-on-deploys',
        ],

        'notifications' => [
            'channel' => env('DEPLOYMENT_NOTIFICATIONS_CHANNEL', 'telegram'),
            'receiver' => env('DEPLOYMENT_NOTIFICATIONS_RECEIVER'),
            'success' => ArgentCrusade\Support\Notifications\Deployments\SuccessDeployment::class,
            'failed' => ArgentCrusade\Support\Notifications\Deployments\FailedDeployment::class,
        ],
    ],

    'dev-server' => [
        'host' => env('DEV_SERVER_HOST'),
        'port' => env('DEV_SERVER_PORT'),
    ],
];
