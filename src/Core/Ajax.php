<?php

namespace Royl\WpThemeBase\Core;

/**
 * WordPress Ajax Handler
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Ajax
{
	/**
	 * 
	 */
	public function __construct() {
        add_action('wp_ajax_royl_ajax', array(&$this, 'executeAjax'));
        add_action('wp_ajax_nopriv_royl_ajax', array(&$this, 'executeAjax'));
	}
	
    /**
     * Simple interface for executing ajax requests
     *
     * Usage: /wp-admin/admin-ajax.php?action=royl_ajax&c=CLASS&m=METHOD&_wpnonce=NONCE
     *
     * Params for ajax request:
     * c         = class to instantiate
     * m         = method to run
     * _wpnonce  = WordPress Nonce
     * display   = json,html
	 * 
	 * <?php
	 * namespace Royl\WpThemeBase\Core;
	 * class MyAjaxThing extends Ajax {
	 * 
	 * public function doThing {
	 *   echo 'heyo';
	 * 	 die();
	 * }
     *
     * Output can be rendered as JSON, or HTML
     *
     * Generate a nonce: wp_create_nonce('execute_ajax_nonce');
     */
	public function executeAjax() {
        try {
            // Expect a valid wp nonce
            if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'execute_ajax_nonce')) {
                throw new \Exception('Invalid ajax request');
            }
 
            // Make sure we have a class and a method to execute
            if (!isset($_REQUEST['c']) && !isset($_REQUEST['m'])) {
                throw new \Exception('Invalid params in ajax request');
            }
 
            // define class
            $c = filter_var($_REQUEST['c'], FILTER_SANITIZE_STRING);
            $class = "\Ecs\\Modules\\$c";
 
            if (!class_exists($class)) {
                throw new \Exception('Class does not exist');
            }
 
            $Obj = new $class();
 
			// define class method
            $m = filter_var($_REQUEST['m'], FILTER_SANITIZE_STRING);
 
            // Make sure that the requested method exists in our object
            if (!method_exists($Obj, $m)) {
                throw new \Exception('Ajax method does not exist');
            }
 
            // Execute
            $result = $Obj->$m();
 
            // Render the response
            $this->json_response($result);
 
        } catch (\Exception $e) {
            $this->json_response(array('error' => $e->getMessage()));
        }
 
        // Make sure this thing dies so it never echoes back that damn zero.
        die();
	}
	
	/**
	 * 
	 */
	private function json_response($payload) {
		echo json_encode($payload);
	}
}
