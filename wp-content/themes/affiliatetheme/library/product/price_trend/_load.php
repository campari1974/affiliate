<?php
/**
 * Laden der Funktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	_load
 */

global $wpdb;
define('AT_PRICE_TREND_TABLE', $wpdb->prefix . 'at_price_trend');

require 'helper.php';