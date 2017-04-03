<?php

use \Royl\WpThemeBase\Util;
use \Royl\WpThemeBase\Wp;
use \Royl\WpThemeBase\Core;

royl_wp_theme_base( [
    //Filter Config
    'filters' => [
        'defaults' => [
            'posts_per_page' => 15,
            'ignore_sticky_posts' => false,
            'post_type' => [],
        ]
    ],
    // Core WP Theme features
    'theme-features' => [
        'automatic-feed-links',
        'post-thumbnails',
    ],
    // Post Types
    'post_types' => [
        'Stakeholder' => [
            'supports' => [
                'title',
                'editor',
                'thumbnail',
                'revisions',
                'excerpt',
            ],
            'args' => [
                'description' => Util\Text::translate( 'Stakeholders' ),
                'public' => true,
                'exclude_from_search' => false,
                'show_ui' => true,
                'show_in_nav_menus' => true,
                'show_in_menu' => true,
                'query_var' => 'stakeholder',
                'rewrite' => true,
                'has_archive' => true,
                'hierarchical' => true,
            ]
        ]
    ],
    // Taxonomies
    'taxonomies' => [
        'stakeholder_type' => [
            'params' => [
                'post_types' => [ 'stakeholder' ]
            ],
            'args' => [
                'description' => Util\Text::translate('Stakeholder Type'),
                'rewrite' => [ 'slug' => 'type' ],
                'hierarchical' => true,
                'show_in_rest' => true,
            ]
        ],
        'stakeholder_neighborhoods' => [
            'params' => [
                'post_types' => [ 'stakeholder' ]
            ],
            'args' => [
                'description' => Util\Text::translate('Stakeholder Neighborhoods/Districts'),
                'rewrite' => [ 'slug' => 'neighborhood' ],
                'hierarchical' => true,
                'show_in_rest' => true,
            ]
        ],
        'stakeholder_ammenities' => [
            'params' => [
                'post_types' => [ 'stakeholder' ]
            ],
            'args' => [
                'description' => Util\Text::translate('Stakeholder Ammenities (dog-friendly, 24hrs, etc.)'),
                'rewrite' => [ 'slug' => 'ammenities' ],
                'hierarchical' => false,
                'show_in_rest' => true,
            ]
        ],
        'stakeholder_budget' => [
            'params' => [
                'post_types' => [ 'stakeholder' ]
            ],
            'args' => [
                'description' => Util\Text::translate('Budgets'),
                'rewrite' => [ 'slug' => 'budget' ],
                'hierarchical' => false,
                'show_in_rest' => true,
                'show_in_nav_menus' => false,
                'show_tagcloud' => false,   
            ]
        ],
    ],
    // Stakeholder Types
    'stakeholder_types' => [
        'Activities',
        'Lodging',
        'Shopping',
        'Dining'
    ],
    // Budgets
    'stakeholder_budgets' => [
        '$',
        '$$',
        '$$$',
        '$$$$'
    ],
    // Stakeholder Metaboxes
    'stakeholder_metaboxes' => [
        'address',
        'phone_number',
        'fax_number',
        'email_address',
        'website_url',
        'booking_url',
        'lat',
        'lng'
    ]
] );

/*
 Create default taxonomy terms for Stakeholder Type
 */
add_action( 'init', 'royl_dmo_init_stakeholder_types' );
function royl_dmo_init_stakeholder_types() {
    $terms = Util\Configure::read( 'stakeholder_types' );

    if ( !$terms ) {
        return;
    }
    
    foreach ( $terms as $term ) {
        Wp\Taxonomy::upsertTaxonomyTerm( $term, 'stakeholder_type' );
    }
}

/*
 Create default taxonomy terms for Stakeholder Budget
 */
add_action( 'init', 'royl_dmo_init_stakeholder_budgets' );
function royl_dmo_init_stakeholder_budgets() {
    $terms = Util\Configure::read( 'stakeholder_budgets' );

    if ( !$terms ) {
        return;
    }
    
    foreach ( $terms as $term ) {
        Wp\Taxonomy::upsertTaxonomyTerm( $term, 'stakeholder_budget' );
    }
}

