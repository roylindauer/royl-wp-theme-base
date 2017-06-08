<?php
use Royl\WpThemeBase\Util;
?><article id="post-<?php the_ID(); ?>" <?php post_class('listing__item-container'); ?>>
    <header class="entry-header">
        <?php
		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
        ?>
    </header>
    <div class="entry-content">
        <?php
        the_content();
		wp_link_pages( array(
			'before'      => '<div class="page-links">' . Util\Text::translate( 'Pages:' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );
        ?>
    </div>
    <?php royl_entry_footer(); ?>
</article>