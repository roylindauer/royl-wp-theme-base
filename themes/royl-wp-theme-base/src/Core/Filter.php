<?php

namespace Royl\WpThemeBase\Core;
use \Royl\WpThemeBase\Util;
use \Royl\WpThemeBase\Wp;

/*
Usage:

// Setup Filters
add_filter( 'royl_config_filters', 'setup_filters' );
function setup_filters() {
	return [
        // Unique filter name. "filter_" is prepended to the name internally

        // TaxQuery
        'FILTER_NAME_HERE' => [
            // The filter query determines the data that posts will be filtered by
            // We can filter by taxonomies, metaboxes, and post data 
            // type should be a Filter Class. 
			// the example below is the taxonomy type
            'filter_query' => [
                'type' => 'Taxonomy',
                'taxonomy' => 'categories',
                'post_types' => [ 'post' ],
            ],
            // the field to render. Type should be a Field Class
            'field' => [
                'type' => 'SelectField',
                'multi' => false,
                'options' => Wp\Taxonomy::getList( 'stakeholder_type' ),
                'name' => 'FILTER_NAME_HERE', // use for the name attr on the field
                'label' => Util\Text::translate('Filter Label'),
            ]
        ],

        // MetaQuery
        'FILTER_NAME_HERE' => [
            // The filter query determines the data that posts will be filtered by
            // We can filter by taxonomies, metaboxes, and post data 
            // type should be a Filter Class. 
            // the example below is the taxonomy type
            'filter_query' => [
                'type' => 'Postmeta',
                'key' => 'metafield',
                'post_types' => [ 'post' ],
            ],
            // the field to render. Type should be a Field Class
            'field' => [
                'type' => 'SelectField',
                'multi' => false,
                'options' => ['get', 'the', 'values', 'somehow'],
                'name' => 'FILTER_NAME_HERE', // use for the name attr on the field
                'label' => Util\Text::translate('Filter Label'),
            ]
        ],
	];
}

// Setup up filter sets. This is how you group the defined filters together into unique filter forms.
add_filter( 'royl_map_filters', 'map_filters' );
function map_filters() {
	return [
        'taxonomy-stakeholder_ammenities'      => [ 'type', 'neighborhoods', 'keyword' ],
        'taxonomy-stakeholder_type'            => [ 'ammenities', 'neighborhoods', 'keyword' ],
        #'taxonomy-stakeholder_type-activities' => [ 'neighborhoods', 'type', 'ammenities', 'keyword' ],
        #'taxonomy-stakeholder_type-dining'     => [ 'neighborhoods', 'type', 'ammenities', 'budget', 'keyword' ],
        #'taxonomy-stakeholder_type-lodging'    => [ 'neighborhoods', 'type', 'ammenities', 'budget', 'keyword' ],
        #'taxonomy-stakeholder_type-shopping'   => [ 'neighborhoods', 'type', 'ammenities', 'keyword' ],
    ];
}

// You can modify the query object even further with the `royl_alter_filter_query_args` action
add_filter( 'royl_alter_filter_query_args', 'royl_dmo_filter_query_args' );
function royl_dmo_filter_query_args( $args ) {
	$args['post_type'] = ['stakeholder']; // define which posts types to include in wp_query
    $args['posts_per_page'] = '10'; // overwrite default of 50 per page
	return $args;
}

// Inject some stuff before the filter form is rendered
add_action( 'royl_before_render_filter_form', 'before_render_filter_bar' );
function before_render_filter_bar() {
    echo '<p>This is before!</p>';
}

// Inject some stuff after the filter form is rendered
add_action( 'royl_after_render_filter_form', 'after_render_filter_bar' );
function after_render_filter_bar() {
    echo '<p>This is after!</p>';
}

// Render the form:
\Royl\WpThemeBase\Util\Filter::renderFilterForm( $set );

// Get query object:
$query = \Royl\WpThemeBase\Util\Filter::getFilterQuery( $set );

*/

/**
 * 
 */
class Filter {
	
    public $prefix = 'filter_';

	/**
	 * 
	 */
	public function __construct() {
		add_action('init', [&$this, 'configFilters'], 20);
        add_action('init', [&$this, 'configFilterTemplateMap'], 20);
        add_action('init', [&$this, 'setDefaults'], 20);
        add_filter('query_vars', [&$this, 'queryVars']);
	}
	
	/**
	 * Setup Filters
	 */
	public function configFilters() {
	    $filters = [];
	    $filters = apply_filters( 'royl_config_filters', $filters );
	    Util\Configure::write('filters.filters', $filters);
	}

    /**
     * Setup filter map
     */
    public function configFilterTemplateMap() {
        $filter_template_map = [];
        $filter_template_map = apply_filters( 'royl_map_filters', $filter_template_map );
        Util\Configure::write('filters.filter_template_map', $filter_template_map);
    }

    /**
     * User can define filter defaults
     */
    public function setDefaults() {
        $this->defaultQueryArgs = Util\Configure::read('filters.defaults');
    }

    /**
     * Add our filter query vars
     */
    public function queryVars($query_vars) {
        $filters = Util\Configure::read('filters.filters');
        foreach ($filters as $filter => $data) {
            $query_vars[] = $this->prefix . $filter;
        }
        return $query_vars;
    }
}
