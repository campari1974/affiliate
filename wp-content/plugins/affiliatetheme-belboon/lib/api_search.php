<?php
add_action('wp_ajax_belboon_api_search', 'at_belboon_search');
add_action('wp_ajax_at_belboon_search', 'at_belboon_search');
function at_belboon_search() {
    $result = array();
    $api = new \Endcore\Belboon\Belboon();
    
    $query = (isset($_GET['q']) ? $_GET['q'] : '');
    $platform = (isset($_GET['platform']) ? $_GET['platform'] : '');
    $shopId = (isset($_GET['shopId']) && $_GET['shopId'] != '' && $_GET['shopId'] != 'null') ? (strpos($_GET['shopId'], ',') !== false ? explode(',', $_GET['shopId']) : array($_GET['shopId'])) : array();
    $min_price = (isset($_GET['min_price']) ? $_GET['min_price'] : '');
    $max_price = (isset($_GET['max_price']) ? $_GET['max_price'] : '');
    $sort = (isset($_GET['sort']) ? $_GET['sort'] : '');
    $order = (isset($_GET['order']) ? $_GET['order'] : '');
    $pageId = (isset($_GET['p']) ? $_GET['p'] : 1);

    $config = array();

    if (!empty($shopId)) {
        $config['feeds'] = $shopId;
    }

    if($platform) {
        $config['platforms'] = array($platform);
    }

    if($min_price) {
        $config['price_min'] = $min_price;
    }

    if($max_price) {
        $config['price_max'] = $max_price;
    }

    if($sort && $order) {
        $config['sort'] = array($sort => $order);
    }
    
    /** @var Endcore\Belboon\ProductList $items */
    try {
        $items = $api->searchProducts($query, $pageId, $config);

        if ($items->getTotalPages()) {
            $result['totalpages'] = $items->getTotalPages();
        }

        foreach ($items as $item) {
            $check = at_get_product_id_by_metakey('product_shops_%_' . BBOON_METAKEY_ID, $item->getId(), 'LIKE');

            $result['items'][] = array(
                'id' => $item->getId(),
                'productid' => $item->getArticleNumber(),
                'name' => $item->getName(),
                'image' => $item->getBigImage(),
                'name' => $item->getName(),
                'description' => $item->getShortDescription(),
                'price' => $item->getDisplayPrice(),
                'price_old' => $item->getOldprice(),
                'category' => $item->getCategory(),
                'shop' => $item->getShopName(),
                'url' => $item->getUrl(),
                'exists' => ($check ? $check : 'false'),
            );
        }
    }
    catch (Exception $e){
        $result['message'] = $e->getMessage();
    }

    echo json_encode($result);
    exit();
}