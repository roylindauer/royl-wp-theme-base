<?php

use Royl\WpThemeBase\Util;

namespace {
    $mockWPGetOption = false;
}

namespace Royl\WpThemeBase\Util {

    function get_option($opt) {
        global $mockWPGetOption;
        if (isset($mockWPGetOption) && $mockWPGetOption === true) {
            return 'string';
        } else {
            return null;
        }
    }

    class CacheTest extends \PHPUnit_Framework_TestCase {

        public function setup() {
            global $mockWPGetOption;
            $mockWPGetOption = false;
        }

        public function testGetHash() {
            $Cache = new \Royl\WpThemeBase\Util\Cache();

            $result = $Cache->getHash(array('test'=>'1234'), 'query');
            $this->assertEquals($result, '0dca84c7e0629fa55299843c4590033a');

            $result = $Cache->getHash(false, 'query');
            $this->assertEquals($result, '9d1742b490bd868c55ae736b7184e29c');
        }

        public function testGetCachedQuery() {
            global $mockWPGetOption;
            $Cache = new \Royl\WpThemeBase\Util\Cache();

            $mockWPGetOption = true;
            $result = $Cache->getCachedQuery(array('test'=>'1234'), 'test');
            $this->assertEquals($result, 'string');

            $mockWPGetOption = false;
            $result = $Cache->getCachedQuery(array('test'=>'1234'), 'test');
            $this->assertEquals($result, null);
        }

        public function testSetCachedQuery() {
            
        }

        public function testGetCachedQueryNames() {

        }

        public function testClearQueryCaching() {

        }

        public function testArrayHash() {

        }

        public function testArrayToString() {

        }
    }

}