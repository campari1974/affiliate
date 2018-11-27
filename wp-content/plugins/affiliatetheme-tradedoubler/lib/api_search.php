<?php
add_action('wp_ajax_at_tradedoubler_search', 'at_tradedoubler_search');
add_action('wp_ajax_tradedoubler_api_search', 'at_tradedoubler_search');
function at_tradedoubler_search() {
	$result = array();
	$api = new Endcore\Api\Tradedoubler();

	$shopId = (isset($_GET['shopId']) && $_GET['shopId'] != '') ? (strpos($_GET['shopId'], ',') !== false ? explode(',', $_GET['shopId']) : array($_GET['shopId'])) : array();
	$query = (isset($_GET['q']) ? $_GET['q'] : '*');
	$pageId = (isset($_GET['p']) ? $_GET['p'] : '1');
	$min_price = (isset($_GET['min_price']) && $_GET['min_price'] > 0 ? $_GET['min_price'] : 0);
	$max_price = (isset($_GET['max_price']) && $_GET['max_price'] > 0 ? $_GET['max_price'] : 99999);
	$sort = (isset($_GET['sort']) ? $_GET['sort'] : '');
	$order = (isset($_GET['order']) ? $_GET['order'] : 'Desc');
    $categoryId = (isset($_GET['category_id']) ? $_GET['category_id'] : '');
	$items_per_page = (isset($_GET['items']) ? $_GET['items'] : 25);

	$items = $api->searchProducts($query, $pageId, $shopId, $min_price, $max_price, $sort, $order, $items_per_page, $categoryId);

    if($items['productHeader']['totalHits']) $result['totalpages']= ceil($items['productHeader']['totalHits']/$items_per_page);
	foreach ($items['products'] as $item) {
        $ean = '';
        if(isset($item['identifiers']['ean'])){
            $ean = $item['identifiers']['ean'];
        }
        foreach($item['fields'] as $field){
            if(strtolower($field['name']) == 'ean'){
                $ean = $field['value'];
            }
        }
	    $offer = $item['offers'][0];
	    $prices = $offer['priceHistory'];
	    $timestamp_newest = 0;
        foreach($prices as $price_tmp){
	        if($price_tmp['date'] > $timestamp_newest){
	            $price = $price_tmp['price'];
	            $timestamp_newest = $price_tmp['date'];
            }
        }
		$check = at_get_product_id_by_metakey('product_shops_%_' . TRADEDOUBLER_METAKEY_ID, $offer['id'], 'LIKE');
		$result['items'][] = array(
			'id' => $offer['id'],
			'productid' => $offer['sourceProductId'],
			'ean' => $ean,
			'name' => $item['name'],
			'image' => $item['productImage']['url'],
			'description' => $item['description'],
            'price' => ($price['value'] != '0')?$price['value'].' '.$price['currency']: 'kA',
			'shop' => $offer['feedId'],
            'shopname' => $offer['programName'],
			'url' => $offer['productUrl'],
            'category'=> (isset($item['categories'][0]['tdCategoryName']))?$item['categories'][0]['tdCategoryName']:'-',
			'exists' => ($check ? $check : 'false'),
            'currency' => $price['currency'],
            );
	}

	echo json_encode($result);

	exit();
}