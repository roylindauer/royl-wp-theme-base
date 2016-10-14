<?php

use Royl\WpThemeBase\WpThemeBase;

class WpThemeBaseTest extends PHPUnit_Framework_TestCase {


    public function testInit() {
        $royl_wp_theme_base = new WpThemeBase();
        $this->assertTrue($royl_wp_theme_base->init());
    }
}