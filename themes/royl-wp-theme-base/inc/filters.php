<?php
namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Ajax;
use Royl\WpThemeBase\Filter;
use Royl\WpThemeBase\Wp;

/**
 * moving yoast seo stuff to the bottom
 */
add_filter( 'wpseo_metabox_prio', function(){
    return 'low';
});

/**
 * Allow widgets to parse shortcodes
 */
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Change screenreader title from H2 to span in pagination output
 *
 * @param String $template  - Comment Pagination Template HTML
 * @param String $class     - Passed HTML Class Attribute
 *
 * @return String $template - Return Modified HTML
 */
function change_reader_heading( $template, $class ) {
    if( ! empty( $class ) && false !== strpos( $class, 'pagination' ) ) {
        $template = preg_replace('#<h2.*?>(.*?)<\/h2>#si', '<span class="screen-reader-text">$1</span>', $template);
    }

    return $template;
}
add_filter( 'navigation_markup_template', 'change_reader_heading', 10, 2 );