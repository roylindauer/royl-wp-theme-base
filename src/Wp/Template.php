<?php

namespace Royl\WpThemeBase\Wp;

/**
 * WordPress Tools - Templates
 *
 * This class is for doing WordPress-related things in code that we do
 * commonly, such as creating a new post type or creating an image attachment.
 *
 * @package     WpThemeBase
 * @subpackage  Wp
 * @author      Tim Shaw
 * @author      Nitish Narala
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Template
{
    /**
     * Renders a template partial.
     * This method allows us to pass data to the template partial instead of relying on nasty globals
     * The irony of globally including wp_query is not lost on me.
     *
     * @param  [type] $partial [description]
     * @param  array  $data    [description]
     * @return [type]          [description]
     */
    public static function renderPartial($partial, $data = array(), $absdir = false)
    {
        global $wp_query;

        $partial_dir = \Royl\WpThemeBase\Util\Configure::read('partial_dir');
        if (!$partial_dir) {
            $partial_dir = 'partials';
        }

        $wp_query->query_vars = array_merge($wp_query->query_vars, $data);

        $file = $partial_dir . '/' . $partial . '.php';
		
		if ($absdir) {
			load_template($absdir . '/' . $file, false);
		} else {
			locate_template($file, true, false);
		}
    }
}
