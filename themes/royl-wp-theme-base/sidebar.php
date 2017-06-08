<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package royl-wp-theme-base
 */

if ( ! is_active_sidebar( 'default-sidebar' ) ) {
	return;
}
?>
<aside id="sidebar" class="widget-area" role="complementary">
    <?php dynamic_sidebar( 'default-sidebar' ); ?>
</aside>