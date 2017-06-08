<?php
use Royl\WpThemeBase\Util;
?><article id="post-<?php the_ID(); ?>" <?php post_class('listing__item-container'); ?>>
    <header class="entry-header">
        <?php
		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        ?>
    </header>
    <div class="entry-content">
        <?php the_excerpt(); ?>
    </div>
    <?php royl_entry_footer(); ?>
</article>
