<?php
if(get_option('cj_user') != "" &&  get_option('cj_password') != "") {
    if( !wp_next_scheduled( 'affiliatetheme_cj_api_update', $args = array('hash' => CJ_CRON_HASH))) {
        wp_schedule_event(time(), 'hourly', 'affiliatetheme_cj_api_update', $args = array('hash' => CJ_CRON_HASH));
    }
} else {
    wp_clear_scheduled_hook('affiliatetheme_cj_api_update', $args = array('hash' => CJ_CRON_HASH));
}
add_action('wp_ajax_at_cj_update', 'at_cj_update');
add_action('wp_ajax_nopriv_at_cj_update', 'at_cj_update');
add_action('wp_ajax_cj_api_update', 'at_cj_update');
add_action('wp_ajax_nopriv_cj_api_update', 'at_cj_update');
add_action('affiliatetheme_cj_api_update', 'at_cj_update');
function at_cj_update($args = array()) {
    global $wpdb;

    $hash = CJ_CRON_HASH;
    $check_hash = ($args ? $args : (isset($_GET['hash']) ? $_GET['hash'] : ''));

    if($check_hash != $hash) {
        wp_clear_scheduled_hook('affiliatetheme_cj_api_update', $args = array('hash' => $check_hash));
        die('Security check failed.');
    }

    $products = $wpdb->get_results(
        $wpdb->prepare(
            "
                SELECT DISTINCT p.ID FROM {$wpdb->posts} p
                LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                LEFT JOIN {$wpdb->postmeta} a ON p.ID = a.post_id
                WHERE a.meta_key = '%s' AND (a.meta_value+3600 < UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) OR a.meta_id IS NULL) AND pm.meta_key LIKE '%s' AND p.post_type = '%s' AND p.post_status != 'trash'
                LIMIT 0,999
            ",
            CJ_METAKEY_LAST_UPDATE, 'product_shops_%_' . CJ_METAKEY_ID, 'product'
        )
    );

    $wlProducts = $wpdb->get_results(
        $wpdb->prepare(
            "
                SELECT DISTINCT p.ID FROM {$wpdb->posts} p
                LEFT JOIN {$wpdb->postmeta} pm1 ON (p.ID = pm1.post_id AND pm1.meta_key = '%s')
                INNER JOIN {$wpdb->postmeta} pm2 ON (p.ID = pm2.post_id AND pm2.meta_key LIKE '%s')
                WHERE pm1.meta_key IS NULL AND pm2.meta_value != '' AND p.post_type = '%s' AND p.post_status != 'trash'
                LIMIT 0,999
            ",
            CJ_METAKEY_LAST_UPDATE, 'product_shops_%_' . CJ_METAKEY_ID, 'product'
        )
    );

    $products = array_merge($products, $wlProducts);

    //print_r($products); die();

    at_write_api_log('cj', 'system', 'start cron');

    if ($products) {
        $api = new Endcore\Api\CommissionJunction();

        foreach ($products as $product) {
            try {
                // ProductShops
                $shops = (get_field('product_shops', $product->ID) ? get_field('product_shops', $product->ID) : array());
                if($shops) {
                    foreach($shops as $key => $val) {
                        if($val['portal'] == 'cj') { // check if cj product
                            try {
                                // cj item
                                $item = $api->lookupProduct(get_post_meta($product->ID, 'cj_shopid',true),$val[CJ_METAKEY_ID], false);

                                if ($item->products->{'@attributes'}->{'total-matched'} != '0') {
                                    $item = $item->products->product;

                                    $old_ean = get_post_meta($product->ID, 'product_ean', true);
                                    $ean = $item->sku;
                                    $old_price = ($val['price'] ? $val['price'] : '');
                                    $price = ($item->price != '0')?$item->price: '';
                                    $old_link = ($val['link'] ? $val['link'] : '');
                                    $link = ($item->{'buy-url'} ? $item->{'buy-url'} : '');
                                    $old_currency = ($val['currency']? $val['currency']: '');
                                    $currency = ((strtolower($item->currency) == 'eur')? 'euro':strtolower($item->currency));
                                    $shopid = $item->{'advertiser-id'};
                                    $old_shopid = get_post_meta($product->ID, 'cj_shopid', true);

                                    // update ean
                                    if($ean && $ean != $old_ean && get_option('cj_update_ean') != 'no') {
                                        if(ctype_digit($ean)&&strlen($ean)== 13) {
                                            // only numeric ean and only with 13 digits
                                            update_post_meta($product->ID, 'product_ean', $ean);
                                            at_write_api_log('cj', $product->ID, '(' . $key . ') updated ean from ' . $old_ean . ' to ' . $ean);
                                        }
                                    }

                                    // update price
                                    if (($price != $old_price || $currency != $old_currency)&& get_option('cj_update_price') != 'no' && $price != '') {
                                        $shops[$key]['price'] = $price;
                                        $shops[$key]['price_old'] = $old_price;
                                        $shops[$key]['currency'] = $currency;
                                        at_write_api_log('cj', $product->ID, '(' . $key . ') updated price from ' . $old_price . ' to ' . $price);
                                    }

                                    // update url
                                    if ($link != $old_link && get_option('cj_update_url') != 'no') {
                                        $shops[$key]['link'] = $link;
                                        at_write_api_log('cj', $product->ID, '(' . $key . ') changed cj url');
                                    }

                                    // update shop id
                                    if ($shopid && ($shopid != $old_shopid)) {
                                        update_post_meta($product->ID, 'cj_shopid', $shopid);
                                    }

                                    // check if product is not changed in cj database
                                    if(get_option('cj_check_product_unique') != 'no') {
                                        $unique_key_old = CJ_METAKEY_ID + $old_ean;
                                        $unique_key = CJ_METAKEY_ID + $ean;

                                        // get shopid instead of ean, if available
                                        if($old_shopid) {
                                            $unique_key_old = CJ_METAKEY_ID + $old_shopid;
                                            $unique_key = CJ_METAKEY_ID + $shopid;
                                        }

                                        if($unique_key != $unique_key_old) {
                                            if(get_option('cj_check_product_unique') == 'log') {
                                                at_write_api_log('cj', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: unique id changed? please check this product!');
                                            } else if(get_option('cj_check_product_unique') == 'draft') {
                                                $args = array(
                                                    'ID' => $product->ID,
                                                    'post_status' => 'draft'
                                                );
                                                wp_update_post($args);
                                                
                                                at_write_api_log('cj', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: unique id changed! changed status to draft!');
                                            }
                                        }
                                    }
                                } else {
                                    // do something with old products
                                    if(get_option('cj_check_product_unique') != 'no') {
                                        if(get_option('cj_check_product_unique') == 'log') {
                                            at_write_api_log('cj', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from cj feed! please check this product!');
                                        } else if(get_option('cj_check_product_unique') == 'draft') {
                                            $args = array(
                                                'ID' => $product->ID,
                                                'post_status' => 'draft'
                                            );
                                            wp_update_post($args);

                                            at_write_api_log('cj', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from cj feed! changed status to draft!');
                                        } else if(get_option('cj_check_product_unique') == 'remove') {
                                            unset($shops[$key]);
                                            update_post_meta($product->ID, get_field('product_shops', $product->ID), $shops );

                                            at_write_api_log('cj', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from cj feed! removed price from price comparison!');
                                        }
                                    }
                                }
                            } catch (\Exception $e) {
                                continue;
                            }
                        }
                    }

                    update_field('product_shops', $shops, $product->ID);
                    update_post_meta($product->ID, CJ_METAKEY_LAST_UPDATE, time());
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    do_action('at_cj_update');

    at_write_api_log('cj', 'system', 'end cron');

    exit();
}
