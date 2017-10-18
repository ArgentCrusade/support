<?php

namespace ArgentCrusade\Support\Tests;

use ArgentCrusade\Support\Providers\SupportServiceProvider;
use ArgentCrusade\Support\Tests\Migrations\CreateUsersTable;
use ArgentCrusade\Support\Tests\Stubs\HttpKernel;
use ArgentCrusade\Support\Tests\Stubs\User;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();

        (new CreateUsersTable())->up();

        $factory = app(Factory::class);
        $factory->define(User::class, function (Generator $faker) {
            return [
                'name' => $faker->name,
                'email' => $faker->safeEmail,
                'password' => bcrypt('secret'),
                'timezone' => $faker->timezone,
            ];
        });
    }

    protected function getPackageProviders($app)
    {
        return [SupportServiceProvider::class];
    }

    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', HttpKernel::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
