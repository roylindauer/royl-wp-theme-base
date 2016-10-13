# Ajax

Usage: /wp-admin/admin-ajax.php?action=ns_ajax&c=<CLASS>&m=<METHOD>&_wpnonce=<NONCE>

Params for ajax request:
* c         = class to instantiate
* m         = method to run
* _wpnonce  = WordPress Nonce
* display   = json,html

## Naming Conventions

The method parameter should be the "tableized" version of the method name, ie: all lowercase with underscores to separate words. eg: do_thing, my_method. 

The method name will be prefixed with 'ajax_' and run through an Inflector to properly camelCase it. `do_thing` would become `ajaxDoThing`. 

In your class you would create a method called `ajaxDoThing`. 

The class parameter should be the lowercase version of your class name. 

Classes can be named whatever makes sense for your application. They must to be namespaced to `\ECS\Theme\App`

## Example

Create a class called Test.class.php under Ecs/App.

```
namespace ECS\Theme\App;

class Test
{
    public function ajaxDoThing()
    {
        return array('message' => 'testing');
    }
}
```

Output can be rendered as JSON, or HTML

Generate a nonce: `wp_create_nonce('execute_ajax_nonce');`

The class would be `test` and the method would be `do_thing`.

Execute `/wp-admin/admin-ajax.php?action=ns_ajax&c=test&m=do_thing&_wpnonce=<NONCE>`

The default response will be JSON. You can control the output by passing the display parameter: 

Execute `/wp-admin/admin-ajax.php?action=ns_ajax&c=test&m=do_thing&_wpnonce=<NONCE>&display=html`

This could be useful if you are just returning back HTML or some text data. 

```
namespace ECS\Theme\App;

class Test
{
    public function ajaxDoThing()
    {
        return 'Hello, Worl?';
    }
}
```

