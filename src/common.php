<?php
/**
 * Bootstrap the theme
 */

// Setup constants
define('ROYL_WPTHEME_BASE_APP_PATH', dirname(dirname(realpath(__FILE__))));

// Set base Theme Name and Version into Theme Config
$curtheme = wp_get_theme();
\Royl\WpThemeBase\Util\Configure::write('name', $curtheme->get('Name'));
\Royl\WpThemeBase\Util\Configure::write('version', $curtheme->get('Version'));
unset($curtheme);

// Load helper functions
require_once 'helpers.php';

// Load configs
include_once __DIR__ . '/Config/core.php';

// Load required vendors
require_once __DIR__ . '/vendor/plugin-activation/class-tgm-plugin-activation.php';

// Bootstrap the Theme!
$royl_wp_core = new Royl\WpThemeBase\Core\Core();
$royl_wp_core->run();
\Royl\WpThemeBase\register_object('RoylWpCore', $royl_wp_core);