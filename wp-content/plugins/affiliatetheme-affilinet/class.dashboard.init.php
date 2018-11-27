<?php		
if(!class_exists('AffiliateTheme_affilinet_Dashboard_Init'))
{
	class AffiliateTheme_affilinet_Dashboard_Init	{
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
			define('ANET_PATH', plugin_dir_path( __FILE__ ) );
			define('ANET_USER', get_option('affilinet_user'));
			define('ANET_PASS', get_option('affilinet_password'));
			define('ANET_CRON_HASH', md5(get_option('affilinet_user') . get_option('affilinet_password')));
			define('ANET_METAKEY_ID', 'affilinet_id');
			define('ANET_METAKEY_LAST_UPDATE', 'last_product_price_check_affilinet');
			define('WSDL_LOGON', 'https://api.affili.net/V2.0/Logon.svc?wsdl');
			define('WSDL_PROD',  'https://api.affili.net/V2.0/ProductServices.svc?wsdl');
			define('WSDL_LOGON_V3', 'http://product-api.affili.net/Authentication/Logon.svc?wsdl');
			define('WSDL_PROD_V3', 'https://product-api.affili.net/V3/WSDLFactory/Product_ProductData.wsdl');

			// helpers
			require_once(ANET_PATH . '/lib/bootstrap.php');
            require_once(ANET_PATH . '/lib/api_acf_helper.php');
			require_once(ANET_PATH . '/lib/api_helper.php');
			require_once(ANET_PATH . '/lib/api_shoplist.php');
			require_once(ANET_PATH . '/lib/api_categorylist.php');
			require_once(ANET_PATH . '/lib/api_search.php');
			require_once(ANET_PATH . '/lib/api_search_ean.php');
			require_once(ANET_PATH . '/lib/api_lookup.php');
			require_once(ANET_PATH . '/lib/api_import.php');
			require_once(ANET_PATH . '/lib/api_update.php');
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
			register_setting('endcore_api_affilinet_options', 'affilinet_user');
			register_setting('endcore_api_affilinet_options', 'affilinet_password');
			register_setting('endcore_api_affilinet_options', 'affilinet_subid');
            register_setting('endcore_api_affilinet_options', 'affilinet_post_status');
			register_setting('endcore_api_affilinet_options', 'affilinet_import_description');
			register_setting('endcore_api_affilinet_options', 'affilinet_update_ean');
			register_setting('endcore_api_affilinet_options', 'affilinet_update_price');
			register_setting('endcore_api_affilinet_options', 'affilinet_update_url');
			register_setting('endcore_api_affilinet_options', 'affilinet_check_product_unique');
			register_setting('endcore_api_affilinet_button_options', 'affilinet_buy_short_button');
			register_setting('endcore_api_affilinet_button_options', 'affilinet_buy_button');
		}
		
		/*
		 * SCRIPTS
		 */
		public function menu_scripts($page) {
			if ('import_page_endcore_api_affilinet' != $page) {
				return;
			}

			wp_enqueue_style('at-select2', plugin_dir_url( __FILE__ ) . 'view/css/select2.min.css');
			wp_enqueue_script('at-select2', plugin_dir_url( __FILE__ ) . 'view/js/select2.min.js', '', '1.0', false);
			wp_enqueue_script('at-anet-functions', plugin_dir_url( __FILE__ ) . 'view/js/anet_functions.js', '', '1.2.7', false);
			wp_localize_script('at-anet-functions', 'anet_vars', array(
					'connection' => __('Verbindungsaufbau...', 'affiliatetheme-affilinet'),
					'connection_ok' => __('Verbindung erfolgreich hergestellt.', 'affiliatetheme-affilinet'),
					'connection_error' => __('Eine Verbindung zu affilinet konnte nicht hergestellt werden.</p><p><strong>Fehlermeldung:</strong>', 'affiliatetheme-affilinet'),
					'connection_error_2' => __('Eine Verbindung zu affilinet konnte nicht hergestellt werden. Bitte überprüfe deine Einstellungen.', 'affiliatetheme-affilinet'),
					'no_image' => __('Kein Bild vorhanden', 'affiliatetheme-affilinet'),
					'import' => __('Importieren', 'affiliatetheme-affilinet'),
					'edit' => __('Editieren', 'affiliatetheme-affilinet'),
					'no_products_found' => __('Es wurden keine Produkte gefunden. Bitte definiere deine Suche neu.', 'affiliatetheme-affilinet'),
					'edit_product' => __('Produkt bearbeiten', 'affiliatetheme-affilinet'),
					'import_count' => __('Importiere Produkt <span class="current">1</span> von', 'affiliatetheme-affilinet'),
					'search_error' => __('Die Suche konnte nicht ausgef&uuml;hrt werden. Es trat ein Fehler auf.', 'affiliatetheme-affilinet'),
					'adblocker_hint' => __('Bitte deaktiviere deinen Adblocker um alle Funktionen der API zu nutzen!', 'affiliatetheme-affilinet')
				)
			);
		}

		/**
		 * menu content
		 */
		public function menu_dashboard() {
			$plugin_options = 'endcore_api_affilinet_options';
			$plugin_button_options = 'endcore_api_affilinet_button_options';

			require_once(ANET_PATH . '/view/panel.php');
		} 
			
			
		/**
		 * add a menu
		 */		
		public function add_submenu_page()	{
			add_submenu_page('endcore_api_dashboard', __('Affilinet', 'affiliatetheme-affilinet'), __('Affilinet', 'affiliatetheme-affilinet'), apply_filters('at_set_import_dashboard_capabilities', 'administrator'), 'endcore_api_affilinet', array(&$this, 'menu_dashboard'));
		}

		/**
		 * load textdomain
		 */
		public function load_textdomain() {
			load_plugin_textdomain('affiliatetheme-affilinet', false, dirname(plugin_basename( __FILE__ )) . '/languages');
		}
    } 
} 