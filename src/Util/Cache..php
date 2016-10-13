<?php
/**
 * Utility class for working with Cache (Query Caching, etc)
 */

namespace Royl\WpThemeBase\Util;

/**
 * Utility class for working with Cache (Query Caching, etc)
 *
 * Methods are to be called statically.
 *
 * @package Royl\WpThemeBase\Util
 */
final class Cache
{
    /**
     * @todo Make this configurable, add expiration functionality again
     */
    const CACHE_TIMEOUT = 60; // Minutes

    /**
     * Given an array of arguments, check for cached query for the args (args
     * will be converted to a unique hash).  Args are assumed to be valid args
     * for a WP_Query query.
     *
     * @param array $args Args to check for query with
     * @param string $prefix Prefix to use when pulling cached query (default:
     * 'query')
     * @return mixed Returns cached value if it exists or null if not
     **/
    public static function getCachedQuery($args, $prefix = 'query')
    {
        // Generate unique hash for the args - if there are no args, just hash
        // the prefix
        if ($args && !empty($args)) {
            $hash = self::arrayHash($args);
        } else {
            $hash = hash('md4', $prefix);
        }

        // Then pull the cached value
        $cached = get_option('query_cache_' . $prefix . '_' . $hash);

        return $cached ? $cached : null;
    }

    /**
     * Given an array of args used to generate a unique WP_Query, generate
     * cached version of query result.
     *
     * @param array $args Query args used to generate query with
     * @param mixed $data Data to cache
     * @param string $prefix Prefix to use when setting cache (default: 'query')
     * @param int $expire Expiration time for cached value in minutes.
     * Defaults to value in CACHE_TIMEOUT.
     * @return bool Returns true/false on cache set success/fail
     **/
    public static function setCachedQuery($args, &$data, $prefix = 'query', $expire = null)
    {
        if (!$expire) {
            $expire = self::CACHE_TIMEOUT;
        }

        // Generate unique hash for the args - if there are no args, just hash
        // the prefix
        if ($args && !empty($args)) {
            $hash = self::arrayHash($args);
        } else {
            $hash = hash('md4', $prefix);
        }

        // And set the cached value.  This isn't using Transients due to issues
        // with larger values and autoloading.
        delete_option('query_cache_' . $prefix . '_' . $hash);
        add_option('query_cache_' . $prefix . '_' . $hash, $data, null, 'no');
    }

    /**
     * Retrieve the current list of cached queries.
     *
     * @param string $prefix Prefix to use when pulling cached query (default:
     * 'query')
     * @return array Returns array of names of queries that are currently
     * cached
     **/
    public static function getCachedQueryNames($prefix = 'query')
    {
        global $wpdb;

        $queries = $wpdb->get_results(sprintf(
            "SELECT option_name AS name
            FROM {$wpdb->OPTIONS}
            WHERE option_name LIKE '%%query_cache_%s_%%'",
            $prefix
        ));

        if (!empty($queries)) {
            $names = array_map(function ($item) {
                return $item->name;
            }, $queries);
        }

        return empty($queries) ? null : $names;
    }

    /**
     * Clear query-specific caching (used for caching queries used in certain
     * API endpoint calls)
     *
     * @param string $prefix Prefix to clear query caches under (default:
     * 'query')
     * @return boolean Returns true/false on cache clear/no clear
     */
    public static function clearQueryCaching($prefix = 'query')
    {
        // Pull in the collection of current query caches
        $queries = self::getCachedQueryNames($prefix);

        // And blow them all out
        if (!empty($queries)) {
            foreach ($queries as $query) {
                delete_option($query);
            }

            return true;
        }

        // Well something didn't work out
        return false;
    }


    ///////////////////////////////////////////////////////////////////////////
    // Utility functions //////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////


    /**
     * Simple function for creating a unique hash from a passed array of args. This
     * is used for determining the uniqueness of a query.  For speed reasons this
     * also means you shouldn't pass giant arrays to this.  Will recurse through
     * nested arrays as necessary.  Note that this does NOT guarantee uniqueness.
     *
     * @param array $args Args to generate hash from
     * @return string Returns unique hashed string from arg array or empty string
     * if something explodes
     **/
    private static function arrayHash(&$args)
    {
        $string = self::arrayToString($args);

        return empty($string) ? '' : hash('md4', $string);
    }

    /**
     * Function for recursing through an array and returning all values as a string.
     *
     * @param array $args Args to generate hash from
     * @param string $parentKey parent key
     * @return string Returns concatted string of all array properties or empty
     * string if something explodes
     **/
    private static function arrayToString(&$args, $parentKey = null)
    {
        $string = '';

        foreach ($args as $key => $value) {
            if (is_array($value) || is_object($value)) {
                // Force objects to arrays before passing
                $value = (array) $value;

                // And recurse
                $string .= self::arrayToString($value, $key);
            } else {
                $string .= ($parentKey ? $parentKey : $key) . (string) $value;
            }
        }

        return empty($string) ? '' : $string;
    }
}
