<?php

/**
 * Define the core configuration of the Theme.
 * Specify custom post types, taxonomies, etc., here.
 */

// Set theme config
$config = [

    // Post Filtering Defaults
    'filters' => [
        'defaults' => [
            'posts_per_page' => 6,
            'ignore_sticky_posts' => false,
            'post_type' => [],
    	]
    ],
    
    // Custom Template Partials directory
    /*
    'partial_dir' => 'template-parts', // this directory is relative to your theme root
    */

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

    // Define theme features
    // http://codex.wordpress.org/Function_Reference/add_theme_support
    'theme_features' => [
        'automatic-feed-links',
        'post-thumbnails',
        'post-formats' => [
            'aside',
            'gallery',
            'link',
            'image',
            'quote',
            'status',
            'video',
            'audio',
            'chat',
        ],
        'title-tag',
        'automatic-feed-links',
        'customize-selective-refresh-widgets',
        'html5' => [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ]
    ],

    // Custom Image Sizes
    /*
    'image_sizes' => [
        'cover_large' => [
            'width' => 1920,
            'height' => 1080,
            'crop' => false
        ],
    ][,
    */

    // Define custom nav menus
    // https://codex.wordpress.org/Function_Reference/register_nav_menu
    /*
    'menus' => [
        'main-menu'   => \Royl\WpThemeBase\Util\Text::translate('Main Menu'),
        'sub-menu'    => \Royl\WpThemeBase\Util\Text::translate('Sub Menu'),
        'footer-menu' => \Royl\WpThemeBase\Util\Text::translate('Footer Menu')
    ],
    */

    // Define custom sidebars
    // https://codex.wordpress.org/Function_Reference/register_sidebar
    'sidebars' => [
        [
            'id'            => 'default-sidebar',
            'name'          => \Royl\WpThemeBase\Util\Text::translate('Default Sidebar'),
            'description'   => '',
            'class'         => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ],
    ],

    // Define theme dependencies
    // Require WP Plugins - http://tgmpluginactivation.com/
    // Require Core PHP Classes / Libraries
    /*
    'dependencies' => [
        'plugins' => [
            [
                'name'      => 'Meta Box',
                'slug'      => 'meta-box',
                'required'  => true,
            ],
            [
                'name'      => 'Options Framework',
                'slug'      => 'options-framework',
                'required'  => false,
            ],
            [
                'name'      => 'Wordpress SEO',
                'slug'      => 'wordpress-seo',
                'required'  => true,
            ],
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
