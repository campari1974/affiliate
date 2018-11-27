<?php
if(!class_exists('AffiliateTheme_CJ_Dashboard_Init'))
{
    class AffiliateTheme_CJ_Dashboard_Init	{
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
            define('CJ_PATH', plugin_dir_path( __FILE__ ) );
            define('CJ_USER', get_option('cj_user'));
            define('CJ_PASS', get_option('cj_password'));
            define('CJ_CRON_HASH', md5(get_option('cj_user') . get_option('cj_password')));
            define('CJ_METAKEY_ID', 'cj_id');
            define('CJ_METAKEY_LAST_UPDATE', 'last_product_price_check_cj');
            define('CJ_DEVELOPER_KEY', get_option('cj_devkey'));

            // helpers
            require_once(CJ_PATH . '/lib/bootstrap.php');
            require_once(CJ_PATH . '/lib/api_helper.php');
            require_once(CJ_PATH . '/lib/api_shoplist.php');
            require_once(CJ_PATH . '/lib/api_search.php');
            require_once(CJ_PATH . '/lib/api_search_ean.php');
            require_once(CJ_PATH . '/lib/api_lookup.php');
            require_once(CJ_PATH . '/lib/api_import.php');
            require_once(CJ_PATH . '/lib/api_update.php');
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
            register_setting('endcore_api_cj_options', 'cj_user');
            register_setting('endcore_api_cj_options', 'cj_password');
            register_setting('endcore_api_cj_options', 'cj_devkey');
            register_setting('endcore_api_cj_options', 'cj_website_id');
            register_setting('endcore_api_cj_options', 'cj_post_status');
            register_setting('endcore_api_cj_options', 'cj_import_description');
            register_setting('endcore_api_cj_options', 'cj_update_ean');
            register_setting('endcore_api_cj_options', 'cj_update_price');
            register_setting('endcore_api_cj_options', 'cj_update_url');
            register_setting('endcore_api_cj_options', 'cj_check_product_unique');
            register_setting('endcore_api_cj_button_options', 'cj_buy_short_button');
            register_setting('endcore_api_cj_button_options', 'cj_buy_button');
        }

        /*
         * SCRIPTS
         */
        public function menu_scripts($page) {
            if ('import_page_endcore_api_cj' != $page) {
                return;
            }

            wp_enqueue_style('at-select2', plugin_dir_url( __FILE__ ) . 'view/css/select2.min.css');
            wp_enqueue_script('at-select2', plugin_dir_url( __FILE__ ) . 'view/js/select2.min.js', '', '1.1', false);
            wp_enqueue_script('at-cj-functions', plugin_dir_url( __FILE__ ) . 'view/js/cj_functions.js', '', '1.1', false);
            wp_localize_script('at-cj-functions', 'cj_vars', array(
                    'connection' => __('Verbindungsaufbau...', 'affiliatetheme-cj'),
                    'connection_ok' => __('Verbindung erfolgreich hergestellt.', 'affiliatetheme-cj'),
                    'connection_error' => __('Eine Verbindung zu CJ Affiliate konnte nicht hergestellt werden.</p><p><strong>Fehlermeldung:</strong>', 'affiliatetheme-cj'),
                    'connection_error_2' => __('Eine Verbindung zu CJ Affiliate konnte nicht hergestellt werden. Bitte überprüfe deine Einstellungen.', 'affiliatetheme-cj'),
                    'no_image' => __('Kein Bild vorhanden', 'affiliatetheme-cj'),
                    'import' => __('Importieren', 'affiliatetheme-cj'),
                    'edit' => __('Editieren', 'affiliatetheme-cj'),
                    'no_products_found' => __('Es wurden keine Produkte gefunden. Bitte definiere deine Suche neu.', 'affiliatetheme-cj'),
                    'edit_product' => __('Produkt bearbeiten', 'affiliatetheme-cj'),
                    'import_count' => __('Importiere Produkt <span class="current">1</span> von', 'affiliatetheme-cj'),
                    'search_error' => __('Die Suche konnte nicht ausgef&uuml;hrt werden. Es trat ein Fehler auf.', 'affiliatetheme-cj'),
                    'adblocker_hint' => __('Bitte deaktiviere deinen Adblocker um alle Funktionen der API zu nutzen!', 'affiliatetheme-cj')
                )
            );
        }

        /**
         * menu content
         */
        public function menu_dashboard() {
            $plugin_options = 'endcore_api_cj_options';
            $plugin_button_options = 'endcore_api_cj_button_options';

            require_once(CJ_PATH . '/view/panel.php');
        }


        /**
         * add a menu
         */
        public function add_submenu_page()	{
            add_submenu_page('endcore_api_dashboard', __('CJ Affiliate', 'affiliatetheme-cj'), __('CJ Affiliate', 'affiliatetheme-cj'), apply_filters('at_set_import_dashboard_capabilities', 'administrator'), 'endcore_api_cj', array(&$this, 'menu_dashboard'));
        }

        /**
         * load textdomain
         */
        public function load_textdomain() {
            load_plugin_textdomain('affiliatetheme-cj', false, dirname(plugin_basename( __FILE__ )) . '/languages');
        }
    }
}