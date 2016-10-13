<?php
/**
 * Utility class for working with Dates
 */

namespace Royl\WpThemeBase\Util;

/**
 * Utility class for working with Dates
 *
 * Methods are to be called statically.
 *
 * @package Royl\WpThemeBase\Util
 */
class Date
{
    /**
     * Render a date the right way (aka not the WordPress way)
     *
     * @param string $format date format
     * @param integer $timestamp timestamp to generate date from
     * @param string $timezone timezone to generate date from
     */
    public static function date($format = 'Y-m-d H:i:s', $timestamp = false, $timezone = false)
    {
        if ($timezone) {
            $tz = new \DateTimeZone($timezone);
        }

        $dt = new \DateTime('now', $tz);

        if ($timestamp) {
            $dt->setTimestamp($timestamp);
        }
        
        return $dt->format($format);
    }
}
