<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;

/**
 * TaxonomyRegistry
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class TaxonomyRegistry
{
    /**
     * Collection of post type objects.
     *
     * @var array $post_types collection of post type objects
     */
    public $taxonomies = [];
	
	/**
	 * 
	 */
	public function __construct() {
		add_action('init', [&$this, 'registerTaxonomies']);
	}

    /**
     * Register Taxonomies
     *
     * @return void
     */
    public function registerTaxonomies()
    {
        $taxonomies = Util\Configure::read('taxonomies');

        if (empty($taxonomies)) {
            return;
        }

        foreach ($taxonomies as $name => $opts) {
            $this->taxonomies[$name] = new Wp\TaxonomyType($name, $opts['params'], $opts['args']);
        }
    }
}