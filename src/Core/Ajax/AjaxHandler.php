<?php

namespace Royl\WpThemeBase\Core\Ajax;
use Royl\WpThemeBase\Util;

/**
 * Simple interface for handling ajax requests
 *
 * Usage: /wp-admin/admin-ajax.php?action=royl_ajax&c=CLASS&m=METHOD&_wpnonce=NONCE
 * 
 * Generate a nonce: wp_create_nonce('royl_execute_ajax_nonce');
 *
 * You will need to set the ajax.namespace in your Config
 * Configure::write('ajax.namespace', 'MyTheme\\IsGreat');
 *
 * Params for ajax request:
 * c         = class to instantiate
 * m         = method to run
 * _wpnonce  = WordPress Nonce
 * 
 * <?php
 * 
 * namespace MyTheme\IsGreat;
 * use Royl\WpThemeBase\Core;
 * use Royl\WpThemeBase\Util;
 * use Royl\WpThemeBase\Wp;
 * 
 * class MyAjaxThing extends AjaxBase {
 * 
 * public function doThing {
 *   $this->response(['heyo'], 'json);
 * }
 *
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class AjaxHandler
{
    private $ajaxClassNamespace = 'Royl\\WpThemeBase\\Ajax';

	/**
	 * Setup the Ajax handler
	 */
	public function __construct() {
        add_action('wp_ajax_royl_ajax', array(&$this, 'execute'));
        add_action('wp_ajax_nopriv_royl_ajax', array(&$this, 'execute'));
        add_action('wp_enqueue_scripts', array(&$this, 'generateWPNonce'));
        $this->setClassNamespace();
	}
    
    /**
     * Set the custom namespace
     */
    public function setClassNamespace() {
        $customNamespace = Util\Configure::read('ajax.namespace');
        if ($customNamespace) {
            $this->ajaxClassNamespace = $customNamespace;
        }
    }
	
    /**
     * Handles the ajax request
     */
	public function execute() {
        try {
            // Expect a valid wp nonce
            if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'royl_execute_ajax_nonce')) {
                throw new \Exception('Invalid ajax request');
            }
 
            // Make sure we have a class and a method to execute
            if (!isset($_REQUEST['c']) && !isset($_REQUEST['m'])) {
                throw new \Exception('Invalid params in ajax request');
            }
 
            // define class
            $c = filter_var($_REQUEST['c'], FILTER_SANITIZE_STRING);
            $class = "{$this->ajaxClassNamespace}\\$c";
 
            if (!class_exists($class)) {
                throw new \Exception('Class does not exist');
            }
 
            // Instantiate new Ajax Response object
            $AjaxResponse = new $class();
 
			// Get the requested method
            $ajaxMethod = filter_var($_REQUEST['m'], FILTER_SANITIZE_STRING);
 
            // Execute method
            $AjaxResponse->execute($ajaxMethod);

        } catch (\Exception $e) {
            wp_die($e->getMessage());
        }
 
        die();
	}

    /**
     * Generates a nonce and make it available for use in main javascript file
     */
    public function generateWPNonce()
    {
        // Localize the script with new data
        $translation_array = array(
        	'wp_nonce' => wp_create_nonce( 'royl_execute_ajax_nonce' )
        );

        // The handle will need to be changed to a javascript file once the front end
        // javascript file is in place. Use a placeholder now to pevent wp from erroring
        wp_localize_script( 'wp_nonce_helper', 'wp_nonce', $translation_array );
        wp_enqueue_script( 'wp_nonce_helper' );
    }
}
