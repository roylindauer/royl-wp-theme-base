<?php
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;

add_action( 'wp_enqueue_scripts', 'royl_child_enqueue_styles' );
function royl_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action('after_setup_theme', 'royl_init_theme', 10);
function royl_init_theme() {
    # @todo this is for testing. should be removed
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
        ]
    ]);
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
    ];
}

add_filter( 'royl_map_filters', 'map_filters' );
function map_filters() {
    return [
        'post-category' => [ 'category' ],
    ];
}


