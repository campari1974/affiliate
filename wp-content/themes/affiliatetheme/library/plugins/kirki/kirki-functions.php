<?php
/**
 * Kirki Funktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	kirki
 */

add_filter( 'kirki/config', 'at_customizer_config' );
function at_customizer_config() {
	$dsgvo_google_fonts = get_field('dsgvo_google_fonts', 'options');

    $args = array(
        'logo_image'   => 'http://affiliatetheme.io/wp-content/uploads/logo.png',
        'url_path' => get_template_directory_uri() . '/library/plugins/kirki/core-v2/',
        'disable_google_fonts' => ($dsgvo_google_fonts ? true : false),
        'i18n' => array(
            'background-color'      => esc_attr__( 'Hintergrundfarbe', 'affiliatetheme-backend' ),
            'background-image'      => esc_attr__( 'Hintergrundbild', 'affiliatetheme-backend' ),
            'no-repeat'             => esc_attr__( 'No Repeat', 'affiliatetheme-backend' ),
            'repeat-all'            => esc_attr__( 'Repeat All', 'affiliatetheme-backend' ),
            'repeat-x'              => esc_attr__( 'Repeat Horizontally', 'affiliatetheme-backend' ),
            'repeat-y'              => esc_attr__( 'Repeat Vertically', 'affiliatetheme-backend' ),
            'inherit'               => esc_attr__( 'Inherit', 'affiliatetheme-backend' ),
            'background-repeat'     => esc_attr__( 'Background Repeat', 'affiliatetheme-backend' ),
            'cover'                 => esc_attr__( 'Cover', 'affiliatetheme-backend' ),
            'contain'               => esc_attr__( 'Contain', 'affiliatetheme-backend' ),
            'background-size'       => esc_attr__( 'Background Size', 'affiliatetheme-backend' ),
            'fixed'                 => esc_attr__( 'Fixed', 'affiliatetheme-backend' ),
            'scroll'                => esc_attr__( 'Scroll', 'affiliatetheme-backend' ),
            'background-attachment' => esc_attr__( 'Background Attachment', 'affiliatetheme-backend' ),
            'left-top'              => esc_attr__( 'Left Top', 'affiliatetheme-backend' ),
            'left-center'           => esc_attr__( 'Left Center', 'affiliatetheme-backend' ),
            'left-bottom'           => esc_attr__( 'Left Bottom', 'affiliatetheme-backend' ),
            'right-top'             => esc_attr__( 'Right Top', 'affiliatetheme-backend' ),
            'right-center'          => esc_attr__( 'Right Center', 'affiliatetheme-backend' ),
            'right-bottom'          => esc_attr__( 'Right Bottom', 'affiliatetheme-backend' ),
            'center-top'            => esc_attr__( 'Center Top', 'affiliatetheme-backend' ),
            'center-center'         => esc_attr__( 'Center Center', 'affiliatetheme-backend' ),
            'center-bottom'         => esc_attr__( 'Center Bottom', 'affiliatetheme-backend' ),
            'background-position'   => esc_attr__( 'Background Position', 'affiliatetheme-backend' ),
            'background-opacity'    => esc_attr__( 'Background Opacity', 'affiliatetheme-backend' ),
            'on'                    => esc_attr__( 'An', 'affiliatetheme-backend' ),
            'off'                   => esc_attr__( 'Aus', 'affiliatetheme-backend' ),
            'all'                   => esc_attr__( 'All', 'affiliatetheme-backend' ),
            'cyrillic'              => esc_attr__( 'Cyrillic', 'affiliatetheme-backend' ),
            'cyrillic-ext'          => esc_attr__( 'Cyrillic Extended', 'affiliatetheme-backend' ),
            'devanagari'            => esc_attr__( 'Devanagari', 'affiliatetheme-backend' ),
            'greek'                 => esc_attr__( 'Greek', 'affiliatetheme-backend' ),
            'greek-ext'             => esc_attr__( 'Greek Extended', 'affiliatetheme-backend' ),
            'khmer'                 => esc_attr__( 'Khmer', 'affiliatetheme-backend' ),
            'latin'                 => esc_attr__( 'Latin', 'affiliatetheme-backend' ),
            'latin-ext'             => esc_attr__( 'Latin Extended', 'affiliatetheme-backend' ),
            'vietnamese'            => esc_attr__( 'Vietnamese', 'affiliatetheme-backend' ),
            'hebrew'                => esc_attr__( 'Hebrew', 'affiliatetheme-backend' ),
            'arabic'                => esc_attr__( 'Arabic', 'affiliatetheme-backend' ),
            'bengali'               => esc_attr__( 'Bengali', 'affiliatetheme-backend' ),
            'gujarati'              => esc_attr__( 'Gujarati', 'affiliatetheme-backend' ),
            'tamil'                 => esc_attr__( 'Tamil', 'affiliatetheme-backend' ),
            'telugu'                => esc_attr__( 'Telugu', 'affiliatetheme-backend' ),
            'thai'                  => esc_attr__( 'Thai', 'affiliatetheme-backend' ),
            'serif'                 => _x( 'Serif', 'font style', 'affiliatetheme-backend' ),
            'sans-serif'            => _x( 'Sans Serif', 'font style', 'affiliatetheme-backend' ),
            'monospace'             => _x( 'Monospace', 'font style', 'affiliatetheme-backend' ),
            'font-family'           => esc_attr__( 'Schriftart', 'affiliatetheme-backend' ),
            'font-size'             => esc_attr__( 'Schriftgröße', 'affiliatetheme-backend' ),
            'font-weight'           => esc_attr__( 'Schriftstärke', 'affiliatetheme-backend' ),
            'line-height'           => esc_attr__( 'Lininehöhe', 'affiliatetheme-backend' ),
            'font-style'            => esc_attr__( 'Schriftstil', 'affiliatetheme-backend' ),
            'letter-spacing'        => esc_attr__( 'Zeichenabstand', 'affiliatetheme-backend' ),
            'top'                   => esc_attr__( 'Top', 'affiliatetheme-backend' ),
            'bottom'                => esc_attr__( 'Bottom', 'affiliatetheme-backend' ),
            'left'                  => esc_attr__( 'Left', 'affiliatetheme-backend' ),
            'right'                 => esc_attr__( 'Right', 'affiliatetheme-backend' ),
            'color'                 => esc_attr__( 'Schriftfarbe', 'affiliatetheme-backend' ),
            'add-image'             => esc_attr__( 'Add Image', 'affiliatetheme-backend' ),
            'change-image'          => esc_attr__( 'Change Image', 'affiliatetheme-backend' ),
            'remove'                => esc_attr__( 'Remove', 'affiliatetheme-backend' ),
            'no-image-selected'     => esc_attr__( 'No Image Selected', 'affiliatetheme-backend' ),
            'select-font-family'    => esc_attr__( 'Schriftart wählen', 'affiliatetheme-backend' ),
            'variant'               => esc_attr__( 'Schriftstärke', 'affiliatetheme-backend' ),
            'subsets'               => esc_attr__( 'Subset', 'affiliatetheme-backend' ),
            'size'                  => esc_attr__( 'Größe', 'affiliatetheme-backend' ),
            'height'                => esc_attr__( 'Height', 'affiliatetheme-backend' ),
            'spacing'               => esc_attr__( 'Spacing', 'affiliatetheme-backend' ),
            'ultra-light'           => esc_attr__( 'Ultra-Light 100', 'affiliatetheme-backend' ),
            'ultra-light-italic'    => esc_attr__( 'Ultra-Light 100 Italic', 'affiliatetheme-backend' ),
            'light'                 => esc_attr__( 'Light 200', 'affiliatetheme-backend' ),
            'light-italic'          => esc_attr__( 'Light 200 Italic', 'affiliatetheme-backend' ),
            'book'                  => esc_attr__( 'Book 300', 'affiliatetheme-backend' ),
            'book-italic'           => esc_attr__( 'Book 300 Italic', 'affiliatetheme-backend' ),
            'regular'               => esc_attr__( 'Normal 400', 'affiliatetheme-backend' ),
            'italic'                => esc_attr__( 'Normal 400 Italic', 'affiliatetheme-backend' ),
            'medium'                => esc_attr__( 'Medium 500', 'affiliatetheme-backend' ),
            'medium-italic'         => esc_attr__( 'Medium 500 Italic', 'affiliatetheme-backend' ),
            'semi-bold'             => esc_attr__( 'Semi-Bold 600', 'affiliatetheme-backend' ),
            'semi-bold-italic'      => esc_attr__( 'Semi-Bold 600 Italic', 'affiliatetheme-backend' ),
            'bold'                  => esc_attr__( 'Bold 700', 'affiliatetheme-backend' ),
            'bold-italic'           => esc_attr__( 'Bold 700 Italic', 'affiliatetheme-backend' ),
            'extra-bold'            => esc_attr__( 'Extra-Bold 800', 'affiliatetheme-backend' ),
            'extra-bold-italic'     => esc_attr__( 'Extra-Bold 800 Italic', 'affiliatetheme-backend' ),
            'ultra-bold'            => esc_attr__( 'Ultra-Bold 900', 'affiliatetheme-backend' ),
            'ultra-bold-italic'     => esc_attr__( 'Ultra-Bold 900 Italic', 'affiliatetheme-backend' ),
            'invalid-value'         => esc_attr__( 'Invalid Value', 'affiliatetheme-backend' ),
        )
    );

    return $args;
}

