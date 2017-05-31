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
     * Renders a template partial.
     *
     * @param  string $partial name of the template partial to load
     * @param  array  $data    array of data to make available to the template
     * @return bool            return truthy value
     */
    public static function load($partial, $data = array())
    {
        try {
            global $wp_query;

            // Allow users to change the default location of template partials
            $partial_dir = \Royl\WpThemeBase\Util\Configure::read('partial_dir');
            if (!$partial_dir) {
                $partial_dir = 'partials';
            }

            $filepath = trailingslashit( get_stylesheet_directory() ) . $partial_dir . '/' . $partial . '.php';
        
            if ( is_array( $data ) ) {
                extract( $data );
            }
        
            if ( file_exists( $filepath ) ) {
                return include ( $filepath );
            }
        
            throw new \Exception( __( sprintf( 'Template partial not found: %s', $filepath ) ) );
        } catch ( \Exception $e ) {
            Util\Debug::log( $e->getMessage() );
        }
    }
}
