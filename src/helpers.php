<?php

use Carbon\Carbon;
use Carbon\CarbonInterface;

if (!function_exists('iif')) {
    /**
     * Returns second or third operand depending on $condition.
     *
     * @param bool|mixed $condition
     * @param mixed      $truthy
     * @param mixed      $falsy
     *
     * @return mixed
     */
    function iif($condition, $truthy, $falsy = '')
    {
        return $condition ? $truthy : $falsy;
    }
}

if (!function_exists('carbon_timezone')) {
    /**
     * Sets timezone of given Carbon instance to user's timezone.
     *
     * @param \DateTimeInterface $date
     *
     * @return \DateTimeInterface|CarbonInterface
     */
    function carbon_timezone(DateTimeInterface $date)
    {
        if (!app()->bound('user.timezone')) {
            return $date;
        }

        return $date->setTimezone(app('user.timezone'));
    }
}

if (!function_exists('carbon_format')) {
    /**
     * Formats given Carbon instance in a desired format.
     *
     * @param \DateTimeInterface $date
     * @param string             $formatType = 'default'
     *
     * @return string
     */
    function carbon_format(DateTimeInterface $date, string $formatType = 'default')
    {
        return carbon_timezone($date)->format(
            trans('support::formats.dates.'.$formatType)
        );
    }
}

if (!function_exists('carbon_diff')) {
    /**
     * Returns human-friendly localized time difference according to the current time.
     *
     * @param \DateTimeInterface $date
     *
     * @return string
     */
    function carbon_diff(DateTimeInterface $date)
    {
        $now = Carbon::now();
        $diff = carbon_timezone($now)->diff(
            carbon_timezone($date)
        );

        $values = [
            'years' => intval($diff->format('%y')),
            'months' => intval($diff->format('%m')),
            'days' => intval($diff->format('%a')),
            'hours' => intval($diff->format('%h')),
            'minutes' => intval($diff->format('%i')),
        ];

        $isFuture = carbon_timezone($now)->timestamp < carbon_timezone($date)->timestamp;
        $format = 'support::formats.date_plurals.'.($isFuture ? 'future' : 'past');

        foreach ($values as $period => $value) {
            if ($value > 0) {
                return trans($format, [
                    'value' => pluralize($value, trans('support::formats.date_plurals.'.$period)),
                ]);
            }
        }

        $seconds = intval($diff->format('%s'));

        if ($seconds < 10) {
            return trans('support::formats.date_plurals.just_now');
        }

        return trans($format, [
            'value' => pluralize($seconds, trans('support::formats.date_plurals.seconds')),
        ]);
    }
}

if (!function_exists('pluralize')) {
    /**
     * Picks correct plural variation from given choices.
     *
     * @param int   $value
     * @param array $choices
     * @param bool  $prependWithValue
     *
     * @return string
     */
    function pluralize(int $value, array $choices, bool $prependWithValue = true)
    {
        $word1 = $choices[0];
        $word2 = $choices[1];
        $word5 = $choices[2];

        $n = $value % 100;

        if ($n >= 5 && $n <= 20) {
            return ($prependWithValue ? $value.' ' : '').$word5;
        }

        if ($n > 19) {
            $n = $n % 10;
        }

        if ($n > 4 || $n === 0) {
            return ($prependWithValue ? $value.' ' : '').$word5;
        } elseif ($n === 1) {
            return ($prependWithValue ? $value.' ' : '').$word1;
        }

        return ($prependWithValue ? $value.' ' : '').$word2;
    }
}
