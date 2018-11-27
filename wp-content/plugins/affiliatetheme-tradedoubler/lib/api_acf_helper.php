<?php
add_action('wp_ajax_at_tradedoubler_add_acf', 'at_tradedoubler_add_acf');
function at_tradedoubler_add_acf()
{
    $nonce = $_POST['_wpnonce'];

    if (!wp_verify_nonce($nonce, 'at_tradedoubler_import_wpnonce')) {
        die('Security Check failed');
    }

    // vars
    $id = $_POST['id'];
    $taxs = isset($_POST['tax']) ? $_POST['tax'] : array();
    // quick import
    $api = new Endcore\Api\Tradedoubler();

    $items = $api->lookupProduct($id);

    foreach ($items['products'] as $item) {
        $offer = $item['offers'][0];
        $prices = $offer['priceHistory'];
        $timestamp_newest = 0;
        foreach ($prices as $price_tmp) {
            if ($price_tmp['date'] > $timestamp_newest) {
                $price_arr = $price_tmp['price'];
                $timestamp_newest = $price_tmp['date'];
            }
        }
        $id = $offer['id'];
        $price = $price_arr['value'];
        $shop_id = $offer['feedId'];
        $shop_name = $offer['programName'];
        $url = $offer['productUrl'];
        $currency = $price_arr['currency'];
    }
    $currency = (strtolower($currency) == 'eur')?'euro':strtolower($currency);

    $portal = 'tradedoubler';
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