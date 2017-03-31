<?php

namespace Royl\WpThemeBase\Wp;

/**
 * WordPress Taxonomy Base Class
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class TaxonomyType
{

    /**
     * Name of this taxonomy.
     *
     * @var string $name
     */
    public $name = '';

    /**
     * Default args.
     *
     * @var array $args
     */
    public $args = array();
    
    /**
     * Label sets.
     *
     * @var array $labels
     */
    public $labels = array();

    /**
     * Class Constructor
     * @param string $name   The name of the taxonomy
     * @param array  $params currently only an array of posttypes to attach tax to
     * @param array  $args   array of tax arguments (labels and capabilities, etc)
     */
    public function __construct($name, $params = array(), $args = array())
    {
        $this->name = $name;

        $this->Inflector = new \Doctrine\Common\Inflector\Inflector();
        
        // Convention over configuration!
        $singular = \Royl\WpThemeBase\Util\Text::humanize($this->name);
        $plural   = \Royl\WpThemeBase\Util\Text::humanize($this->Inflector->pluralize($this->name));

        $this->labels = array(
            'name' =>                   $plural,
            'singular_name' =>          $singular,
            'add_new' =>                sprintf(\Royl\WpThemeBase\Util\Text::translate('Add New %s'), $singular),
            'add_new_item' =>           sprintf(\Royl\WpThemeBase\Util\Text::translate('Add New %s'), $singular),
            'edit_item' =>              sprintf(\Royl\WpThemeBase\Util\Text::translate('Edit %s'), $singular),
            'new_item_name' =>          sprintf(\Royl\WpThemeBase\Util\Text::translate('New %s'), $singular),
            'all_items' =>              sprintf(\Royl\WpThemeBase\Util\Text::translate('All %s'), $plural),
            'view_item' =>              sprintf(\Royl\WpThemeBase\Util\Text::translate('View %s'), $singular),
            'search_items' =>           sprintf(\Royl\WpThemeBase\Util\Text::translate('Search %s'), $plural),
            'popular_items' =>          sprintf(\Royl\WpThemeBase\Util\Text::translate('Popular %s'), $plural),
            'not_found' =>              sprintf(\Royl\WpThemeBase\Util\Text::translate('No %s found'), $plural),
            'not_found_in_trash' =>     sprintf(\Royl\WpThemeBase\Util\Text::translate('No %s found in trash'), $plural),
            'parent_item' =>            sprintf(\Royl\WpThemeBase\Util\Text::translate('Parent %s'), $singular),
            'parent_item_colon' =>      sprintf(\Royl\WpThemeBase\Util\Text::translate('Parent %s:'), $singular),
            'menu_name' =>              $plural,
            'separate_items_with_commas' => sprintf(\Royl\WpThemeBase\Util\Text::translate('Separate %s with commas'), $plural),
            'add_or_remove_items' =>    sprintf(\Royl\WpThemeBase\Util\Text::translate('Add or remove %s'), $plural),
            'choose_from_most_used' =>  sprintf(\Royl\WpThemeBase\Util\Text::translate('Choose from the most used %s'), $plural),
        );
        
        // Post type defaults
        $this->args = array(
            'labels' => $this->labels,
            'public' => true,
            'description' => '',
            'exclude_from_search ' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'slug' => $singular,
            'show_tagcloud' => true,
            'show_in_quick_edit' => true,
            'show_admin_column' => true
        );

        $this->args = array_merge($this->args, $args);

        $taxname = strtolower($this->name);

        // Do not re-register built in tax types..
        if (!in_array($taxname, array('category', 'tag'))) {
            register_taxonomy($taxname, $params['post_types'], $this->args);
        }

        /*
        https://codex.wordpress.org/Function_Reference/register_taxonomy
        Better be safe than sorry when registering custom taxonomies for custom post types. 
        Use register_taxonomy_for_object_type() right after the function to interconnect them. 
        Else you could run into minetraps where the post type isn't attached inside filter 
        callback that run during parse_request or pre_get_posts.
        */
        //
        foreach ($params['post_types'] as $post_type) {
            register_taxonomy_for_object_type($taxname, $post_type);
        }
    }
}
