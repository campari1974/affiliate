<?php
add_action('wp_ajax_at_affilinet_search_ean', 'at_affilinet_search_ean');
add_action('wp_ajax_affilinet_api_ean', 'at_affilinet_search_ean');
function at_affilinet_search_ean() {
	$result = array();
	$api = new Endcore\Api\Affilinet();
	$ean = (isset($_GET['ean']) ? $_GET['ean'] : '');
	$query = (isset($_GET['query']) ? $_GET['query'] : '');
    $shopId = (isset($_GET['shopId']) ? $_GET['shopId'] : array());
    $pageId = (isset($_GET['p']) ? $_GET['p'] : '1');

	/** @var \Endcore\Result\searchProductsInCategory $items */
	$items = $api->searchProductsByEan($ean, $query, $pageId, $shopId);
	if ($items->getTotalPages()) $result['totalpages'] = $items->getTotalPages();

	/** @var \Endcore\ProductNew $item */
	foreach ($items as $item) {
		$check = at_get_product_id_by_metakey('product_shops_%_' . ANET_METAKEY_ID, $item->getId(), 'LIKE');

		$result['items'][] = array(
			'id' => $item->getId(),
			'productid' => $item->getArticleNumber(),
			'ean' => $item->getEan(),
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