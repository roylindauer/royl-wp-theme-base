<?php

// Include core config
include_once __DIR__ . '/config.php';
\Royl\WpThemeBase\Util\Configure::set($config);

// Set base Theme Name and Version into Theme Config
if (function_exists('wp_get_theme')) {
    $curtheme = wp_get_theme();
    \Royl\WpThemeBase\Util\Configure::write('name', $curtheme->get('Name'));
    \Royl\WpThemeBase\Util\Configure::write('domain', $curtheme->get('TextDomain'));
    \Royl\WpThemeBase\Util\Configure::write('version', $curtheme->get('Version'));
    unset($curtheme);
}

/**
 * Bootstrap the theme
 *
 * @param  array $config array of theme config options
 * @return void
 */
function royl_wp_theme_base($config = []) {
    \Royl\WpThemeBase\Util\Configure::set($config);

    $royl_wp_theme_base = new \Royl\WpThemeBase\Core\Core();

    $reg = \Royl\WpThemeBase\Core\Registry::getInstance();
    $reg->set('WpThemeBase', $royl_wp_theme_base);
    
    return $royl_wp_theme_base;
}

/**
 * Get an object from the object registry
 *
 * @return mixed
 */
function royl_instance($class) {
    $reg = \Royl\WpThemeBase\Core\Registry::getInstance();
	return $reg->get($class);
}

/**
 *
 */
function royl_create_ajax_nonce() {
	return wp_create_nonce( 'royl_execute_ajax_nonce' );
}
