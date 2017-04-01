<?php

namespace Royl\WpThemeBase\Core\Filter;
use \Royl\WpThemeBase\Util;
use \Royl\WpThemeBase\Wp;

class FilterHandler {
	
	/**
	 * Collection of Filters
	 */
	public $filters = array();
	
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
	    $args = $this->defaultQueryArgs;

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
