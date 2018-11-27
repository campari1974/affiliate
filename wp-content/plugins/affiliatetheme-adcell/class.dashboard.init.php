<?php		
if(!class_exists('AffiliateTheme_Adcell_Dashboard_Init'))
{
	class AffiliateTheme_Adcell_Dashboard_Init	{
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
			$upload_dir = wp_upload_dir();
			$adcell_csv_path = $upload_dir['basedir'] . '/adcell-csv';
			define('ADCELL_PATH', plugin_dir_path( __FILE__ ));
			define('ADCELL_CSV_PATH', $adcell_csv_path);
			define('ADCELL_USER', get_option('adcell_user'));
			define('ADCELL_PASS', get_option('adcell_password'));
			define('ADCELL_METAKEY_ID', 'adcell_id');
			define('ADCELL_METAKEY_LAST_UPDATE', 'last_product_price_check_adcell');
            define('ADCELL_CRON_HASH', md5(get_option('adcell_user') . get_option('adcell_password')));
			
			// helpers
			require_once(ADCELL_PATH . '/vendor/autoload.php');
			require_once(ADCELL_PATH . '/lib/api_helper.php');
			require_once(ADCELL_PATH . '/lib/api_programs.php');
			require_once(ADCELL_PATH . '/lib/api_promo.php');
			require_once(ADCELL_PATH . '/lib/api_search.php');
			require_once(ADCELL_PATH . '/lib/api_lookup.php');
			require_once(ADCELL_PATH . '/lib/api_import.php');
            require_once(ADCELL_PATH . '/lib/api_update.php');
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
			register_setting('endcore_api_adcell_options', 'adcell_user');
			register_setting('endcore_api_adcell_options', 'adcell_password');
            register_setting('endcore_api_adcell_options', 'adcell_post_status');
			register_setting('endcore_api_adcell_options', 'adcell_import_description');
            register_setting('endcore_api_adcell_options', 'adcell_check_product_unique');
			register_setting('endcore_api_adcell_button_options', 'adcell_buy_short_button');
			register_setting('endcore_api_adcell_button_options', 'adcell_buy_button');
		}
		
		/*
		 * SCRIPTS
		 */
		public function menu_scripts($page) {
            if ('import_page_endcore_api_adcell' != $page) {
                return;
            }

			wp_enqueue_script('at-select2', plugin_dir_url( __FILE__ ).'view/js/select2.min.js', '1.0', true);
			wp_enqueue_style('at-select2', plugin_dir_url( __FILE__ ).'view/css/select2.min.css');
			wp_enqueue_script('at-adcell-functions', plugin_dir_url( __FILE__ ).'view/js/adcell_functions.js', '1.2.2', true);
			wp_localize_script('at-adcell-functions', 'adcell_vars', array(
					'connection' => __('Verbindungsaufbau...', 'affiliatetheme-adcell'),
					'connection_ok' => __('Verbindung erfolgreich hergestellt.', 'affiliatetheme-adcell'),
					'connection_error' => __('Eine Verbindung zu adcell konnte nicht hergestellt werden.</p><p><strong>Fehlermeldung:</strong>', 'affiliatetheme-adcell'),
					'connection_error_2' => __('Eine Verbindung zu adcell konnte nicht hergestellt werden. Bitte überprüfe deine Einstellungen.', 'affiliatetheme-adcell'),
					'no_image' => __('Kein Bild vorhanden', 'affiliatetheme-adcell'),
					'import' => __('Importieren', 'affiliatetheme-adcell'),
					'edit' => __('Editieren', 'affiliatetheme-adcell'),
					'no_products_found' => __('Es wurden keine Produkte gefunden. Bitte definiere deine Suche neu.', 'affiliatetheme-adcell'),
					'edit_product' => __('Produkt bearbeiten', 'affiliatetheme-adcell'),
					'import_count' => __('Importiere Produkt <span class="current">1</span> von', 'affiliatetheme-adcell'),
					'no_csv_files' => __('Keine CSV-Daten gefunden.', 'affiliatetheme-adcell'),
					'adblocker_hint' => __('Bitte deaktiviere deinen Adblocker um alle Funktionen der API zu nutzen!', 'affiliatetheme-adcell')
				)
			);
		}

		/**
		 * menu content
		 */
		public function menu_dashboard() {
			$plugin_options = 'endcore_api_adcell_options';
			$plugin_button_options = 'endcore_api_adcell_button_options';

			require_once(ADCELL_PATH . '/view/panel.php');
		}
			
			
		/**
		 * add a menu
		 */		
		public function add_submenu_page()	{
			add_submenu_page('endcore_api_dashboard', __('Adcell', 'affiliatetheme-adcell'), __('Adcell', 'affiliatetheme-adcell'), apply_filters('at_set_import_dashboard_capabilities', 'administrator'), 'endcore_api_adcell', array(&$this, 'menu_dashboard'));
		}

		/**
		 * load textdomain
		 */
		public function load_textdomain() {
			load_plugin_textdomain('affiliatetheme-adcell', false, dirname(plugin_basename( __FILE__ )) . '/languages');
		}
    } 
} 