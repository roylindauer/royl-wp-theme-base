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

add_filter( 'royl_config_filters', 'setup_filters' );
function setup_filters() {
    return [
        'category' => [
            'filter_query' => [
                'type' => 'Taxonomy',
                'taxonomy' => 'category',
                'post_types' => [ 'post' ],
            ],
            'field' => [
                'type' => 'SelectField',
                'multi' => false,
                'options' => Wp\Taxonomy::getList( 'category' ),
                'name' => 'category', // use for the name attr on the field
                'label' => Util\Text::translate('Post Category'),
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
                'type' => 'TextField',
                'multi' => false,
                'name' => 'mycustomfield', // use for the name attr on the field
                'label' => Util\Text::translate('Custom Fields'),
            ]
        ],
    ];
}

add_filter( 'royl_map_filters', 'map_filters' );
function map_filters() {
    return [
        'post-category' => [ 'category', 'mycustomfield' ],
    ];
}


