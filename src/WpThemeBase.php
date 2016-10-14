<?php

namespace Royl\WpThemeBase;

class WpThemeBase {

    /**
     * Initialize Theme
     */
    public function init($config = array()) {
        require __DIR__ . DIRECTORY_SEPARATOR . 'common.php';
        \Royl\WpThemeBase\Util\Configure::set($config);
    }
}