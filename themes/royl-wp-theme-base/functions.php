<?php

// Load the Autoloader
require_once 'inc/autoload.php';

// Load core functions
require_once 'inc/royl_wp_theme_base.php';

// Include template tags
require_once 'inc/template-tags.php';

// Setup default theme config
include_once 'inc/config.php';
\Royl\WpThemeBase\Util\Configure::set($config);

// Set some theme specific configurations in the global Config
if (function_exists('wp_get_theme')) {
    $curtheme = wp_get_theme();
    \Royl\WpThemeBase\Util\Configure::write('name', $curtheme->get('Name'));
    \Royl\WpThemeBase\Util\Configure::write('domain', $curtheme->get('TextDomain'));
    \Royl\WpThemeBase\Util\Configure::write('version', $curtheme->get('Version'));
    unset($curtheme);
}