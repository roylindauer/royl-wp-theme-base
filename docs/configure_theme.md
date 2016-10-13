# Configure Theme

Config Files:

* app/Ecs/Config/core.php

# Add custom post types

New post types can be added by passing in a config array to the Theme::run() method.

```
// Define Post Types
$config['post_types] => array(
    'Cover'
));
```

The default args and post type supports options below can be overridden in the post type config array you pass to the Theme::run() method. 

```
// Supports
array(
    'title',
    'editor',
    'page-attributes',
    'author',
    'thumbnail',
    'custom-fields',
    'revisions',
    'page-attributes',
    'post-formats'
)

// Post Type Args
array(
    'description' => '',
    'public' => true,
    'exclude_from_search ' => false,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => true,
    'has_archive' => true,
    'hierarchical' => false,
    'slug' => 'cover'
)
```

For example:

```
$config['post_types'] => array(
    'Cover' => array(
        'supports' => array(
            'title',
            'editor',
            'page-attributes',
            'author',
            'thumbnail',
            'custom-fields',
            'revisions',
            'page-attributes',
            'post-formats',
        ),
        'args' => array(
            'description' => \ECS\Theme\Helpers\__('Ima Post Type!'),
            'description' => '',
            'public' => true,
            'exclude_from_search ' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'slug' => 'cover'
        )
    )
));
```


# Taxonomies

# Plugin Dependencies

# Class Registry

# Front end assets

# Shortcodes

# Theme Features

# Menus

# Sidebars