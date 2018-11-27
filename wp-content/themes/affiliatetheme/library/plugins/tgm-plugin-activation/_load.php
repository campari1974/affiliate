<?php
/**
 * Laden der Funktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	_load
 */

require_once(ENDCORE_PLUGINS . '/tgm-plugin-activation/class-tgm-plugin-activation.php');

add_action( 'tgmpa_register', 'at_register_required_plugins' );
function at_register_required_plugins() {
    $plugins = array(
        array(
            'name'        => 'WordPress SEO by Yoast',
            'slug'        => 'wordpress-seo',
        ),
        array(
            'name'        => 'WooSidebars',
            'slug'        => 'woosidebars',
        ),
    );

    $plugins = apply_filters('at_plugin_installer', $plugins);

    $config = array(
        'id'           => 'affiliatetheme-backend',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'at-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php',            // Parent menu slug.
        'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => false,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );
}
