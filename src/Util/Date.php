<?php

namespace Royl\WpThemeBase\Util;

/**
 * Utility class for working with Dates
 *
 * @package     WpThemeBase
 * @subpackage  Util
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Date
{
    /**
     * Render a date the right way (aka not the WordPress way)
     *
     * @param string   $format     date format
     * @param integer  $timestamp  timestamp to generate date from. If false, will use "now" for date
     * @param string   $timezone   timezone to generate date from
     */
    public static function format($format = 'Y-m-d H:i:s', $timestamp = false, $timezone = false)
    {
        if ($timezone) {
            $tz = new \DateTimeZone($timezone);
            $dt = new \DateTime('now', $tz);
        } else {
            $dt = new \DateTime('now');
        }

        if ($timestamp) {
            $dt->setTimestamp($timestamp);
        }
        
        return $dt->format($format);
    }
}
