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
class WpThemeBase {

    /**
     * Initialize Theme
     *
     * @param  array $config Theme Configuration Options
     * @return void
     */
    public function init($theme_config = array()) {

        // Set base Theme Name and Version into Theme Config
        $curtheme = wp_get_theme();
        \Royl\WpThemeBase\Util\Configure::write('name', $curtheme->get('Name'));
        \Royl\WpThemeBase\Util\Configure::write('version', $curtheme->get('Version'));
        unset($curtheme);

        // Load plugin activator
        require_once __DIR__ . '/vendor/plugin-activation/class-tgm-plugin-activation.php';

        // Bootstrap the Theme! 
        if (!empty($theme_config)) {
            $config = $theme_config;
        } else {
            include_once __DIR__ . '/Config/core.php'; 
        }

        \Royl\WpThemeBase\Util\Configure::set($config);

        $royl_wp_core = new \Royl\WpThemeBase\Core\Core();
        $royl_wp_core->run();
    }
}