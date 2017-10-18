<?php

namespace ArgentCrusade\Support\Console\Commands;

use ArgentCrusade\Support\Configurator\AbstractConfiguration;
use ArgentCrusade\Support\Configurator\AssetsConfiguration;
use ArgentCrusade\Support\Configurator\DeploymentNotificationsConfiguration;
use ArgentCrusade\Support\Configurator\SelectelBackupsConfiguration;
use ArgentCrusade\Support\Configurator\SelectelConfiguration;
use ArgentCrusade\Support\Configurator\SentryConfiguration;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ConfigureApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:configure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configures application.';

    /**
     * Default configurations.
     *
     * @var array
     */
    protected $defaultConfigurations = [
        DeploymentNotificationsConfiguration::class,
        AssetsConfiguration::class,
        SelectelConfiguration::class,
        SelectelBackupsConfiguration::class,
        SentryConfiguration::class,
    ];

    /**
     * Available configurations.
     *
     * @var array
     */
    protected $configurations = [
        //
    ];

    /**
     * Configuration values for .env file patching.
     *
     * @var array
     */
    protected $envPatch = [
        //
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->laravel->environment() === 'production') {
            $this->error('You can not configure application in production mode.');

            return;
        }

        $envFile = '.env';

        $configurations = array_unique(
            array_merge($this->defaultConfigurations, $this->configurations)
        );

        collect($configurations)->each(function (string $configuration) {
            /** @var AbstractConfiguration $instance */
            $instance = new $configuration($this);

            if (!method_exists($instance, 'configure') || !$instance->shouldConfigure()) {
                return;
            }

            $this->laravel->call([$instance, 'configure']);
            $this->saveIntermediateEnv($instance->getEnvValues());
        });

        $rows = $this->envTableRows();

        if (!count($rows)) {
            $this->info('No variables were updated. Your '.$envFile.' file left untouched.');

            return;
        }

        $this->info('Your .env file will be patched with given values:');
        $this->table(['Variable', 'Old Value', 'New Value'], $rows);

        if (!$this->confirm('Save your new '.$envFile.' file?')) {
            $this->comment('Ok, your '.$envFile.' file left untouched.');

            return;
        }

        $this->updateEnvFile($envFile);

        $this->info('Application configured.');
    }

    protected function saveIntermediateEnv(array $values)
    {
        $this->envPatch = array_merge($this->envPatch, $values);
    }

    protected function envTableRows()
    {
        return collect($this->envPatch)
            ->map(function ($value, string $env) {
                if (env($env) === $value) {
                    return null;
                }

                return [$env, env($env), $value];
            })
            ->reject(null);
    }

    protected function updateEnvFile(string $filename = '.env')
    {
        $env = base_path($filename);

        if (!file_exists($env)) {
            touch($env);
        }

        $contents = file_get_contents($env);

        collect($this->envPatch)->each(function (string $value, string $var) use (&$contents) {
            $find = $var.'='.env($var, '');
            $replace = $var.'='.$value;

            if (Str::contains($contents, $find)) {
                $contents = str_replace($find, $replace, $contents);
            } else {
                $contents.= "\n".$replace;
            }
        });

        file_put_contents($env, $contents);
    }
}
