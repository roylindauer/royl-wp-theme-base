<?php
/**
 * Example Class for testing ajax calls, etc.
 */

namespace Royl\WpThemeBase\Core;

/**
 * Example Module class for testing ajax calls, etc.
 *
 * @package Royl\WpThemeBase\Core
 */
class TestAjax
{
    /**
     * test ajax endpoint
     */
    public function ajaxDoThingJson()
    {
        return array('message' => 'testing');
    }

    /**
     * test ajax endpoint
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
