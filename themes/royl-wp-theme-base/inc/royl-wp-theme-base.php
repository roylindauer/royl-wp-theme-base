<?php

/**
 * Bootstrap the theme
 *
 * @param  array $config array of theme config options
 * @return void
 */
function royl_wp_theme_base($config = [])
{
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
function royl_instance($class)
{
    $reg = \Royl\WpThemeBase\Core\Registry::getInstance();
    return $reg->get($class);
}
