<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;

/**
 * PostTypeRegistry
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class PostTypeRegistry
{
    /**
     * Collection of post type objects.
     *
     * @var array $post_types collection of post type objects
     */
    public $post_types = array();
	
	/**
	 * 
	 */
	public function __construct() {
        add_action('init', array(&$this, 'loadPostTypes'));
	}

    /**
     * Load post type classes
     *
     * @return void
     */
    public function loadPostTypes()
    {
        $post_types = Configure::read('post_types');

        if ($post_types === false) {
            return;
        }

        foreach ($post_types as $post_type => $params) {
            $this->$post_types[$post_type] = new PostType($post_type, $params);
        }
    }
}