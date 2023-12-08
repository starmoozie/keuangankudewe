<?php

if (!function_exists('rupiah')) {
    /**
     * Format number to rupiah
     * @param number $amount
     */
    function rupiah($amount): string
    {
        return number_format($amount, 0, ',' , '.');
    }
}

if (!function_exists('dateFormat')) {
    /**
     * Format date to Y-m-d
     * @param number $date
     */
    function dateFormat($date): string
    {
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }
}

if (!function_exists('rupiahToNumber')) {
    /**
     * number format
     * @param string
     */
    function rupiahToNumber($number_format): string
    {
        return str_replace('.', '', $number_format);
    }
}
