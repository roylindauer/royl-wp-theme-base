<?php
/**
 * The main template file
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package royl-wp-theme-base
 */

use Royl\WpThemeBase\Wp;
use Royl\WpThemeBase\Util;
get_header();
?>
<section class="site-section" id="posts">
  <div class="site-section__content">
    <?php
    if ( have_posts() ) :
      ?>
      <ul class="listing">
      <?php
      while ( have_posts() ) : the_post();
        ?><li class="listing__item"><?php
        get_template_part( 'template-parts/post/content', get_post_format() );
        ?></li><?php
      endwhile;
      ?>
      </ul>
      <?php
      the_posts_pagination( array(
          'prev_text' => '<span class="previous" aria-label="previous">' . Util\Text::translate( 'Previous page' ) . '</span>',
          'next_text' => '<span class="next" aria-label="next">' . Util\Text::translate( 'Next page' ) . '</span>',
          'before_page_number' => '<span class="meta-nav screen-reader-text">' . Util\Text::translate( 'Page' ) . ' </span>',
      ) );
    else :
      get_template_part( 'template-parts/post/content', 'none' );
    endif;
    ?>
  </div>
</section>
<?php get_sidebar(); ?>
<?php get_footer();