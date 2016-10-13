<?php

/**
 * Define the core configuration of the Theme.
 * Specify custom post types, taxonomies, etc., here.
 */

// Set theme config
$config = array(
    // Define Post Types
    // Uncomment below and edit to create custom post types
    /*
    'post_types' => array(
        'Cover' => array(
            // Default supports
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'revisions',
            ),
            // Default args
            'args' => array(
                'description' => \Royl\WpThemeBase\__('Describe the post type'),
            )
        )
    ),
    */

    // Define Taxonomies
    // https://codex.wordpress.org/Function_Reference/register_taxonomy
    // Uncomment below and edit to create taxonomies
    /*
    'taxonomies' => array(
        'cover_categories' => array(
            'params' => array(
                'post_types'   => array('cover', 'post', 'page'), // this can include built in post types (page, post, etc)
            ),
            'args' => array(
                'label'        => \Royl\WpThemeBase\__('Cover Categories'),
                'rewrite'      => 'cover_categories',
                'hierarchical' => true,
            )
        ),
        'cover_tags' => array(
            'params' => array(
                'post_types'   => array('cover'),
            ),
            'args' => array(
                'label'        => \Royl\WpThemeBase\__('Cover Tags'),
                'rewrite'      => 'cover_tags',
                'hierarchical' => false,
                'show_admin_column' => false
            )
        ),
    ),
    */

    // Define theme features
    // http://codex.wordpress.org/Function_Reference/add_theme_support
    /*
    'theme_features' => array(
        'automatic-feed-links',
        'post-thumbnails',
        'post-formats' => array(
            'aside',
            'gallery'
        )
    ),
    */

    // Custom Image Sizes
    /*
    'image_sizes' => array(
        'cover_large' => array(
            'width' => 1920,
            'height' => 1080,
            'crop' => false
        ),
    ),
    */

    // Define custom nav menus
    // https://codex.wordpress.org/Function_Reference/register_nav_menu
    /*
    'menus' => array(
        'main-menu'   => \Royl\WpThemeBase\__('Main Menu'),
        'sub-menu'    => \Royl\WpThemeBase\__('Sub Menu'),
        'footer-menu' => \Royl\WpThemeBase\__('Footer Menu')
    ),
    */

    // Define custom sidebars
    // https://codex.wordpress.org/Function_Reference/register_sidebar
    /*
    'sidebars' => array(
        array(
            'id'            => 'my-custom-sidebar',
            'name'          => \Royl\WpThemeBase\__('My Custom Sidebar'),
            'description'   => '',
            'class'         => '',
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ),
    ),
    */

    // Define theme dependencies
    // Require WP Plugins - http://tgmpluginactivation.com/
    // Require Core PHP Classes / Libraries
    /*
    'dependencies' => array(
        'plugins' => array(
            // MetaBox is amazing, and we use it in the PostType model
            array(
                'name'      => 'Meta Box',
                'slug'      => 'meta-box',
                'required'  => true,
            ),
            // Options Framework is also amazing
            array(
                'name'      => 'Options Framework',
                'slug'      => 'options-framework',
                'required'  => false,
            ),
            array(
                'name'      => 'Wordpress SEO',
                'slug'      => 'wordpress-seo',
                'required'  => true,
            ),
        ),
        'classes' => array(
            'Imagick',
        ),
    ),
    */
    
    // Define stylesheets and scripts
    // WordPress core throws a NOTICE if you don't enqueue at least one stylesheet
    // This bug should be patched in WordPress core 4.2.3
    'assets' => array(
        'stylesheets' => array(
            'style' => array(
                'source' => get_stylesheet_directory_uri() . '/style.css',
                'dependencies' => false,
                'version' => \Royl\WpThemeBase\Util\Configure::read('version')
            ),
        ),
        #'scripts' => array(
        #    'main' => array(
        #        'source' => get_stylesheet_directory_uri() . '/assets/js/main.js',
        #        'dependencies' => false,
        #        'version' => \Ecs\Core\Utilities\Configure::read('version'),
        #        'in_footer' => true
        #    ),
        #)
    )
);

// Register the config array with the Configure class
\Royl\WpThemeBase\Util\Configure::set($config);
