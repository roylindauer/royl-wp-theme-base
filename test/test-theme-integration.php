<?php

use \Royl\WpThemeBase\Util;
use \Royl\WpThemeBase\Wp;
use \Royl\WpThemeBase\Core;

royl_wp_theme_base( [
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
