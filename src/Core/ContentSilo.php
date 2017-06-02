<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Wp;

/**
 * Content Siloing
 *
 * Allow content creators to define a custom url for a post.
 * Custom url is stored in a routes table
 * We hook into WordPress early to look up the route in the table, and to set the appropriate values in the global wp_query object 
 * 
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class ContentSilo
{
    public $tableName = 'siloroutes';
    
    public function __construct() {
        
        add_action('add_meta_boxes', array(&$this, 'addMetaBox'));
        add_action('save_post', array(&$this, 'saveCustomMetabox'), 10, 3);
        add_action('parse_request', array(&$this, 'parseRequest'), PHP_INT_MAX - 1, 1);
        add_action('parse_query', array(&$this, 'parseQuery'), PHP_INT_MAX - 1, 1);
        add_action('init', array(&$this, 'addRewriteRules'));
        add_action('admin_init', function(){
            $this->init();
        });
        
        add_filter('query_vars', array(&$this, 'queryVars'));
    }
    
    public function addRewriteRules() {
        add_rewrite_rule('^(.*)', 'index.php?siloing=true', 'bottom');
    }
    
    public function queryVars($query_vars) {
        $query_vars[] = 'is_siloing';
        return $query_vars;
    }
    
    public function parseRequest(\WP $wp) {
        global $wpdb;
        
        $url = '/' . $wp->request;
        $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . $this->tableName . ' WHERE url = "%s"', $url);
        $result = $wpdb->get_row($sql, ARRAY_A);
        
        if ($result !== null) {
            remove_action('template_redirect', 'redirect_canonical');
            $post = get_post($result['post_id']);
            
		    $wp->query_vars['p']    = $result['post_id'];
            $wp->query_vars['post_name'] = $post->post_name;
            $wp->query_vars['page'] = 0;
            $wp->query_vars['is_siloing'] = true;
            $wp->query_vars['post_type'] = 'post';
        }
    }
    
    public function parseQuery($query) {
        return $query;
    }
    
    public function addMetaBox() {
        add_meta_box('silo-route-id', Util\Text::translate('Content Siloing'), array(&$this, 'renderField'), 'post', 'normal');
    }
    
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
        
        $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . $this->tableName . ' WHERE post_id = ' . $post_id, ARRAY_A);
        if ($result === null) {
            $wpdb->insert($wpdb->prefix . $this->tableName, array('url' => $metabox_custom_url_path, 'post_id' => $post_id), array('%s', '%d'));
        } else {
            $wpdb->update($wpdb->prefix . $this->tableName, array('url' => $metabox_custom_url_path, 'post_id' => $post_id), array('post_id' => $post_id), array('%s', '%d'));
        }
        
        
    }
    
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