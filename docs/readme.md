# Overview

Do not edit core. Do not edit anything under the following directories:

* Ecs/Core
* Ecs/Utilities

If you wish to make changes to a Utility simply extend the class in App. 

All customizations and new functionaly should be under Ecs/App.

# Init Theme

Ecs/App/Theme.class.php. 

Any hooks and functions defined in Ecs/App/Theme::run() will be executed in theme initialization. 

```
class Theme
{
    public function run()
    {
        ///// Add Hooks Below /////
        add_action('init', array(&$this, 'myInitFunction'));
        add_action(...);
    }

    public function myInitFunction()
    {
        // do stuff...
    }
}
```

The theme initialization occurs in functions.php

```
$theme = new \ECS\Theme\App\Theme();
```

To initialize the theme you must now call the run method

```
$theme->run();
```

Finall add the theme object to the class registry. Objects in the registry can be retreived for use in templates and tin other classes without relying on "global".

```
\ECS\Theme\Helpers\register_object('Theme', $theme);
```

# Core Helpers Functions
* \ECS\Theme\Helpers\__()
* \ECS\Theme\Helpers\pr()
* \ECS\Theme\Helpers\debug()

