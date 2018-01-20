<?php
// Template for posts

use Royl\WpThemeBase\Wp;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Ajax;
use Royl\WpThemeBase\Filter;
get_header();
?>

<div class="container">

    <div class="row">
        <div class="col-sm-9">
            <section class="site-section" id="posts">
                <div class="site-section__content">

                    <?php Wp\Template::load( 'filter-ajax-test' ); ?>

                    <?php Wp\Template::load( 'filter-form-test', [ 'wp_query' => $wp_query ] ); ?>

                </div>
            </section>
        </div>
        <div class="col-sm-3">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
