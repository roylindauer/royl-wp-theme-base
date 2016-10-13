<?php
/**
 * Misc. helper functions for our Theme.
 */

namespace Royl\WpThemeBase;


/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 *
 */
if (!function_exists('of_get_option')) {
/**
 * Options Framework Get Option helper method
 * @param  [type]  $name    [description]
 * @param  boolean $default [description]
 * @return [type]           [description]
 */
    function of_get_option($name, $default = false)
    {
        $optionsframework_settings = get_option('optionsframework');
        
        // Gets the unique option id
        $option_name = $optionsframework_settings['id'];
        
        if (get_option($option_name)) {
            $options = get_option($option_name);
        }
            
        if (isset($options[$name])) {
            return $options[$name];
        } else {
            return $default;
        }
    }
}

/**
 * Retrieve object from class registry
 *
 * @param string $name name of object to retrieve from registry
 */
function get_instance($name)
{
    $registry = \Royl\WpThemeBase\Core\Registry::getInstance();
    return $registry->get($name);
}

/**
 * Wrapper function for registering an object to the class registry
 *
 * @param string $name key to register object as
 * @param Object $object The object to register, passed as reference
 */
function register_object($name, &$object)
{
    $registry = \Royl\WpThemeBase\Core\Registry::getInstance();
    $registry->set($name, $object);
}

if (!function_exists('pr')) {
/**
 * Pretty version of print_r, for debugging in the browser.
 *
 * @param mixed $var variable to print out
 */
    function pr($var = null)
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
}

if (!function_exists('debug')) {
/**
 * debug
 *
 * @param mixed $var
 */
    function debug($var = null)
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

/**
 * Helper method to render translated string for theme package.
 *
 * Helper method includes the domain to retrieve translated text.
 * This is technically optional in Wordpress,
 * but should be considered good practice to use.
 *
 * @param string $str The string to translate
 * @param string $domain lang domain to get translated string
 */
function __($str = '', $domain = false)
{
    // Default domain to theme
    if ($domain === false) {
        $domain = \Royl\WpThemeBase\Util\Configure::read('name');
    }

    return \__($str, $domain);
}
