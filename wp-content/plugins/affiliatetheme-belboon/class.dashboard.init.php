<?php		
if(!class_exists('AffiliateTheme_Belboon_Dashboard_Init'))
{
	class AffiliateTheme_Belboon_Dashboard_Init	{
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
			define('BBOON_PATH', plugin_dir_path( __FILE__ ) );
			define('BELBOON_USER', get_option('belboon_user'));
			define('BELBOON_PASS', get_option('belboon_password'));
			define('BBOON_METAKEY_ID', 'belboon_id');
			define('BBOON_METAKEY_LAST_UPDATE', 'last_product_price_check_belboon');
			define('BBOON_CRON_HASH', md5(get_option('belboon_user') . get_option('belboon_password')));

			// helpers
			require_once(BBOON_PATH . '/lib/bootstrap.php');
			require_once(BBOON_PATH . '/lib/api_helper.php');
			require_once(BBOON_PATH . '/lib/api_platforms.php');
			require_once(BBOON_PATH . '/lib/api_shoplist.php');
			require_once(BBOON_PATH . '/lib/api_search.php');
			require_once(BBOON_PATH . '/lib/api_lookup.php');
			require_once(BBOON_PATH . '/lib/api_import.php');
			require_once(BBOON_PATH . '/lib/api_update.php');
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
			register_setting('endcore_api_belboon_options', 'belboon_user');
			register_setting('endcore_api_belboon_options', 'belboon_password');
            register_setting('endcore_api_belboon_options', 'belboon_post_status');
			register_setting('endcore_api_belboon_options', 'belboon_import_description');
			register_setting('endcore_api_belboon_options', 'belboon_update_ean');
			register_setting('endcore_api_belboon_options', 'belboon_update_price');
			register_setting('endcore_api_belboon_options', 'belboon_update_url');
			register_setting('endcore_api_belboon_options', 'belboon_check_product_unique');
			register_setting('endcore_api_belboon_button_options', 'belboon_buy_short_button');
			register_setting('endcore_api_belboon_button_options', 'belboon_buy_button');
		}
		
		/*
		 * SCRIPTS
		 */
		public function menu_scripts($page)	{
            if ('import_page_endcore_api_belboon' != $page) {
                return;
            }

			wp_enqueue_style('at-select2', plugin_dir_url( __FILE__ ) . 'view/css/select2.min.css');
			wp_enqueue_script('at-select2', plugin_dir_url( __FILE__ ) . 'view/js/select2.min.js', '', '1.0', true);
			wp_enqueue_script('at-belboon-functions', plugin_dir_url( __FILE__ ) . 'view/js/bboon_functions.js', '', '1.3.2', true);
			wp_localize_script('at-belboon-functions', 'belboon_vars', array(
					'connection' => __('Verbindungsaufbau...', 'affiliatetheme-belboon'),
					'connection_ok' => __('Verbindung erfolgreich hergestellt.', 'affiliatetheme-belboon'),
					'connection_error' => __('Eine Verbindung zu belboon konnte nicht hergestellt werden.</p><p><strong>Fehlermeldung:</strong>', 'affiliatetheme-belboon'),
					'connection_error_2' => __('Eine Verbindung zu belboon konnte nicht hergestellt werden. Bitte überprüfe deine Einstellungen.', 'affiliatetheme-belboon'),
					'no_image' => __('Kein Bild vorhanden', 'affiliatetheme-belboon'),
					'import' => __('Importieren', 'affiliatetheme-belboon'),
					'edit' => __('Editieren', 'affiliatetheme-belboon'),
					'no_products_found' => __('Es wurden keine Produkte gefunden. Bitte definiere deine Suche neu.', 'affiliatetheme-belboon'),
					'edit_product' => __('Produkt bearbeiten', 'affiliatetheme-belboon'),
					'import_count' => __('Importiere Produkt <span class="current">1</span> von', 'affiliatetheme-belboon'),
					'search_error' => __('Die Suche konnte nicht ausgef&uuml;hrt werden. Es trat ein Fehler auf.', 'affiliatetheme-belboon'),
					'adblocker_hint' => __('Bitte deaktiviere deinen Adblocker um alle Funktionen der API zu nutzen!', 'affiliatetheme-belboon')
				)
			);
		}

		/**
		 * menu content
		 */
		public function menu_dashboard() {
			$plugin_options = 'endcore_api_belboon_options';
			$plugin_button_options = 'endcore_api_belboon_button_options';

			require_once(BBOON_PATH . '/view/panel.php');
		} 

		/**
		 * add a menu
		 */		
		public function add_submenu_page()	{
			add_submenu_page('endcore_api_dashboard', __('Belboon', 'affiliatetheme-belboon'), __('Belboon', 'affiliatetheme-belboon'), apply_filters('at_set_import_dashboard_capabilities', 'administrator'), 'endcore_api_belboon', array(&$this, 'menu_dashboard'));
		}

		/**
		 * load textdomain
		 */
		public function load_textdomain() {
			load_plugin_textdomain('affiliatetheme-belboon', false, dirname(plugin_basename( __FILE__ )) . '/languages');
		}
    } 
} 