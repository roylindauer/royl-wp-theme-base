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
    /**
     * Get Query Var
     * @param  string  $var the query var
     * @return mixed        returns false if no value found
     */
    public static function getQueryVar($var) {
        $ret = get_query_var($var, false);
        if ($ret === false) {
            if (isset($_REQUEST[$var])) {
                $ret = $_REQUEST[$var];
            }
        }
        return filter_var($ret);
    }

    /**
     * Get an array of all of the defined filters and the filter template mappings for specific filter set
     * @param  [type] $set [description]
     * @return [type]      [description]
     */
    private static function getDefinedFilterData($set) {

        $filters    = \Royl\WpThemeBase\Util\Configure::read('filters.filters');
        $filterlist = \Royl\WpThemeBase\Util\Configure::read('filters.filter_groups.' . $set);

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
        /**
         * Collection of all defined filters and the template mapping for $set
         * @var [type]
         */
        $filterdata = self::getDefinedFilterData($set);

        /**
         * Collection of Filter Fields for $set
         * @var array
         */
        $fields = [];

        // Instantiate new Filter Fields to be rendered in the filter form
        foreach ($filterdata['filterlist'] as $_f) {
            if (isset($filterdata['filters'][$_f]['field']['type'])) {
                $fieldClass = 'Royl\WpThemeBase\Filter\Field\\' . $filterdata['filters'][$_f]['field']['type'];
                $fields[] = new $fieldClass($filterdata['filters'][$_f]['field']);
            }
        }

        // Modify output before the filter form is rendered
        do_action('royl_before_render_filter_form');

        // Load the filter form
        Wp\Template::load( 'filter/' . $partial, ['fields' => $fields]);

        // Modify output after the filter form is rendered
        do_action('royl_after_render_filter_form');
    }

    /**
     * Build and return a custom filtered WP_Query object
     * @return WP_Query
     */
    public static function getFilterQuery($set)
    {
        // Setup default query args
        $args = [
            'posts_per_page' => 6,
            'ignore_sticky_posts' => false,
        ];

        $args = apply_filters('royl_set_filter_query_arg_defaults', $args);

        // Reset post type array. Post types should be defined in the filter config.
        // We build the array of post types from each filter in the $set.
        $args['post_type'] = [];

        $filterdata = self::getDefinedFilterData($set);

        foreach ($filterdata['filterlist'] as $_f) {
            if (!isset( $filterdata['filters'][$_f])) {
                continue;
            }

            $filter_conf = $filterdata['filters'][$_f];

            // Process Filter Query
            $queryclass = 'Royl\WpThemeBase\Filter\Query\\' . $filter_conf['filter_query']['type'];
            $filter = new $queryclass($filter_conf['name'], $filter_conf['filter_query']);
            $args = array_merge_recursive($args, $filter->getFilter());

            // Post Types
            if ( isset( $filter_conf['filter_query']['post_types'] ) ) {
                $args['post_type'] = array_merge($args['post_type'], $filter_conf['filter_query']['post_types']);
            }
        }

        // Clean up Post Types
        $args['post_type'] = array_filter(array_unique($args['post_type']));

        // Paged must be an int
        $args['paged'] = intval(self::getQueryVar('paged'));

        // Global filter
        $args = apply_filters('royl_filter_alter_query_args', $args);

        // Filter specifically this $set
        $args = apply_filters('royl_filter_alter_query_args_' . $set, $args);

        // Create and return new WP_Query object
        return new \WP_Query($args);
    }
}
