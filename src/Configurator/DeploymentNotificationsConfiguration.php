<?php

namespace ArgentCrusade\Support\Configurator;

class DeploymentNotificationsConfiguration extends AbstractConfiguration
{
    protected $env = [
        'channel' => 'DEPLOYMENT_NOTIFICATIONS_CHANNEL',
        'receiver' => 'DEPLOYMENT_NOTIFICATIONS_RECEIVER',
    ];

    public function name()
    {
        return 'Deployment Notifications';
    }

    public function configure()
    {
        $this->setConfig('channel', 'telegram');

        if (!$this->command->confirm('Do you want to use Telegram as default channel for deployment notifications?', true)) {
            $this->setConfig('channel', $this->command->ask('Enter notification channel for deloyment notifications'));
        } elseif ($this->command->confirm('Do you want to update your Telegram Bot Token?', env('TELEGRAM_BOT_TOKEN') === '')) {
            $this->env['token'] = 'TELEGRAM_BOT_TOKEN';
            $this->setConfig('token', $this->command->ask('Enter new Telegram Bot Token'));
        }

        $this->setConfig(
            'receiver',
            $this->command->ask('Enter notifications receiver ID (channel: '.$this->getConfig('channel').')')
        );
    }
}
