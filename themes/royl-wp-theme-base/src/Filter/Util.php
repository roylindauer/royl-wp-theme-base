<?php

namespace Royl\WpThemeBase\Filter;

use Royl\WpThemeBase\Wp;

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
    private static function getDefinedFilterData($set) {

        $filters    = \Royl\WpThemeBase\Util\Configure::read('filters.filters');
        $filterlist = \Royl\WpThemeBase\Util\Configure::read('filters.filter_template_map.' . $set);

        return [
            'filters' => $filters,
            'filterlist' => $filterlist
        ];
    }

    /**
     * Render Filter Bar
     *
     * @param string  $set      Required, the set of filters to render in the filter bar
     * @param string  $partial  Optional, the custom template partial to use.
     */
    public static function renderFilterForm($set, $partial = 'filter-bar')
    {
        $filterdata = self::getDefinedFilterData($set);

        $filter_objects = [];
        foreach ($filterdata['filterlist'] as $_f) {
            $filterclass = 'Royl\WpThemeBase\Filter\Query\\' . $filterdata['filters'][$_f]['filter_query']['type'];
            $filter_objects[] = new $filterclass($filterdata['filters'][$_f]);
        }

        do_action('royl_before_render_filter_form');
        Wp\Template::load( 'filter/' . $partial, ['filters' => $filter_objects]);
        do_action('royl_after_render_filter_form');
    }

    /**
     * Build and return a custom filtered WP_Query object
     * @return WP_Query
     */
    public static function getFilterQuery($set)
    {
        // Setup default query args
        $args = \Royl\WpThemeBase\Util\Configure::read('filters.defaults');
        if (!$args) {
            $args = [];
        }

        $args['post_type'] = [];

        $filterdata = self::getDefinedFilterData($set);

        foreach ($filterdata['filterlist'] as $_f) {
            if (!isset( $filterdata['filters'][$_f])) {
                continue;
            }

            $filter_conf = $filterdata['filters'][$_f];

            // Process Filter Query
            $filterclass = 'Royl\WpThemeBase\Filter\Query\\' . $filter_conf['filter_query']['type'];
            $filter = new $filterclass($filter_conf);
            $args = array_merge_recursive($args, $filter->getFilter());

            // Post Types
            $args['post_type'] = array_merge($args['post_type'], $filter_conf['filter_query']['post_types']);
        }

        // Clean up Post Types
        $args['post_type'] = array_filter(array_unique($args['post_type']));
        
        $args['paged'] = get_query_var('paged');
        
        // last chance to modify filter args before WP_Query object is created
        $args = apply_filters('royl_alter_filter_query_args', $args);

        // Create new WP_Query object and return it
        return new \WP_Query($args);
    }
}
