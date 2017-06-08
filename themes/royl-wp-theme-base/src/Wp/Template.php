<?php

namespace Royl\WpThemeBase\Wp;
use Royl\WpThemeBase\Util;

/**
 * WordPress Tools - Templates
 *
 * @package     WpThemeBase
 * @subpackage  Wp
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Template
{
    public static $default_template_dir = 'template-parts';

    /**
     * Load and render a template.
     *
     * @param  string $template name of the template to load
     * @param  array  $data     array of data to make available to the template
     * @return bool             return truthy value
     */
    public static function load($template, $data = [])
    {
        try {
            // Allow users to change the default location of template partials
            $tpl_dir = Util\Configure::read('templates_dir');
            if (!$tpl_dir) {
                $tpl_dir = self::$default_template_dir;
            }

            $filepath = $tpl_dir . DIRECTORY_SEPARATOR . $template . '.php';
            $located = locate_template( $filepath, false );

            if (!$located) {
                throw new \Exception(__(sprintf('Template not found: %s', $filepath)));
            }

            if (is_array($data)) {
                extract($data);
            }
        
            return include ($located);
        } catch (\Exception $e) {
            Util\Debug::log($e->getMessage());
        }
    }
}
