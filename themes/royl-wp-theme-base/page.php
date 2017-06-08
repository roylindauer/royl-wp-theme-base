<?php
// Template for Pages

use Royl\WpThemeBase\Wp;
get_header(); ?>
<section class="site-section" id="pages">
    <div class="site-section__content">
        <?php
        // the loop
    	if ( have_posts() ) :
    		while ( have_posts() ) : the_post();
                get_template_part( 'template-parts/page/content', 'page' );
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
            endwhile;
        endif;
        ?>
    </div>
</section>
<?php get_footer();