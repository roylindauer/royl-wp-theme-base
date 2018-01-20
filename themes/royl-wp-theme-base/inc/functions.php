<?php

namespace Royl\WpThemeBase\Core;

/**
 * Helper function to make it easier to use NAMESPACE in callbacks
 * This helper only works for scripts with the exact namespace `Royl\WpThemeBase\Core`
 * It's not realy a helper. It's kinda lame and only useful for init and filters 
 *
 * @param  string  $function  function to namespace
 * @return string
 */
function __n( $function ) {
    return __NAMESPACE__ . '\\' . $function;
}

/**
 * Register and Enqueue Stylesheets
 * @param  array  $stylesheets  multidim array - [ HANDLE => [ SOURCE, DEPENDENCIES, VERSION ] ]
 * @return void
 */
function do_load_stylesheets( $stylesheets=[] ) {
    // Register the stylesheets
    foreach ($stylesheets as $handle => $data) {
        wp_register_style($handle, $data['source'], $data['dependencies'], $data['version']);
    }

    // Enqueue the stylesheets
    foreach ($stylesheets as $handle => $data) {
        wp_enqueue_style($handle, $data['source'], $data['dependencies'], $data['version']);
    }
}

/**
 * Register and Enqueue Scripts
 * @param  array  $scripts  multidim array - [ HANDLE => [ SOURCE, DEPENDENCIES, VERSION, IN-FOOTER ] 
 * @return void
 */
function do_load_scripts( $scripts=[] ) {
    // Register the scripts
    foreach ($scripts as $handle => $data) {
        wp_register_script($handle, $data['source'], $data['dependencies'], $data['version'], $data['in_footer']);
    }

    // Enqueue the scripts
    foreach ($scripts as $handle => $data) {
        wp_enqueue_script($handle, $data['source'], $data['dependencies'], $data['version'], $data['in_footer']);
    }
}