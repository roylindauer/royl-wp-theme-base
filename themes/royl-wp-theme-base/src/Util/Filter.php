<?php

namespace Royl\WpThemeBase\Util;
use \Royl\WpThemeBase\Wp;

/**
 * Utility class for working with content Filters
 *
 * @package     WpThemeBase
 * @subpackage  Util
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Filter
{
    /**
     * Render Filter Bar
     * 
     * @param string  $set      Required, the set of filters to render in the filter bar
     * @param string  $partial  Optional, the custom template partial to use. 
     */
    public static function renderFilterForm($set, $partial='filter-bar') {
        $filters    = Configure::read('filters.filters');
        $filterlist = Configure::read('filters.filter_template_map.' . $set);

        $filter_objects = [];
        foreach ($filterlist as $_f) {
            $filterclass = 'Royl\WpThemeBase\Core\Filter\\' . $filters[$_f]['filter_query']['type'] . 'Filter';
            $filter_objects[] = new $filterclass($filters[$_f]);
        }

        do_action('royl_before_render_filter_form');
        Wp\Template::load( 'filter/' . $partial, ['filters' => $filter_objects]);
        do_action('royl_after_render_filter_form');
    }

    /**
     * Build and return a custom filtered WP_Query object
     * @return WP_Query
     */
    public static function getFilterQuery($set) {
    
        // Setup default query args
        $args = Configure::read('filters.defaults');
        if (!$args) {
            $args = [];
        }

        $args['post_type'] = [];
        $_args[] = [];

        // With each Filter Object get its WP_Query args and merge into $args
        $filters    = Configure::read('filters.filters');
        $filterlist = Configure::read('filters.filter_template_map.' . $set);

        foreach ($filterlist as $_f) {
            if (!isset( $filters[$_f])) {
                continue;
            }

            // Process Filter Query
            $filterclass = 'Royl\WpThemeBase\Core\Filter\\' . $filters[$_f]['filter_query']['type'] . 'Filter';
            $filter = new $filterclass( $filters[$_f] );
            $args = array_merge_recursive($args, $filter->getFilter());

            // Post Types
            $args['post_type'] = array_merge($args['post_type'], $filters[$_f]['filter_query']['post_types']);
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
