<?php

use Royl\WpThemeBase\Util;

namespace Royl\WpThemeBase\Util {

    // Date Test Class
    class DateTest extends \PHPUnit_Framework_TestCase {

        public function setup() {
            date_default_timezone_set('America/Los_Angeles');
        }

        public function testFormat() {
            global $mockWPGetOption;
            $Date = new \Royl\WpThemeBase\Util\Date();

            $result = $Date->format();
            $this->assertTrue(is_string($result));
            $this->assertStringMatchesFormat('%i-%i-%i %i:%i:%i', $result);

            $result = $Date->format('Y-m-d');
            $this->assertTrue(is_string($result));
            $this->assertStringMatchesFormat('%i-%i-%i', $result);

            $timestamp = mktime(9, 30, 0, 3, 6, 1981);
            $result = $Date->format('Y-m-d H:i:s', $timestamp);
            $this->assertTrue(is_string($result));
            $this->assertEquals('1981-03-06 09:30:00', $result);

            $result = $Date->format('Y-m-d H:i:s', $timestamp, 'UTC');
            $this->assertTrue(is_string($result));
            $this->assertEquals('1981-03-06 17:30:00', $result);
        }
    }

}