<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php echo \Royl\WpThemeBase\Util\Text::translate( 'Nothing Found' ); ?></h1>
	</header>
	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p><?php printf( \Royl\WpThemeBase\Util\Text::translate( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
		<?php else : ?>
			<p><?php echo \Royl\WpThemeBase\Util\Text::translate( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.' ); ?></p>
			<?php 
            get_search_form();
		endif; ?>
	</div>
</section>
