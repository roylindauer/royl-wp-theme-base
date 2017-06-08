<?php

use Royl\WpThemeBase\Util;

require_once( 'src/autoload.php' );
require_once( 'src/royl_wp_theme_base.php' );

/**
 * Render post meta data. if post type is "post" will render tags and categories
 */
if ( !function_exists( 'royl_entry_footer' ) ) {
    function royl_entry_footer() {
        $separate_meta = Util\Text::translate( ', ' );
        $categories_list = get_the_category_list( $separate_meta );
        $tags_list = get_the_tag_list( '', $separate_meta );
        if ( $categories_list || $tags_list || get_edit_post_link() ) {
        ?>
        <footer class="entry-footer">
            <span class="entry-date"><?php echo Util\Text::translate( 'Posted on: ' ) ?><?php the_date( 'F j, Y' ); ?></span>
            <?php if ( 'post' === get_post_type() ): ?>
            <span class="entry-tags"><?php echo Util\Text::translate( 'Tags: ' ) ?><?php echo $tags_list; ?></span>
            <span class="entry-categories"><?php echo Util\Text::translate( 'Categories: ' ) ?><?php echo $categories_list; ?></span>
            <?php endif; ?>
        </footer>
        <?php
        }
    }
}

/**
 * Render custom logo
 */
if ( !function_exists( 'royl_custom_logo' ) ) {
    function royl_custom_logo() {
        if ( function_exists( 'the_custom_logo' ) ) {
            the_custom_logo();
        }
    }
}