<?php

namespace ArgentCrusade\Support\Configurator;

class SelectelConfiguration extends AbstractConfiguration
{
    /**
     * @var array
     */
    protected $env = [
        'username' => 'SELECTEL_USERNAME',
        'password' => 'SELECTEL_PASSWORD',
        'container' => 'SELECTEL_CONTAINER',
        'container_url' => 'SELECTEL_CONTAINER_URL',
    ];

    public function name()
    {
        return 'Selectel Cloud Storage';
    }

    protected function shouldAskContainerUrl()
    {
        return true;
    }

    public function configure()
    {
        $this->setConfig('username', $this->command->ask('Enter Selectel Username'))
            ->setConfig('password', $this->command->secret('Enter Selectel Password'))
            ->setConfig('container', $this->command->ask('Enter Container Name'));

        if ($this->shouldAskContainerUrl() && $this->command->confirm('Do you want to set container URL?')) {
            $this->setConfig('container_url', $this->command->ask('Enter container URL'));
        }
    }
}
