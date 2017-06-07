<?php

namespace Royl\WpThemeBase\Util;

/**
 * Theme Debug Helpers
 *
 * @package     WpThemeBase
 * @subpackage  Util
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Debug
{
    
    /**
     * Prettier version of print_r
     *
     * @param mixed $var variable to print out
     */
    public static function pr($var = null)
    {
        if (PHP_SAPI === 'cli') {
            echo str_repeat('-', 50) . "\n";
            print_r($var);
            echo str_repeat('-', 50) . "\n\n";
        } else {
            echo '<pre>';
            print_r($var);
            echo '</pre>';
        }
    }

    /**
     * debug
     *
     * @param mixed $var
     */
    public static function debug($var = null)
    {
        $backtrace = debug_backtrace();
        $file = '.'.str_replace($_SERVER['DOCUMENT_ROOT'], '', $backtrace[0]['file']);

        if (PHP_SAPI === 'cli') {
            echo str_repeat('-', 50) . "\n";
            echo 'DEBUG: file: '.$file.' - line: '.$backtrace[0]['line'] . "\n\n";
            var_dump($var);
            echo str_repeat('-', 50) . "\n\n";
        } else {
            echo '<div class="__theme_debug">';
            echo '<p>DEBUG: file: '.$file.' - line: '.$backtrace[0]['line'].'</p>';
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
            echo '</div>';
        }
    }
    
    public static function log($message='') {
        error_log($message, 4);
    }
    
    /**
     * Adds theme specific messages to the global theme WP_Error object.
     *
     * Takes the theme name as $code for the WP_Error object.
     * Merges old $data and new $data arrays @uses wp_parse_args().
     *
     * @param  (string)  $message
     * @param  (mixed)   $data_key
     * @param  (mixed)   $data_value
     * @return WP_Error|Boolean
     */
    public static function addThemeError($message, $data_key = '', $data_value = '')
    {
        global $wp_theme_error, $wp_theme_error_code;

        if (!isset($wp_theme_error_code)) {
            $theme_data = wp_get_theme();
            $name = str_replace(' ', '', strtolower($theme_data['Name']));
            $wp_theme_error_code = preg_replace("/[^a-zA-Z0-9\s]/", '', $name);
        }

        if (!is_wp_error($wp_theme_error) || !$wp_theme_error) {
            $data[$data_key] = $data_value;
            $wp_theme_error = new \WP_Error($wp_theme_error_code, $message, $data);
            return $wp_theme_error;
        }

        // merge old and new data
        $old_data = $wp_theme_error->get_error_data($wp_theme_error_code);
        $new_data[$data_key] = $data_value;
        $data = wp_parse_args($new_data, $old_data);

        return $wp_theme_error->add($wp_theme_error_code, $message, $data);
    }
}
