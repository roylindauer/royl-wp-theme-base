<?php
/**
 * Bootstrap the theme
 */

// Setup constants
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(dirname(realpath(__FILE__))));
define('VENDOR_PATH', APP_PATH . DS . 'vendor');

// Autoloader
require_once 'autoloader.php';

// Set base Theme Name and Version into Theme Config
$curtheme = wp_get_theme();
\Royl\WpThemeBase\Util\Configure::write('name', $curtheme->get('Name'));
\Royl\WpThemeBase\Util\Configure::write('version', $curtheme->get('Version'));
unset($curtheme);

// Load helper functions
require_once 'helpers.php';

// Load configs
\Royl\WpThemeBase\Util\Configure::init();

// Load required vendors
require_once VENDOR_PATH . DS . 'plugin-activation' . DS . 'class-tgm-plugin-activation.php';

// Bootstrap the Theme!
$royl_wp_core = new Royl\WpThemeBase\Core\Core();
$royl_wp_core->run();
\Royl\WpThemeBase\register_object('RoylWpCore', $royl_wp_core);