<?php
/*
Plugin Name: ROYL WordPress Theme Base
Plugin URI: https://www.roylindauer.com/wordpress-ecs-theme-framework/
Description: Theme Development Framework
Version: 1.0
Author: Roy Lindauer
Author URI: https://www.roylindauer.com/
License: GPL2
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    exit;
}

// Autoloader
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    // Try to find autoloader in the current directory
    include_once __DIR__ . '/vendor/autoload.php';
} else if ( file_exists( ABSPATH . '/vendor/autoload.php' ) ) {
    // Try to find autoloader in WP Root
    include_once ABSPATH . '/vendor/autoload.php';
} else {
    // Ok we need our own autoloader
    spl_autoload_register(function ($class) {
        // project-specific namespace prefix
        $prefix = 'Royl\\WpThemeBase\\';
        // base directory for the namespace prefix
        $base_dir = __DIR__ . '/src/';
        // does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // no, move to the next registered autoloader
            return;
        }
        // get the relative class name
        $relative_class = substr($class, $len);
        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        // if the file exists, require it
        if (file_exists($file)) {
            require $file;
        }
    });
}

require_once('src/wp_theme_base.php');