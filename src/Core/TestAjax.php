<?php

namespace Royl\WpThemeBase\Core;

/**
 * Example Module for AJAX Endpoint
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class TestAjax
{
    /**
     * test ajax endpoint - JSON
     */
    public function ajaxDoThingJson()
    {
        return array('message' => 'testing');
    }

    /**
     * test ajax endpoint - XML
     */
    public function ajaxDoThingHtml()
    {
        ob_start();
        echo 'Testing';
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
}
