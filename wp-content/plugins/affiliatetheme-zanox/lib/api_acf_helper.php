<?php

add_action('wp_ajax_at_zanox_add_acf', 'at_zanox_add_acf');
function at_zanox_add_acf()
{
    $nonce = $_POST['_wpnonce'];

    if (!wp_verify_nonce($nonce, 'at_zanox_import_wpnonce')) {
        die('Security Check failed');
    }

    // vars
    $id = $_POST['id'];
    // quick import
    $api = new \EcZanox\Zanox();

    $products = $api->getProduct($id);

    foreach ($products as $product) {
        $id = $product->getId();
        $price = $product->getPrice();
        $currency = $product->getCurrency();
        $url = $product->getUrl();
        $shop = $product->getProgram();
        $shop_id = md5($shop);

        break;
    }

    $portal = 'zanox';
    $output['rmessage']['success'] = 'true';
    $output['shop_info']['price'] = $price;
    $output['shop_info']['currency'] = (strtolower($currency) == 'eur' )? 'euro':strtolower($currency);
    $output['shop_info']['portal'] = $portal;
    $output['shop_info']['metakey'] = $id;
    $output['shop_info']['link'] = $url;
    $output['shop_info']['shop'] = at_get_shop_id($shop_id, $shop, true);
    $output['shop_info']['shopname'] = $shop;
    echo json_encode($output);
    exit();
}