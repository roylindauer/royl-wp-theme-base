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
        add_action('wp_enqueue_scripts', array(&$this, 'stylesheets'), 'frontend');
        add_action('wp_enqueue_scripts', array(&$this, 'scripts'), 'frontend');
        
        add_action('admin_enqueue_scripts', array(&$this, 'stylesheets'), 'admin');
        add_action('admin_enqueue_scripts', array(&$this, 'scripts'), 'admin');
        
        add_action('login_enqueue_scripts', array(&$this, 'stylesheets'), 'login');
        add_action('login_enqueue_scripts', array(&$this, 'scripts'), 'login');
	}

    /**
     * Does the actual work of registered stylesheets. Must be called before enqueue.
     *
     * @param  string  which set of assets to load (frontend, admin, login)
     * @return void
     */
    public function stylesheets($screen = 'frontend')
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
    public function scripts($screen = 'frontend')
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