<?php

use Carbon\Carbon;

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
     * @param Carbon $date
     *
     * @return Carbon
     */
    function carbon_timezone(Carbon $date)
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
     * @param Carbon $date
     * @param string $formatType = 'default'
     *
     * @return string
     */
    function carbon_format(Carbon $date, string $formatType = 'default')
    {
        return carbon_timezone($date)->format(
            trans('support::formats.dates.'.$formatType)
        );
    }
}

if (!function_exists('carbon_diff')) {
    /**
     * Returns human-friendly localized time difference.
     * If $till is null, Carbon::now() will be used.
     *
     * @param Carbon      $from
     * @param Carbon|null $till
     *
     * @return string
     */
    function carbon_diff(Carbon $from, Carbon $till = null)
    {
        $till = $till ?? Carbon::now();
        $diff = carbon_timezone($till)->diff(
            carbon_timezone($from)
        );

        $values = [
            'years' => intval($diff->format('%y')),
            'months' => intval($diff->format('%m')),
            'days' => intval($diff->format('%a')),
            'hours' => intval($diff->format('%h')),
            'minutes' => intval($diff->format('%i')),
            'seconds' => intval($diff->format('%s')),
        ];

        $ago = trans('support::common.ago');

        if ($values['years'] > 0) {
            return pluralize($values['years'], trans('support::formats.date_plurals.years')).' '.$ago;
        } elseif ($values['months'] > 0) {
            return pluralize($values['months'], trans('support::formats.date_plurals.months')).' '.$ago;
        } elseif ($values['days'] > 0) {
            return pluralize($values['days'], trans('support::formats.date_plurals.days')).' '.$ago;
        } elseif ($values['hours'] > 0) {
            return pluralize($values['hours'], trans('support::formats.date_plurals.hours')).' '.$ago;
        } elseif ($values['minutes'] > 0) {
            return pluralize($values['minutes'], trans('support::formats.date_plurals.minutes')).' '.$ago;
        }

        if ($values['seconds'] < 10) {
            return trans('support::formats.date_plurals.just_now');
        }

        return pluralize($values['seconds'], trans('support::formats.date_plurals.seconds')).' '.$ago;
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
        }

        if ($n === 1) {
            return ($prependWithValue ? $value.' ' : '').$word1;
        }

        return ($prependWithValue ? $value.' ' : '').$word2;
    }
}
