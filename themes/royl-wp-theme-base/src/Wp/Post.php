<?php

namespace Royl\WpThemeBase\Wp;

/**
 * WordPress Tools - Posts
 *
 * This class is for doing WordPress-related things in code that we do
 * commonly, such as creating a new post type or creating an image attachment.
 *
 * @package     WpThemeBase
 * @subpackage  Wp
 * @author      Tim Shaw
 * @author      Nitish Narala
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Post
{
    /**
     * Return a url to a page (or custom post type) by title.
     *
     * @param string $page_title
     * @return string
     */
    public static function pageUrl($page_title = '')
    {
        return get_permalink(get_page_by_title($page_title));
    }

    /**
     * Return a url to a page (or custom post type) by slug.
     *
     * @param string $page_slug
     * @param string $type
     * @return string
     */
    public static function pageUrlBySlug($page_slug = '', $type = 'page')
    {
        global $wpdb;

        $page = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_name = '{$page_slug}' AND post_type = '{$type}'");
        return get_permalink($page);
    }

    /**
     * Given an image URL and a post, set the image as the post thumbnail
     * (featured image).
     *
     * @param string $url URL of image to set
     * @param object $post Post to set image to
     * @return integer Returns attachment ID if successful or null if not
     **/
    public static function setPostThumbnail($url, $post)
    {
        // Save the image to start
        $fileInfo = \Royl\WpThemeBase\Wp\Attachment::saveImage($url);

        if ($fileInfo) {
            // If that worked out, attach the image
            return \Royl\WpThemeBase\Wp\Attachment::createImageAttachment($fileInfo, $post->ID);
        }

        // Guess it didn't work out
        return null;
    }

    /**
     * Set post parent on child post.
     *
     * @param int $parent_post_id Post ID of the parent post
     * @param int $child_post_id Post ID of the child post
     **/
    public static function setPostParent($parent_post_id, $child_post_id)
    {
        global $wpdb;

        $wpdb->query($wpdb->prepare(
            "UPDATE wp_posts SET post_parent = %d WHERE ID = %d",
            $parent_post_id,
            $child_post_id
        ));
    }

    /**
     * Check for an existing post and reset it (delete it then make a new one
     * with the same title).  If no post exists under the title, simple make a
     * new one and call it day.
     *
     * @param string $postTitle The title of the post to look for/create
     * @param string $postType The post type to search within/create
     * @return object Returns new post object
     **/
    public static function resetPost($postTitle, $postType)
    {
        // Kill existing post if it exists
        $existingPost = self::getPost($postTitle, $postType);

        if ($existingPost) {
            wp_delete_post($existingPost->ID, true);
        }

        // Then make new post
        $newPostId = self::createPost($postTitle, $postType);

        // Retrieve the new post object for the new post
        $existingPost = self::getPost($postTitle, $postType);

        return $existingPost;
    }

    /**
     * Check if post exists and create it if not.  Will not fill in meta
     * content, only title, so additional post fields have to filled in after
     * creating/retrieving the initial post.
     *
     * @param string $post_title The title of the post to look for/create
     * @param string $post_type The post type to search within/create
     * @return object Returns post object, either existing or new
     **/
    public static function upsertPost($post_title, $post_type)
    {
        // Check if relevant post already exists
        $existing_post = self::getPost($post_title, $post_type);

        // If the post doesn't exist, make it
        if (!$existing_post) {
            $new_post_id = self::createPost($post_title, $post_type);

            // Retrieve the new post object for the new post
            $existing_post = self::getPost($post_title, $post_type);
        }

        return $existing_post;
    }

    /**
     * Retrieve single post object.  This differs from WP's internal get post
     * functions in that it simple returns raw post output directly from
     * wp_posts.
     *
     * @param string $titleOrId ID or title of post (will determine based on
     * type)
     * @param string $postType The post type to search within/create
     * @return object Returns the post object if found or null
     * @todo Consider renaming to getRawPost() to more clearly express what this does
     **/
    public static function getPost($titleOrId, $postType)
    {
        global $wpdb;

        if (is_int($titleOrId)) {
            $sql = $wpdb->prepare(
                "SELECT * FROM wp_posts WHERE post_type = '%s' AND ID = %d",
                $postType,
                $titleOrId
            );
        } else {
            $sql = $wpdb->prepare(
                "SELECT * FROM wp_posts WHERE post_type = '%s' AND post_title LIKE '%s'",
                $postType,
                $titleOrId
            );
        }

        return $wpdb->get_row($sql);
    }

    /**
     * Similar to getPost, this is just a quick wrapper for returning posts
     * via $wpdb.  Useful if you're doing a lot of these/watching your memory
     * diet.
     *
     * @param  mixed  $postTypes Type of post to pull, single string or array
     * @param  string $orderBy   Field to order returned posts by
     * @param  string $orderDir  Direction posts should be ordered in
     * @param  int    $limit     Limit number of posts returned
     * @return array             Returns array of posts on success or null
     * @todo  consider removing as it is just a gimped version of the built in get_posts
     **/
    public static function getPosts($postTypes, $orderBy = 'post_title', $orderDir = 'ASC', $limit = null)
    {
        global $wpdb;

        if (is_array($postTypes)) {
            $pieces = array_map(function ($item) use ($wpdb) {
                return $wpdb->prepare("post_type = '%s'", $item);
            }, $postTypes);
            $typeClause = implode(' OR ', $pieces);
        } else {
            $typeClause = $wpdb->prepare("post_type = '%s'", $postTypes);
        }

        $sql = sprintf(
            "SELECT * FROM wp_posts WHERE ( %s ) AND post_status = 'publish'",
            $typeClause
        );

        // Mix in other args if present
        if ($orderBy) {
            $sql .= sprintf(' ORDER BY %s %s', $orderBy, $orderDir);
        }

        if ($limit) {
            $sql .= sprintf(' LIMIT %d', $limit);
        }

        // And get the results
        $posts = $wpdb->get_results($sql);

        return $posts ? $posts : null;
    }

    /**
     * Creates a post by title and post type.  Any additional fields must be
     * filled in after that.
     *
     * @param string $post_title The title of the post to look for/create
     * @param string $post_type The post type to search within/create
     * @return int Returns the post ID of the new post
     **/
    public static function createPost($post_title, $post_type)
    {
        // Enter the basic post
        $post = [
            'post_title'    => $post_title,
            'post_status'   => 'publish',
            'post_type'     => $post_type,
            'post_author'   => 1
        ];

        $post_id = wp_insert_post($post);

        return $post_id ? $post_id : false;
    }

    /**
     * Update a passed post with the passed taxonomy terms.  This will create/
     * update the terms as necessary and will attach them to the post object.
     *
     * @param integer   $postId     ID of post object to add terms to
     * @param string    $taxonomy   Taxonomy to add terms to
     * @param array     $terms      Terms to add to post
     * @param boolean   $append     If true will append new terms rather than replace
     **/
    public static function addTermsToPost($postId, $taxonomy, $terms, $append = false)
    {
        $categoryIds = [];

        foreach ($terms as $term) {
            $categoryIds[] = \Royl\WpThemeBase\Wp\Taxonomy::upsertTaxonomyTerm($term, $taxonomy);
        }

        // Attach terms to the post
        wp_set_object_terms($postId, $categoryIds, $taxonomy, $append);
    }

    /**
     * Clear a single post type. Will delete basic post info along with meta
     * data and any post associations.
     *
     * @param string $postType Type of post to blow out
     * @return bool Returns true/fall on success/fail
     **/
    public static function flushPostType($postType)
    {
        $posts = self::getPosts($postType);

        if (!empty($posts)) {
            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }

            echo 'Deleted all ' . $postType . ' posts.' . "\n";
        }
    }

    /**
     * Given a post meta key/value, find all posts which contain the value.
     *
     * @param  string $key      Meta key to search for
     * @param  string $value    Meta value to search for
     * @param  string $postType Post type to search within
     * @param  bool   $single   Pass true to only return first result
     * @return array            Returns array of found posts or null
     * @todo   consider refactoring to use WP_Query
     **/
    public static function getPostsFromMeta($key, $value, $postType, $single = false)
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT p.* FROM $wpdb->postmeta AS m
            JOIN $wpdb->posts AS p ON p.ID = m.post_id AND p.post_type = '%s'
            WHERE meta_key = '%s' AND meta_value = '%s'",
            $postType,
            $key,
            $value
        );

        return $single ? $wpdb->get_row($sql) : $wpdb->get_results($sql);
    }


    /**
     * Retrieve basic post field.  This will work based on the post object if
     * populated or simply from $_GET data (this is useful if you need the post
     * slug before wordpress is fully bootstrapped).
     *
     * @param string $field field to retrieve from post
     *
     * @return string Returns post slug if able to find it or null
     */
    public static function getPostField($field)
    {
        global $post;
        global $wpdb;

        if ($post && !empty($post) && isset($post->{$field})) {
            return $post->{$field};
        } elseif (!empty($_GET) && isset($_GET['post']) && !empty($_GET['post'])) {
            $id = $_GET['post'];

            // Pull the slug from the ID info
            $result = $wpdb->get_row($wpdb->prepare(
                "SELECT {$field} FROM wp_posts WHERE ID = %d",
                $id
            ));

            if ($result) {
                return $result->{$field};
            }
        }

        return null;
    }
}
