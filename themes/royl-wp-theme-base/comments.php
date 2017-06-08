<?php
/**
 * The template for displaying comments
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package royl-wp-theme-base
 */

use Royl\WpThemeBase\Wp;
use Royl\WpThemeBase\Util;

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
  return;
}
?>

<div id="comments" class="comments-area">
  <?php
  if ( have_comments() ) : ?>
    <h2 class="comments-title">
      <?php
        $comments_number = get_comments_number();
        if ( $comments_number === '1' ) {
          /* translators: %s: post title */
          printf( Util\Text::translate( 'One Reply to &ldquo;%s&rdquo;' ), get_the_title() );
        } else {
          printf(
            /* translators: 1: number of comments, 2: post title */
            Util\Text::nx(
              '%1$s Reply to &ldquo;%2$s&rdquo;',
              '%1$s Replies to &ldquo;%2$s&rdquo;',
              $comments_number,
              'comments title'
            ),
            number_format_i18n( $comments_number ),
            get_the_title()
          );
        }
      ?>
    </h2>

    <ol class="comment-list">
      <?php
        wp_list_comments( array(
          'avatar_size' => 100,
          'style'       => 'ol',
          'short_ping'  => true,
          'reply_text'  => Util\Text::translate( 'Reply' ),
        ) );
      ?>
    </ol>
        
    <?php the_comments_pagination( array(
      'prev_text' => '<span class="previous" aria-label="previous">' . Util\Text::translate( 'Previous page' ) . '</span>',
      'next_text' => '<span class="next" aria-label="next">' . Util\Text::translate( 'Next page' ) . '</span>',
    ) );
  endif; // Check for have_comments().

  // If comments are closed and there are comments, let's leave a little note, shall we?
  if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
    <p class="no-comments"><?php echo Util\Text::translate( 'Comments are closed.' ); ?></p>
  <?php
  endif;
  comment_form();
  ?>
</div><!-- #comments -->
