<?php

namespace ArgentCrusade\Support\Configurator;

class AssetsConfiguration extends AbstractConfiguration
{
    protected $env = [
        'frontend_host' => 'FRONTEND_ASSETS_HOST',
        'backend_host' => 'BACKEND_ASSETS_HOST',
    ];

    public function name()
    {
        return 'Assets';
    }

    public function configure()
    {
        $this->setConfig('frontend_host', $this->command->ask('Enter frontend assets host'))
            ->setConfig('backend_host', $this->command->ask('Enter backend assets host'));
    }
}
