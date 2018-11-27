<?php
/**
 * Plugin Name: AffiliateTheme - CJ Affiliate Schnittstelle
 * Plugin URI: http://affiliatetheme.io
 * Description: Dieses Plugin erweitert das AffiliateTheme um eine CJ Affiliate Schnittstelle
 * Version: 1.1
 * Author: endcore Medienagentur
 * Author URI: http://endcore.com
 * License: GPL2
 */

if(!class_exists('AffiliateTheme_CJ')) {
    class AffiliateTheme_CJ {
        /**
         * Construct the plugin object
         */
        public function __construct() {
            require_once(sprintf("%s/class.dashboard.init.php", dirname(__FILE__)));
            $affiliatetheme_cj_dashboard = new AffiliateTheme_CJ_Dashboard_Init();

            require 'plugin-update-checker/plugin-update-checker.php';
            $myUpdateChecker = PucFactory::buildUpdateChecker(
                'http://update.affiliatetheme.io/affiliatetheme-cj.json',
                __FILE__
            );

            register_deactivation_hook( __FILE__, array(&$this, 'deactivate'));

            add_filter('http_request_args', array(&$this, 'remove_from_wp_update'), 5, 2);
        }

        /**
         * Activate the plugin
         */
        public static function activate() {

        }

        /**
         * Deactivate the plugin
         */
        public static function deactivate() {
            wp_clear_scheduled_hook('affiliatetheme_cj_api_update', array('hash' => ANET_CRON_HASH));
        }

        /**
         * Remove Plugin from wp.org update check
         */
        public function remove_from_wp_update( $r, $url )
        {
            // If it's not a plugins update request, bail.
            if ( FALSE === strpos( $url, '//api.wordpress.org/plugins/update-check' ) ) {
                return $r;
            }

            if ( empty($r['body']['plugins']) ){
                return $r;
            }

            // Decode the JSON response
            $plugins = json_decode( $r['body']['plugins'], true );

            // Remove the plugin from the check
            $active_index = array_search( plugin_basename( __FILE__ ), $plugins['active'] );
            unset( $plugins['active'][ $active_index ] );
            unset( $plugins['plugins'][ plugin_basename( __FILE__ ) ] );

            // Encode the updated JSON response
            $r['body']['plugins'] = wp_json_encode( $plugins );

            return $r;
        }
    }
}

if(class_exists('AffiliateTheme_CJ'))
{
    $affiliatetheme_cj = new AffiliateTheme_CJ();
}
