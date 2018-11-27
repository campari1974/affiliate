<?php		
if(!class_exists('AffiliateTheme_Zanox_Dashboard_Init'))
{
	class AffiliateTheme_Zanox_Dashboard_Init	{
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
			define('ZANOX_PATH', plugin_dir_path( __FILE__ ));
			define('ZANOX_API_ID', get_option('zanox_connect_id'));
			define('ZANOX_API_SECRET_KEY', get_option('zanox_secret_key'));
			define('ZANOX_API_COUNTRY', get_option('zanox_country'));
			define('ZANOX_METAKEY_ID', 'zanox_id');
			define('ZANOX_METAKEY_SHOP', 'zanox_shop');
			define('ZANOX_METAKEY_LAST_UPDATE', 'last_product_price_check_zanox');
			define('ZANOX_CRON_HASH', md5(get_option('zanox_connect_id') . get_option('zanox_secret_key')));
			define('ZANOX_FIX_ENCODING', false);

			// helpers
			require_once(ZANOX_PATH . '/vendor/autoload.php');
            require_once(ZANOX_PATH . '/lib/api_acf_helper.php');
			require_once(ZANOX_PATH . '/lib/api_helper.php');
			require_once(ZANOX_PATH . '/lib/api_adspaces.php');
			require_once(ZANOX_PATH . '/lib/api_programs.php');
			require_once(ZANOX_PATH . '/lib/api_categories.php');
			require_once(ZANOX_PATH . '/lib/api_products.php');
			require_once(ZANOX_PATH . '/lib/api_lookup.php');
			require_once(ZANOX_PATH . '/lib/api_import.php');
			require_once(ZANOX_PATH . '/lib/api_update.php');
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
			register_setting('endcore_api_zanox_options', 'zanox_connect_id');
			register_setting('endcore_api_zanox_options', 'zanox_secret_key');
			register_setting('endcore_api_zanox_options', 'zanox_country');
            register_setting('endcore_api_zanox_options', 'zanox_post_status');
			register_setting('endcore_api_zanox_options', 'zanox_import_description');
			register_setting('endcore_api_zanox_options', 'zanox_update_price');
			register_setting('endcore_api_zanox_options', 'zanox_update_url');
            register_setting('endcore_api_zanox_options', 'zanox_check_product_unique');
			register_setting('endcore_api_zanox_button_options', 'zanox_buy_short_button');
			register_setting('endcore_api_zanox_button_options', 'zanox_buy_button');
		}
		
		/*
		 * SCRIPTS
		 */
		public function menu_scripts($page) {
			if('import_page_endcore_api_zanox' != $page) {
				return;
			}

			wp_enqueue_style('at-select2', plugin_dir_url( __FILE__ ) . 'view/css/select2.min.css');
			wp_enqueue_script('at-select2', plugin_dir_url( __FILE__ ) . 'view/js/select2.min.js', '', '1.0', false);
			wp_enqueue_script('at-zanox-functions', plugin_dir_url( __FILE__ ) . 'view/js/zan_functions.js', '', '1.2.1', false);
			wp_localize_script('at-zanox-functions', 'zanox_vars', array(
					'connection' => __('Verbindungsaufbau...', 'affiliatetheme-zanox'),
					'connection_ok' => __('Verbindung erfolgreich hergestellt.', 'affiliatetheme-zanox'),
					'connection_error' => __('Eine Verbindung zu zanox konnte nicht hergestellt werden.</p><p><strong>Fehlermeldung:</strong>', 'affiliatetheme-zanox'),
					'connection_error_2' => __('Eine Verbindung zu zanox konnte nicht hergestellt werden. Bitte überprüfe deine Einstellungen.', 'affiliatetheme-zanox'),
					'no_image' => __('Kein Bild vorhanden', 'affiliatetheme-zanox'),
					'import' => __('Importieren', 'affiliatetheme-zanox'),
					'edit' => __('Editieren', 'affiliatetheme-zanox'),
					'no_products_found' => __('Es wurden keine Produkte gefunden. Bitte definiere deine Suche neu.', 'affiliatetheme-zanox'),
					'edit_product' => __('Produkt bearbeiten', 'affiliatetheme-zanox'),
					'import_count' => __('Importiere Produkt <span class="current">1</span> von', 'affiliatetheme-zanox'),
					'adblocker_hint' => __('Bitte deaktiviere deinen Adblocker um alle Funktionen der API zu nutzen!', 'affiliatetheme-zanox')
				)
			);
		}

		/**
		 * menu content
		 */
		public function menu_dashboard() {
			$plugin_options = 'endcore_api_zanox_options';
			$plugin_button_options = 'endcore_api_zanox_button_options';

			require_once(ZANOX_PATH . '/view/panel.php');
		} 
			
			
		/**
		 * add a menu
		 */		
		public function add_submenu_page()	{
			add_submenu_page('endcore_api_dashboard', __('Zanox', 'affiliatetheme-zanox'), __('Zanox', 'affiliatetheme-zanox'), apply_filters('at_set_import_dashboard_capabilities', 'administrator'), 'endcore_api_zanox', array(&$this, 'menu_dashboard'));
		}

		/**
		 * load textdomain
		 */
		public function load_textdomain() {
			load_plugin_textdomain('affiliatetheme-zanox', false, dirname(plugin_basename( __FILE__ )) . '/languages');
		}
    }
} 