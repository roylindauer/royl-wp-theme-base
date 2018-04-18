<?php

namespace Royl\WpThemeBase\Filter;

/**
 *
 */
class Filter
{
    /**
     *
     */
    public function __construct()
    {
        add_action('init', [&$this, 'configFilters'], 20);
        add_action('init', [&$this, 'configFilterGroups'], 20);
        add_filter('royl_filter_define_filters', [&$this, 'setFilterFieldDefaultAttributes']);
        add_filter('query_vars', [&$this, 'addQueryVars']);
    }

    /**
     * Setup default field attributes for defined filter fields
     * Every field must have a name an id.
     */
    public function setFilterFieldDefaultAttributes($filters = []) {
        foreach ($filters as $k => $v) {
            if (isset($v['field'])) {
                if (!isset($filters[$k]['field']['name'])) {
                    $filters[$k]['field']['name'] = $v['name'];
                }
                if (!isset($filters[$k]['field']['id'])) {
                    $filters[$k]['field']['id'] = 'filter_field_id_' . $v['name'];
                }
            }
        }
        return $filters;
    }
    
    /**
     * Setup Filters
     */
    public function configFilters()
    {
        $filters = apply_filters( 'royl_filter_define_filters', [] );
        \Royl\WpThemeBase\Util\Configure::write('filters.filters', $filters);
    }

    /**
     * Setup filter map
     */
    public function configFilterGroups()
    {
        $filter_groups = apply_filters( 'royl_filter_define_filter_groups', [] );
        \Royl\WpThemeBase\Util\Configure::write('filters.filter_groups', $filter_groups);
    }

    /**
     * Add a custom query var for each of our defined filters
     */
    public function addQueryVars($query_vars)
    {
        $filters = \Royl\WpThemeBase\Util\Configure::read('filters.filters');

        // Add each of our defined filters as query var
        foreach ($filters as $filter => $data) {
            $query_vars[] = $data['name'];
        }
        
        return $query_vars;
    }
}
