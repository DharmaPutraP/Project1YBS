<?php

if (!function_exists('format_number_negative')) {
    /**
     * Format number with parentheses for negative values
     * 
     * @param mixed $value
     * @param int $decimals
     * @return string
     */
    function format_number_negative($value, $decimals = 2)
    {
        if ($value === null) {
            return '-';
        }

        if ($value < 0) {
            return '(' . number_format(abs($value), $decimals) . ')';
        }

        return number_format($value, $decimals);
    }
}
