<?php

use Carbon\Carbon;

/**
 * Format a given datetime to "12 Nov 2024 11:12 AM"
 *
 * @param  string  $dateTime
 * @return string
 */
if (! function_exists('formatDateTime')) {
    function formatDateTime($dateTime)
    {
        return Carbon::parse($dateTime)->format('d M Y h:i A');
    }
}
