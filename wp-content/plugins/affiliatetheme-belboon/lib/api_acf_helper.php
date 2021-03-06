<?php

add_action('wp_ajax_at_belboon_add_acf', 'at_belboon_add_acf');
function at_belboon_add_acf()
{
    $nonce = $_POST['_wpnonce'];

    if (!wp_verify_nonce($nonce, 'at_belboon_import_wpnonce')) {
        die('Security Check failed');
    }

    // vars
    $id = $_POST['id'];
    // quick import
    $api = new Endcore\Belboon\Belboon();

    $items = $api->getProductById($id);


    if ($items) {
        $item = $items->current();
        $price = $item->getPrice();
        $price_old = $item->getOldprice();
        $currency = $item->getCurrency();
        $shop_id = $item->getShopId();
        $shop_name = $item->getShopName();
        $url = $item->getUrl();
    }

    $portal = 'belboon';
    $output['rmessage']['success'] = 'true';
    $output['shop_info']['price'] = $price;
    $output['shop_info']['price_old'] = $price_old;
    $output['shop_info']['currency'] = (strtolower($currency) == 'eur' )? 'euro':strtolower($currency);
    $output['shop_info']['portal'] = $portal;
    $output['shop_info']['metakey'] = $id;
    $output['shop_info']['link'] = $url;
    $output['shop_info']['shop'] = at_get_shop_id($shop_id, $shop_name, true);
    $output['shop_info']['shopname'] = $shop_name;
    echo json_encode($output);
    exit();
}