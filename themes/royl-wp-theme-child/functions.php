<?php
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;

add_action( 'wp_enqueue_scripts', 'royl_child_enqueue_styles' );
function royl_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action('after_setup_theme', 'royl_init_theme', 10);
function royl_init_theme() {
    royl_wp_theme_base();
}

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
                'label' => Util\Text::translate('Custom Fields'),
            ]
        ],
    ];
}

add_filter( 'royl_map_filters', 'map_filters' );
function map_filters() {
    return [
        'post-category' => [ 'category', 'mycustomfield', 'search' ],
    ];
}

add_action( 'royl_before_render_filter_field_filter_search', function(){
    echo '<u>stuff before the field</u>&nbsp;';
} );
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

