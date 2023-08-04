<?php
/**
 * The main template file
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package royl-wp-theme-base
 */

get_header();
?>
    <section class="site-section" id="posts">
        <div class="site-section__content">
            <?php
            // the loop
            if (have_posts()) :
                ?>
                <ul class="listing">
                    <?php
                    while (have_posts()) : the_post();
                        ?>
                        <li class="listing__item"><?php
                        get_template_part('template-parts/post/content', get_post_format());
                        ?></li><?php
                    endwhile;
                    ?>
                </ul>
                <?php
                get_the_posts_pagination();
            else :
                get_template_part('template-parts/post/content', 'none');
            endif;
            ?>
        </div>
    </section>
<?php get_sidebar(); ?>
<?php get_footer();