<?php

namespace Royl\WpThemeBase\Ajax;

use \Royl\WpThemeBase\Wp;

/**
 * Utility class for working with content Filters
 *
 * @package     WpThemeBase
 * @subpackage  Util
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Util
{
    static function createNonce()
    {
        return wp_create_nonce('royl_execute_ajax_nonce');
    }

    static function url($c, $m) {
        return admin_url('admin-ajax.php?_wpnonce=' . self::createNonce() . '&action=royl_ajax&c=' . $c . '&m=' . $m);
    }
}
