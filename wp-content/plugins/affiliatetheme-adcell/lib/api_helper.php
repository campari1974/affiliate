<?php
if ( ! function_exists( 'adcell_array_insert' ) ) {
	/**
	 * adcell_array_insert
	 * @deprecated since 1.0.1
	 *
	 */
	function adcell_array_insert(&$array, $position, $insert) {
		if (!is_array($array))
			return;

		if (is_int($position)) {
			array_splice($array, $position, 0, $insert);
		} else {
			$pos = array_search($position, array_keys($array));
			$array = array_merge(
				array_slice($array, 0, $pos),
				$insert,
				array_slice($array, $pos)
			);
		}
	}
}

if ( ! function_exists( 'at_adcell_array_insert' ) ) {
	/**
	 * at_adcell_array_insert
	 *
	 * Array helper
	 * @param   array $array
	 * @param   int $position
	 * @param   int $insert
	 * @return  -
	 */
	function at_adcell_array_insert(&$array, $position, $insert) {
		if (!is_array($array))
			return;

		if (is_int($position)) {
			array_splice($array, $position, 0, $insert);
		} else {
			$pos = array_search($position, array_keys($array));
			$array = array_merge(
				array_slice($array, 0, $pos),
				$insert,
				array_slice($array, $pos)
			);
		}
	}
}

if ( ! function_exists( 'at_adcell_add_as_portal' ) ) {
	/**
	 * at_adcell_add_as_portal
	 *
	 * Add adcell to Product Portal Dropdown
	 */
	add_filter('at_add_product_portal', 'at_adcell_add_as_portal', 10, 2);
	function at_adcell_add_as_portal($choices) {
		$choices['adcell'] = __('Adcell', 'affiliatetheme-adcell');
		return $choices;
	}
}

if ( ! function_exists( 'at_adcell_add_field_portal_id' ) ) {
	/**
	 * at_adcell_add_field_portal_id
	 *
	 * Add adcell Fields to Products
	 */
	add_filter('at_add_product_fields', 'at_adcell_add_field_portal_id', 10, 2);
	function at_adcell_add_field_portal_id($fields) {
		$new_field[] = array(
			'key' => 'field_553b14zy2332cc',
			'label' => __('Adcell ID', 'affiliatetheme-adcell'),
			'name' => 'adcell_id',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_553b83de246bb',
						'operator' => '==',
						'value' => 'adcell',
					),
				),
			),
			'wrapper' => array(
				'width' => 25,
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		);

		at_adcell_array_insert($fields['fields'][4]['sub_fields'], 7, $new_field);
		return $fields;
	}
}

if ( ! function_exists( 'at_adcell_overwrite_product_button_short_text' ) ) {
	/**
	 * at_adcell_overwrite_product_button_short_text
	 *
	 * Overwrite Product Button Text (short)
	 */
	add_filter('at_product_api_button_short_text', 'at_adcell_overwrite_product_button_short_text', 10, 5);
	function at_adcell_overwrite_product_button_short_text($var = '', $product_portal = '', $product_shop = '', $pos = '', $short = false) {
		if ('adcell' == $product_portal && 'buy' == $pos) {
			$var = (get_option('adcell_buy_short_button') ? get_option('adcell_buy_short_button') : __('Kaufen', 'affiliatetheme-adcell'));

			if ($product_shop) {
				$var = sprintf($var, $product_shop->post_title);
			}
		}

		return $var;
	}
}

if ( ! function_exists( 'at_adcell_overwrite_product_button_text' ) ) {
	/**
	 * at_adcell_overwrite_product_button_text
	 *
	 * Overwrite Product Button Text
	 */
	add_filter('at_product_api_button_text', 'at_adcell_overwrite_product_button_text', 10, 5);
	function at_adcell_overwrite_product_button_text($var = '', $product_portal = '', $product_shop = '', $pos = '', $short = false) {
		if ('adcell' == $product_portal && 'buy' == $pos) {
			$var = (get_option('adcell_buy_button') ? get_option('adcell_buy_button') : __('Jetzt bei %s kaufen', 'affiliatetheme-adcell'));

			if ($product_shop) {
				$var = sprintf($var, $product_shop->post_title);
			}
		}

		return $var;
	}
}

if ( ! function_exists('at_adcell_notices') ) {
	/**
	 * at_adcell_notices function.
	 *
	 */
	add_action('admin_notices', 'at_adcell_notices');
	function at_adcell_notices() {
		if ((isset($_GET['page']) && $_GET['page'] == 'endcore_api_adcell')) {
			// check php version
			if(version_compare(PHP_VERSION, '5.5.0', '<')) {
				?>
				<div class="notice notice-error">
					<p><?php printf(__('Achtung: Um dieses Plugin zu verwenden benötigst du mindestens PHP Version 5.3.x. Derzeit verwendest du Version %s.', 'affiliatetheme-adcell'), PHP_VERSION); ?></p>
				</div>
				<?php
			}

			// check curl
			if(extension_loaded('curl') != function_exists('curl_version')) {
				?>
				<div class="notice notice-error">
					<p><?php _e('Um dieses Plugin zu verwenden benötigst du cURL. <a href="http://php.net/manual/de/book.curl.php" taget="_blank">Hier</a> findest du mehr Informationen darüber. Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-adcell'); ?></p>
				</div>
				<?php
			}

			// check allow_url_fopen
			if(ini_get('allow_url_fopen') == false) {
				?>
				<div class="notice notice-error">
					<p><?php _e('Achtung: Du hast allow_url_fopen deaktiviert. Bitte kontaktiere deinen Administrator.', 'affiliatetheme-adcell'); ?></p>
				</div>
				<?php
			}

			// check soap
			if(extension_loaded('soap') == false) {
				?>
				<div class="notice notice-error" id="required-by-plugin">
					<p><?php _e('Um dieses Plugin zu verwenden benötigst du SOAP. <a href="http://php.net/manual/en/book.soap.php" taget="_blank">Hier</a> findest du mehr Informationen darüber. Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-adcell'); ?></p>
				</div>
				<?php
			}

			// check file permissions
			$test_file = @fopen(ADCELL_CSV_PATH . "/chmod-test-file", "a+");
			if (!$test_file) :
				?>
				<div class="notice notice-error" id="required-by-plugin">
					<p><?php printf(__('Damit wir CSV-Dateien auf deinem Server ablegen können benötigen wir Schreibrechte für den folgenden Ordner: <br><br> <strong>%s.</strong> (CHMOD 775/777)<br><br>Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-adcell'), ADCELL_CSV_PATH); ?></p>
				</div>
				<?php
			endif;
			@fclose($test_file);
			@unlink(ADCELL_CSV_PATH . "/chmod-test-file");
		}
	}
}