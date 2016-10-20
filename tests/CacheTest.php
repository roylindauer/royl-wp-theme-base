<?php

use Royl\WpThemeBase\Util;

namespace {
    // Define return value for our mocked get_option() function
    $mockWPGetOption = false;
}

namespace Royl\WpThemeBase\Util {

    // WordPress Mock Functions
    function get_option($opt) {
        global $mockWPGetOption;
        if (isset($mockWPGetOption) && $mockWPGetOption === true) {
            return 'string';
        } else {
            return null;
        }
    }

    // Cache Test Class
    class CacheTest extends \PHPUnit_Framework_TestCase {

        public function setup() {

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

        public function testArrayHash() {
            // need to use Reflection api to get access to private method of class
            $method = new \ReflectionMethod('\Royl\WpThemeBase\Util\Cache', 'arrayHash');
            $method->setAccessible(true);

            $result = $method->invoke(new \Royl\WpThemeBase\Util\Cache, array('test'=>'1234'));
            $this->assertEquals($result, '0dca84c7e0629fa55299843c4590033a');

            $result = $method->invoke(new \Royl\WpThemeBase\Util\Cache, false);
            $this->assertFalse($result);

            $result = $method->invoke(new \Royl\WpThemeBase\Util\Cache, '');
            $this->assertEquals($result, '');
        }

        public function testArrayToString() {
            $method = new \ReflectionMethod('\Royl\WpThemeBase\Util\Cache', 'arrayToString');
            $method->setAccessible(true);

            $result = $method->invoke(new \Royl\WpThemeBase\Util\Cache, array('test'=>'1234'));
            $this->assertEquals($result, 'test1234');

            $result = $method->invoke(new \Royl\WpThemeBase\Util\Cache, array(
                'test'=>'1234', 
                'foo' => array(
                    'bar' => '1234'
                ),
                'foo2' => array(
                    'bar2' => '1234',
                    'baz2' => '1234'
                )
            ));
            $this->assertEquals($result, 'test1234foo1234foo21234foo21234');
        }
    }

}