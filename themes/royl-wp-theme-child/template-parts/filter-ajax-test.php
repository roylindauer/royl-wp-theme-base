<?php
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Ajax;
use Royl\WpThemeBase\Filter;
?>
<div class="alert alert-info">AJAX FILTER TEST</div>
<a href="#" class="js-ajax-test">AJAX TESTERINO (look at your console)</a>
<div class="well" id="ajax-filter-test-output"></div>
<hr>

<script type="text/javascript">
$(document).ready(function(){
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
            success: function(res){
                console.log('success');
                console.log(res);
                $('#ajax-filter-test-output').html( 'found posts: ' + res.found_posts );
            },
            error: function(res){
                console.log('error');
            }
        });
    });
});
</script>