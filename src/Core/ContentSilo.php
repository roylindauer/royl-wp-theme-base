<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;

/**
 * Content Siloing
 *
 * Interesting plugins we should consider using to help support this:
 *     - https://wordpress.org/plugins/no-category-base-wpml/
 *     - https://wordpress.org/plugins/wp-category-permalink/
 *
 * hierarchical pages are inneficient and should be avoided. 
 * It seems, for now, the best structure here is to use taxonomies and posts
 *
 * @todo allow user to define mutliple content silo taxonomies
 * @todo render correct silo url when viewing list of posts
 * 
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class ContentSilo
{
    public $siloString   = '%silo%';
    public $siloTaxonomy = 'silos';
    public $siloDefault  = 'content';

    /**
     * 
     */
    public function __construct() {
        // Setup Silo Taxonomy
        Util\Configure::write('taxonomies.' . $this->siloTaxonomy, array(
            'params' => array(
                'post_types' => array('post')
            ),
            'args' => array(
                'description' => Util\Text::translate('Content Silos'),
                'hierarchical' => true,
                'show_in_rest' => true,
                'rewrite' => true,
                'query_var' => $this->siloTaxonomy,
                'public' => true,
                'show_ui' => true,
                'show_in_nav_menus' => true,
            )
        ));
        // Add silo to rewrite tag
        // This is required for WordPress to know what to do with our custom permalink
        add_action('init', array(&$this, 'silo_rewrite_tag'), 10, 0);
        // Setup Content Siloing Permalink Structure
        add_filter('post_link', array(&$this, 'silo_permalinks'), 10, 3);
        add_filter('post_type_link', array(&$this, 'silo_permalinks'), 10, 3);
    }

    /**
     * Setup Silo Permalink Structure
     * @return [type] [description]
     */
    public function silo_permalinks($permalink, $post) {
        

        if (strpos($permalink, $this->siloString) === FALSE) {
            return $permalink;
        }
        
        $post_id = $post->ID;

        $terms = wp_get_object_terms($post_id, $this->siloTaxonomy);
        if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
            $taxonomy_slug = [];
            foreach ($terms as $term) {
                $taxonomy_slug[] = $term->slug;
            }
            $taxonomy_slug = implode( '/', $taxonomy_slug );
        } else { 
            $taxonomy_slug = $this->siloDefault; // default "content"
        }

        return str_replace($this->siloString, $taxonomy_slug, $permalink);
    }

    /**
     * [silo_rewrite_tag description]
     * @return [type] [description]
     */
    public function silo_rewrite_tag() {
        add_rewrite_tag($this->siloString, '(.+)', sprintf('%s=', $this->siloTaxonomy));
    }
}