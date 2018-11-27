<?php
use EcAdcell\Item;
use EcAdcell\Search;

add_action('wp_ajax_at_adcell_import', 'at_adcell_import');
add_action('wp_ajax_adcell_api_import', 'at_adcell_import');
function at_adcell_import() {
    $nonce = $_POST['_wpnonce'];

    if (!wp_verify_nonce($nonce, 'at_adcell_import_wpnonce')) {
        die('Security Check failed');
    }

    // vars
    $id = $_POST['id'];

    if (isset($_POST['func']) && $_POST['func'] == 'quick-import') {
        // quick import
        $lookup = new Search();
        $item = $lookup->getItemByUniqueId($id);
        $ean = $item->getEan();
        $title = $item->getName();
        $price = $item->getPrice();
        $currency = (('EUR' == $item->getCurrency()) ? 'euro' : $item->getCurrency());
        $pimages = array($item->getImage());
        $images = array();
        $rating = '0';
        $rating_cnt = '0';
        $taxs = isset($_POST['tax']) ? $_POST['tax'] : array();
        $shop_id = $_POST['program'];
        $shop_name = at_adcell_get_program_by_id($shop_id);
        $url = $item->getUrl();

        if ($pimages && $item->hasImage()) {
            $i = 0;
            foreach ($pimages as $image) {
                $image_info = explode('/', $image);
                $image_info = array_pop($image_info);
                $image_info = pathinfo($image_info);
                $image_filename = sanitize_title($title . '-' . $i);
                $image_ext = $image_info['extension'];

                $images[$i]['filename'] = $image_filename . '.' . $image_ext;
                $images[$i]['alt'] = $title;
                $images[$i]['url'] = $image;

                if ($i == 0) {
                    $images[$i]['thumb'] = 'true';
                } else {
                    $images[$i]['thumb'] = 'false';
                }

                $i++;
            }
        }

        $description = '';
        if ('1' == get_option('adcell_import_description')) {
            $description = ($item->getDescription() ? $item->getDescription() : $item->getShortDescription());
        }

    } else {
        // manual import
        $ean = $_POST['ean'];
        $title = $_POST['title'];
        $price = floatval($_POST['price']);
        $currency = (('EUR' == $_POST['currency']) ? 'euro' : $_POST['currency']);
        $rating = $_POST['rating'];
        $rating_cnt = $_POST['rating_cnt'];
        $taxs = isset($_POST['tax']) ? $_POST['tax'] : '';
        $images = $_POST['image'];
        $url = $_POST['url'];
        $shop_id = $_POST['shop_id'];
        $shop_name = $_POST['shop_name'];

        $description = '';
        if ('1' == get_option('adcell_import_description')) {
            $description = isset($_POST['description']) ? $_POST['description'] : '';
        }
    }

    // append
    $append = (isset($_POST['ex_page_id']) ? $_POST['ex_page_id'] : '');
    if(!$append && $ean) {
        $append = at_get_product_id_by_ean($ean);
    }

    // start import
    if (false == ($check = at_get_product_id_by_metakey('product_shops_%_' . ADCELL_METAKEY_ID, $id, '='))) {
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
                    'portal' => 'adcell',
                    ADCELL_METAKEY_ID => $id,
                    'shop' => at_get_shop_id($shop_id, $shop_name, true),
                    'link' => $url,
                );
                update_field('field_557c01ea87000', $shop_info, $append);

                at_write_api_log('adcell', $append, 'extended product successfully');

                $output['rmessage']['success'] = 'true';
                $output['rmessage']['post_id'] = $append;
            } else {
                $output['rmessage']['success'] = 'false';
                $output['rmessage']['reason'] = __('Dieses Produkt existiert bereits.', 'affiliatetheme-adcell');
                $output['rmessage']['post_id'] = $append;
            }
        } else {
            $args = array(
                'post_title' => $title,
                'post_status' => (get_option('adcell_post_status') ? get_option('adcell_post_status') : 'publish'),
                'post_type' => 'product',
                'post_content' => ($description ? $description : '')
            );

            $post_id = wp_insert_post($args);
            if ($post_id) {
                // customfields
                update_post_meta($post_id, 'product_ean', $ean);
                update_post_meta($post_id, 'last_product_price_check', '0');
                update_post_meta($post_id, ADCELL_METAKEY_LAST_UPDATE, '0');
                update_post_meta($post_id, 'product_rating', $rating);
                update_post_meta($post_id, 'product_rating_cnt', $rating_cnt);

                $shop_info[] = array(
                    'price' => $price,
                    'currency' => $currency,
                    'portal' => 'adcell',
                    ADCELL_METAKEY_ID => $id,
                    'shop' => at_get_shop_id($shop_id, $shop_name, true),
                    'link' => $url,
                );
                update_field('field_557c01ea87000', $shop_info, $post_id);

                //taxonomie
                if ($taxs) {
                    foreach ($taxs as $key => $value) {
                        if (is_array($value)) {
                            foreach ($value as $k => $v) {
                                if (strpos($v, ',') !== false) {
                                    $value[$k] = '';
                                    $exploded = explode(',', $v);

                                    $value = array_merge($value, $exploded);
                                }
                            }
                        }

                        $value = array_filter($value);
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

            at_write_api_log('adcell', $post_id, 'imported product successfully');

            $output['rmessage']['success'] = 'true';
            $output['rmessage']['post_id'] = $post_id;
        }
    } else {
        $output['rmessage']['success'] = 'false';
        $output['rmessage']['reason'] = __('Dieses Produkt existiert bereits.', 'affiliatetheme-adcell');
        $output['rmessage']['post_id'] = $check;
    }

    sleep(1);

    echo json_encode($output);
    exit();
}
?>
