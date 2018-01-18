<?php
// Template for posts

use Royl\WpThemeBase\Wp;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Ajax;
use Royl\WpThemeBase\Filter;
get_header();
?>
<section class="site-section" id="posts">
    <div class="site-section__content">

        <a href="#" class="js-ajax-test">AJAX TESTERINO</a>

        <?php
        // Render the filter form:
        Filter\Util::renderFilterForm( 'post-category' );

        // Get filtered query object:
        $query = Filter\Util::getFilterQuery( 'post-category' );

        // WordPress pagination is based on the Main query.
        // We have to kinda trick WP when we use a custom query object in the main loop.. 
        $temp_query = $wp_query;
        $wp_query = $query;
        ?>
        <?php
        // the loop
    	if ( $query->have_posts() ) :
            ?>
            <ul class="listing">
            <?php
    		while ( $query->have_posts() ) : $query->the_post();
                ?><li class="listing__item"><?php
                get_template_part( 'template-parts/post/content', get_post_format() );
                ?></li><?php
            endwhile;
            ?>
            </ul>
            <?php
    		the_posts_pagination( array(
    			'prev_text' => '<span class="previous" aria-label="previous">' . Util\Text::translate( 'Previous page' ) . '</span>',
    			'next_text' => '<span class="next" aria-label="next">' . Util\Text::translate( 'Next page' ) . '</span>',
    			'before_page_number' => '<span class="meta-nav screen-reader-text">' . Util\Text::translate( 'Page' ) . ' </span>',
    		) );
    	else :
    		get_template_part( 'template-parts/post/content', 'none' );
        endif;
        $wp_query = $temp_query;
        ?>
    </div>
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

<script type="text/javascript">

// Returns list of fields for filter set
$.ajax({
    url: '<?php echo Ajax\Util::url('FilterPost', 'getFields'); ?>',
    data: {
        'filter_set': 'post-category'
    },
    success: function(res){
        console.log(res);
    },
    error: function(res){
        console.log(res);
    }
});

// 
$('.js-ajax-test').click(function(evt){
    evt.preventDefault();
    $.ajax({
        url: '<?php echo Ajax\Util::url('FilterPost', 'doFilter'); ?>',
        data: {
            'filter_set': 'post-category',
            'filter_search': "test"
        },
        success: function(){
            console.log('success');
        },
        error: function(){
            console.log('error');
        }
    });
});
</script>
