<?php
/**
 * Taxonomy Class. For registering taxonomies
 */

namespace Royl\WpThemeBase\Core;

/**
 * Taxonomy Base Class
 *
 * @package Royl\WpThemeBase\Core
 */
class Taxonomy
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

        $this->Inflector = new \Royl\WpThemeBase\Core\Inflector();
        
        // Convention over configuration!
        $singular = $this->Inflector->humanize($this->name);
        $plural   = $this->Inflector->humanize($this->Inflector->pluralize($this->name));

        $this->labels = array(
            'name' =>                   $plural,
            'singular_name' =>          $singular,
            'add_new' =>                sprintf(__('Add New %s'), $singular),
            'add_new_item' =>           sprintf(__('Add New %s'), $singular),
            'edit_item' =>              sprintf(__('Edit %s'), $singular),
            'new_item_name' =>          sprintf(__('New %s'), $singular),
            'all_items' =>              sprintf(__('All %s'), $plural),
            'view_item' =>              sprintf(__('View %s'), $singular),
            'search_items' =>           sprintf(__('Search %s'), $plural),
            'popular_items' =>          sprintf(__('Popular %s'), $plural),
            'not_found' =>              sprintf(__('No %s found'), $plural),
            'not_found_in_trash' =>     sprintf(__('No %s found in trash'), $plural),
            'parent_item' =>            sprintf(__('Parent %s'), $singular),
            'parent_item_colon' =>      sprintf(__('Parent %s:'), $singular),
            'menu_name' =>              $plural,
            'separate_items_with_commas' => sprintf(__('Separate %s with commas'), $plural),
            'add_or_remove_items' =>    sprintf(__('Add or remove %s'), $plural),
            'choose_from_most_used' =>  sprintf(__('Choose from the most used %s'), $plural),
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
