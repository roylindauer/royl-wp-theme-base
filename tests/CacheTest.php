<?php

use Royl\WpThemeBase\Util;

namespace {
    // Define return value for our mocked get_option() function
    $mockWPGetOption = false;
}

namespace Royl\WpThemeBase\Util {

    // WordPress Mock Functions
    function get_transient($key) {
        global $mockWPGetOption;
        if (isset($mockWPGetOption) && $mockWPGetOption === true) {
            return 'test';
        } else {
            return false;
        }
    }

    function set_transient($key, $data, $expiration) {
        global $mockWPGetOption;
        if (isset($mockWPGetOption) && $mockWPGetOption === true) {
            return true;
        } else {
            return false;
        }
    }

    function delete_transient($key) {
        global $mockWPGetOption;
        if (isset($mockWPGetOption) && $mockWPGetOption === true) {
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
            global $mockWPGetOption;
            $Cache = new \Royl\WpThemeBase\Util\Cache('my_cache_store');

            $mockWPGetOption = true;
            $result = $Cache->write('test');
            $this->assertTrue($result);

            $mockWPGetOption = false;
            $result = $Cache->write('test');
            $this->assertFalse($result);
        }

        public function testExpiration() {
            $Cache = new \Royl\WpThemeBase\Util\Cache('my_cache_store', 300);
            $this->assertEquals($Cache->expiration, 300);

            $Cache = new \Royl\WpThemeBase\Util\Cache('my_cache_store');
            $this->assertEquals($Cache->expiration, 3600);
        }

        public function testRead() {
            global $mockWPGetOption;
            $Cache = new \Royl\WpThemeBase\Util\Cache('my_cache_store');

            $mockWPGetOption = true;
            $result = $Cache->read();
            $this->assertEquals($result, 'test');

            $mockWPGetOption = false;
            $result = $Cache->read();
            $this->assertEquals($result, false);
        }

        public function testDestroy() {
            global $mockWPGetOption;
            $Cache = new \Royl\WpThemeBase\Util\Cache('my_cache_store');

            $mockWPGetOption = true;
            $result = $Cache->destroy();
            $this->assertEquals($result, true);

            $mockWPGetOption = false;
            $result = $Cache->destroy();
            $this->assertEquals($result, false);
        }
    }

}