<?php
add_action('wp_ajax_zanox_api_products', 'at_zanox_products');
add_action('wp_ajax_at_zanox_products', 'at_zanox_products');
function at_zanox_products() {
    $result = array();
    $result['items'] = array();

    $query = isset($_GET['q']) ? (string)$_GET['q'] : '';
    $page = isset($_GET['p']) ? (int)$_GET['p'] : 0;
    $searchType = array('phrase');
    $ean = null;
    $region = ZANOX_API_COUNTRY ?: 'DE';
    $merchantCategoryId = null;
    $programId = isset($_GET['program']) ? array($_GET['program']) : array();
    $hasImages = true;
    $minPrice = (isset($_GET['min_price']) ? intval($_GET['min_price']) : 0);
    $maxPrice = (isset($_GET['max_price']) && $_GET['max_price'] > 0 ? intval($_GET['max_price']) : null);
    $adspaceId = (isset($_GET['adspace']) && ($_GET['adspace'] != "")) ? $_GET['adspace'] : null;
    $items = isset($_GET['items']) ? (int)$_GET['items'] : 10;
    $api = new \EcZanox\Zanox();

    $products = $api->searchProducts($query, $searchType, $ean, $region,
        $merchantCategoryId, $programId, $hasImages,
        $minPrice, $maxPrice, $adspaceId, $page, $items);

    /** @var \EcZanox\Product $product */
    foreach ($products as $product) {
        $check = at_get_product_id_by_metakey('product_shops_%_' . ZANOX_METAKEY_ID, $product->getId(), 'LIKE');

        $result['items'][] = array(
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getFormattedPrice(),
            'description' => $product->getShortDescription(),
            'images' => $product->getImages(),
            'url' => $product->getUrl(),
            'exists' => ($check ? $check : 'false'),
        );
    }

    $result['extras'] = $products->getExtras();

    header('Content-Type: application/json; charset=utf-8');
    echo safe_json_encode($result);
    die();
}

function safe_json_encode($value){
    if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
        $encoded = json_encode($value, JSON_PRETTY_PRINT);
    } else {
        $encoded = json_encode($value);
    }
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            return $encoded;
        case JSON_ERROR_DEPTH:
            return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_STATE_MISMATCH:
            return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_CTRL_CHAR:
            return 'Unexpected control character found';
        case JSON_ERROR_SYNTAX:
            return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_UTF8:
            $clean = utf8ize($value);
            return safe_json_encode($clean);
        default:
            return 'Unknown error'; // or trigger_error() or throw new Exception()

    }
}

function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } else if (is_string ($mixed)) {
        return EcZanox\Encoding::toUTF8($mixed);
    }
    return $mixed;
}