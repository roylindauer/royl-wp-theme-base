<?php

use Royl\WpThemeBase\Wp;

namespace {
    // Define return value for our mocked get_option() function
    $mockWPMethodReturn = false;
}

namespace Royl\WpThemeBase\Wp {

    // WordPress Mock Functions
    function get_transient($key) {
        global $mockWPMethodReturn;
        if (isset($mockWPMethodReturn) && $mockWPMethodReturn === true) {
            return 'test';
        } else {
            return false;
        }
    }

    function set_transient($key, $data, $expiration) {
        global $mockWPMethodReturn;
        if (isset($mockWPMethodReturn) && $mockWPMethodReturn === true) {
            return true;
        } else {
            return false;
        }
    }

    function delete_transient($key) {
        global $mockWPMethodReturn;
        if (isset($mockWPMethodReturn) && $mockWPMethodReturn === true) {
            return true;
        } else {
            return false;
        }
    }

    // Cache Test Class
    class CacheTest extends \PHPUnit_Framework_TestCase {

        public function setup() {

        }

        public function testWrite() {
            global $mockWPMethodReturn;
            $Cache = new \Royl\WpThemeBase\Wp\Transient('my_cache_store');

            $mockWPMethodReturn = true;
            $result = $Cache->write('test');
            $this->assertTrue($result);

            $mockWPMethodReturn = false;
            $result = $Cache->write('test');
            $this->assertFalse($result);
        }

        public function testExpiration() {
            $Cache = new \Royl\WpThemeBase\Wp\Transient('my_cache_store', 300);
            $this->assertEquals($Cache->expiration, 300);

            $Cache = new \Royl\WpThemeBase\Wp\Transient('my_cache_store');
            $this->assertEquals($Cache->expiration, 3600);
        }

        public function testRead() {
            global $mockWPMethodReturn;
            $Cache = new \Royl\WpThemeBase\Wp\Transient('my_cache_store');

            $mockWPMethodReturn = true;
            $result = $Cache->read();
            $this->assertEquals($result, 'test');

            $mockWPMethodReturn = false;
            $result = $Cache->read();
            $this->assertEquals($result, false);
        }

        public function testDestroy() {
            global $mockWPMethodReturn;
            $Cache = new \Royl\WpThemeBase\Wp\Transient('my_cache_store');

            $mockWPMethodReturn = true;
            $result = $Cache->destroy();
            $this->assertEquals($result, true);

            $mockWPMethodReturn = false;
            $result = $Cache->destroy();
            $this->assertEquals($result, false);
        }
    }

}