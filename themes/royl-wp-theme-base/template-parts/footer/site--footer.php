<footer id="colophon" class="site__footer" role="contentinfo">

    <section class="footer-content">
        <div class="footer-content__content">
            <?php if ( has_nav_menu( 'footer-main' ) ) : ?>
        	<?php wp_nav_menu( array(
        		'theme_location' => 'footer-main',
        		'menu_id'        => 'footer-menu-main',
                'menu_class'     => 'footer__nav-items',
                'container'      => 'nav',
                'container_class' => 'footer__nav',
                'container_id'    => 'footer-menu-main-wrapper'
        	) ); ?>
            <?php endif; ?>
            
            <div class="footer-content__legal">
                <p class="footer-content__legal-content">&copy; <?php echo date('Y'); ?> <?php echo get_bloginfo( 'name' ); ?></p>
            </div>
        </div>
    </section>

    <?php wp_footer(); ?>
</footer>