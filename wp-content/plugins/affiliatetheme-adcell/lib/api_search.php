<?php
use EcAdcell\Search;

add_action('wp_ajax_at_adcell_search', 'at_adcell_search');
add_action('wp_ajax_adcell_api_search', 'at_adcell_search');
function at_adcell_search() {
    $result = array();

    $query = (isset($_GET['q']) ? $_GET['q'] : '');
    $promotionId = (isset($_GET['promo']) ? $_GET['promo'] : '');

    $search = new Search();

    $items = $search->findByPromotionIdAndKeyword($promotionId, $query);
    /** @var EcAdcell\Item $item */
    foreach ($items as $item) {
        $check = at_get_product_id_by_metakey('product_shops_%_' . ADCELL_METAKEY_ID, $item->getId(), 'LIKE');

        $result['items'][] = array(
            'id' => $item->getId(),
            'productid' => $item->getArticleNumber(),
            'name' => $item->getName(),
            'image' => $item->getImage(),
            'description' => $item->getShortDescription(),
            'price' => $item->getDisplayPrice(),
            'category' => $item->getCategory(),
            'url' => $item->getUrl(),
            'exists' => ($check ? $check : 'false'),
        );
    }

    echo json_encode($result);
    exit();
}