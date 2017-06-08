<?php
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;
?>
<div class="header-menu">
    <?php if ( has_nav_menu( 'header-main' ) ) : ?>
    	<?php wp_nav_menu( array(
    		'theme_location' => 'header-main',
    		'menu_id'        => 'header-menu-main',
            'menu_class'     => 'main__nav-items',
            'container'      => 'nav',
            'container_class' => 'main__nav',
            'container_id'    => 'header-menu-main-wrapper'
    	) ); ?>
    <?php endif; ?>

    <?php if ( has_nav_menu( 'header-secondary' ) ) : ?>
    	<?php wp_nav_menu( array(
    		'theme_location' => 'header-secondary',
    		'menu_id'        => 'header-menu-secondary',
            'menu_class'     => 'secondary__nav-items',
            'container'      => 'nav',
            'container_class' => 'secondary__nav',
            'container_id'    => 'header-menu-secondary-wrapper'
    	) ); ?>
    <?php endif; ?>
</div>