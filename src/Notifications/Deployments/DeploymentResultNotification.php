<?php

namespace ArgentCrusade\Support\Notifications\Deployments;

use ArgentCrusade\Support\AppRevision;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

abstract class DeploymentResultNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification content.
     *
     * @return string
     */
    abstract public function content();

    /**
     * Get replacements for notification content.
     *
     * @return array
     */
    protected function replacements()
    {
        return [
            'app' => config('app.name'),
            'env' => config('app.env'),
            'hostname' => config('support.deployments.hostname'),
            'revision' => AppRevision::get(),
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the Telegram representation of the notification.
     *
     * @return TelegramMessage
     */
    public function toTelegram()
    {
        return TelegramMessage::create()
            ->content($this->content())
            ->button(trans('support::deployments.notifications.button_label'), url('/'));
    }
}
