<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Ajax;
use Royl\WpThemeBase\Filter;

// Load the Autoloader
require_once 'inc/autoload.php';

// WP Actions and Filters and Bootstrapyness
require_once 'inc/init.php';
require_once 'inc/filters.php';

// Include template tags
require_once 'inc/template-tags.php';

/*****************************************************************************
 I N I T   C O R E   T H E M E   O B J E C T S  &   S E R V I C E S
*****************************************************************************/

$Ajax             = new Ajax\Ajax();
$CoreFilter       = new Filter\Filter();
$PostTypeRegistry = new PostTypeRegistry();
$TaxonomyRegistry = new TaxonomyRegistry();
$VanityUrlRouter  = new VanityUrlRouter();

// Set some theme specific configurations in the global Config
if (function_exists('wp_get_theme')) {
    $curtheme = wp_get_theme();
    Util\Configure::write('name', $curtheme->get('Name'));
    Util\Configure::write('domain', $curtheme->get('TextDomain'));
    Util\Configure::write('version', $curtheme->get('Version'));
    unset($curtheme);
}
