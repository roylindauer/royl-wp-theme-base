<article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    </header>
    <div class="entry-content">
        <?php
            the_content(); 
			wp_link_pages( array(
				'before' => '<div class="page-links">' . \Royl\WpThemeBase\Util\Text::translate( 'Pages:' ),
				'after'  => '</div>',
			) );
        ?>
    </div>
    <?php royl_entry_footer(); ?>
</article>