<?php

use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Filter;

/**
 * Render post metadata. if post type is "post" will render tags and categories
 */
if (!function_exists('royl_entry_footer')) {
    function royl_entry_footer(): void
    {
        $separate_meta = Util\Text::translate(', ');
        $categories_list = get_the_category_list($separate_meta);
        $tags_list = get_the_tag_list('', $separate_meta);
        if ($categories_list || $tags_list || get_edit_post_link()) {
            ?>
            <footer class="entry-footer">
                <span class="entry-date"><?php echo Util\Text::translate('Posted on: ') ?><?php the_date('F j, Y'); ?></span>
                <?php if ('post' === get_post_type()): ?>
                    <span class="entry-tags"><?php echo Util\Text::translate('Tags: ') ?><?php echo $tags_list; ?></span>
                    <span class="entry-categories"><?php echo Util\Text::translate('Categories: ') ?><?php echo $categories_list; ?></span>
                <?php endif; ?>
            </footer>
            <?php
        }
    }
}

/**
 * Render custom logo
 */
if (!function_exists('royl_custom_logo')) {
    function royl_custom_logo(): void
    {
        if (function_exists('the_custom_logo')) {
            the_custom_logo();
        }
    }
}

/**
 * Retrieve a Filtered Query Object
 */
if (!function_exists('royl_get_filter_query')) {
    function royl_get_filter_query($set): WP_Query|Filter\WP_Query
    {
        return Filter\Util::getFilterQuery($set);
    }
}

/**
 * Description: Render a form with filter fields
 */
if (!function_exists('royl_render_filter_form')) {
    function royl_render_filter_form($set): void
    {
        Filter\Util::renderFilterForm($set);
    }
}


if (!function_exists('get_the_posts_pagination')) {
    function get_the_posts_pagination(): void
    {
        the_posts_pagination(array(
            'prev_text' => '<span class="previous" aria-label="previous">' . Util\Text::translate('Previous page') . '</span>',
            'next_text' => '<span class="next" aria-label="next">' . Util\Text::translate('Next page') . '</span>',
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . Util\Text::translate('Page') . ' </span>',
        ));
    }
}
