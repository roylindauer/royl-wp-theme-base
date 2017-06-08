<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;

/**
 * Theme Core Class
 *
 * Configures WordPress runtime according to the themes configuration.
 * This class is responsible for:
 *     * Creating custom post types
 *     * Creatig custom taxonomies
 *     * Defines & provides AJAX handler
 *     * Registering and enqueing scripts and stylesheets
 *     * Defining sidebars
 *     * Defining menus
 *     * Defining image sizes
 *     * Defining theme features
 *     * Plugin dependencies using the http://tgmpluginactivation.com/ TGMPA Plugin
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class Core
{
    /**
     * Do the thing
     *
     * @param array $config
     */
    public function __construct()
    {
        if (function_exists('add_action')) {

            $reg = \Royl\WpThemeBase\Core\Registry::getInstance();
            
            $reg->set('Assets', new Assets());
            $reg->set('Ajax', new Ajax());
            $reg->set('CoreFilter', new Filter());
            $reg->set('PostTypeRegistry', new PostTypeRegistry());
            $reg->set('TaxonomyRegistry', new TaxonomyRegistry());
            #$reg->set('ContentSilo', new ContentSilo());

            // Display admin notices
            add_action('admin_notices', array(&$this, 'printThemeErrors'), PHP_INT_MAX-1);

            // Require plugins
            add_action('tgmpa_register', array(&$this, 'registerRequiredPlugins'));

            // Setup wp theme features
            add_action('after_setup_theme', array(&$this, 'registerThemeFeatures'), PHP_INT_MAX-1);
            add_action('after_setup_theme', array(&$this, 'registerImageSizes'), PHP_INT_MAX-1);
            add_action('after_setup_theme', array(&$this, 'registerNavMenus'), PHP_INT_MAX-1);
            add_action('after_setup_theme', array(&$this, 'registerSidebars'), PHP_INT_MAX-1);
            add_action('after_setup_theme', [&$this, 'loadTextDomain'], PHP_INT_MAX-1);
        }
    }

    /**
     * Register Required Plugins
     *
     * @return void
     */
    public function registerRequiredPlugins()
    {
        if (!function_exists('tgmpa')) {
            return;
        }

        if (($plugins = Util\Configure::read('dependencies.plugins')) == false) {
            return;
        }

        /**
         * Array of configuration settings. Amend each line as needed.
         * If you want the default strings to be available under your own theme domain,
         * leave the strings uncommented.
         * Some of the strings are added into a sprintf, so see the comments at the
         * end of each line for what each argument will be.
         */
        $config = array(
            'default_path' => '',                      // Default absolute path to pre-packaged plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
            'strings'      => array(
                'page_title'                      => __('Install Required Plugins', 'tgmpa'),
                'menu_title'                      => __('Install Plugins', 'tgmpa'),
                'installing'                      => __('Installing Plugin: %s', 'tgmpa'), // %s = plugin name.
                'oops'                            => __('Something went wrong with the plugin API.', 'tgmpa'),
                'notice_can_install_required'     => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.'), // %1$s = plugin name(s).
                'notice_can_install_recommended'  => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.'), // %1$s = plugin name(s).
                'notice_cannot_install'           => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.'), // %1$s = plugin name(s).
                'notice_can_activate_required'    => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.'), // %1$s = plugin name(s).
                'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.'), // %1$s = plugin name(s).
                'notice_cannot_activate'          => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.'), // %1$s = plugin name(s).
                'notice_ask_to_update'            => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.'), // %1$s = plugin name(s).
                'notice_cannot_update'            => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.'), // %1$s = plugin name(s).
                'install_link'                    => _n_noop('Begin installing plugin', 'Begin installing plugins'),
                'activate_link'                   => _n_noop('Begin activating plugin', 'Begin activating plugins'),
                'return'                          => __('Return to Required Plugins Installer', 'tgmpa'),
                'plugin_activated'                => __('Plugin activated successfully.', 'tgmpa'),
                'complete'                        => __('All plugins installed and activated successfully. %s', 'tgmpa'), // %s = dashboard link.
                'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            )
        );

        tgmpa($plugins, $config);
    }

    /**
     * Prints the error messages added to the global theme specific WP_Error object
     *
     * Only displays for users that have 'manage_options' capability,
     * needs WP_DEBUG & WP_DEBUG_DISPLAY constants set to true.
     * Doesn't output anything if there's no error object present.
     *
     * Adds the output to the 'shutdown' hook to render after the theme viewport is output.
     *
     * @return string
     */
    public function printThemeErrors()
    {
        global $wp_theme_error, $wp_theme_error_code;

        if (!current_user_can('manage_options') || !is_wp_error($wp_theme_error)) {
            return;
        }

        $output = '';
        foreach ($wp_theme_error->errors[$wp_theme_error_code] as $error) {
            $output .= '<li>' . $error . '</li>';
        }

        echo '<div class="error"><h4>' . Util\Text::translate('Theme Errors & Warnings').'</h4><ul>';
        echo $output;
        echo '</ul></div>';
    }

    /**
     * Add theme features
     *
     * @return void
     */
    public function registerThemeFeatures()
    {
        $features = Util\Configure::read('theme_features');

        if (empty($features)) {
            return;
        }

        foreach ($features as $k => $v) {
            if (is_array($v)) {
                add_theme_support($k, $v);
            } else {
                add_theme_support($v);
            }
        }
    }

    /**
     * Register Image Sizes
     *
     * @return void
     */
    public function registerImageSizes()
    {
        $image_sizes = Util\Configure::read('image_sizes');

        if (empty($image_sizes)) {
            return;
        }

        foreach ($image_sizes as $name => $opts) {
            // Check wp reserved names for image sizes
            if (in_array($name, array('thumb', 'thumbnail'))) {
                Util\Debug::addThemeError(sprintf('Image size identifier "%s" is reserved', $name));
            } else {
                add_image_size($name, @$opts['width'], @$opts['height'], @$opts['crop']);
            }
        }
    }

    /**
     * Register Nav Menus
     *
     * @return void
     */
    public function registerNavMenus()
    {
        $menus = Util\Configure::read('menus');

        if (empty($menus)) {
            return;
        }

        register_nav_menus($menus);
    }

    /**
     * Register Sidebars
     *
     * @return void
     */
    public function registerSidebars()
    {
        $sidebars = Util\Configure::read('sidebars');

        if (empty($sidebars)) {
            return;
        }
        
        foreach ($sidebars as $sidebar) {
            register_sidebar($sidebar);
        }
    }

    /**
     * Load Text for Theme Translations
     *
     * @return void
     */
    public function loadTextDomain() {
        load_theme_textdomain( Util\Configure::read('domain'), get_template_directory() . '/languages' );
    }
}
