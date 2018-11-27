<?php
add_action('wp_ajax_at_affilinet_shoplist', 'at_affilinet_shoplist');
add_action('wp_ajax_affilinet_api_shoplist', 'at_affilinet_shoplist');
function at_affilinet_shoplist() {
	$api = new Endcore\Api\Affilinet();

	/** @var \Endcore\Result\ShopList $shops */
	$shops = $api->getShopList();
	/** @var \Endcore\Shop $shop */

	$result = $shops->getData();
    echo json_encode($result);

    exit();
}