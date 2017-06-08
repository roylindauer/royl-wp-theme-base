<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package _s
 */

get_header(); ?>

<section class="site-section" id="notfound" role="main">
    <div class="site-section__content">

      <article class="error-404 not-found">
          <header class="entry-header">
            <h1 class="page-title"><?php echo \Royl\WpThemeBase\Util\Text::translate( 'Oops! That page can&rsquo;t be found.' ); ?></h1>
          </header>
          <div class="entry-content">
            <p><?php echo \Royl\WpThemeBase\Util\Text::translate( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?' ); ?></p>
            <?php
              get_search_form();

              the_widget( 'WP_Widget_Recent_Posts' );

              $archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', '_s' ), convert_smilies( ':)' ) ) . '</p>';
              the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );

              the_widget( 'WP_Widget_Tag_Cloud' );
            ?>

          </div>
      </article>
  </div>
</section>

<?php
get_footer();
