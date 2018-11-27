<?php
add_action('wp_ajax_at_affilinet_add_acf', 'at_affilinet_add_acf');
function at_affilinet_add_acf()
{
    $nonce = $_POST['_wpnonce'];

    if (!wp_verify_nonce($nonce, 'at_affilinet_import_wpnonce')) {
        die('Security Check failed');
    }

    // vars
    $id = $_POST['id'];
    $taxs = isset($_POST['tax']) ? $_POST['tax'] : array();
    // quick import
    $api = new Endcore\Api\Affilinet();
    $item = $api->lookupProduct($id);
    $item = $item->current();
    $price = $item->getPrice();
    $currency = (('eur' == $item->getCurrency()) ? 'euro' : $item->getCurrency());
    $shop_id = $item->getShopId();
    $shop_name = $item->getShopName();
    $url = $item->getUrl();

    $portal = 'affilinet';
    $output['rmessage']['success'] = 'true';
    $output['shop_info']['price'] = $price;
    $output['shop_info']['currency'] = strtolower($currency);
    $output['shop_info']['portal'] = $portal;
    $output['shop_info']['metakey'] = $id;
    $output['shop_info']['link'] = $url;
    $output['shop_info']['shop'] = at_get_shop_id($shop_id, $shop_name, true);
    $output['shop_info']['shopname'] = $shop_name;
    echo json_encode($output);
    exit();
}