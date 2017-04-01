<?php

// Include core config
include_once __DIR__ . '/config.php';
\Royl\WpThemeBase\Util\Configure::set($config);

// Set base Theme Name and Version into Theme Config
if (function_exists('wp_get_theme')) {
    $curtheme = wp_get_theme();
    \Royl\WpThemeBase\Util\Configure::write('name', $curtheme->get('Name'));
    \Royl\WpThemeBase\Util\Configure::write('version', $curtheme->get('Version'));
    unset($curtheme);
}

/**
 * Bootstrap the theme
 * @param  array $config array of theme config options
 * @return void
 */
function royl_wp_theme_base($config = array()) {
    \Royl\WpThemeBase\Util\Configure::set($config);

    $royl_wp_theme_base = new \Royl\WpThemeBase\Core\Core();

    $reg = \Royl\WpThemeBase\Core\Registry::getInstance();
    $reg->set('WpThemeBase', $royl_wp_theme_base);
    
    return $royl_wp_theme_base;
}

/**
 * 
 */
function royl_core() {
    $reg = \Royl\WpThemeBase\Core\Registry::getInstance();
	return $reg->get('WpThemeBase');
}