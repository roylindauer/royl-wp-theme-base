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
        add_action('wp_enqueue_scripts', array(&$this, 'registerStylesheets'));
        add_action('wp_enqueue_scripts', array(&$this, 'registerScripts'));

        add_action('wp_enqueue_scripts', array(&$this, 'enqueueStylesheets'));
        add_action('wp_enqueue_scripts', array(&$this, 'enqueueScripts'));
	}

    /**
     * Does the actual work of registered stylesheets. Must be called before enqueue.
     *
     * @return void
     */
    public function registerStylesheets()
    {
        $stylesheets = Util\Configure::read('assets.stylesheets');

        if (empty($stylesheets)) {
            return;
        }

        foreach ($stylesheets as $handle => $data) {
            wp_register_style($handle, $data['source'], $data['dependencies'], $data['version']);
        }
    }

    /**
     * Does the actual work of enqueing stylesheets
     *
     * @return void
     */
    public function enqueueStylesheets()
    {
        $stylesheets = Util\Configure::read('assets.stylesheets');

        if (empty($stylesheets)) {
            return;
        }

        foreach ($stylesheets as $handle => $data) {
            wp_enqueue_style($handle, $data['source'], $data['dependencies'], $data['version']);
        }
    }

    /**
     * Does the actual work of registered scripts. Must be called before enqueue.
     *
     * @return void
     */
    public function registerScripts()
    {
        $scripts = Util\Configure::read('assets.scripts');

        if (empty($scripts)) {
            return;
        }

        foreach ($scripts as $handle => $data) {
            wp_register_script($handle, $data['source'], $data['dependencies'], $data['version'], $data['in_footer']);
        }
    }

    /**
     * Does the actual work of enqueing scripts
     *
     * @return void
     */
    public function enqueueScripts()
    {
        $scripts = Util\Configure::read('assets.scripts');

        if (empty($scripts)) {
            return;
        }

        foreach ($scripts as $handle => $data) {
            wp_enqueue_script($handle, $data['source'], $data['dependencies'], $data['version'], $data['in_footer']);
        }
    }
}