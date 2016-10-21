<?php

namespace Royl\WpThemeBase\Wp;

/**
 * WordPress Tools - Attachments
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
class Attachment
{
    /**
     * Creates a new image attachment in wordpress and return the attachment ID.
     *
     * @param object $file_info Object containing image info from saveImage
     * @param object $post_id The post ID to attach the new attachment to
     * @return int Returns the new attachment ID
     */
    public static function createImageAttachment($file_info, $post_id = null)
    {
        $wp_filetype = wp_check_filetype($file_info->filename, null);
        $attachment = array(
            'guid' => $file_info->url,
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => $file_info->filename,
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment($attachment, $file_info->path, $post_id);

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_info->path);
        wp_update_attachment_metadata($attach_id, $attach_data);

        // wp_insert_attachment sometimes fails to associate the image correctly
        // so do it manually as well just to be sure if post_id was passed
        if ($post_id) {
            add_post_meta($post_id, '_thumbnail_id', $attach_id, true);
        }

        return $attach_id;
    }

    /**
     * Save an image to WordPress from an arbitrary URL.
     *
     * @param string $url The image URL to save
     * @return object If save is successful, returns object with info about the file
     **/
    public static function saveImage($url)
    {
        // Get upload directory info from WP
        $wp_upload     = wp_upload_dir();
        $upload_dir    = $wp_upload['path'];

        // Pull info about the image for saving
        $pathinfo = pathinfo($url);

        // Save the image
        $ch = curl_init($url);
        $fp = fopen($upload_dir . DIRECTORY_SEPARATOR . $pathinfo['basename'], 'wb');

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);

        fclose($fp);

        // Set info on file and return
        $file_info = new \stdClass();

        $file_info->filename = $pathinfo['basename'];
        $file_info->path     = $upload_dir . DIRECTORY_SEPARATOR . $pathinfo['basename'];
        $file_info->url      = $wp_upload['url'] . DIRECTORY_SEPARATOR . $pathinfo['basename'];
        $file_info->wp_url   = $wp_upload['subdir'] . DIRECTORY_SEPARATOR . $pathinfo['basename'];

        return $file_info;
    }
}
