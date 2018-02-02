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
        add_action('init', [&$this, 'configFilterTemplateMap'], 20);
        
        add_filter('royl_config_filters', [&$this, 'preProcessFilters']);
        add_filter('query_vars', [&$this, 'queryVars']);
    }

    /**
     * Pre Process Filters
     */
    public function preProcessFilters($filters = []) {
        foreach ($filters as $k => $v) {
            $filters[$k]['field']['name'] = 'filter_' . $v['field']['name'];
            $filters[$k]['field']['id'] = 'filter_' . $v['field']['name'];
        }
        return $filters;
    }
    
    /**
     * Setup Filters
     */
    public function configFilters()
    {
        $filters = [];
        $filters = apply_filters( 'royl_config_filters', $filters );
        \Royl\WpThemeBase\Util\Configure::write('filters.filters', $filters);
    }

    /**
     * Setup filter map
     */
    public function configFilterTemplateMap()
    {
        $filter_template_map = [];
        $filter_template_map = apply_filters( 'royl_map_filters', $filter_template_map );
        \Royl\WpThemeBase\Util\Configure::write('filters.filter_template_map', $filter_template_map);
    }

    /**
     * Add our filter query vars
     */
    public function queryVars($query_vars)
    {
        $filters = \Royl\WpThemeBase\Util\Configure::read('filters.filters');
        
        // Add each of our defined filters as query var
        foreach ($filters as $filter => $data) {
            $query_vars[] = $data['field']['name'];
        }
        
        return $query_vars;
    }
}
