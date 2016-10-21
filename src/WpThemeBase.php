<?php

namespace Royl\WpThemeBase;

/**
 * WpThemeBase base class
 *
 * Bootstrap the theme framework
 *
 * @package  WpThemeBase
 * @author   Roy Lindauer <hello@roylindauer.com>
 * @version  1.0
 */
class WpThemeBase
{

    /**
     * Initialize Theme
     *
     * @param  array $theme_config User supplied theme configuration options
     * @return boolean
     */
    public function init($theme_config = array())
    {
        // Load Theme Configuration
        if (!empty($theme_config)) {
            $config = $theme_config;
        } else {
            include_once __DIR__ . '/Config/core.php';
        }

        \Royl\WpThemeBase\Util\Configure::set($config);

        // Set base Theme Name and Version into Theme Config
        if (function_exists('wp_get_theme')) {
            $curtheme = wp_get_theme();
            \Royl\WpThemeBase\Util\Configure::write('name', $curtheme->get('Name'));
            \Royl\WpThemeBase\Util\Configure::write('version', $curtheme->get('Version'));
            unset($curtheme);
        }

        // Do the thing
        $royl_wp_core = new \Royl\WpThemeBase\Core\Core();
        $royl_wp_core->run();

        return true;
    }
}
