<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;

/**
 * Content Siloing
 *
 * Allow content creators to define a custom url for a post.
 * Custom url is stored in a routes table
 * 
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 * @todo - add permalink filters
 */
class ContentSilo
{
    public $tableName = 'siloroutes';
    
    public function __construct() {
        
        add_action('add_meta_boxes', array(&$this, 'addMetaBox'));
        add_action('save_post', array(&$this, 'saveCustomMetabox'), 10, 3);
        
        add_action('parse_request', array(&$this, 'parseRequest'), PHP_INT_MAX - 1, 1);

        add_action('init', array(&$this, 'addRewriteTags'));
        add_action('init', array(&$this, 'addRewriteRules'));
        
        add_action('admin_init', function(){
            $this->init();
        });
        
        add_filter('post_link', array(&$this, 'permalinks'), 10, 3);
        add_filter('page_link', array(&$this, 'permalinks'), 10, 3);
        add_filter('post_type_link', array(&$this, 'permalinks'), 10, 3);
        add_filter('query_vars', array(&$this, 'queryVars'));
    }
    
    /**
     * Create rewrite rules for every entry in the routes table
     */
    public function addRewriteRules() {
        $routes = $this->getRoutes();
        foreach ($routes as $route) {
            add_rewrite_rule('^' . $route['url'] . '$', 'index.php?is_siloing=true', 'top');
        }
        #add_rewrite_rule($this->rewriteRule, 'index.php?is_siloing=true', 'top');
    }
    
    public function addRewriteTags() {
        add_rewrite_tag('is_siloing', '([^&]+)');
    }
    
    /**
     * Add siloing query vars
     */
    public function queryVars($query_vars) {
        $query_vars[] = 'is_siloing';
        return $query_vars;
    }
    
    /**
     * Will return the user defined route if it is available
     *
     * @param  string       $link
     * @param  WP_Post|int  $post
     * @param  bool         $leavename
     * @return string
     */
    public function permalinks($link, $post, $leavename) {

        if (!is_object($post)) {
            $post = get_post($post);
        }
        
        $result = $this->getSiloRouteByID($post->ID);
        
        if ($result !== null) {
            return get_site_url(null, $result['url']);
        }
        
        return $link;
    }
    
    /**
     * Get all silo routes
     * 
     * @return null|array
     */
    private function getRoutes() {
        global $wpdb;
        return $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . $this->tableName, ARRAY_A);
    }
    
    /**
     * Retrieve siloroute record by post id
     *
     * @param  int  $post_id
     * @return null|array
     */
    private function getSiloRouteByID($post_id) {
        global $wpdb;
        return $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . $this->tableName . ' WHERE post_id = ' . $post_id, ARRAY_A);
    }
    
    /**
     * Retrieve siloroute record by exact url
     *
     * @param  string  $url
     * @return null|array
     */
    private function getPostByURL($url) {
        global $wpdb;
        $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . $this->tableName . ' WHERE url = "%s"', $url);
        $result = $wpdb->get_row($sql, ARRAY_A);
        return $result;
    }
    
    /**
     * Hijack the initial request.
     *
     * Checks for matching siloroute, if found then populates query params with the proper post data
     *
     * @param  WP  $wp
     * @return null|array
     */
    public function parseRequest(\WP $wp) {
        global $wp_rewrite, $wp_query;
        $result = $this->getPostByURL($wp->request);
        
        if ($result !== null) {
            /*
             * WordPress will redirect to the internal canonical url unless we 
             * explicitly tell it not to here. 
             */
            remove_action('template_redirect', 'redirect_canonical');
            
            /*
             * Manually set query vars based on our matched route.
             * Normally WordPress would auto fill this information.
             */
            $post = get_post($result['post_id']);
		    $wp->query_vars['p']         = $post->ID;
            $wp->query_vars['post_name'] = $post->post_name;
            $wp->query_vars['post_type'] = $post->post_type;
        }
    }
    
    /**
     * Add custom meta box to post and pages
     */
    public function addMetaBox() {
        add_meta_box('silo-route-id', Util\Text::translate('Content Siloing'), array(&$this, 'renderField'), ['post', 'page'], 'normal');
    }
    
    /**
     * Render custom meta box field
     */
    public function renderField($post) {
        wp_nonce_field(basename(__FILE__), 'silo-customurl-nonce');
        
        global $wpdb;
        
        $metabox_custom_url_path = '';
        $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . $this->tableName . ' WHERE post_id = ' . $post->ID, ARRAY_A);
        if ($result !== null) {
            $metabox_custom_url_path = $result['url'];
        }
        ?>
        <div>
            <label for="custom-url-path"><?php echo Util\Text::translate('Custom URL Path') ?></label>
            <input name="custom-url-path" type="text" value="<?php echo $metabox_custom_url_path; ?>">
            <p><small><?php echo Util\Text::translate('eg: /my-content-silo/secondary-silo/name-of-the-post') ?></small></p>
        </div>
        <?php
    }
    
    /**
     * Save meta box data
     */
    public function saveCustomMetabox($post_id, $post, $update) {
        if (!isset($_POST['silo-customurl-nonce']) || !wp_verify_nonce($_POST['silo-customurl-nonce'], basename(__FILE__))) {
            return $post_id;
        }
        
        if(!current_user_can("edit_post", $post_id)) {
            return $post_id;
        }
        
        if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
            return $post_id;
        }
        
        global $wpdb;
        
        $metabox_custom_url_path = '';
        if (isset($_POST['custom-url-path'])) {
            $metabox_custom_url_path = sanitize_text_field($_POST['custom-url-path']);
        }
        
        $result = $this->getSiloRouteByID($post_id);
        if ($result === null) {
            $wpdb->insert($wpdb->prefix . $this->tableName, array('url' => $metabox_custom_url_path, 'post_id' => $post_id), array('%s', '%d'));
        } else {
            $wpdb->update($wpdb->prefix . $this->tableName, array('url' => $metabox_custom_url_path, 'post_id' => $post_id), array('post_id' => $post_id), array('%s', '%d'));
        }
        
        
    }
    
    /**
     * Setup content silo
     */
    private function init() {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->tableName;
        
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                url varchar(255) DEFAULT '' NOT NULL,
                post_id mediumint(9) NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
}