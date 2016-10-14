<?php

namespace Royl\WpThemeBase;

class WpThemeBase {

    /**
     * Initialize Theme
     */
    public function init($config = array()) {

        // Set base Theme Name and Version into Theme Config
        $curtheme = wp_get_theme();
        \Royl\WpThemeBase\Util\Configure::write('name', $curtheme->get('Name'));
        \Royl\WpThemeBase\Util\Configure::write('version', $curtheme->get('Version'));
        unset($curtheme);

        // Load core config
        include_once __DIR__ . '/Config/core.php';

        // Load plugin activator
        require_once __DIR__ . '/vendor/plugin-activation/class-tgm-plugin-activation.php';

        // Bootstrap the Theme! 
        \Royl\WpThemeBase\Util\Configure::set($config);

        $royl_wp_core = new \Royl\WpThemeBase\Core\Core();
        $royl_wp_core->run();
    }
}