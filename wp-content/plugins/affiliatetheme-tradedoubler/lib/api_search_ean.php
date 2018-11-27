<?php
add_action('wp_ajax_at_tradedoubler_search_ean', 'at_tradedoubler_search_ean');
add_action('wp_ajax_tradedoubler_api_ean', 'at_tradedoubler_search_ean');
function at_tradedoubler_search_ean() {
	$result = array();
	$api = new Endcore\Api\Tradedoubler();
	$ean = (isset($_GET['ean']) ? $_GET['ean'] : '');
	$query = (isset($_GET['query']) ? $_GET['query'] : '');
	$pageId = (isset($_GET['p']) ? $_GET['p'] : '1');
	$items_per_page = 25;

	$items = $api->searchProductsByEAN($ean, $query, $pageId,$items_per_page);

    if($items['productHeader']['totalHits'])
        $result['totalpages']= ceil($items['productHeader']['totalHits']/$items_per_page);

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
        foreach($item['offers'] as $offer) {
            $prices = $offer['priceHistory'];
            $timestamp_newest = 0;
            foreach ($prices as $price_tmp) {
                if ($price_tmp['date'] > $timestamp_newest) {
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
                'price' => ($price['value'] != '0') ? $price['value'] . ' ' . $price['currency'] : 'kA',
                'shop' => $offer['feedId'],
                'shopname' => $offer['programName'],
                'url' => $offer['productUrl'],
                'category' => ($item['categories'][0]['tdCategoryName'] != null) ? $item['categories'][0]['tdCategoryName'] : '-',
                'exists' => ($check ? $check : 'false'),
                'currency' => $price['currency'],
            );
        }
    }
    echo json_encode($result);

	exit();
}