add_action('customize_register', 'at_clean_customizer', -1);
function at_clean_customizer($WP_Customize_Manager) {
    if (isset($WP_Customize_Manager->nav_menus) && is_object($WP_Customize_Manager->nav_menus)) {
        remove_filter('customize_refresh_nonces', array($WP_Customize_Manager->nav_menus, 'filter_nonces'));
        remove_action('wp_ajax_load-available-menu-items-customizer', array($WP_Customize_Manager->nav_menus, 'ajax_load_available_items'));
        remove_action('wp_ajax_search-available-menu-items-customizer', array($WP_Customize_Manager->nav_menus, 'ajax_search_available_items'));
        remove_action('customize_controls_enqueue_scripts', array($WP_Customize_Manager->nav_menus, 'enqueue_scripts'));
        remove_action('customize_register', array($WP_Customize_Manager->nav_menus, 'customize_register'), 11);
        remove_filter('customize_dynamic_setting_args', array($WP_Customize_Manager->nav_menus, 'filter_dynamic_setting_args'), 10, 2);
        remove_filter('customize_dynamic_setting_class', array($WP_Customize_Manager->nav_menus, 'filter_dynamic_setting_class'), 10, 3);
        remove_action('customize_controls_print_footer_scripts', array($WP_Customize_Manager->nav_menus, 'print_templates'));
        remove_action('customize_controls_print_footer_scripts', array($WP_Customize_Manager->nav_menus, 'available_items_template'));
        remove_action('customize_preview_init', array($WP_Customize_Manager->nav_menus, 'customize_preview_init'));
        remove_filter('customize_dynamic_partial_args', array($WP_Customize_Manager->nav_menus, 'customize_dynamic_partial_args'), 10, 2);
    }

    if (isset($WP_Customize_Manager->widgets) && is_object($WP_Customize_Manager->widgets)) {
         remove_filter( 'customize_refresh_nonces', array( $WP_Customize_Manager->widgets, 'filter_nonces' ) );
         remove_action( 'wp_ajax_load-available-menu-items-customizer', array( $WP_Customize_Manager->widgets, 'ajax_load_available_items' ) );
         remove_action( 'wp_ajax_search-available-menu-items-customizer', array( $WP_Customize_Manager->widgets, 'ajax_search_available_items' ) );
         remove_action( 'customize_controls_enqueue_scripts', array( $WP_Customize_Manager->widgets, 'enqueue_scripts' ) );
         remove_action( 'customize_register', array( $WP_Customize_Manager->widgets, 'customize_register' ), 11 );
         remove_filter( 'customize_dynamic_setting_args', array( $WP_Customize_Manager->widgets, 'filter_dynamic_setting_args' ), 10, 2 );
         remove_filter( 'customize_dynamic_setting_class', array( $WP_Customize_Manager->widgets, 'filter_dynamic_setting_class' ), 10, 3 );
         remove_action( 'customize_controls_print_footer_scripts', array( $WP_Customize_Manager->widgets, 'print_templates' ) );
         remove_action( 'customize_controls_print_footer_scripts', array( $WP_Customize_Manager->widgets, 'available_items_template' ) );
         remove_action( 'customize_preview_init', array( $WP_Customize_Manager->widgets, 'customize_preview_init' ) );
         remove_filter( 'customize_dynamic_partial_args', array( $WP_Customize_Manager->widgets, 'customize_dynamic_partial_args' ), 10, 2 );
    }
}

add_action('wp_footer', 'at_kirki_preview_css');
function at_kirki_preview_css() {
    if(is_customize_preview()) {
        echo '
        <style>
            .kirki-customizer-loading-wrapper {  background-image: url("' . get_template_directory_uri() . '/_/img/loading.gif") !important; }
            .kirki-customizer-loading-wrapper .kirki-customizer-loading { background: none !important; width: 64px !important; height: 64px !important; margin: -32px !important; -webkit-animation: none !important; animation: none !important; }
        </style>
        ';
    }
}