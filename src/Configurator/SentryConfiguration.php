<?php

namespace ArgentCrusade\Support\Configurator;

class SentryConfiguration extends AbstractConfiguration
{
    protected $env = [
        'sentry_dsn' => 'SENTRY_DSN',
        'raven_dsn' => 'RAVEN_DSN',
    ];

    public function name()
    {
        return 'Sentry';
    }

    public function configure()
    {
        $this->setConfig('sentry_dsn', $this->command->ask('Enter private Sentry DSN'))
            ->setConfig('raven_dsn', $this->command->ask('Enter public Sentry DSN (for raven.js)'));
    }
}
