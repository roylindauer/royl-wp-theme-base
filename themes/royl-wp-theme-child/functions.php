<?php
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;
use Royl\WpThemeBase\Ajax;

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
 * Example: Register Custom Post Types
 */
add_filter( 'royl_register_post_types', function( $post_types ){

    $custom_post_types = [
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
    ];
    return array_merge( $post_types, $custom_post_types );
});

/**
 * Example: Register Custom Taxonomies
 */
add_filter( 'royl_register_taxonomies', function( $tax ){

    $custom_tax = [
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
    ];
    return array_merge( $tax, $custom_tax );
});

/**
 * Example: Set Theme Config Options
 */
add_filter( 'royl_set_theme_config', function( $config = [] ){
    $theme_config = [];
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
                'classes' => [ 'form-control' ],
                'type' => 'Select',
                'multi' => false,
                'options' => Wp\Taxonomy::getList( 'category' ),
                'name' => 'category', // use for the name attr on the field
                'label' => Util\Text::translate( 'Post Category' ),
            ]
        ],
        'search' => [
            'filter_query' => [
                'type' => 'Search',
                'post_types' => [ 'post' ],
            ],
            'field' => [
                'classes' => [ 'form-control' ],
                'type' => 'Text',
                'name' => 'search', // use for the name attr on the field
                'label' => Util\Text::translate( 'Search' ),
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
                'classes' => [ 'form-control' ],
                'type' => 'Text',
                'name' => 'mycustomfield', // use for the name attr on the field
                'label' => Util\Text::translate( 'Custom Field <code>you can include html in the label</code>' ),
                'placeholder' => Util\Text::translate( 'This is a placeholder' )
            ]
        ],
        // sort and direction
        'orderby' => [
            'filter_query' => [
                'type' => 'OrderBy',
                'rand_seed' => 1234,
            ],
            'field' => [
                'classes' => [ 'form-control' ],
                'type' => 'Select',
                'multi' => false,
                'options' => [
                    'ID' => Util\Text::translate( 'ID' ),
                    'name' => Util\Text::translate( 'Slug' ),
                    'title' => Util\Text::translate( 'Post Title' ),
                    'date' => Util\Text::translate( 'Publish Date' ),
                    'modified' => Util\Text::translate( 'Modified Date' ),
                    'rand' => Util\Text::translate( 'Random' ),
                ],
                'name' => 'orderby',
                'label' => Util\Text::translate( 'Order By' ),
            ]
        ],
        'order' => [
            'filter_query' => [
                'type' => 'Order'
            ],
            'field' => [
                'classes' => [ 'form-control' ],
                'type' => 'Select',
                'multi' => false,
                'options' => [
                    'ASC' => Util\Text::translate( 'ASC' ),
                    'DESC' => Util\Text::translate( 'DESC' ),
                ],
                'name' => 'order',
                'label' => Util\Text::translate( 'Direction' ),
            ]
        ]
    ];
}

/**
 * Example: Map Custom Filters to create Filter Sets
 */
add_filter( 'royl_map_filters', 'map_filters' );
function map_filters() {
    return [
        'test-filter-form' => [ 'category', 'mycustomfield', 'search', 'orderby', 'order' ],
        'test-filter-form-secondary' => [ 'category', 'search' ],
    ];
}

/**
 * Example: Injecting some content before a custom filters field
 */
add_action( 'royl_before_render_filter_field_wrapper_filter_search', function(){
    echo '<em>This appears before the field, inside the field wrapper</em><br><code>royl_before_render_filter_field_wrapper_filter_{$field_name}</code>';
} );

/**
 * Example: Injecting some content after a custom filters field
 */
add_action( 'royl_after_render_filter_field_wrapper_filter_search', function(){
    echo '<em>This appears after the field, inside the field wrapper</em><br><code>royl_after_render_filter_field_wrapper_filter_{$field_name}</code>.';
} );

/**
 * Example: Adding additional classes to field containers
 */
add_filter( 'filter_field_container_classes', function( $classes ){
    $classes[] = 'form-group';
    $classes[] = 'row';
    return $classes;
});

/**
 * Example: Adding additional classes to field wrappers
 */
add_filter( 'filter_field_wrapper_classes', function( $classes ){
    $classes[] = 'col-sm-10';
    return $classes;
});

/**
 * Example: Adding additional classes to field labels
 */
add_filter( 'filter_field_label_classes', function( $classes ){
    $classes[] = 'col-sm-2';
    $classes[] = 'col-form-label';
    return $classes;
});

/**
 * Example: Add content before the orderby filter
 */
add_action( 'royl_before_render_filter_field_filter_orderby', function(){
    echo '<hr>';
} );

/**
 * Example: Add content after the orderby direction filter
 */
add_action( 'royl_after_render_filter_field_filter_order', function(){
    echo '<hr>';
} );

// include custom jQuery
function royl_include_custom_jquery()
{
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, false);

}
add_action('wp_enqueue_scripts', 'royl_include_custom_jquery');

