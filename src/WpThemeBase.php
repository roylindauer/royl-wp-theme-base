<?php

namespace Royl\WpThemeBase;

class WpThemeBase {

    /**
     * Initialize Theme
     */
    public function init() {
        require __DIR__ . DIRECTORY_SEPARATOR . 'common.php';
    }
}