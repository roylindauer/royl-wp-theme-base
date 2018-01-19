<?php

namespace Royl\WpThemeBase\Core;

use Royl\WpThemeBase\Util;

/**
 * Vanity URL Router
 *
 * Allow content creators to define a custom url for a post.
 * Custom url is stored in a routes table
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class VanityUrlRouter
{
    public $tableName = 'vanityurls';
    public $queryVar = 'is_vanityurl';
    
    public function __construct()
    {
        /**
         * Define meta box to manage vanity url
         */
        add_action('add_meta_boxes', [&$this, 'addMetaBox']);
        add_action('save_post', [&$this, 'saveCustomMetabox'], 99, 3);

        /**
         * This does most of the work of handling the custoM URL
         */
        add_action('parse_request', [&$this, 'parseRequest'], PHP_INT_MAX - 1, 1);

        /**
         * We define a custom rewrite rule for EVERY VANITY URL
         * @todo  this may not be scalable, test it, and refactor if required
         */
        add_action('init', [&$this, 'addRewriteTags'], 10);
        add_action('init', [&$this, 'addRewriteRules'], 10);

        /**
         * Flushes rewrite rules if required. This must be executed after addRewriteRules()
         */
        add_action('init', [&$this, 'maybeUpdateRewriteRules'], 9999);

        /**
         * Does the initial setup. 
         * @todo  this should be part of theme activation
         */
        add_action('admin_init', function () {
            $this->init();
        });

        /**
         * Show vanity URLs in permalinks
         */
        add_filter('post_link', [&$this, 'permalinks'], 10, 3);
        add_filter('page_link', [&$this, 'permalinks'], 10, 3);
        add_filter('post_type_link', [&$this, 'permalinks'], 10, 3);

        /**
         * Adds a custom query var so we know we are in a vanity url request
         */
        add_filter('query_vars', [&$this, 'queryVars']);
    }

    /**
     * Flushes Rewrite Rules if required
     * @return [type] [description]
     */
    public function maybeUpdateRewriteRules() {
        if (get_option('vanityurl_requires_update')) {
            global $wp_rewrite;
            flush_rewrite_rules( false );
            $wp_rewrite->flush_rules();
            update_option('vanityurl_requires_update', false);
        }
    }

    /**
     * Create rewrite rules for every entry in the routes table
     */
    public function addRewriteRules()
    {
        $routes = $this->getRoutes();
        foreach ($routes as $route) {
            add_rewrite_rule('^' . $route['url'] . '$', 'index.php?' . $this->queryVar . '=true', 'top');
        }
    }

    public function addRewriteTags()
    {
        add_rewrite_tag($this->queryVar, '([^&]+)');
    }

    /**
     * Add query var
     */
    public function queryVars($query_vars)
    {
        $query_vars[] = $this->queryVar;
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
    public function permalinks($link, $post, $leavename)
    {

        if (!is_object($post)) {
            $post = get_post($post);
        }
        
        $result = $this->getVanityUrlRouteByID($post->ID);
        
        if ($result !== null) {
            return get_site_url(null, $result['url']);
        }
        
        return $link;
    }

    /**
     * Get all vanity url routes
     *
     * @return null|array
     */
    private function getRoutes()
    {
        global $wpdb;
        return $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . $this->tableName, ARRAY_A);
    }

    /**
     * Retrieve route record by post id
     *
     * @param  int  $post_id
     * @return null|array
     */
    private function getVanityUrlRouteByID($post_id)
    {
        global $wpdb;
        return $wpdb->get_row('SELECT *  FROM ' . $wpdb->prefix . $this->tableName . ' WHERE post_id = ' . $post_id . ' ORDER BY post_id ASC', ARRAY_A);
    }

    /**
     * Returns a clean URL
     * No trailing slas. No beginning slash. Returns a relative url
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    private function cleanUrl($url) {

        // Remove prefixed slash
        if (substr($url, 0, 1) == '/') {
            $url = ltrim($url, '/');
        }

        // Rermove trailing slash
        if (substr($url, 0, -1) == '/') {
            $url = rtrim($url, '/');
        }


        return $url;
    }

    /**
     * Retrieve vanity url route record by exact url
     *
     * @param  string  $url
     * @return null|array
     */
    private function getPostByURL($url)
    {
        global $wpdb;

        $url = $this->cleanUrl($url);
        $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . $this->tableName . ' WHERE url = "%s"', $url);
        $result = $wpdb->get_row($sql, ARRAY_A);
        return $result;
    }

    /**
     * Hijack the initial request.
     *
     * Checks for matching vanity url route, if found then populates query params with the proper post data
     *
     * @param  WP  $wp
     * @return null|array
     */
    public function parseRequest(\WP $wp)
    {
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

            if ($post->post_type == 'page') {
                $wp->query_vars['page']     = '';
                $wp->query_vars['pagename'] = $post->post_name;
            } else {
                $wp->query_vars['p']         = $post->ID;
                $wp->query_vars['post_name'] = $post->post_name;
                $wp->query_vars['post_type'] = $post->post_type;
            }
        }

        return $wp;
    }

    /**
     * Add custom meta box to post and pages
     */
    public function addMetaBox()
    {
        add_meta_box(
            'vanityurl-route-id',
            Util\Text::translate('Vanity URL'),
            [&$this, 'renderField'], ['post', 'page'],
            'normal'
        );
    }

    /**
     * Render custom meta box field
     */
    public function renderField($post)
    {
        wp_nonce_field(basename(__FILE__), 'vanityurl-customurl-nonce');
        
        global $wpdb;
        
        $metabox_custom_url_path = '';
        $result = $wpdb->get_row(
            'SELECT * 
            FROM ' . $wpdb->prefix . $this->tableName . ' 
            WHERE post_id = ' . $post->ID, ARRAY_A);
        if ($result !== null) {
            $metabox_custom_url_path = $result['url'];
        }
        ?>
        <div>
            <label for="custom-url-path"><?php echo Util\Text::translate('Custom URL Path') ?></label>
            <input name="custom-url-path" type="text" value="<?php echo $metabox_custom_url_path; ?>">
            <p><small><?php echo Util\Text::translate('eg: primary-content-container/secondary-structure/name-of-the-post') ?></small></p>
        </div>
        <?php
    }

    /**
     * Save meta box data
     */
    public function saveCustomMetabox($post_id, $post, $update)
    {
        if (!isset($_POST['vanityurl-customurl-nonce']) || !wp_verify_nonce($_POST['vanityurl-customurl-nonce'], basename(__FILE__))) {
            return $post_id;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // DO NOT store vanity urls for revisions. This will only cause you pain.
        if ($post->post_type == 'revision') {
            return $post_id;
        }
        
        global $wpdb;
        
        $metabox_custom_url_path = '';
        if (isset($_POST['custom-url-path'])) {
            $metabox_custom_url_path = sanitize_text_field($_POST['custom-url-path']);
            $metabox_custom_url_path = $this->cleanUrl($metabox_custom_url_path);
        }

        $result = $this->getVanityUrlRouteByID($post->ID);

        if ($result === null) {
            $wpdb->insert(
                $wpdb->prefix . $this->tableName,
                ['url' => $metabox_custom_url_path, 'post_id' => $post->ID],
                ['%s', '%d']
            );
        } else {
            $wpdb->update(
                $wpdb->prefix . $this->tableName,
                ['url' => $metabox_custom_url_path, 'post_id' => $post->ID],
                ['post_id' => $post->ID],
                ['%s', '%d']
            );
        }

        update_option('vanityurl_requires_update', true);
    }

    /**
     * Setup Vanity URL Table
     */
    private function init()
    {
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
