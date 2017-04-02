<?php

namespace Royl\WpThemeBase\Core\Filter;
use \Royl\WpThemeBase\Util;
use \Royl\WpThemeBase\Wp;

/*
Usage:

// Setup Filters
add_filter( 'royl_config_filters', 'setup_filters' );
function setup_filters() {
	return [
        // Unique filter name. "filter_" is prepended to the name internally
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
                'options' => Wp\Taxonomy::list( 'stakeholder_type' ),
                'name' => 'FILTER_NAME_HERE', // use for the name attr on the field
                'label' => Util\Text::translate('Type'),
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

*/

/**
 * 
 */
class FilterHandler {

	/**
	 * 
	 */
	private $defaultQueryArgs = [
        'posts_per_page' => 50,
        'ignore_sticky_posts' => false,
        'post_type' => [],
	];
	
	/**
	 * 
	 */
	public function __construct() {
		add_action('init', array(&$this, 'configFilters'), 20);
        add_action('init', array(&$this, 'configFilterTemplateMap'), 20);
        add_action('init', array(&$this, 'setDefaults'), 20);
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
        $defs = Util\Configure::read('filters.defaults');
        if (!empty($defs)) {
            $this->defaultQueryArgs = array_merge($this->defaultQueryArgs, $defs);
        }
    }

    /**
     * Returns array of defined filters in theme config
     * @return array|false
     */
    private function getDefinedFilters() {
        return Util\Configure::read('filters.filters');
    }

    /**
     * Returns array of filter template mappings
     * @return array|false
     */
    private function getFilterTemplateMap($set) {
        return Util\Configure::read('filters.filter_template_map.' . $set);
    }

	/**
	 * Build and return a custom WP_Query object for Stakeholders
	 * @return WP_Query
	 */
	public function getFilterQuery($set) {
    
	    /**
	     * @type array
	     * Defaults for our filter WP_Query object
	     */
	    $args = $this->defaultQueryArgs;

	    // With each Filter Object get its WP_Query args and merge into $args
	    $filters    = $this->getDefinedFilters();
	    $filterlist = $this->getFilterTemplateMap($set);

	    foreach ($filterlist as $_f) {
	        if (!isset( $filters[$_f])) {
	            continue;
	        }

	        // Process Filter Query
	        $filterclass = '\Royl\WpThemeBase\Core\Filter\\' . $filters[$_f]['filter_query']['type'];
	        $filter = new $filterclass( $filters[$_f] );
	        $args = array_merge($args, $filter->doFilter());

	        // Post Types
	        $args['post_type'] = array_merge($args['post_type'], $filters[$_f]['filter_query']['post_types']);
	    }

	    // Clean up Post Types
	    $args['post_type'] = array_unique($args['post_type']);
    
	    // last chance to modify filter args before WP_Query object is created
	    $args = apply_filters('royl_alter_filter_query_args', $args);

	    // Create new WP_Query object and return it
	    $query = new \WP_Query( $args );
	    return $query;
	}
	
	/**
	 * Render Filter Bar
	 */
	public function renderFilterForm($set, $partial='filter-bar') {
        $filters    = $this->getDefinedFilters();
        $filterlist = $this->getFilterTemplateMap($set);

	    $filter_objects = [];
	    foreach ($filterlist as $_f) {
	        $filterclass = '\Royl\WpThemeBase\Core\Filter\\' . $filters[$_f]['filter_query']['type'];
	        $filter = new $filterclass($filters[$_f]);
	        $filter_objects[] = $filter;
	    }

	    do_action('royl_before_render_filter_form');
	    Wp\Template::renderPartial( $partial, ['filters' => $filter_objects], __DIR__);
	    do_action('royl_after_render_filter_form');
	}
}
