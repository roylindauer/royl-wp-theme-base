<?php

namespace Royl\WpThemeBase\Ajax\Response;

use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Filter;

/**
 * Ajax End Point for Filtering
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class FilterPost extends \Royl\WpThemeBase\Ajax\Response
{
    private $format = 'json';
    private $response;
    
    public function doFilter()
    {
        if (isset($_GET['filter_query'])) {
            $set = filter_var($_GET['filter_query']);

            $query = Filter\Util::getFilterQuery($set);
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

    public function getQueryVars() {

        if (isset($_GET['filter_query'])) {
            $ret = [];
            $set = filter_var($_GET['filter_query']);
            $filters    = Util\Configure::read('filters.filters');
            $filterlist = Util\Configure::read('filters.filter_template_map.' . $set);

            foreach ($filterlist as $_f) {
                if (!isset( $filters[$_f])) {
                    continue;
                }

                $ret[] = $filters[$_f];
            }

            $this->response(['filters' => $ret]);
        }
    }
}
