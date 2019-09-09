<?php

namespace ArgentCrusade\Support\Tests;

use ArgentCrusade\Support\Providers\SupportServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [SupportServiceProvider::class];
    }
}
