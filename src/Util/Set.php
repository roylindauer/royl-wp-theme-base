<?php
/**
 * Utility class for working with Arrays
 */

namespace Royl\WpThemeBase\Util;

/**
 * Utility class for working with Arrays
 *
 * Usage:
 * $result = \Ecs\Core\Utilities\Set::isArrayEmpty($array);
 *
 * Methods are to be called statically.
 *
 * @package Royl\WpThemeBase\Util
 */
class Set
{
    /**
     * Check if Multidim array is empty
     * Basically, if there is a value this thing needs to return false immediately.
     * @param  array        $array multidim array to check
     * @return boolean      Return true if empty, false if not
     */
    public static function isArrayEmpty($array)
    {
        if (empty($array)) {
            return true;
        }

        foreach ($array as $k => $arr) {
            if (is_array($arr)) {
                return self::isArrayEmpty($arr);
            } elseif (!empty($arr) || $arr !== false) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Returns the passed array, but re-keyed by the specified key
     *
     * @param array $array Array of items to rekey
     * @param string $keyName Key to rekey array by
     * @param string $keyValue Optional value key to assign to the key name
     * (instead of just assigning the entire item with all values)
     * @return array Returns rekeyed array of items
     **/
    public static function keyArrayBy($array, $keyName, $keyValue = null)
    {
        $rekeyed = array_reduce($array, function ($rekeyed, $item) use ($keyName, $keyValue) {
            // Make sure we're referencing the key by array but still preserve
            // actual item type
            $itemArray = (array) $item;

            if ($itemArray[$keyName]) {
                if ($keyValue) {
                    $rekeyed[$itemArray[$keyName]] = $itemArray[$keyValue];
                } else {
                    $rekeyed[$itemArray[$keyName]] = $item;
                }

                return $rekeyed;
            }

            // So much for that
            return null;
        }, array());

        return $rekeyed;
    }

    /**
     * PHP's default array_uintersect treats array1 as a master array, and will
     * always keep whatever is in array1 regardless of whether it's in the
     * others or not (it's not a true intersect).  This computes the actual
     * intersection of two arrays with no "master" array.
     *
     * @param array $array1 First array to intersect
     * @param array $array2 Second array to intersect
     * @return array Returns result of array intersection or null
     **/
    public static function arrayIntersect($array1, $array2)
    {
        return array_filter($array1, function ($a) use ($array2) {
            $found = array_filter($array2, function ($b) use ($a) {
                return ((int) $a->ID) === ((int) $b->ID);
            });

            return !empty($found);
        });
    }
}
