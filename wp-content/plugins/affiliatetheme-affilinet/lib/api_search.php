<?php
add_action('wp_ajax_at_affilinet_search', 'at_affilinet_search');
add_action('wp_ajax_affilinet_api_search', 'at_affilinet_search');
function at_affilinet_search() {
	$result = array();
	$api = new Endcore\Api\Affilinet();

	$shopId = (isset($_GET['shopId']) && $_GET['shopId'] != '') ? (strpos($_GET['shopId'], ',') !== false ? explode(',', $_GET['shopId']) : array($_GET['shopId'])) : array();
	$categoryId = (isset($_GET['categoryId']) && $_GET['categoryId'] != '') ? array($_GET['categoryId']) : array();
	$query = (isset($_GET['q']) ? $_GET['q'] : '*');
	$pageId = (isset($_GET['p']) ? $_GET['p'] : '1');
	$min_price = (isset($_GET['min_price']) && $_GET['min_price'] > 0 ? $_GET['min_price'] : 0);
	$max_price = (isset($_GET['max_price']) && $_GET['max_price'] > 0 ? $_GET['max_price'] : 99999);
	$sort = (isset($_GET['sort']) ? $_GET['sort'] : 'Score');
	$order = (isset($_GET['order']) ? $_GET['order'] : 'descending');
	$items = (isset($_GET['items']) ? $_GET['items'] : 25);

	/** @var \Endcore\Result\searchProductsInCategory $items */
	//$items = $api->searchProductsInCategory($shopId, $categoryId, $query, $pageId);
	$items = $api->searchProducts($query, $pageId, $categoryId, $shopId, $min_price, $max_price, $sort, $order, $items);
	if ($items->getTotalPages()) $result['totalpages'] = $items->getTotalPages();

	/** @var \Endcore\ProductNew $item */
	foreach ($items as $item) {
		$check = at_get_product_id_by_metakey('product_shops_%_' . ANET_METAKEY_ID, $item->getId(), 'LIKE');

		$result['items'][] = array(
			'id' => $item->getId(),
			'productid' => $item->getArticleNumber(),
			'name' => $item->getName(),
			'image' => $item->getImage(),
			'name' => $item->getName(),
			'description' => $item->getShortDescription(),
			'price' => $item->getDisplayPrice(),
			'category' => $item->getCategory(),
			'shop' => $item->getShopName(),
			'url' => $item->getUrl(),
			'exists' => ($check ? $check : 'false'),
		);
	}

	echo json_encode($result);

	exit();
}