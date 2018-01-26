<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Ajax;
use Royl\WpThemeBase\Filter;

// Load the Autoloader
require_once 'inc/autoload.php';

// Load core functions
require_once 'inc/functions.php';

// WP Actions and Filters and Bootstrapyness
require_once 'inc/init.php';
require_once 'inc/customizer.php';
require_once 'inc/filters.php';

// Include template tags
require_once 'inc/template-tags.php';

// Init core theme services
$Ajax             = new Ajax\Ajax();
$CoreFilter       = new Filter\Filter();
$VanityUrlRouter  = new Util\VanityUrlRouter();

// Set some theme specific configurations in the Theme Config
if ( function_exists( 'wp_get_theme' ) ) {
    $curtheme = wp_get_theme();
    Util\Configure::write( 'name', $curtheme->get('Name') );
    Util\Configure::write( 'domain', $curtheme->get('TextDomain') );
    Util\Configure::write( 'version', $curtheme->get('Version') );
    unset( $curtheme );
}
