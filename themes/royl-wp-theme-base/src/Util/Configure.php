<?php

namespace Royl\WpThemeBase\Util;

/**
 * Configure class for handling theme configurations.
 *
 * Provides dot notation for retrieving multidimensional config data
 *
 * Usage:
 * \Ecs\Core\Utilities\Configure::write('key', 'value');
 * $val = \Ecs\Core\Utilities\Configure::read('key');
 *
 * \Ecs\Core\Utilities\Configure::write('data', ['test' => 'value']);
 * $val = \Ecs\Core\Utilities\Configure::read('data.test');
 *
 * @package     WpThemeBase
 * @subpackage  Util
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
final class Configure
{
    /**
     * Array of configure data.
     *
     * @param $config array
     */
    protected static array $config = [];

    /**
     * Set $config. Used to initialize the Configure object
     *
     * @param array $config
     * @todo refactor, remove this method and use write()
     */
    public static function set(array $config = []): void
    {
        self::$config = array_merge(self::$config, $config);
    }

    /**
     * Write config data
     *
     * @todo refactor, support passing an array of config data
     */
    public static function write(string $key, array|string $val = ''): void
    {
        self::setConfig($key, $val);
    }

    /**
     * Read config data from the array
     *
     * @todo refactor, enhance to support dot notation (Configure::read('my.config.data'))
     */
    public static function read($key = false)
    {
        if ($key) {
            $key = explode('.', $key);
            return self::getConfig($key, self::$config);
        }

        return self::$config;
    }

    /**
     * Handle the dot notation to set a config value to the config array
     */
    private static function setConfig($index, $val): void
    {
        $link =& self::$config;

        if (!empty($index)) {
            $keys = explode('.', $index);

            if (count($keys) == 1) {
                self::$config[$index] = $val;
            } else {
                foreach ($keys as $key) {
                    if (!isset(self::$config[$key])) {
                        $link[$key] = [];
                    }

                    $link =& $link[$key];
                }

                $link = $val;
            }
        }
    }

    /**
     * Handle the dot notation to get a config value from the config array
     */
    private static function getConfig($index, $config)
    {
        $current_index = $index;
        if (is_array($index) && count($index)) {
            $current_index = array_shift($index);
        }

        if (is_array($index) &&
            count($index) &&
            isset($config[$current_index]) &&
            is_array($config[$current_index]) &&
            count($config[$current_index])) {
            return self::getConfig($index, $config[$current_index]);
        } else {
            return (!empty($config[$current_index])) ? $config[$current_index] : false;
        }
    }
}
