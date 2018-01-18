<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Ajax;
use Royl\WpThemeBase\Filter;
use Royl\WpThemeBase\Wp;

/**
 * Helper function to use NAMESPACE in callbacks
 * @param  [type] $function [description]
 * @return [type]           [description]
 */
function n($function) {
    return __NAMESPACE__ . '\\' . $function;
}

add_action( 'after_setup_theme', n( 'register_theme_features' ), PHP_INT_MAX-1 );
add_action( 'after_setup_theme', n( 'register_image_sizes' ), PHP_INT_MAX-1 );
add_action( 'after_setup_theme', n( 'register_nav_menus' ), PHP_INT_MAX-1 );
add_action( 'after_setup_theme', n( 'load_textdomain' ), PHP_INT_MAX-1 );

add_action( 'widgets_init', n( 'register_sidebars' ), PHP_INT_MAX-1 );

add_action('admin_notices', n( 'print_theme_errors' ), PHP_INT_MAX-1);

add_action( 'tgmpa_register', n( 'tgmpa_register' ), PHP_INT_MAX-1 );

/*
 * Setup Core WP Objects
 */
$Assets = new Wp\Assets();
$Ajax = new Ajax\Ajax();
$CoreFilter = new Filter\Filter();
$PostTypeRegistry = new PostTypeRegistry();
$TaxonomyRegistry = new TaxonomyRegistry();
$VanityUrlRouter = new VanityUrlRouter();

/**
 * [register_theme_features description]
 * @return [type] [description]
 */
function register_theme_features() {
    $features = Util\Configure::read('theme_features');

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
    $image_sizes = Util\Configure::read('image_sizes');

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
    $menus = Util\Configure::read('menus');

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
    $sidebars = Util\Configure::read('sidebars');

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
