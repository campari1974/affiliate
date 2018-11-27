<?php
add_action('wp_ajax_at_affilinet_categorylist', 'at_affilinet_categorylist');
add_action('wp_ajax_affilinet_api_categorylist', 'at_affilinet_categorylist');
function at_affilinet_categorylist() {
	$result = array();
	$api = new Endcore\Api\Affilinet();

	/** @var \Endcore\Result\getCategoryList $categories */
	$current_shop = $_GET['shop'];
	if ($current_shop) {
		$categories = $api->getCategoryList($current_shop);
		$result = $categories->getData();
	} else {
		$result['items'][] = array(
			'id' => '',
			'name' => __('Kein Shop ausgewählt', 'affiliatetheme-affilinet'),
		);
	}
    echo json_encode($result);
	exit();
}