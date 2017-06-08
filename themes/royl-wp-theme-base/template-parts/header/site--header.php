<?php
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;
?>
<header id="masthead" class="site__header" role="banner">
    <div class="header-content__main">
        <?php Wp\Template::load( 'header/header-branding' ); ?>
        <?php Wp\Template::load( 'header/header-menu' ); ?>
    </div>
</header><!-- .site-header -->