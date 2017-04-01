<?php

namespace Royl\WpThemeBase\Core\Filter;
use \Royl\WpThemeBase\Util;
use \Royl\WpThemeBase\Wp;

/*
Usage:

// Setup Filters
add_filter( 'royl_config_filters', 'royl_dmo_setup_filters' );
function royl_dmo_setup_filters() {
	return [
        // Unique filter name. "filter_" is prepended to the name internally
        'FILTER_NAME_HERE' => [
            // The filter query determines the data that posts will be filtered by
            // We can filter by taxonomies, metaboxes, and post data 
            // modifier should be a Filter Modifier Class. 
			// the example below is the taxonomy Modifier
            'filter_query' => [
                'modifier' => 'Taxonomy',
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
add_filter( 'royl_map_filters', 'royl_dmo_map_filters' );
function royl_dmo_map_filters() {
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
*/

/**
 * 
 */
class FilterHandler {

	/**
	 * 
	 */
	public $defaultQueryArgs = [
        'posts_per_page' => 50,
        'ignore_sticky_posts' => false,
        'post_type' => [],
	];
	
	/**
	 * 
	 */
	public function __construct() {
		add_action( 'init', array(&$this, 'configFilters'), 20 );
	}
	
	/**
	 * 
	 */
	public function configFilters() {

	    $filters = [];
	    $filter_template_map = [];

	    $filters = apply_filters( 'royl_config_filters', $filters );
	    $filter_template_map = apply_filters( 'royl_map_filters', $filters );

	    $config = [];
	    $config[ 'filters' ] = $filters;
	    $config[ 'filter_template_map' ] = $filter_template_map;

	    Util\Configure::set( $config );
	}

	/**
	 * Render Filter Bar
	 * @return bool|void
	 */	
	public function getFilterFields($set) {
	    $filters    = Util\Configure::read( 'filters' );
	    $filterlist = Util\Configure::read( 'filter_template_map.' . $set );

	    $filter_objects = [];
	    foreach ( $filterlist as $_f ) {
	        $filterclass = '\Royl\WpFilter\Core\Filter\\' . $filters[ $_f ][ 'filter_query' ][ 'type' ];
	        $filter = new $filterclass( $filters[ $_f ] );
	        $filter_objects[] = $filter;
	    }

	    do_action( 'royl_before_render_filter_bar' );
	    Wp\Template::renderPartial( 'filter-bar', [ 'filters' => $filter_objects ] );
	    do_action( 'royl_after_render_filter_bar' );
	}

	/**
	 * Build and return a custom WP_Query object for Stakeholders
	 * @return WP_Query
	 */
	function getFilterQuery($set) {
    
	    /**
	     * @type array
	     * Defaults for our filter WP_Query object
	     */
	    $args = $this->$defaultQueryArgs;

	    /*
	     * With each Filter Object get its WP_Query args and merge into $args
	     */
	    $filters    = Util\Configure::read( 'filters' );
	    $filterlist = Util\Configure::read( 'filter_template_map.' . $set );

	    foreach ( $filterlist as $_f ) {
	        if ( !isset( $filters[$_f] ) ) {
	            continue;
	        }

	        // Process Filter Query
	        $filterclass = '\Royl\WpThemeBase\Core\Filter\Modifiers\\' . $filters[ $_f ][ 'filter_query' ][ 'modifier' ];
	        $filter = new $filterclass( $filters[ $_f ] );
	        $args = array_merge( $args, $filter->doFilter() );

	        // Post Types
	        $args['post_type'] = array_merge( $args['post_type'], $filters[ $_f ][ 'filter_query' ][ 'post_types' ] );
	    }

	    // Clean up post type
	    $args['post_type'] = array_unique( $args['post_type'] );
    
	    /*
	     * Filter to alter filter args before WP_Query object is created
	     */
	    $args = apply_filters( 'royl_alter_filter_query_args', $args );

	    Util\Debug::pr( $args );

	    /*
	     * Create new WP_Query object and return it
	     */
	    $query = new \WP_Query( $args );
	    return $query;
	}
	
	/**
	 * Render Filter Bar
	 */
	public function renderFilterBar($set) {
	    $filters    = Util\Configure::read( 'filters' );
	    $filterlist = Util\Configure::read( 'filter_template_map.' . $set );

	    $filter_objects = [];
	    foreach ( $filterlist as $_f ) {
	        $filterclass = '\Royl\WpThemeBase\Core\Filter\Modifiers\\' . $filters[ $_f ][ 'filter_query' ][ 'modifier' ];
	        $filter = new $filterclass( $filters[ $_f ] );
	        $filter_objects[] = $filter;
	    }

	    do_action( 'royl_before_render_filter_bar' );

	    Wp\Template::renderPartial( 'filter-bar', [ 'filters' => $filter_objects ] );

	    do_action( 'royl_after_render_filter_bar' );
	}
}
