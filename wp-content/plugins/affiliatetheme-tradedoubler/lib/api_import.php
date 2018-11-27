<?php
add_action('wp_ajax_at_tradedoubler_import', 'at_tradedoubler_import');
add_action('wp_ajax_tradedoubler_api_import', 'at_tradedoubler_import');
function at_tradedoubler_import() {
    $nonce = $_POST['_wpnonce'];

    if (!wp_verify_nonce($nonce, 'at_tradedoubler_import_wpnonce')) {
        die('Security Check failed');
    }

    // vars
    $id = $_POST['id'];
    $taxs = isset($_POST['tax']) ? $_POST['tax'] : array();
    $description = '';

    if (isset($_POST['func']) && $_POST['func'] == 'quick-import') {
        // quick import
        $api = new Endcore\Api\Tradedoubler();

        $items = $api->lookupProduct($id);


        if($items['productHeader']['totalHits']) {

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
                $title = $item['name'];
                $pimage = $item['productImage']['url'];
                $description = $item['description'];
                $price = $price_arr['value'];
                $shop_id = $offer['feedId'];
                $shop_name = $offer['programName'];
                $url = $offer['productUrl'];
                $currency = $price_arr['currency'];
            }
            $pimage = is_string($pimage) ? $pimage : '';

            if ($pimage) {
                $images[0]['filename'] = sanitize_title($title);
                $images[0]['alt'] = $title;
                $images[0]['url'] = $pimage;
                $images[0]['thumb'] = 'true';
            }

            if ('1' != get_option('tradedoubler_import_description'))
                $description = '';
        }

    } else {
        // normal import
        $ean = $_POST['ean'];
        $title = $_POST['title'];
        $price = floatval($_POST['price']);
        $currency = strtolower($_POST['currency']);
        $rating = $_POST['rating'];
        $images = $_POST['image'];
        $shop_id = $_POST['shop_id'];
        $shop_name = $_POST['shop_name'];
        $url = $_POST['url'];

        if ('1' == get_option('tradedoubler_import_description'))
            $description = $_POST['description'];
    }

    // append
    $append = (isset($_POST['ex_page_id']) ? $_POST['ex_page_id'] : '');
    if(!$append && $ean) {
        $append = at_get_product_id_by_ean($ean);
    }

    if (false == ($check = at_get_product_id_by_metakey('product_shops_%_' . TRADEDOUBLER_METAKEY_ID, $ean, '='))) {
        // try to append product
        if ($append) {
            // product already exists, append
            $product_shops = get_field('product_shops', $append);
            $product_index = getRepeaterRowID($product_shops, 'ID', at_get_shop_id($shop_id), true);

            if (!$product_index) {
                $shop_info = get_field('field_557c01ea87000', $append);
                $shop_info[] = array(
                    'price' => $price,
                    'currency' => (strtolower($currency) == 'eur')?'euro':strtolower($currency),
                    'portal' => 'tradedoubler',
                    TRADEDOUBLER_METAKEY_ID => $id,
                    'shop' => at_get_shop_id($shop_id, $shop_name, true),
                    'link' => $url,
                );
                update_field('field_557c01ea87000', $shop_info, $append);

                at_write_api_log('tradedoubler', $append, 'extended product successfully');

                $output['rmessage']['success'] = 'true';
                $output['rmessage']['post_id'] = $append;
                $output['rmessage']['id'] = $id;
            } else {
                $output['rmessage']['success'] = 'false';
                $output['rmessage']['reason'] = __('Dieses Produkt existiert bereits.', 'affiliatetheme-tradedoubler');
                $output['rmessage']['post_id'] = $append;
            }
        } else {
            $args = array(
                'post_title' => $title,
                'post_status' => (get_option('tradedoubler_post_status') ? get_option('tradedoubler_post_status') : 'publish'),
                'post_type' => 'product',
                'post_content' => ($description ? $description : '')
            );

            $post_id = wp_insert_post($args);
            if ($post_id) {
                // customfields
                if(ctype_digit($ean)&&strlen($ean) == 13) {
                    // only numeric ean
                    update_post_meta($post_id, 'product_ean', $ean);
                }
                update_post_meta($post_id, 'last_product_price_check', '0');
                update_post_meta($post_id, TRADEDOUBLER_METAKEY_LAST_UPDATE, '0');
                update_post_meta($post_id, 'product_rating', $rating);
                update_post_meta($post_id, 'tradedoubler_shopid', $shop_id);
                $selector = new acf_field_field_selector();
                $fields = $selector->get_selectable_item_fields(null,true);
                $selectable = $selector->get_items("", $fields);
                foreach($selectable as $field){
                    update_post_meta($post_id, $field['name'], $_POST[$field['name']]);
                }


                $shop_info[] = array(
                    'price' => $price,
                    'currency' => (strtolower($currency) == 'eur')?'euro':strtolower($currency),
                    'portal' => 'tradedoubler',
                    TRADEDOUBLER_METAKEY_ID => $id,
                    'shop' => at_get_shop_id($shop_id, $shop_name, true),
                    'link' => $url,
                );
                update_field('field_557c01ea87000', $shop_info, $post_id);

                // taxonomies
                if ($taxs) {
                    foreach ($taxs as $key => $value) {
                        wp_set_object_terms($post_id, $value, $key, true);
                    }
                }

                // product image
                if ($images) {
                    $attachments = array();

                    foreach ($images as $image) {
                        $image_filename = sanitize_title($image['filename']);
                        $image_alt = (isset($image['alt']) ? $image['alt'] : '');
                        $image_url = $image['url'];
                        $image_thumb = (isset($image['thumb']) ? $image['thumb'] : '');
                        $image_exclude = (isset($image['exclude']) ? $image['exclude'] : '');

                        if ("true" == $image_exclude)
                            continue;

                        if ("true" == $image_thumb) {
                            $att_id = at_attach_external_image($image_url, $post_id, true, $image_filename, array('post_title' => $image_alt));
                            update_post_meta($att_id, '_wp_attachment_image_alt', $image_alt);
                        } else {
                            $att_id = at_attach_external_image($image_url, $post_id, false, $image_filename, array('post_title' => $image_alt));
                            update_post_meta($att_id, '_wp_attachment_image_alt', $image_alt);
                            $attachments[] = $att_id;
                        }

                        if ($attachments)
                            update_field('field_553b84fb117b1', $attachments, $post_id);
                    }
                }
            }

            at_write_api_log('tradedoubler', $post_id, 'imported product successfully');

            $output['rmessage']['success'] = 'true';
            $output['rmessage']['post_id'] = $post_id;
            $output['rmessage']['id'] = $id;
        }
    } else {
        $output['rmessage']['success'] = 'false';
        $output['rmessage']['reason'] = __('Dieses Produkt existiert bereits.', 'affiliatetheme-tradedoubler');
        $output['rmessage']['post_id'] = $check;
    }

    do_action('at_tradedoubler_import');

    sleep(1);

    echo json_encode($output);
    exit();
}
?>