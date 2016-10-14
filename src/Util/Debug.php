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
class Debug {
    
    /**
     * Prettier version of print_r
     *
     * @param mixed $var variable to print out
     */
    public function pr($var = null)
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
    public function debug($var = null)
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
}