<?php
if(!class_exists('AffiliateTheme_TRADEDOUBLER_Dashboard_Init'))
{
    class AffiliateTheme_TRADEDOUBLER_Dashboard_Init	{
        /**
         * Construct the plugin object
         */
        public function __construct() {
            // actions
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_submenu_page'), 999);
            add_action('admin_init', array(&$this, 'settings'));
            add_action('admin_enqueue_scripts', array(&$this, 'menu_scripts'), 999);
            add_action('init',  array(&$this, 'load_textdomain'));

            // vars
            define('TRADEDOUBLER_PATH', plugin_dir_path( __FILE__ ) );
            define('TRADEDOUBLER_USER', get_option('tradedoubler_user'));
            define('TRADEDOUBLER_PASS', get_option('tradedoubler_password'));
            define('TRADEDOUBLER_PRODUCTS_TOKEN', get_option('tradedoubler_products_token'));
            define('TRADEDOUBLER_CRON_HASH', md5(get_option('tradedoubler_user') . get_option('tradedoubler_password')));
            define('TRADEDOUBLER_METAKEY_ID', 'tradedoubler_id');
            define('TRADEDOUBLER_METAKEY_LAST_UPDATE', 'last_product_price_check_tradedoubler');
            define('TRADEDOUBLER_DEVELOPER_KEY', get_option('tradedoubler_devkey'));

            // helpers
            require_once(TRADEDOUBLER_PATH . '/lib/bootstrap.php');
            require_once(TRADEDOUBLER_PATH . '/lib/api_acf_helper.php');
            require_once(TRADEDOUBLER_PATH . '/lib/api_categorylist.php');
            require_once(TRADEDOUBLER_PATH . '/lib/api_helper.php');
            require_once(TRADEDOUBLER_PATH . '/lib/api_shoplist.php');
            require_once(TRADEDOUBLER_PATH . '/lib/api_search.php');
            require_once(TRADEDOUBLER_PATH . '/lib/api_search_ean.php');
            require_once(TRADEDOUBLER_PATH . '/lib/api_lookup.php');
            require_once(TRADEDOUBLER_PATH . '/lib/api_import.php');
            require_once(TRADEDOUBLER_PATH . '/lib/api_update.php');
        }

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init() {

        }

        /*
         * SETTINGS
         */
        public function settings() {
            register_setting('endcore_api_tradedoubler_options', 'tradedoubler_user');
            register_setting('endcore_api_tradedoubler_options', 'tradedoubler_password');
            register_setting('endcore_api_tradedoubler_options', 'tradedoubler_products_token');
            register_setting('endcore_api_tradedoubler_options', 'tradedoubler_post_status');
            register_setting('endcore_api_tradedoubler_options', 'tradedoubler_import_description');
            register_setting('endcore_api_tradedoubler_options', 'tradedoubler_update_ean');
            register_setting('endcore_api_tradedoubler_options', 'tradedoubler_update_price');
            register_setting('endcore_api_tradedoubler_options', 'tradedoubler_update_url');
            register_setting('endcore_api_tradedoubler_options', 'tradedoubler_check_product_unique');
            register_setting('endcore_api_tradedoubler_button_options', 'tradedoubler_buy_short_button');
            register_setting('endcore_api_tradedoubler_button_options', 'tradedoubler_buy_button');
        }

        /*
         * SCRIPTS
         */
        public function menu_scripts($page) {
            if ('import_page_endcore_api_tradedoubler' != $page) {
                return;
            }

            wp_enqueue_style('at-select2', plugin_dir_url( __FILE__ ) . 'view/css/select2.min.css');
            wp_enqueue_script('at-select2', plugin_dir_url( __FILE__ ) . 'view/js/select2.min.js', '', '1.0', false);
            wp_enqueue_script('at-tradedoubler-functions', plugin_dir_url( __FILE__ ) . 'view/js/tradedoubler_functions.js', '', '1.0.1', false);
            wp_localize_script('at-tradedoubler-functions', 'tradedoubler_vars', array(
                    'connection' => __('Verbindungsaufbau...', 'affiliatetheme-tradedoubler'),
                    'connection_ok' => __('Verbindung erfolgreich hergestellt.', 'affiliatetheme-tradedoubler'),
                    'connection_error' => __('Eine Verbindung zu Tradedoubler konnte nicht hergestellt werden.</p><p><strong>Fehlermeldung:</strong>', 'affiliatetheme-tradedoubler'),
                    'connection_error_2' => __('Eine Verbindung zu Tradedoubler konnte nicht hergestellt werden. Bitte überprüfe deine Einstellungen.', 'affiliatetheme-tradedoubler'),
                    'no_image' => __('Kein Bild vorhanden', 'affiliatetheme-tradedoubler'),
                    'import' => __('Importieren', 'affiliatetheme-tradedoubler'),
                    'edit' => __('Editieren', 'affiliatetheme-tradedoubler'),
                    'no_products_found' => __('Es wurden keine Produkte gefunden. Bitte definiere deine Suche neu.', 'affiliatetheme-tradedoubler'),
                    'edit_product' => __('Produkt bearbeiten', 'affiliatetheme-tradedoubler'),
                    'import_count' => __('Importiere Produkt <span class="current">1</span> von', 'affiliatetheme-tradedoubler'),
                    'search_error' => __('Die Suche konnte nicht ausgef&uuml;hrt werden. Es trat ein Fehler auf.', 'affiliatetheme-tradedoubler'),
                    'adblocker_hint' => __('Bitte deaktiviere deinen Adblocker um alle Funktionen der API zu nutzen!', 'affiliatetheme-tradedoubler')
                )
            );
        }

        /**
         * menu content
         */
        public function menu_dashboard() {
            $plugin_options = 'endcore_api_tradedoubler_options';
            $plugin_button_options = 'endcore_api_tradedoubler_button_options';

            require_once(TRADEDOUBLER_PATH . '/view/panel.php');
        }


        /**
         * add a menu
         */
        public function add_submenu_page()	{
            add_submenu_page('endcore_api_dashboard', __('Tradedoubler', 'affiliatetheme-tradedoubler'), __('Tradedoubler', 'affiliatetheme-tradedoubler'), apply_filters('at_set_import_dashboard_capabilities', 'administrator'), 'endcore_api_tradedoubler', array(&$this, 'menu_dashboard'));
        }

        /**
         * load textdomain
         */
        public function load_textdomain() {
            load_plugin_textdomain('affiliatetheme-tradedoubler', false, dirname(plugin_basename( __FILE__ )) . '/languages');
        }
    }
}