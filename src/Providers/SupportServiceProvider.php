<?php

namespace ArgentCrusade\Support\Providers;

use ArgentCrusade\Support\Console\Commands\ArtisanServeCommand;
use ArgentCrusade\Support\Console\Commands\ConfigureApp;
use ArgentCrusade\Support\Console\Commands\SendDeploymentResultNotification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ConfigureApp::class,
                SendDeploymentResultNotification::class,
            ]);

            if (!$this->isLumen()) {
                $this->commands([ArtisanServeCommand::class]);
            }
        }

        $this->publishes([
            realpath(__DIR__.'/../../config/support.php') => config_path('support.php'),
            realpath(__DIR__.'/../../resources/lang') => resource_path('lang/vendor/support'),
        ]);

        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/support.php'), 'support');
        $this->loadTranslationsFrom(realpath(__DIR__.'/../../resources/lang'), 'support');
    }

    protected function isLumen()
    {
        return Str::contains($this->app->version(), 'Lumen');
    }
}
