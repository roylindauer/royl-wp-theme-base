<?php

namespace Royl\WpThemeBase\Core;

/**
 * Class Registry
 *
 * Store and retreive Objects
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Registry
{

    /**
     * Store instance of $class
     * @var null
     */
    private static $instance = null;

    /**
     * Array of registered objects
     * @var array
     */
    private $registry = array();

    /**
     * Register an object
     * @param string $key   name of object to store
     * @param Object $value the object to store
     */
    public function set($key, $value)
    {
        if (isset($this->registry[$key])) {
            throw new \Exception("There is already an entry for key " . $key);
        }

        $this->registry[$key] = $value;
    }
 
    /**
     * Get object from registry
     * @param  string $key object to get
     * @return Object      returns an instance of $Object
     */
    public function get($key)
    {
        if (!isset($this->registry[$key])) {
            throw new \Exception("There is no entry for key " . $key);
        }

        return $this->registry[$key];
    }

    /**
     * Get instance of Registry class
     * @return Registry  registry class
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Registry();
        }

        return self::$instance;
    }
}
