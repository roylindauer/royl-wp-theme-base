<?php

namespace Royl\WpThemeBase\Wp;
use \Royl\WpThemeBase\Util;

/**
 * WordPress Post Type base class
 *
 * @package     WpThemeBase
 * @subpackage  Wp
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class PostType
{

    /**
     * ID of this post type. aka the all lowercase no spaces version of $name
     *
     * @var string $name
     */
    public $id = '';

    /**
     * Name of this post type.
     *
     * @var string $name
     */
    public $name = '';
    
    /**
     * The type of post type. Or the post types type...
     *
     * @var string $post
     */
    public $type = 'post';
    
    /**
     * Post type support features.
     *
     * @var array $supports
     */
    public $supports = [
        'title',
        'editor',
        'page-attributes',
        'author',
        'thumbnail',
        'custom-fields',
        'revisions',
        'page-attributes',
        'post-formats',
    ];

    /**
     * Default args.
     *
     * @var array $args
     */
    public $args = array[];
    
    /**
     * Label sets.
     *
     * @var array $labels
     */
    public $labels = array[];

    /**
     * Class Constructor. Does the heavy lifting of registering posttype
     * @param string $name   Name of the post type to generate
     * @param array  $params Array of options to configure posttype
     */
    public function __construct($name, $params = array[]
    {
        $this->name = $name;
        $this->id   = strtolower($this->name);

        if (in_array($this->id, ['post', 'page', 'attachment', 'revision', 'nav_menu_item'])) {
            Util\Debug::addThemeError(sprintf('Post type "%s" is reserved', $this->id));
            return;
        }

        if (isset($params['supports'])) {
            $this->supports = $params['supports'];
        }

        $this->Inflector = new \Doctrine\Common\Inflector\Inflector();
        
        // Convention over configuration!
        $singular = \Royl\WpThemeBase\Util\Text::humanize($this->name);
        $plural   = \Royl\WpThemeBase\Util\Text::humanize($this->Inflector->pluralize($this->name));
        
        $this->labels = [
            'name' =>                   $plural,
            'singular_name' =>          $singular,
            'add_new' =>                sprintf(\Royl\WpThemeBase\Util\Text::translate('Add New %s'), $singular),
            'add_new_item' =>           sprintf(\Royl\WpThemeBase\Util\Text::translate('Add New %s'), $singular),
            'edit_item' =>              sprintf(\Royl\WpThemeBase\Util\Text::translate('Edit %s'), $singular),
            'new_item' =>               sprintf(\Royl\WpThemeBase\Util\Text::translate('New %s'), $singular),
            'all_items' =>              sprintf(\Royl\WpThemeBase\Util\Text::translate('All %s'), $plural),
            'view_item' =>              sprintf(\Royl\WpThemeBase\Util\Text::translate('View %s'), $singular),
            'search_items' =>           sprintf(\Royl\WpThemeBase\Util\Text::translate('Search %s'), $plural),
            'not_found' =>              sprintf(\Royl\WpThemeBase\Util\Text::translate('No %s found'), $plural),
            'not_found_in_trash' =>     sprintf(\Royl\WpThemeBase\Util\Text::translate('No %s found in trash'), $plural),
            'parent_item_colon' =>      '',
            'menu_name' =>              $plural
        ];
        
        // Post type defaults
        $this->args = [
            'labels' => $this->labels,
            'description' => '',
            'public' => true,
            'exclude_from_search ' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => $this->type,
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => $this->supports,
            'slug' => $singular
        ];

        if (isset($params['args'])) {
            $this->args = array_merge($this->args, $params['args']);
        }

        register_post_type($this->id, $this->args);
    }
}
