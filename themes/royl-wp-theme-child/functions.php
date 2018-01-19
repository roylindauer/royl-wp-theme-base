<?php
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;

/**
 * Enqueue Parent Theme Styles
 */
add_action( 'wp_enqueue_scripts', 'royl_child_enqueue_styles' );
function royl_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

/**
 * Example: Enqueue Stylesheets
 */
add_filter( 'royl_frontend_stylesheets', function( $styles ){
    $styles['bootstrap'] = [
        'source' => 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
        'dependencies' => false,
        'version' => '4.0.0'
    ];
    return $styles;
});

/**
 * Debug Theme Configure Array
 */
add_action( 'shutdown', function(){
    if ( defined('DOING_AJAX') && DOING_AJAX ) {
        return;
    }
    Util\Debug::pr( Util\Configure::read() );
});

/**
 * Example: Pass config array for custom Post Types and Taxonomies
 */
add_filter('royl_set_theme_config', function( $config = [] ){
    $theme_config = [
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
                    'description' => Util\Text::translate('Describe the post type'),
                ]
            ]
        ],
        'taxonomies' => [
            'cover_categories' => [
                'params' => [
                    'post_types'   => ['cover', 'post', 'page'], // this can include built in post types (page, post, etc)
                ],
                'args' => [
                    'label'        => Util\Text::translate('Cover Categories'),
                    'rewrite'      => 'cover_categories',
                    'hierarchical' => true,
                ]
            ],
            'cover_tags' => [
                'params' => [
                    'post_types'   => ['cover'],
                ],
                'args' => [
                    'label'        => Util\Text::translate('Cover Tags'),
                    'rewrite'      => 'cover_tags',
                    'hierarchical' => false,
                    'show_admin_column' => false
                ]
            ],
        ],
    ];
    $config = array_merge( $config, $theme_config );

    return $config;
});

/**
 * Example: Register Theme Features
 */
add_filter( 'royl_register_theme_features', function( $features ){
    return [
        'custom-logo' => [
            'size' => 'theme-logo'
        ],
        'automatic-feed-links',
        'post-thumbnails',
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
    ];
});

/**
 * Example: Register Custom Image Sizes
 */
add_filter( 'royl_register_image_sizes', function( $sizes ){
    return [
        'theme-logo' => [
            'width' => 150,
            'height' => 150,
            'crop' => false
        ],
        'cover_large' => [
            'width' => 1920,
            'height' => 1080,
            'crop' => false
        ]
    ];
});

/**
 * Example: Register Sidebars
 */
add_filter( 'royl_register_sidebars', function( $sidebars ){
    return [
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
    ];
});

/**
 * Example: Register Nav Menus
 */
add_filter( 'royl_register_nav_menus', function( $navs ){
    return [
        'main-menu'   => \Royl\WpThemeBase\Util\Text::translate('Main Menu'),
        'sub-menu'    => \Royl\WpThemeBase\Util\Text::translate('Sub Menu'),
        'footer-menu' => \Royl\WpThemeBase\Util\Text::translate('Footer Menu'),
        'social-menu' => \Royl\WpThemeBase\Util\Text::translate('Social Menu')
    ];
});

/**
 * Example: Setup custom filter query arg defaults. ANY VALID WP_QUERY arg is acceptable, except post_type.
 */
add_filter( 'royl_set_filter_query_arg_defaults', function( $defaults ){
    return array_merge( $defaults, [
        'posts_per_page' => 10,
        'ignore_sticky_posts' => false,
    ]);
});

/**
 * Example: Setup Custom WP Query Filters
 */
add_filter( 'royl_config_filters', 'setup_filters', 10 );
function setup_filters() {
    return [
        'category' => [
            'filter_query' => [
                'type' => 'Taxonomy',
                'taxonomy' => 'category',
                'post_types' => [ 'post' ],
            ],
            'field' => [
                'type' => 'Select',
                'multi' => false,
                'options' => Wp\Taxonomy::getList( 'category' ),
                'name' => 'category', // use for the name attr on the field
                'label' => Util\Text::translate('Post Category'),
            ]
        ],
        'search' => [
            'filter_query' => [
                'type' => 'Search',
                'post_types' => [ 'post' ],
            ],
            'field' => [
                'type' => 'Text',
                'name' => 'search', // use for the name attr on the field
                'label' => Util\Text::translate('Search'),
            ]
        ],
        'mycustomfield' => [
            'filter_query' => [
                'type' => 'Postmeta',
                'key' => 'mycustomfield',
                'compare' => 'LIKE',
                'post_types' => [ 'post' ],
            ],
            'field' => [
                'type' => 'Text',
                'name' => 'mycustomfield', // use for the name attr on the field
                'label' => Util\Text::translate('Custom Field <code>mycustomfield</code>'),
            ]
        ],
    ];
}

/**
 * Example: Map Custom Filters to create Filter Sets
 */
add_filter( 'royl_map_filters', 'map_filters' );
function map_filters() {
    return [
        'post-category' => [ 'category', 'mycustomfield', 'search' ],
    ];
}

/**
 * Example: Injecting some content before a custom filters field
 */
add_action( 'royl_before_render_filter_field_filter_search', function(){
    echo '<u>stuff before the field</u>&nbsp;';
} );

/**
 * Example: Injecting some content after a custom filters field
 */
add_action( 'royl_after_render_filter_field_filter_search', function(){
    echo ' <br><em>You can add a custom description or something using the action <code>royl_after_render_filter_field_filter_{$field_name}</code>.</em>';
} );




// include custom jQuery
function royl_include_custom_jquery()
{
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);

}
add_action('wp_enqueue_scripts', 'royl_include_custom_jquery');

