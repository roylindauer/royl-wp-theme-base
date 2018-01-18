<?php

use Royl\WpThemeBase\Util;

/**
 * Helper Method to set the Theme Config 
 *
 * @param  array $config array of theme config options
 * @return void
 */
function royl_wp_theme_base($config = [])
{
    Util\Configure::set($config);
}
