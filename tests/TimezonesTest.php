<?php

namespace ArgentCrusade\Support\Tests;

use ArgentCrusade\Support\Tests\Stubs\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Routing\Router;

class TimezonesTest extends TestCase
{
    use DatabaseMigrations;

    const DEFAULT_TIMEZONE_URI = '/default-timezone';
    const USER_TIMEZONE_URI = '/user-timezone';

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['router']->get(static::DEFAULT_TIMEZONE_URI, \Closure::fromCallable([$this, 'requestHandler']));
        $app['router']->group(['middleware' => ['web']], function (Router $router) {
            $router->get(static::USER_TIMEZONE_URI, \Closure::fromCallable([$this, 'requestHandler']));
        });
    }

    public function testDefaultTimezone()
    {
        $expected = config('support.timezones.default');

        $this->json('GET', static::DEFAULT_TIMEZONE_URI)->assertExactJson(['timezone' => $expected]);
    }

    public function testOverridenDefaultTimezone()
    {
        $expected = 'Asia/Irkutsk';

        $this->assertSame('Europe/Moscow', config('support.timezones.default'));

        $this->json('GET', static::DEFAULT_TIMEZONE_URI.'?timezone='.$expected)
            ->assertExactJson(['timezone' => $expected]);
    }

    public function testUserTimezone()
    {
        $expected = 'Asia/Irkutsk';
        $expectedDefault = config('support.timezones.default');

        $this->actingAs(
            factory(User::class)->create(['timezone' => $expected])
        );

        $this->json('GET', static::USER_TIMEZONE_URI)->assertExactJson(['timezone' => $expected]);
        $this->json('GET', static::DEFAULT_TIMEZONE_URI)->assertExactJson(['timezone' => $expectedDefault]);
    }

    public function testOverridenUserTimezone()
    {
        $expected = 'Asia/Novosibirsk';
        $userTimezone = 'Asia/Irkutsk';

        $this->actingAs(
            factory(User::class)->create(['timezone' => $userTimezone])
        );

        $this->json('GET', static::USER_TIMEZONE_URI)->assertExactJson(['timezone' => $userTimezone]);
        $this->json('GET', static::USER_TIMEZONE_URI.'?timezone='.$expected)->assertExactJson(['timezone' => $expected]);
    }

    public function requestHandler()
    {
        $now = Carbon::now();

        return response()->json([
            'timezone' => carbon_timezone($now)->timezoneName,
        ]);
    }
}
