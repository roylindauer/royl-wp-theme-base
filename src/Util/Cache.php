<?php

namespace Royl\WpThemeBase\Util;

/**
 * Cache using wp_transients
 *
 * @package     WpThemeBase
 * @subpackage  Util
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Cache
{
    const CACHE_TIMEOUT = 3600; // Value in seconds

    public $key = false;
    public $expiration = false;

    /**
     * Constructor
     *
     * @param string  $key        Name of the cache store
     * @param boolean $expiration Optional expiration value
     */
    public function __construct($key, $expiration = false)
    {
        $this->key = $key;
        $this->expiration = ($expiration !== false) ? $expiration : self::CACHE_TIMEOUT;
    }

    /**
     * Get cache
     *
     * @return mixed Returns cached value if it exists or false if not
     **/
    public function read()
    {
        if (!$this->key) {
            return false;
        }

        return get_transient($this->key);
    }

    /**
     * Write cache
     *
     * @param  mixed    $data  Data to cache
     * @return boolean  Returns true/false on cache set success/fail
     **/
    public function write($data = '')
    {
        if (!$this->key) {
            return false;
        }

        if (!$this->expiration) {
            return false;
        }

        return set_transient($this->key, $data, $this->expiration);
    }

    /**
     * Destroy cache
     *
     * @param  string  $key  The cache to destroy
     * @return boolean
     */
    public function destroy()
    {
        if (!$this->key) {
            return false;
        }

        return delete_transient($this->key);
    }
}
