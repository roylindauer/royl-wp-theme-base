<?php

namespace Royl\WpThemeBase\Util;

/**
 * Utility class for handling Data Output.
 *
 * The purpose of this class is to handle a response to a request that is NOT a regular browser request.
 * For example, if you need to respond with JSON to an AJAX request you would call the json method of this class.
 * Another example would be the need to respond with a file attachment header to force download of a file.
 * Or returning valid XML data.
 *
 * Usage:
 *
 * $data = array('results' => array('name' => 'My User', 'email' => 'user@example.org'));
 * \Ecs\Core\Utilities\Output::json($data);
 *
 * would return the following in your browser:
 *
 * {"results":{"name":"My User","email":"user@example.org"}}
 *
 * @package     WpThemeBase
 * @subpackage  Util
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Output
{
    /**
     * Return a JSON response
     * @param  string $content The JSON payload
     */
    public static function json($content = '')
    {
        if (!empty($content)) {
            header('Content-type: application/json');
            die(json_encode($content));
        }
    }

    /**
     * Return an XML response
     * @param  string $content The XML payload
     */
    public static function xml($content = '')
    {
        header('Content-Type: text/xml');
        die($content);
    }

    /**
     * Force download of $filename
     * @param  string $filename name of the file download
     * @param  string $path path to file
     */
    public static function file($filename, $path)
    {
        // IE6?
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        // Force download
        header("Content-Disposition: attachment; filename=\"$filename\"");

        set_time_limit(0);
        $file = @fopen($path, "rb");
        while (!feof($file)) {
            print(@fread($file, 1024*8));
            ob_flush();
            flush();
        }
    }
}
