<?php

namespace ArgentCrusade\Support\Tests;

use Carbon\Carbon;

class HelperFunctionsTest extends TestCase
{
    public function testAdminAssetFunction()
    {
        $this->app['config']->set('support.assets.backend_host', 'admin.localhost');

        $expected = 'https://admin.localhost/css/vendor.css';
        $this->assertSame($expected, admin_asset('/css/vendor.css'));
    }

    public function testAppAssetFunction()
    {
        $this->app['config']->set('support.assets.frontend_host', 'assets.app.localhost');
        $expected = 'https://assets.app.localhost/css/vendor.css';

        $this->assertSame($expected, app_asset('/css/vendor.css'));
    }

    public function testIifFunction()
    {
        $this->assertSame('first', iif(true, 'first', 'second'));
        $this->assertSame('second', iif(false, 'first', 'second'));
    }

    public function testCarbonTimezoneFunction()
    {
        $first = Carbon::now('America/New_York');
        $this->assertSame('America/New_York', $first->timezoneName);
        $this->assertSame('America/New_York', carbon_timezone($first)->timezoneName);

        $this->app->instance('user.timezone', 'Asia/Novosibirsk');

        $second = Carbon::now('America/New_York');
        $this->assertSame('America/New_York', $second->timezoneName);
        $this->assertSame('Asia/Novosibirsk', carbon_timezone($second)->timezoneName);
    }

    public function testCarbonFormatFunction()
    {
        $format = trans('support::formats.dates.datetime');
        $date = Carbon::now();

        $expected = $date->format($format);
        $this->assertSame($expected, carbon_format($date, 'datetime'));
    }

    /**
     * @param Carbon $from
     * @param Carbon $till
     * @param string $expected
     * @dataProvider carbonDiffDataProvider
     */
    public function testCarbonDiffFunction(Carbon $from, Carbon $till, string $expected)
    {
        $this->assertSame($expected, carbon_diff($from, $till));
    }

    /**
     * @param int    $value
     * @param array  $choices
     * @param string $expected
     * @param bool   $prependWithValue
     * @dataProvider pluralizeDataProvider
     */
    public function testPluralizeFunction(int $value, array $choices, string $expected, bool $prependWithValue = true)
    {
        $this->assertSame($expected, pluralize($value, $choices, $prependWithValue));
    }

    public function carbonDiffDataProvider()
    {
        return [
            [
                'from' => Carbon::now()->subSeconds(5),
                'till' => Carbon::now(),
                'expected' => 'just now',
            ],
            [
                'from' => Carbon::now()->subSeconds(50),
                'till' => Carbon::now(),
                'expected' => '50 seconds ago',
            ],
            [
                'from' => Carbon::now()->subMinutes(5),
                'till' => Carbon::now(),
                'expected' => '5 minutes ago',
            ],
            [
                'from' => Carbon::now()->subHour(),
                'till' => Carbon::now(),
                'expected' => '1 hour ago',
            ],
            [
                'from' => Carbon::now()->subDays(3),
                'till' => Carbon::now(),
                'expected' => '3 days ago',
            ],
            [
                'from' => Carbon::now()->subMonth(),
                'till' => Carbon::now(),
                'expected' => '1 month ago',
            ],
            [
                'from' => Carbon::now()->subYears(15),
                'till' => Carbon::now(),
                'expected' => '15 years ago',
            ],
        ];
    }

    public function pluralizeDataProvider()
    {
        return [
            [
                'value' => 1,
                'choices' => ['человек', 'человека', 'человек'],
                'expected' => '1 человек',
            ],
            [
                'value' => 2,
                'choices' => ['человек', 'человека', 'человек'],
                'expected' => '2 человека',
            ],
            [
                'value' => 5,
                'choices' => ['человек', 'человека', 'человек'],
                'expected' => '5 человек',
            ],
            [
                'value' => 32,
                'choices' => ['человек', 'человека', 'человек'],
                'expected' => '32 человека',
            ],
            [
                'value' => 1,
                'choices' => ['человек', 'человека', 'человек'],
                'expected' => 'человек',
                'prependWithValue' => false,
            ],
        ];
    }
}
