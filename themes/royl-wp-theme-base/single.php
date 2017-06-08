<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package royl-wp-theme-base
 */

use Royl\WpThemeBase\Util;
get_header();
?>
<section class="site-section" id="posts">
    <div class="site-section__content">
        <?php
        // the loop
		while ( have_posts() ) : the_post();
            get_template_part( 'template-parts/post/content', get_post_format() );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
        endwhile;
        
		the_posts_pagination( array(
			'prev_text' => '<span class="previous" aria-label="previous">' . Util\Text::translate( 'Previous page' ) . '</span>',
			'next_text' => '<span class="next" aria-label="next">' . Util\Text::translate( 'Next page' ) . '</span>',
		) );
        ?>
    </div>
</section>
<?php get_sidebar(); ?>
<?php get_footer();