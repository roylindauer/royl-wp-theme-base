<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package royl-wp-theme-base
 */

get_header(); ?>

<section class="site-section" id="error-404">
    <div class="site-section__content">
      <article class="error-404 not-found" role="main">
          <header class="entry-header">
            <h1 class="page-title"><?php echo \Royl\WpThemeBase\Util\Text::translate( 'Oops! That page can&rsquo;t be found.' ); ?></h1>
          </header>
          <div class="entry-content">
            <p><?php echo \Royl\WpThemeBase\Util\Text::translate( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?' ); ?></p>
            <?php get_search_form(); ?>
          </div>
      </article>
  </div>
</section>

<?php
get_footer();
