<?php

/**
 * helpers.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace {

    use Carbon\Carbon;

    /**
     * @param DateTime|null $dateTime
     *
     * @return string
     */
    function ec_to_datetime(DateTime $dateTime = null): string
    {
        if (!$dateTime) {
            return '';
        }

        return $dateTime->format(config('eclaims.format.web_service.date_time'));
    }

    /**
     * @param DateTime $dateTime
     *
     * @return string
     */
    function ec_to_date(DateTime $dateTime = null): string
    {
        if (!$dateTime) {
            return '';
        }

        return $dateTime->format(config('eclaims.format.web_service.date'));
    }

    /**
     * @param DateTime $dateTime
     *
     * @return string
     */
    function ec_to_time(DateTime $dateTime = null): string
    {
        if (!$dateTime) {
            return '';
        }

        return $dateTime->format(config('eclaims.format.web_service.time'));
    }

    /**
     * @param string $data
     *
     * @return Carbon
     */
    function ec_from_datetime(?string $data) : ?Carbon
    {
        if (!$data) {
            return null;
        }
        return Carbon::createFromFormat(config('eclaims.format.web_service.date_time'), $data);
    }

    /**
     * @param string $data
     *
     * @return Carbon
     */
    function ec_from_date(?string $data) : ?Carbon
    {
        if (!$data) {
            return null;
        }

        return Carbon::createFromFormat(config('eclaims.format.web_service.date'), $data);
    }

    /**
     * @param string $data
     *
     * @return Carbon
     */
    function ec_from_time(?string $data) : ?Carbon
    {
        if (!$data) {
            return null;
        }
        return Carbon::createFromFormat(config('eclaims.format.web_service.time'), $data);
    }


    /**
     * Returns the luhn check digit. Based on {@link https://github.com/odan/luhn}
     *
     * @param string $s numbers as string
     * @return int checksum digit
     *
     */
    function luhn($s)
    {
        // Add a zero check digit
        $s = $s . '0';
        $sum = 0;
        // Find the last character
        $i = strlen($s);
        $oddLength = $i % 2;
        // Iterate all digits backwards
        while ($i-- > 0) {
            // Add the current digit
            $sum+=$s[$i];
            // If the digit is even, add it again. Adjust for digits 10+ by subtracting 9.
            ($oddLength == ($i % 2)) ? ($s[$i] > 4) ? ($sum+=($s[$i] - 9)) : ($sum+=$s[$i]) : false;
        }
        return (10 - ($sum % 10)) % 10;
    }

    /**
     * Search array by filter and returns the index if found else -1
     * @param  array    $list   [description]
     * @param  function $filter [description]
     * @return [type]           [description]
     */
    function array_index(array $list, Callable $filter) : int
    {
        foreach($list as $key => $data) {
            $found = $filter($data);
            if ($found) {
                return $key;
            }
        }
        return -1;
    }

    /**
     * @param  mixed $value
     * @return string
     */
    function formatDbDate($value) {
        if (empty($value)) {
            return null;
        }

        $ts = normalizeDateValue(str_replace('-', '/', $value));
        if ($ts !== false) {
            return date('Ymd', $ts);
        } else {
            return null;
        }
    }

    /**
     * @param  mixed $value
     * @return string
     */
    function formatDbTime($value) {
        if (empty($value)) {
            return null;
        }
        $ts = normalizeDateValue($value);
        if ($ts !== false) {
            return date('His', $ts);
        } else {
            return null;
        }
    }

    /**
     * @param  mixed $value
     * @return string
     */
    function formatDbDatetime($value) {
        if (empty($value)) {
            return null;
        }

        $ts = normalizeDateValue(str_replace('-', '/', $value));
        if ($ts !== false) {
            return date('YmdHis', $ts);
        } else {
            return null;
        }
    }

    function normalizeDateValue($time)
    {
        if(is_string($time))
        {
            if(ctype_digit($time) || ($time{0}=='-' && ctype_digit(substr($time, 1))))
                return (int)$time;
            else
                return strtotime($time);
        }
        return (int)$time;
    }
}
