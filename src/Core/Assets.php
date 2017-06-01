<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;

/**
 * Assets
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Assets
{
	/**
	 * 
	 */
	public function __construct() {
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_stylesheets'));
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
        
        add_action('admin_enqueue_scripts', array(&$this, 'admin_stylesheets'));
        add_action('admin_enqueue_scripts', array(&$this, 'admin_scripts'));
        
        add_action('login_enqueue_scripts', array(&$this, 'login_stylesheets'));
        add_action('login_enqueue_scripts', array(&$this, 'login_scripts'));
	}
    
    /**
     * Wrapper function to load frontend stylesheets
     */
    public function frontend_stylesheets() {
        self::stylesheets('frontend');
    }
    
    /**
     * Wrapper function to load frontend scripts
     */
    public function frontend_scripts() {
        self::scripts('frontend');
    }
    
    /**
     * Wrapper function to load admin stylesheets
     */
    public function admin_stylesheets() {
        self::stylesheets('admin');
    }
    
    /**
     * Wrapper function to load admin scripts
     */
    public function admin_scripts() {
        self::scripts('admin');
    }
    
    /**
     * Wrapper function to load login stylesheets
     */
    public function login_stylesheets() {
        self::stylesheets('login');
    }
    
    /**
     * Wrapper function to load login scripts
     */
    public function login_scripts() {
        self::scripts('login');
    }

    /**
     * Does the actual work of registered stylesheets. Must be called before enqueue.
     *
     * @param  string  which set of assets to load (frontend, admin, login)
     * @return void
     */
    private function stylesheets($screen = 'frontend')
    {
        $stylesheets = Util\Configure::read('assets.' . $screen . '.stylesheets');

        if (empty($stylesheets)) {
            return;
        }

		// Register the stylesheets
        foreach ($stylesheets as $handle => $data) {
            wp_register_style($handle, $data['source'], $data['dependencies'], $data['version']);
        }

		// Enqueue the stylesheets
        foreach ($stylesheets as $handle => $data) {
            wp_enqueue_style($handle, $data['source'], $data['dependencies'], $data['version']);
        }
    }

    /**
     * Does the actual work of registered scripts. Must be called before enqueue.
     *
     * @return void
     */
    private function scripts($screen = 'frontend')
    {
        $scripts = Util\Configure::read('assets.' . $screen . '.scripts');

        if (empty($scripts)) {
            return;
        }

		// Register the scripts
        foreach ($scripts as $handle => $data) {
            wp_register_script($handle, $data['source'], $data['dependencies'], $data['version'], $data['in_footer']);
        }

		// Enqueue the scripts
        foreach ($scripts as $handle => $data) {
            wp_enqueue_script($handle, $data['source'], $data['dependencies'], $data['version'], $data['in_footer']);
        }
    }
}