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
    
    /**
     * Process Filter Request
     *
     * @return [type] [description]
     */
    public function doFilter()
    {
        $set = Filter\Util::getQueryVar('filter_set');
        if ($set) {
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

    /**
     * Returns an array of Fields for a defined Filter Set
     * @return [type] [description]
     */
    public function getFields() {

        $set = Filter\Util::getQueryVar('filter_set');
        if ($set) {
            $ret = [];
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
