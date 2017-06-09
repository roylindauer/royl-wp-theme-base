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
    	if (isset($_GET['filter_query'])) {
    		$set = filter_var($_GET['filter_query']);

    		$query = Util\Filter::getFilterQuery($set);
    		$this->response([
    			'posts' => $query->posts,
    			'post_count' => $query->post_count,
    			'found_posts' => $query->found_posts,
    			'query' => $query->query,
    		]);
    	} else {
    		$this->response(['error' => 'invalid request']);
    	}
    }
}