/*
 * Configure Filters
 */
add_filter( 'royl_config_filters', 'royl_dmo_setup_filters' );
function royl_dmo_setup_filters( $filters ) {

    $filters = [
        // Unique filter name. "filter_" is prepended to the name internally
        'type' => [
            // The filter query determines the data that posts will be filtered by
            // We can filter by taxonomies, metaboxes, and post data 
            // type should be a Filter Class
            'filter_query' => [
                'type' => 'TaxonomyFilter',
                'taxonomy' => 'stakeholder_type',
                'post_types' => [ 'stakeholder' ],
            ],
            // the field to render. Type should be a Field Class
            'field' => [
                'type' => 'SelectField',
                'multi' => false,
                'options' => Wp\Taxonomy::getList( 'stakeholder_type' ),
                'name' => 'type',
                'label' => Util\Text::translate('Type'),
            ]
        ],

        'neighborhoods' => [
            'filter_query' => [
                'type' => 'TaxonomyFilter',
                'taxonomy' => 'stakeholder_neighborhoods',
                'post_types' => [ 'stakeholder' ],
            ],
            'field' => [
                'type' => 'SelectField',
                'multi' => false,
                'options' => Wp\Taxonomy::getList( 'stakeholder_neighborhoods'),
                'name' => 'neighborhoods',
                'label' => Util\Text::translate('Neighborhood'),
            ]
        ],

        'ammenities' => [
            'filter_query' => [
                'type' => 'TaxonomyFilter',
                'taxonomy' => 'stakeholder_ammenities',
                'post_types' => [ 'stakeholder' ],
            ],
            'field' => [
                'type' => 'SelectField',
                'multi' => false,
                'options' => Wp\Taxonomy::getList( 'stakeholder_ammenities' ),
                'name' => 'type',
                'label' => Util\Text::translate('Ammenities'),
            ]
        ],

        'keyword' => [
            'filter_query' => [
                'type' => 'SearchFilter',
                'post_types' => [ 'stakeholder' ],
            ],
            'field' => [
                'type' => 'TextField',
                'placeholder' => Util\Text::translate('eg: Keyword'),
                'name' => 'keyword',
                'label' => Util\Text::translate('Search Keyword'),
            ]
        ],
    ];

    return $filters;
}

/*
 * Setup Filter Mapping
 */
add_filter( 'royl_map_filters', 'royl_dmo_map_filters' );
function royl_dmo_map_filters( $filter_map ) {
    return [
        'taxonomy-stakeholder_ammenities'      => [ 'type', 'neighborhoods', 'keyword' ],
        'taxonomy-stakeholder_type'            => [ 'ammenities', 'neighborhoods', 'keyword' ],
        #'taxonomy-stakeholder_type-activities' => [ 'neighborhoods', 'type', 'ammenities', 'keyword' ],
        #'taxonomy-stakeholder_type-dining'     => [ 'neighborhoods', 'type', 'ammenities', 'budget', 'keyword' ],
        #'taxonomy-stakeholder_type-lodging'    => [ 'neighborhoods', 'type', 'ammenities', 'budget', 'keyword' ],
        #'taxonomy-stakeholder_type-shopping'   => [ 'neighborhoods', 'type', 'ammenities', 'keyword' ],
    ];
}

/*
 * Use this filter to specify post types to filter
 * or to inject additional filter criteria into the filter wp_query object
 */
add_filter( 'royl_alter_filter_query_args', 'royl_dmo_filter_query_args' );
function royl_dmo_filter_query_args( $args ) {
    $args['posts_per_page'] = '10'; // overwrite default of 50 per page
    return $args;
}

add_action( 'royl_before_render_filter_form', 'royl_dmo_before_render_filter_bar' );
function royl_dmo_before_render_filter_bar() {
    echo '<p>This is before!</p>';
}

add_action( 'royl_after_render_filter_form', 'royl_dmo_after_render_filter_bar' );
function royl_dmo_after_render_filter_bar() {
    echo '<p>This is after!</p>';
}
