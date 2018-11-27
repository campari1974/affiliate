<?php
add_action('wp_ajax_at_affilinet_import', 'at_affilinet_import');
add_action('wp_ajax_affilinet_api_import', 'at_affilinet_import');
function at_affilinet_import() {
    $nonce = $_POST['_wpnonce'];

    if (!wp_verify_nonce($nonce, 'at_affilinet_import_wpnonce')) {
        die('Security Check failed');
    }

    // vars
    $id = $_POST['id'];
    $taxs = isset($_POST['tax']) ? $_POST['tax'] : array();
    $description = '';

    if (isset($_POST['func']) && $_POST['func'] == 'quick-import') {
        // quick import
        $api = new Endcore\Api\Affilinet();
        $item = $api->lookupProduct($id);
        $item = $item->current();
        $ean = $item->getEan();
        $title = $item->getName();
        $price = $item->getPrice();
        $currency = (('eur' == $item->getCurrency()) ? 'euro' : $item->getCurrency());
        $pimage = $item->getImage();
        $shop_id = $item->getShopId();
        $shop_name = $item->getShopName();
        $rating = '0';
        $url = $item->getUrl();

        if ($pimage) {
            $images[0]['filename'] = sanitize_title($title);
            $images[0]['alt'] = $title;
            $images[0]['url'] = $pimage;
            $images[0]['thumb'] = 'true';
        }

        if ('1' == get_option('affilinet_import_description'))
            $description = $item->getDescription();

    } else {
        // normal import
        $ean = $_POST['ean'];
        $title = $_POST['title'];
        $price = floatval($_POST['price']);
        $currency = (('EUR' == $_POST['currency']) ? 'euro' : $_POST['currency']);
        $rating = $_POST['rating'];
        $images = $_POST['image'];
        $shop_id = $_POST['shop_id'];
        $shop_name = $_POST['shop_name'];
        $url = $_POST['url'];

        if ('1' == get_option('affilinet_import_description'))
            $description = $_POST['description'];
    }

    // append
    $append = (isset($_POST['ex_page_id']) ? $_POST['ex_page_id'] : '');
    if(!$append && $ean) {
        $append = at_get_product_id_by_ean($ean);
    }

    if (false == ($check = at_get_product_id_by_metakey('product_shops_%_' . ANET_METAKEY_ID, $id, '='))) {
        // try to append product
        if ($append) {
            // product already exists, append
            $product_shops = get_field('product_shops', $append);
            $product_index = getRepeaterRowID($product_shops, 'ID', at_get_shop_id($shop_id), true);

            if (!$product_index) {
                $shop_info = get_field('field_557c01ea87000', $append);
                $shop_info[] = array(
                    'price' => $price,
                    'currency' => $currency,
                    'portal' => 'affilinet',
                    ANET_METAKEY_ID => $id,
                    'shop' => at_get_shop_id($shop_id, $shop_name, true),
                    'link' => $url,
                );
                update_field('field_557c01ea87000', $shop_info, $append);

                at_write_api_log('affilinet', $append, 'extended product successfully');

                $output['rmessage']['success'] = 'true';
                $output['rmessage']['post_id'] = $append;
            } else {
                $output['rmessage']['success'] = 'false';
                $output['rmessage']['reason'] = __('Dieses Produkt existiert bereits.', 'affiliatetheme-affilinet');
                $output['rmessage']['post_id'] = $append;
            }
        } else {
            $args = array(
                'post_title' => $title,
                'post_status' => (get_option('affilinet_post_status') ? get_option('affilinet_post_status') : 'publish'),
                'post_type' => 'product',
                'post_content' => ($description ? $description : '')
            );

            $post_id = wp_insert_post($args);
            if ($post_id) {
                // customfields
                if(ctype_digit($ean)) {
                    // only numeric ean
                    update_post_meta($post_id, 'product_ean', $ean);
                }
                update_post_meta($post_id, 'last_product_price_check', '0');
                update_post_meta($post_id, ANET_METAKEY_LAST_UPDATE, '0');
                update_post_meta($post_id, 'product_rating', $rating);
                update_post_meta($post_id, 'affilinet_shopid', $shop_id);
                $selector = new acf_field_field_selector();
                $fields = $selector->get_selectable_item_fields(null,true);
                $selectable = $selector->get_items("", $fields);
                foreach($selectable as $field){
                    update_post_meta($post_id, $field['name'], $_POST[$field['name']]);
                }

                $shop_info[] = array(
                    'price' => $price,
                    'currency' => $currency,
                    'portal' => 'affilinet',
                    ANET_METAKEY_ID => $id,
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
            };

            at_write_api_log('affilinet', $post_id, 'imported product successfully');

            $output['rmessage']['success'] = 'true';
            $output['rmessage']['post_id'] = $post_id;
        }
    } else {
        $output['rmessage']['success'] = 'false';
        $output['rmessage']['reason'] = __('Dieses Produkt existiert bereits.', 'affiliatetheme-affilinet');
        $output['rmessage']['post_id'] = $check;
    }

    do_action('at_affilinet_import');

    sleep(1);

    echo json_encode($output);
    exit();
}
?>