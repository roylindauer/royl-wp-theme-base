# How to include Rilwils MetaBox _"The Right Way"_

Add the plugin as a dependency in your themes config:

```
    'dependencies' => array(
        'plugins' => array(
            ... snip ...
            array(
                'name'      => 'Meta Box',
                'slug'      => 'meta-box',
                'required'  => true,
            ),
        ),
    ),
```

Go to wp-admin and install & activate the plugin.  

In Ecs/App/Theme.php in the run method add the action to register metaboxes

```
add_filter('rwmb_meta_boxes', array(&$this, 'registerMetaBoxes'));
```

Create the registerMetaBoxes method:

```
public function registerMetaBoxes($meta_boxes)
{
    // Define meta boxes here
    ...

    return $meta_boxes;
}
```

If you have a lot of MetaBoxes, and you probably will, a better solution might be to create a metabox config file under app/Ecs/Config/metaboxes.php

```
<?php

$meta_boxes = array();
// define metaboxes

\ECS\Theme\Utilities\Configure::write('meta_boxes', $meta_boxes);

```

Modify registerMetaBoxes:

```
public function registerMetaBoxes($meta_boxes)
{
    require_once APP_PATH . '/Ecs/Config/' . metaboxes.php;
    return \ECS\Theme\Utilities\Configure::read('meta_boxes');
}
```