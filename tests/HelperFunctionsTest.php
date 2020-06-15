<?php

namespace ArgentCrusade\Support\Tests;

use Carbon\Carbon;

class HelperFunctionsTest extends TestCase
{
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
     * @param Carbon $date
     * @param string $expected
     * @dataProvider carbonDiffDataProvider
     */
    public function testCarbonDiffFunction(Carbon $date, string $expected)
    {
        $this->assertSame($expected, carbon_diff($date));
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
            // Past
            [
                'date' => Carbon::now()->subSeconds(5),
                'expected' => 'just now',
            ],
            [
                'date' => Carbon::now()->subSeconds(50),
                'expected' => '50 seconds ago',
            ],
            [
                'date' => Carbon::now()->subMinutes(5),
                'expected' => '5 minutes ago',
            ],
            [
                'date' => Carbon::now()->subHour(),
                'expected' => '1 hour ago',
            ],
            [
                'date' => Carbon::now()->subDays(3),
                'expected' => '3 days ago',
            ],
            [
                'date' => Carbon::now()->subMonth(),
                'expected' => '1 month ago',
            ],
            [
                'date' => Carbon::now()->subYears(15),
                'expected' => '15 years ago',
            ],

            // Future
            // For some reason Carbon's add* methods are missing
            // one second, therefore extra ->addSecond() call.
            [
                'date' => Carbon::now()->addSeconds(50)->addSecond(),
                'expected' => 'In 50 seconds',
            ],
            [
                'date' => Carbon::now()->addMinutes(5)->addSecond(),
                'expected' => 'In 5 minutes',
            ],
            [
                'date' => Carbon::now()->addHour()->addSecond(),
                'expected' => 'In 1 hour',
            ],
            [
                'date' => Carbon::now()->addDays(3)->addSecond(),
                'expected' => 'In 3 days',
            ],
            [
                'date' => Carbon::now()->addMonth()->addSecond(),
                'expected' => 'In 1 month',
            ],
            [
                'date' => Carbon::now()->addYears(15)->addSecond(),
                'expected' => 'In 15 years',
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
