<?php

namespace ArgentCrusade\Support\Configurator;

class SelectelBackupsConfiguration extends SelectelConfiguration
{
    protected $env = [
        'username' => 'SELECTEL_BACKUPS_USERNAME',
        'password' => 'SELECTEL_BACKUPS_PASSWORD',
        'container' => 'SELECTEL_BACKUPS_CONTAINER',
        'container_url' => 'SELECTEL_BACKUPS_CONTAINER_URL',
    ];

    public function name()
    {
        return 'Selectel Backups';
    }

    protected function shouldAskContainerUrl()
    {
        return false;
    }
}
