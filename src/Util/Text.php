<?php

/**
 * Utility class for working with text
 */
namespace Royl\WpThemeBase\Util;

/**
 * Utility class for working with text
 *
 * @package Royl\WpThemeBase\Util
 */
class Text
{
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
    public static function translate($str = '', $domain = false)
    {
        // Default domain to theme
        if ($domain === false) {
            $domain = \Royl\WpThemeBase\Util\Configure::read('name');
        }

        return \__($str, $domain);
    }

    /**
     * truncateText()
     * truncate long strings of text. Optionall include a suffix (eg: ...)
     * Total string length will be $len + strlen($suffix), so a 50 char string will actually
     * by 47 plus a 3 char suffix if a 3 char suffix is included.
     *
     * Usage:
     *
     * $str = "This is my string!";
     * $str = \Ecs\Core\Utilties\Text::truncateText($str, 7, '...');
     * // returns "This is..."
     *
     * @param  string   $str, the string to truncate
     * @param  integer  $len, where to truncate the string
     * @param  string   $suffix, any text to include after the truncated string
     * @param  boolean  $safe, when true will strip html tags before truncating
     */
    public static function truncateText($str, $len = 50, $suffix = '...', $safe = false)
    {
        if ($safe === true) {
            $str = strip_tags($str);
        }

        $curlen = strlen($str);

        if ($suffix) {
            $len = $len - strlen($suffix);
        }

        if ($curlen > $len) {
            return substr($str, 0, $len) . $suffix;
        }
        return $str;
    }
}
