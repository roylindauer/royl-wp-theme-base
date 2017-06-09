<?php

namespace Royl\WpThemeBase\Core\Ajax;

use Royl\WpThemeBase\Util;

/**
 * Ajax End Point for Filtering
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class AjaxFilter extends AjaxBase
{
    private $format = 'json';
    private $response;
    
    public function doFilter()
    {
        $this->response(['hey' => 'heyo', 'this-get-param' => $_GET['test']]);
    }
}
