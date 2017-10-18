<?php

namespace ArgentCrusade\Support\Configurator;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

abstract class AbstractConfiguration
{
    /**
     * @var Command
     */
    protected $command;

    /**
     * @var array
     */
    protected $configs = [];

    /**
     * @var array
     */
    protected $env = [];

    /**
     * AbstractConfiguration constructor.
     *
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    abstract public function name();

    /**
     * Determines if current configuration should be run.
     *
     * @return bool
     */
    public function shouldConfigure(): bool
    {
        $name = Str::lower($this->name());

        if (!$this->command->confirm('Do you want to configure '.$name.'?', true)) {
            $this->command->comment('Ok, '.$name.' won\'t be configured.');

            return false;
        }

        $this->command->comment('Configuring '.$name.'.');

        return true;
    }

    /**
     * Get the config value.
     *
     * @param string $name
     * @param null   $default
     *
     * @return mixed|null
     */
    public function getConfig(string $name, $default = null)
    {
        return $this->configs[$name] ?? $default;
    }

    /**
     * Get all ENV values.
     *
     * @return array
     */
    public function getEnvValues()
    {
        $values = [];

        foreach ($this->env as $config => $env) {
            $values[$env] = $this->getConfig($config, '');
        }

        return $values;
    }

    /**
     * Set config value.
     *
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    protected function setConfig(string $name, string $value)
    {
        $this->configs[$name] = $value;

        return $this;
    }
}
