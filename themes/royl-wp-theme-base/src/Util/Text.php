<?php

namespace Royl\WpThemeBase\Util;

use Royl\WpThemeBase\Util;

/**
 * Utility class for working with text
 *
 * @package     WpThemeBase
 * @subpackage  Util
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
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
            $domain = Util\Configure::read('domain');
        }

        return \__($str, $domain);
    }

    /**
     * Wrapper for https://codex.wordpress.org/Function_Reference/_nx
     */
    public static function nx($single, $plural, $number, $context)
    {
        // Default domain to theme
        $domain = Util\Configure::read('domain');
        return \_nx($single, $plural, $number, $context, $domain);
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
     * $str = \Ecs\Core\Utilties\Text::truncate($str, 7, '...');
     * // returns "This is..."
     *
     * @param string $str , the string to truncate
     * @param integer $len , where to truncate the string
     * @param string $suffix , any text to include after the truncated string
     * @param boolean $safe , when true will strip html tags before truncating
     */
    public static function truncate($str, $len = 50, $suffix = '...', $safe = false)
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

    /**
     * Returns a human-readable string from $word
     *
     * Returns a human-readable string from $word, by replacing
     * underscores with a space, and by upper-casing the initial
     * character by default.
     *
     * If you need to uppercase all the words you just have to
     * pass 'all' as a second parameter.
     *
     * @access public
     * @static
     * @param string $word String to "humanize"
     * @param string $uppercase If set to 'all' it will uppercase all the words
     * instead of just the first one.
     * @return string Human-readable word
     */
    public static function humanize($word, $uppercase = '')
    {
        $uppercase = $uppercase == 'all' ? 'ucwords' : 'ucfirst';
        return $uppercase(str_replace('_', ' ', preg_replace('/_id$/', '', $word)));
    }
}
