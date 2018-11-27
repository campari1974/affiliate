<?php
add_action('wp_ajax_at_cj_search_ean', 'at_cj_search_ean');
add_action('wp_ajax_cj_api_ean', 'at_cj_search_ean');
function at_cj_search_ean() {
	$result = array();
	$api = new Endcore\Api\CommissionJunction();
	$ean = (isset($_GET['ean']) ? $_GET['ean'] : '');
	$query = (isset($_GET['query']) ? $_GET['query'] : '');
	$pageId = (isset($_GET['p']) ? $_GET['p'] : '1');

	$items = $api->searchProductsBySKU($ean, $query, $pageId);
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
                'category'=> $item->{'advertiser-category'},
                'exists' => ($check ? $check : 'false'),
            );
        }
    }
    else
    foreach ($items->products->product as $item) {
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
            'category'=> $item->{'advertiser-category'},
            'exists' => ($check ? $check : 'false'),
        );
    }

    echo json_encode($result);

	exit();
}