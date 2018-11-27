<?php
/**
 * Laden der Funktionen
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	_load
 */

require_once(ENDCORE_LIBRARY . '/product/classes/AT_Product.php');
require_once(ENDCORE_LIBRARY . '/product/classes/AT_Product_Fields.php');
require_once(ENDCORE_LIBRARY . '/product/classes/AT_Product_Field.php');
require_once(ENDCORE_LIBRARY . '/product/posttype.php');
require_once(ENDCORE_LIBRARY . '/product/taxonomy.php');
require_once(ENDCORE_LIBRARY . '/product/helper.php');
require_once(ENDCORE_LIBRARY . '/product/actions.php');
require_once(ENDCORE_LIBRARY . '/product/filter.php');
require_once(ENDCORE_LIBRARY . '/product/comments.php');
require_once(ENDCORE_LIBRARY . '/product/shortcodes.php');
require_once(ENDCORE_LIBRARY . '/product/price_trend/_load.php');
require_once(ENDCORE_LIBRARY . '/product/deprecated.php');
require_once(ENDCORE_LIBRARY . '/product/helper_productdata.php');

if('1' == get_field('product_cloak', 'option')) require_once(ENDCORE_LIBRARY . '/product/cloak.php');