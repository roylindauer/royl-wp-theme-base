<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Ajax;
use Royl\WpThemeBase\Filter;
use Royl\WpThemeBase\Wp;

add_action( 'after_setup_theme', __n( 'register_theme_features' ), PHP_INT_MAX-1 );
add_action( 'after_setup_theme', __n( 'register_image_sizes' ), PHP_INT_MAX-1 );
add_action( 'after_setup_theme', __n( 'register_nav_menus' ), PHP_INT_MAX-1 );
add_action( 'after_setup_theme', __n( 'load_textdomain' ), PHP_INT_MAX-1 );

add_action( 'widgets_init', __n( 'register_sidebars' ), PHP_INT_MAX-1 );

add_action( 'admin_notices', __n( 'print_theme_errors' ), PHP_INT_MAX-1 );

add_action( 'wp_enqueue_scripts', __n( 'load_frontend_scripts' ), PHP_INT_MAX-1 );
add_action( 'wp_enqueue_scripts', __n( 'load_admin_scripts' ), PHP_INT_MAX-1 );
add_action( 'wp_enqueue_scripts', __n( 'load_login_scripts' ), PHP_INT_MAX-1 );

add_action( 'after_setup_theme', __n( 'set_theme_config' ), PHP_INT_MAX-1 );

add_action( 'init', __n( 'register_post_types' ), PHP_INT_MAX-1 );
add_action( 'init', __n( 'register_taxonomies' ), PHP_INT_MAX-1 );

/**
 * https://codex.wordpress.org/Content_Width 
 * @var int
 */
$content_width = (int) get_option( 'content_width', 1080 );

/**
 * Register Custom Post Types
 * @return [type] [description]
 */
function register_post_types() {
    $post_types = [];
    $post_types = apply_filters( 'royl_register_post_types', $post_types );

    if ( empty( $post_types ) ) {
        return;
    }

    foreach ( $post_types as $post_type => $params ) {
        new Wp\PostType( $post_type, $params );
    }

    Util\Configure::write( 'theme_post_types', $post_types );
}

/**
 * Register Custom Taxonomies
 * @return [type] [description]
 */
function register_taxonomies() {

    $taxonomies = [];
    $taxonomies = apply_filters( 'royl_register_taxonomies', $taxonomies );

    if (empty($taxonomies)) {
        return;
    }

    foreach ($taxonomies as $name => $opts) {
        new Wp\TaxonomyType($name, $opts['params'], $opts['args']);
    }

    Util\Configure::write( 'theme_taxonomies', $taxonomies );
}

/**
 * Set Core Theme Config (i suppose things like, api keys, or other data you want to pass around)
 * @param  array  $config [description]
 * @return [type]         [description]
 */
function set_theme_config() {
    $config = [];
    $config = apply_filters( 'royl_set_theme_config', $config );
    Util\Configure::set($config);
}

/**
 * [load_frontend_scripts description]
 * @return [type] [description]
 */
function load_frontend_scripts() {
    // Stylesheets
    $stylesheets = [
        'style' => [
            'source' => get_stylesheet_directory_uri() . '/style.css',
            'dependencies' => false,
            'version' => Util\Configure::read('version')
        ]
    ];
    $stylesheets = apply_filters( 'royl_frontend_stylesheets', $stylesheets );
    do_load_stylesheets( $stylesheets );

    // JavaScripts
    $scripts = [];
    $scripts = apply_filters( 'royl_frontend_scripts', $scripts );
    do_load_scripts( $scripts );
}

/**
 * [load_admin_scripts description]
 * @return [type] [description]
 */
function load_admin_scripts() {
    // Stylesheets
    $stylesheets = [];
    $stylesheets = apply_filters( 'royl_admin_stylesheets', $stylesheets );
    do_load_stylesheets( $stylesheets );

    // JavaScripts
    $scripts = [];
    $scripts = apply_filters( 'royl_admin_scripts', $scripts );
    do_load_scripts( $scripts );
}

/**
 * [load_login_scripts description]
 * @return [type] [description]
 */
function load_login_scripts() {
    // Stylesheets
    $stylesheets = [];
    $stylesheets = apply_filters( 'royl_login_stylesheets', $stylesheets );
    do_load_stylesheets( $stylesheets );

    // JavaScripts
    $scripts = [];
    $scripts = apply_filters( 'royl_login_scripts', $scripts );
    do_load_scripts( $scripts );
}

/**
 * [register_theme_features description]
 * @return [type] [description]
 */
function register_theme_features() {
    
    /**
     * Sane Defaults for Theme Features
     * @var array
     */
    $features = [
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

    /*
     * Allow child theme/plugin to customize default theme features
     */
    $features = apply_filters('royl_register_theme_features', $features);

    if (empty($features)) {
        return;
    }

    foreach ($features as $k => $v) {
        if (is_array($v)) {
            \add_theme_support($k, $v);
        } else {
            \add_theme_support($v);
        }
    }
}

/**
 * [register_image_sizes description]
 * @return [type] [description]
 */
function register_image_sizes() {
    $image_sizes = apply_filters('royl_register_image_sizes', []);

    if (empty($image_sizes)) {
        return;
    }

    foreach ($image_sizes as $name => $opts) {
        // Check wp reserved names for image sizes
        if (in_array($name, ['thumb', 'thumbnail'])) {
            Util\Debug::addThemeError(sprintf('Image size identifier "%s" is reserved', $name));
        } else {
            \add_image_size($name, @$opts['width'], @$opts['height'], @$opts['crop']);
        }
    }
}

/**
 * [register_nav_menus description]
 * @return [type] [description]
 */
function register_nav_menus()
{
    $menus = apply_filters('royl_register_nav_menus', []);

    if (empty($menus)) {
        return;
    }

    \register_nav_menus($menus);
}

/**
 * [loadTextDomain description]
 * @return [type] [description]
 */
function load_textdomain()
{
    \load_theme_textdomain(Util\Configure::read('domain'), get_template_directory() . '/languages');
}

/**
 * [register_sidebars description]
 * @return [type] [description]
 */
function register_sidebars()
{
    $sidebars = apply_filters('royl_register_sidebars', []);

    if (empty($sidebars)) {
        return;
    }
    
    foreach ($sidebars as $sidebar) {
        \register_sidebar($sidebar);
    }
}

/**
 * Prints the error messages added to the global theme specific WP_Error object
 *
 * Only displays for users that have 'manage_options' capability,
 * needs WP_DEBUG & WP_DEBUG_DISPLAY constants set to true.
 * Doesn't output anything if there's no error object present.
 *
 * Adds the output to the 'shutdown' hook to render after the theme viewport is output.
 *
 * @return string
 */
function print_theme_errors() {
    global $wp_theme_error, $wp_theme_error_code;

    if (!current_user_can('manage_options') || !is_wp_error($wp_theme_error)) {
        return;
    }

    $output = '';
    foreach ($wp_theme_error->errors[$wp_theme_error_code] as $error) {
        $output .= '<li>' . $error . '</li>';
    }

    echo '<div class="error"><h4>' . Util\Text::translate('Theme Errors & Warnings').'</h4><ul>';
    echo $output;
    echo '</ul></div>';
}

