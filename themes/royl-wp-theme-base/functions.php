<?php

Use \Royl\WpThemeBase\Util;

// Load the Autoloader
require_once 'inc/autoload.php';

// Load core theme functions
require_once 'inc/royl-wp-theme-base.php';

// Load the Autoloader
require_once 'inc/init.php';

// Include template tags
require_once 'inc/template-tags.php';

// Setup default theme config
include_once 'inc/config-default.php';
Util\Configure::set($config);

// Set some theme specific configurations in the global Config
if (function_exists('wp_get_theme')) {
    $curtheme = wp_get_theme();
    Util\Configure::write('name', $curtheme->get('Name'));
    Util\Configure::write('domain', $curtheme->get('TextDomain'));
    Util\Configure::write('version', $curtheme->get('Version'));
    unset($curtheme);
}
