<?php

namespace Royl\WpThemeBase\Core;
use Royl\WpThemeBase\Util;
use Royl\WpThemeBase\Ajax;
use Royl\WpThemeBase\Filter;
use Royl\WpThemeBase\Wp;

add_action( 'login_enqueue_scripts', __n( 'output_custom_login_logo' ), PHP_INT_MAX-1 );
add_action( 'customize_register', __n( 'customizer_logo' ), PHP_INT_MAX-1 );


/**
 * Custom Login Logo
 * @return [type] [description]
 */
function output_custom_login_logo() {
    $logo_url = get_option( 'custom_logo' );
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
function customizer_logo( $wp_customize ) {

    // THEME OPTION SECTION
    // this is the container for our custom settings
    $wp_customize->add_section( 'theme_options_logo', [
        'title' => Util\Text::translate( 'Login Branding' ),
        'description' => Util\Text::translate( 'Customize the logo on the login page' ),
        'panel' => '', // Not typically needed.
        'priority' => 160,
        'capability' => 'edit_theme_options',
        'theme_supports' => '', // Rarely needed.
    ] );

    // Logo Media
    $wp_customize->add_setting( 'custom_logo', [
        'type' => 'option',
        'capability' => 'manage_options',
        'default' => '',
    ]);
    $wp_customize->add_control(
        new \WP_Customize_Upload_Control(
            $wp_customize,
            'custom_logo',
            [
                'label' => Util\Text::translate( 'Logo File' ),
                'section' => 'theme_options_logo',
                'settings' => 'custom_logo',
            ]
        )
    );

    // Logo Width
    $wp_customize->add_setting( 'custom_logo_width', [
      'type' => 'option',
      'capability' => 'manage_options',
      'default' => '',
    ]);
    $wp_customize->add_control( 'custom_logo_width', [
        'type' => 'number',
        'priority' => 10,
        'section' => 'theme_options_logo',
        'label' => __( 'Logo Width' ),
        'description' => Util\Text::translate( 'Defaults to 320px' )
    ]);

    // Logo Height
    $wp_customize->add_setting( 'custom_logo_height', [
      'type' => 'option',
      'capability' => 'manage_options',
      'default' => '',
    ] );
    $wp_customize->add_control( 'custom_logo_height', [
        'type' => 'number',
        'priority' => 10,
        'section' => 'theme_options_logo',
        'label' => __( 'Logo Height' ),
        'description' => Util\Text::translate( 'Defaults to 160px' )
    ]);
}