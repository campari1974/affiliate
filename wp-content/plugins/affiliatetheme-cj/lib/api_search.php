<?php
add_action('wp_ajax_at_cj_search', 'at_cj_search');
add_action('wp_ajax_cj_api_search', 'at_cj_search');
function at_cj_search() {
	$result = array();
	$api = new Endcore\Api\CommissionJunction();

	$shopId = (isset($_GET['shopId']) && $_GET['shopId'] != '') ? (strpos($_GET['shopId'], ',') !== false ? explode(',', $_GET['shopId']) : array($_GET['shopId'])) : array();
	$query = (isset($_GET['q']) ? $_GET['q'] : '*');
	$pageId = (isset($_GET['p']) ? $_GET['p'] : '1');
	$min_price = (isset($_GET['min_price']) && $_GET['min_price'] > 0 ? $_GET['min_price'] : 0);
	$max_price = (isset($_GET['max_price']) && $_GET['max_price'] > 0 ? $_GET['max_price'] : 99999);
	$sort = (isset($_GET['sort']) ? $_GET['sort'] : '');
	$order = (isset($_GET['order']) ? $_GET['order'] : 'desc');
	$items = (isset($_GET['items']) ? $_GET['items'] : 25);

	$items = $api->searchProducts($query, $pageId, $shopId, $min_price, $max_price, $sort, $order, $items);
	//var_dump($items);
    if(at_cj_getTotalPages($items)) $result['totalpages']= at_cj_getTotalPages($items);
    if($items->products->{'@attributes'}->{'total-matched'} == '1'){
        foreach ($items->products as $item) {
            $check = at_get_product_id_by_metakey('product_shops_%_' . CJ_METAKEY_ID, $item->sku, 'LIKE');
            if($item->{'ad-id'})
                $result['items'][] = array(
                    'id' => $item->{'ad-id'},
                    'productid' => $item->sku,
                    'name' => $item->name,
                    'image' => $item->{'image-url'},
                    'description' => $item->description,
                    'price' => ($item->price != '0')?$item->price.' '.$item->currency: 'kA',
                    'shop' => $item->{'advertiser-id'},
                    'shopname' =>$item->{'advertiser-name'},
                    'url' => $item->{'buy-url'},
                    'category'=> at_cj_getCategory($item->{'advertiser-category'}),
                    'exists' => ($check ? $check : 'false'),
                    'currency' => $item->currency,
                );
        }
    }
    else
	foreach ($items->products->product as $item) {
		$check = at_get_product_id_by_metakey('product_shops_%_' . CJ_METAKEY_ID, $item->sku, 'LIKE');

		$result['items'][] = array(
			'id' => $item->{'ad-id'},
			'productid' => $item->sku,
			'name' => $item->name,
			'image' => $item->{'image-url'},
			'description' => $item->description,
            'price' => ($item->price != '0')?$item->price.' '.$item->currency: 'kA',
			'shop' => $item->{'advertiser-id'},
            'shopname' =>$item->{'advertiser-name'},
			'url' => $item->{'buy-url'},
            'category'=> at_cj_getCategory($item->{'advertiser-category'}),
			'exists' => ($check ? $check : 'false'),
            'currency' => $item->currency,
            );
	}

	echo json_encode($result);

	exit();
}