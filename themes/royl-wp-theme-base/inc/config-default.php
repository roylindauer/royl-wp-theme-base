<?php

/**
 * Define the core configuration of the Theme.
 * Specify custom post types, taxonomies, etc., here.
 */

// Set theme config
$config = [

    // Define a namespace for the ajax classes
    /*
    'ajax' => [
        'namespace' => ''
    ],
     */

    // Define Post Types
    // Uncomment below and edit to create custom post types
    /*
    'post_types' => [
        'Cover' => [
            // Default supports
            'supports' => [
                'title',
                'editor',
                'thumbnail',
                'revisions',
            ],
            // Default args
            'args' => [
                'description' => \Royl\WpThemeBase\Util\Text::translate('Describe the post type'),
            ]
        ]
    ],
    */

    // Define Taxonomies
    // https://codex.wordpress.org/Function_Reference/register_taxonomy
    // Uncomment below and edit to create taxonomies
    /*
    'taxonomies' => [
        'cover_categories' => [
            'params' => [
                'post_types'   => ['cover', 'post', 'page'), // this can include built in post types (page, post, etc)
            ],
            'args' => [
                'label'        => \Royl\WpThemeBase\Util\Text::translate('Cover Categories'),
                'rewrite'      => 'cover_categories',
                'hierarchical' => true,
            ]
        ],
        'cover_tags' => [
            'params' => [
                'post_types'   => ['cover'],
            ],
            'args' => [
                'label'        => \Royl\WpThemeBase\Util\Text::translate('Cover Tags'),
                'rewrite'      => 'cover_tags',
                'hierarchical' => false,
                'show_admin_column' => false
            ]
        ],
    ],
    */
    
    // Define stylesheets and scripts
    // WordPress core throws a NOTICE if you don't enqueue at least one stylesheet
    // This bug should be patched in WordPress core 4.2.3
    /*
    'assets' => [
        'frontend' => [
            'stylesheets' => [
                'style' => [
                    'source' => get_stylesheet_directory_uri() . '/style.css',
                    'dependencies' => false,
                    'version' => \Royl\WpThemeBase\Util\Configure::read('version')
                ],
            ],
            'scripts' => [
                'main' => [
                    'source' => get_stylesheet_directory_uri() . '/assets/js/main.js',
                    'dependencies' => false,
                    'version' => \Royl\WpThemeBase\Util\Configure::read('version'),
                    'in_footer' => true
                ],
            ]
        ],
        'admin' => [
            'stylesheets' => [],
            'scripts' => [],
        ],
        'login' => [
            'stylesheets' => [],
            'scripts' => [],
        ],
    ]
    */
];
