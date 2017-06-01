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
    /**
     * Load and render a template.
     *
     * @param  string $template name of the template to load
     * @param  array  $data     array of data to make available to the template
     * @return bool             return truthy value
     */
    public static function load($template, $data = array())
    {
        try {
            // Allow users to change the default location of template partials
            $tpl_dir = Util\Configure::read('templates_dir');
            if (!$tpl_dir) {
                $tpl_dir = 'templates';
            }

            $filepath = trailingslashit( get_stylesheet_directory() ) . $tpl_dir . '/' . $template . '.php';
        
            if ( is_array( $data ) ) {
                extract( $data );
            }
        
            if ( file_exists( $filepath ) ) {
                return include ( $filepath );
            }
        
            throw new \Exception( __( sprintf( 'Template not found: %s', $filepath ) ) );
        } catch ( \Exception $e ) {
            Util\Debug::log( $e->getMessage() );
        }
    }
    
    /**
     * Get custom logo
     */
    public static function logo() {
        if ( function_exists( 'the_custom_logo' ) ) {
            the_custom_logo();
        }
    }
}
