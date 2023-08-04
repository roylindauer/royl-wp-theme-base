<?php

namespace Royl\WpThemeBase\Util;

use WP;

/**
 * Vanity URL Router
 *
 * Allow content creators to define a custom URI for a post.
 * Custom URI is stored in a routes table
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class AURI
{
    public string $tableName = 'auri_map';
    public string $queryVar = 'is_mapped_uri';

    public function __construct()
    {
        /**
         * Define meta box to manage uri
         */
        add_action('add_meta_boxes', [&$this, 'addMetaBox']);
        add_action('save_post', [&$this, 'saveCustomMetabox'], 99, 3);

        /**
         * This does most of the work of handling the custom URL
         */
        add_action('parse_request', [&$this, 'parseRequest'], PHP_INT_MAX - 1, 1);

        /**
         * We define a custom rewrite rule for EVERY VANITY URL
         * @BUGBUG this may not be that scalable, test it, and refactor if required. How many rewrites can we have?
         */
        add_action('init', [&$this, 'addRewriteTags'], PHP_INT_MAX - 1);
        add_action('init', [&$this, 'addRewriteRules'], PHP_INT_MAX - 1);

        /**
         * Does the initial setup.
         * @BUGBUG should this be part of theme activation?
         */
        add_action('admin_init', function () {
            $this->init();
        });

        /**
         * Show AURIs in permalinks
         */
        add_filter('post_link', [&$this, 'permalinks'], 10, 3);
        add_filter('page_link', [&$this, 'permalinks'], 10, 3);
        add_filter('post_type_link', [&$this, 'permalinks'], 10, 3);

        /**
         * Adds a custom query var, so we know we are in a request
         */
        add_filter('query_vars', [&$this, 'queryVars']);
    }

    /**
     * Create rewrite rules for every entry in the routes table
     */
    public function addRewriteRules(): void
    {
        global $wp_rewrite;
        $routes = $this->getRoutes();
        foreach ($routes as $route) {
            add_rewrite_rule('^' . $route['uri'] . '$', 'index.php?' . $this->queryVar . '=true', 'top');
        }

        flush_rewrite_rules(false);
        $wp_rewrite->flush_rules(false);
    }

    /**
     *
     */
    public function addRewriteTags(): void
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
     * @param string $link
     * @param int|WP_Post $post
     * @param bool $leavename
     * @return string
     */
    public function permalinks(string $link, \WP_Post|int $post, bool $leavename): string
    {
        if (!is_object($post)) {
            $post = get_post($post);
        }

        $result = $this->getVanityUrlRouteByID($post->ID);

        if ($result !== null) {
            return get_site_url(null, $result['uri']);
        }

        return $link;
    }

    /**
     * Get all AURI routes
     *
     * @return null|array
     */
    private function getRoutes(): ?array
    {
        global $wpdb;
        return $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . $this->tableName, ARRAY_A);
    }

    /**
     * Retrieve route record by post id
     *
     * @param int $post_id
     * @return null|array
     */
    private function getVanityUrlRouteByID(int $post_id): ?array
    {
        global $wpdb;
        return $wpdb->get_row('SELECT *  FROM ' . $wpdb->prefix . $this->tableName . ' WHERE post_id = ' . $post_id . ' ORDER BY post_id ASC', ARRAY_A);
    }

    /**
     * Returns a clean URL
     * No trailing slash. No beginning slash. Returns a URI that is ready to be used in a rewrite rule
     */
    private function cleanUrl($uri)
    {

        // Remove prefixed slash
        if (str_starts_with($uri, '/')) {
            $uri = ltrim($uri, '/');
        }

        // Remove trailing slash
        if (substr($uri, 0, -1) == '/') {
            $uri = rtrim($uri, '/');
        }

        return $uri;
    }

    /**
     * Retrieve AURI route record by exact uri
     *
     * @param string $uri
     * @return null|array
     */
    private function getPostByURL(string $uri): ?array
    {
        global $wpdb;

        $uri = $this->cleanUrl($uri);
        $sql = $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . $this->tableName . ' WHERE uri = "%s"', $uri);
        return $wpdb->get_row($sql, ARRAY_A);
    }

    /**
     * Hijack the initial request.
     *
     * Checks for matching uri route, if found then populates query params with the proper post data
     */
    public function parseRequest(WP $wp): WP
    {
        $result = $this->getPostByURL($wp->request);

        if ($result !== null) {

            /*
             * WordPress will redirect to the internal canonical uri unless we
             * explicitly tell it not to here.
             * @BUGBUG - do we really need to prevent redirect? I think the answer is no
             */
            #remove_action('template_redirect', 'redirect_canonical');

            /*
             * Manually set query vars based on our matched route.
             * Normally WordPress would autofill this information.
             * @BUGBUG - are we sure we need to do this?
             */
            $post = get_post($result['post_id']);

            if ($post->post_type == 'page') {
                $wp->query_vars['page'] = '';
                $wp->query_vars['pagename'] = $post->post_name;
            } else {
                $wp->query_vars['p'] = $post->ID;
                $wp->query_vars['post_name'] = $post->post_name;
                $wp->query_vars['post_type'] = $post->post_type;
            }
        }

        return $wp;
    }

    /**
     * Add custom meta box to post and pages
     */
    public function addMetaBox(): void
    {
        add_meta_box(
            'auri-route-id',
            Text::translate('Vanity URL'),
            [&$this, 'renderField'], ['post', 'page'],
            'normal'
        );
    }

    /**
     * Render custom meta box field
     */
    public function renderField($post): void
    {
        wp_nonce_field(basename(__FILE__), 'auri-nonce');

        global $wpdb;

        $metabox_auri_path = '';
        $result = $wpdb->get_row(
            'SELECT * 
            FROM ' . $wpdb->prefix . $this->tableName . ' 
            WHERE post_id = ' . $post->ID, ARRAY_A);
        if ($result !== null) {
            $metabox_auri_path = $result['uri'];
        }
        ?>
        <div>
            <label for="auri-path"><?php echo Text::translate('URI') ?></label>
            <input name="auri-path" type="text" value="<?php echo $metabox_auri_path; ?>">
            <p>
                <small><?php echo Text::translate('eg: primary-content-container/secondary-structure/name-of-the-post') ?></small>
            </p>
        </div>
        <?php
    }

    /**
     * Save meta box data
     * @todo - throw error if route already exists
     */
    public function saveCustomMetabox($post_id, $post, $update)
    {
        if (!isset($_POST['auri-nonce']) || !wp_verify_nonce($_POST['auri-nonce'], basename(__FILE__))) {
            return $post_id;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // DO NOT store uris for revisions. This will only cause you pain.
        if ($post->post_type == 'revision') {
            return $post_id;
        }

        if (!isset($_POST['auri-path'])) {
            return;
        }

        global $wpdb;

        $metabox_auri_path = sanitize_text_field($_POST['auri-path']);
        $metabox_auri_path = $this->cleanUrl($metabox_auri_path);

        $result = $this->getVanityUrlRouteByID($post->ID);

        if ($result === null) {
            $wpdb->insert(
                $wpdb->prefix . $this->tableName,
                ['uri' => $metabox_auri_path, 'post_id' => $post->ID],
                ['%s', '%d']
            );
        } else {
            $wpdb->update(
                $wpdb->prefix . $this->tableName,
                ['uri' => $metabox_auri_path, 'post_id' => $post->ID],
                ['post_id' => $post->ID],
                ['%s', '%d']
            );
        }

        $this->addRewriteRules();
    }

    /**
     * Setup Vanity URL Table
     */
    private function init(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . $this->tableName;

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                uri varchar(255) DEFAULT '' NOT NULL,
                post_id mediumint(9) NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
