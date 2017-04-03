<?php

use \Royl\WpThemeBase\Util;
use \Royl\WpThemeBase\Wp;
use \Royl\WpThemeBase\Core;

royl_wp_theme_base( [
    // Ajax
    'ajax' => [
        'namespace' => 'Royl\\WpThemeBaseTest\\Ajax'
    ],
] );

// Ok we need our own autoloader
spl_autoload_register(function ($class) {
    // project-specific namespace prefix
    $prefix = 'Royl\\WpThemeBaseTest\\';
    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/test/';
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