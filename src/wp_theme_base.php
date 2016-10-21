<?php

// Include core config
include_once __DIR__ . '/Config/core.php';
\Royl\WpThemeBase\Util\Configure::set($config);

// Set base Theme Name and Version into Theme Config
if (function_exists('wp_get_theme')) {
    $curtheme = wp_get_theme();
    \Royl\WpThemeBase\Util\Configure::write('name', $curtheme->get('Name'));
    \Royl\WpThemeBase\Util\Configure::write('version', $curtheme->get('Version'));
    unset($curtheme);
}

// Load core
$royl_wp_core = new \Royl\WpThemeBase\Core\Core();
$royl_wp_core->run();