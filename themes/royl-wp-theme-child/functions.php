<?php
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;

add_action( 'wp_enqueue_scripts', 'royl_child_enqueue_styles' );
function royl_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

add_action( 'init', 'royl_test_loading' );
// Now we can execute source from the parent theme in this functions file. 
function royl_test_loading() {
    #$DMO = new \Royl\WpThemeBase\Util\Dmo();
    #$DMO->say();
    #\Royl\WpThemeBase\Util\Debug::debug('this is the child theme');
}

add_action('after_setup_theme', 'royl_init_theme', 10);
function royl_init_theme() {
    royl_wp_theme_base([
        // Core WP Theme features
        'theme_features' => [
            'automatic-feed-links',
            'post-thumbnails',
            'custom-logo' => [
                'height' => 100,
                'width' => 400,
                'flex-width' => true,
                'header-text' => array( 'site-title', 'site-description' )
            ]
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
    ]);
}