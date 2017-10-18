<?php

namespace ArgentCrusade\Support\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class SendDeploymentResultNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deployment:notify {result}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends deployment result notification.';

    /**
     * @var array
     */
    protected $allowedResults = [
        'success', 'failed',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $result = $this->argument('result');

        if (!in_array($result, $this->allowedResults)) {
            $this->error('Unable to send notification: unknown result "'.$result.'".');

            return;
        }

        $notification = app(
            config('support.deployments.notifications.'.$result)
        );

        if (is_null($notification) || !($notification instanceof Notification)) {
            $this->error('Notification class for "'.$result.'" result type is not configured.');
        }

        $channel = config('support.deployments.notifications.channel');
        $receiver = config('support.deployments.notifications.receiver');

        $this->info('Sending "'.$result.'" result type notification to '.$receiver.' via '.$channel);

        NotificationFacade::route($channel, $receiver)->notify($notification);
    }
}
