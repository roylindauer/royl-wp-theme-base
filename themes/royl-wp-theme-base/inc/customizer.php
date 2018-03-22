<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;

add_action( 'login_enqueue_scripts', __n( 'output_custom_login_logo' ), PHP_INT_MAX-1 );

add_action( 'customize_register', __n( 'customizer_login_logo' ), PHP_INT_MAX-1 );
add_action( 'customize_register', __n( 'customizer_content_width' ), PHP_INT_MAX-1 );

/**
 * Custom Login Logo
 * @return [type] [description]
 */
function output_custom_login_logo() {
    $logo_url = get_option( 'custom_login_logo' );
    if ( $logo_url === false ) {
        return;
    }

    $width  = get_option( 'custom_logo_width', 320 );
    $height = get_option( 'custom_logo_height', 160 );
    ?>   
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo $logo_url; ?>);
            height: <?php echo $height ?>px;
            width: <?php echo $width ?>px;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            padding: 0;
        }
    </style>
    <?php
}

/**
 * [customizer_custom_login_logo description]
 * @param  [type]  $wp_customize [description]
 * @return [type]                [description]
 */
function customizer_login_logo( $wp_customize ) {

    // THEME OPTION SECTION
    // this is the container for our custom settings
    $wp_customize->add_section( 'theme_options_login_logo', [
        'title' => Util\Text::translate( 'Login Branding' ),
        'description' => Util\Text::translate( 'Customize the logo on the login page' ),
        'priority' => 160,
        'capability' => 'edit_theme_options',
    ] );

    // Logo Media
    $wp_customize->add_setting( 'custom_login_logo', [
        'type' => 'option',
        'capability' => 'manage_options',
    ]);
    $wp_customize->add_control(
        new \WP_Customize_Upload_Control(
            $wp_customize,
            'custom_login_logo',
            [
                'label' => Util\Text::translate( 'Logo File' ),
                'section' => 'theme_options_login_logo',
                'settings' => 'custom_login_logo',
            ]
        )
    );

    // Logo Width
    $wp_customize->add_setting( 'custom_login_logo_width', [
      'type' => 'option',
      'capability' => 'manage_options',
      'default' => '',
    ]);
    $wp_customize->add_control( 'custom_login_logo_width', [
        'type' => 'number',
        'priority' => 10,
        'section' => 'theme_options_login_logo',
        'label' => __( 'Logo Width' ),
        'description' => Util\Text::translate( 'Defaults to 320px' )
    ]);

    // Logo Height
    $wp_customize->add_setting( 'custom_login_logo_height', [
      'type' => 'option',
      'capability' => 'manage_options',
      'default' => '',
    ] );
    $wp_customize->add_control( 'custom_login_logo_height', [
        'type' => 'number',
        'priority' => 10,
        'section' => 'theme_options_login_logo',
        'label' => __( 'Logo Height' ),
        'description' => Util\Text::translate( 'Defaults to 160px' )
    ]);
}

/**
 * [customizer_custom_login_logo description]
 * @param  [type]  $wp_customize [description]
 * @return [type]                [description]
 */
function customizer_content_width( $wp_customize) {

    // THEME OPTION SECTION
    // this is the container for our custom settings
    $wp_customize->add_section( 'theme_options_content_width', [
        'title' => Util\Text::translate( 'Content Width' ),
        'description' => Util\Text::translate( 'Set a custom content width. This is required for responsive images and videos, and other media features of WordPress - Read more here https://codex.wordpress.org/Content_Width' ),
        'priority' => 160,
        'capability' => 'edit_theme_options',
    ] );

    // Theme Width
    $wp_customize->add_setting( 'content_width', [
      'type' => 'option',
      'capability' => 'manage_options',
      'default' => '',
    ]);
    $wp_customize->add_control( 'content_width', [
        'type' => 'number',
        'priority' => 10,
        'section' => 'theme_options_content_width',
        'label' => __( 'Content Width' ),
        'description' => Util\Text::translate( 'Value in pixels (ie: 960)' )
    ]);
}
