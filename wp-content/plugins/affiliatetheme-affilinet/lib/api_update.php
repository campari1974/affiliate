<?php
if(get_option('affilinet_user') != "" &&  get_option('affilinet_password') != "") {
    if( !wp_next_scheduled( 'affiliatetheme_affilinet_api_update', $args = array('hash' => ANET_CRON_HASH))) {
        wp_schedule_event(time(), 'hourly', 'affiliatetheme_affilinet_api_update', $args = array('hash' => ANET_CRON_HASH));
    }
} else {
    wp_clear_scheduled_hook('affiliatetheme_affilinet_api_update', $args = array('hash' => ANET_CRON_HASH));
}
add_action('wp_ajax_at_affilinet_update', 'at_affilinet_update');
add_action('wp_ajax_nopriv_at_affilinet_update', 'at_affilinet_update');
add_action('wp_ajax_affilinet_api_update', 'at_affilinet_update');
add_action('wp_ajax_nopriv_affilinet_api_update', 'at_affilinet_update');
add_action('affiliatetheme_affilinet_api_update', 'at_affilinet_update');
function at_affilinet_update($args = array()) {
    global $wpdb;

    $hash = ANET_CRON_HASH;
    $check_hash = ($args ? $args : (isset($_GET['hash']) ? $_GET['hash'] : ''));

    if($check_hash != $hash) {
        wp_clear_scheduled_hook('affiliatetheme_affilinet_api_update', $args = array('hash' => $check_hash));
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
            ANET_METAKEY_LAST_UPDATE, 'product_shops_%_' . ANET_METAKEY_ID, 'product'
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
            ANET_METAKEY_LAST_UPDATE, 'product_shops_%_' . ANET_METAKEY_ID, 'product'
        )
    );

    $products = array_merge($products, $wlProducts);

    //print_r($products); die();

    at_write_api_log('affilinet', 'system', 'start cron');

    if ($products) {
        $api = new Endcore\Api\Affilinet();

        $shoplist = $api->getShopList();
        $shoplist = $shoplist->getData();
        $shoplist = $shoplist['items'];

        foreach ($products as $product) {
            try {
                // ProductShops
                $shops = (get_field('product_shops', $product->ID) ? get_field('product_shops', $product->ID) : array());
                if($shops) {
                    foreach($shops as $key => $val) {
                        if($val['portal'] == 'affilinet') { // check if affilinet product
                            try {
                                // affilinet item
                                $item = $api->lookupProduct($val[ANET_METAKEY_ID], false);
                                $old_ean = get_post_meta($product->ID, 'product_ean', true);
                                $old_price = ($val['price'] ? $val['price'] : '');
                                $old_link = ($val['link'] ? $val['link'] : '');
                                $old_shopid = get_post_meta($product->ID, 'affilinet_shopid', true);
                                $unique_identifier = get_post_meta($val['shop']->ID, 'unique_identifier', true);
                                if($unique_identifier) {
                                    $old_shopid = $unique_identifier;
                                }
                                $old_id = $val[ANET_METAKEY_ID];
                                if(!($item != 'error')) {
                                    // do something with old products
                                    if(get_option('affilinet_check_product_unique') != 'no') {
                                        $replacement_found = false;
                                        try {
                                            if(at_affilinet_validate_ean($old_ean)){
                                                $possible_replacements = $api->searchProductsByEanForUpdate($old_ean);
                                                $products_found = true;
                                            }
                                        }
                                        catch (\Exception $e){
                                            $products_found = false;
                                        }
                                        finally {
                                            if ($products_found) {
                                                foreach ($possible_replacements as $possible_replacement) {
                                                    if ($possible_replacement->getShopId() == $old_shopid) {
                                                        $item = $possible_replacement;
                                                        $replacement_found = true;
                                                    }
                                                }
                                            }
                                            if($replacement_found) {
                                                $price = ($item->getPrice() ? $item->getPrice() : '');
                                                $link = ($item->getUrl() ? $item->getUrl() : '');
                                                $shopid = intval($item->getShopId());
                                                $id = $item->getId();
                                                $ean = $item->getEan();

                                                // update Affilinet Id
                                                if ($id != $old_id && $replacement_found) {
                                                    $shops[$key][ANET_METAKEY_ID] = $id;
                                                }

                                                if ($ean && $ean != $old_ean && get_option('affilinet_update_ean') != 'no' && !$replacement_found) {
                                                    if (ctype_digit($ean)) {
                                                        // only numeric ean
                                                        update_post_meta($product->ID, 'product_ean', $ean);
                                                        at_write_api_log('affilinet', $product->ID, '(' . $key . ') updated ean from ' . $old_ean . ' to ' . $ean);
                                                    }
                                                }

                                                // update price
                                                if ($price != $old_price && get_option('affilinet_update_price') != 'no') {
                                                    $shops[$key]['price'] = $price;
                                                    $shops[$key]['price_old'] = $old_price;
                                                    at_write_api_log('affilinet', $product->ID, '(' . $key . ') updated price from ' . $old_price . ' to ' . $price);
                                                }

                                                // update url
                                                if ($link != $old_link && get_option('affilinet_update_url') != 'no') {
                                                    $shops[$key]['link'] = $link;
                                                    at_write_api_log('affilinet', $product->ID, '(' . $key . ') changed affilinet url');
                                                }

                                                // update shop id
                                                if ($shopid && ($shopid != $old_shopid)) {
                                                    update_post_meta($product->ID, 'affilinet_shopid', $shopid);
                                                }

                                            }


                                            if (get_option('affilinet_check_product_unique') == 'log') {
                                                at_write_api_log('affilinet', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from affilinet feed! please check this product!');
                                            } else if (get_option('affilinet_check_product_unique') == 'draft') {
                                                $args = array(
                                                    'ID' => $product->ID,
                                                    'post_status' => 'draft'
                                                );
                                                wp_update_post($args);

                                                at_write_api_log('affilinet', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from affilinet feed! changed status to draft!');
                                            } else if (get_option('affilinet_check_product_unique') == 'remove') {
                                                if (!$replacement_found) {
                                                    unset($shops[$key]);
                                                    update_post_meta($product->ID, get_field('product_shops', $product->ID), $shops );

                                                    at_write_api_log('affilinet', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from affilinet feed! removed this price from the price comparison!');
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $item = $item->current();
                                    $ean = $item->getEan();
                                    $shopid = intval($item->getShopId());
                                    $replacement_found = true;

                                    $old_shopid = get_post_meta($product->ID, 'affilinet_shopid', true);
                                    $unique_identifier = get_post_meta($val['shop']->ID, 'unique_identifier', true);
                                    if($unique_identifier) {
                                        $old_shopid = $unique_identifier;
                                    }

                                    // check if product is not changed in affilinet database
                                    try {
                                        if (get_option('affilinet_check_product_unique') != 'no') {

                                            $unique_key_old = ANET_METAKEY_ID+$old_shopid;
                                            $unique_key = ANET_METAKEY_ID+$shopid;

                                            if ($unique_key != $unique_key_old) {
                                                $replacement_found = false;

                                                if(at_affilinet_validate_ean($old_ean)) {
                                                    $products_found = true;
                                                    $possible_replacements = $api->searchProductsByEanForUpdate($old_ean);
                                                }

                                                if ($products_found) {
                                                    foreach ($possible_replacements as $possible_replacement) {
                                                        if (intval($possible_replacement->getShopId()) == $old_shopid) {
                                                            $item = $possible_replacement;

                                                            $replacement_found = true;
                                                        }
                                                    }
                                                }
                                                if (get_option('affilinet_check_product_unique') == 'log') {
                                                    at_write_api_log('affilinet', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: unique id changed? Please check this product!');
                                                } else if (get_option('affilinet_check_product_unique') == 'draft') {
                                                    $args = array(
                                                        'ID' => $product->ID,
                                                        'post_status' => 'draft'
                                                    );
                                                    wp_update_post($args);

                                                    at_write_api_log('affilinet', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: unique id changed! Changed status to draft!');
                                                } else if (get_option('affilinet_check_product_unique') == 'remove') {
                                                    if (!$replacement_found) {
                                                        unset($shops[$key]);
                                                        update_post_meta($product->ID, get_field('product_shops', $product->ID), $shops );

                                                        at_write_api_log('affilinet', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from affilinet feed! removed this price from the price comparison!');
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    catch (\Exception $e){
                                        $products_found = false;
                                    }
                                    finally {
                                        $price = ($item->getPrice() ? $item->getPrice() : '');
                                        $link = ($item->getUrl() ? $item->getUrl() : '');
                                        $shopid = intval($item->getShopId());
                                        $id = $item->getId();

                                        // update id
                                        if ($id != $old_id && $replacement_found) {
                                            $shops[$key][ANET_METAKEY_ID] = $id;
                                        }

                                        if ($ean && $ean != $old_ean && get_option('affilinet_update_ean') != 'no' && !$replacement_found) {
                                            if (ctype_digit($ean)) {
                                                // only numeric ean
                                                update_post_meta($product->ID, 'product_ean', $ean);
                                                at_write_api_log('affilinet', $product->ID, '(' . $key . ') updated ean from ' . $old_ean . ' to ' . $ean);
                                            }
                                        }

                                        // update price
                                        if ($price != $old_price && get_option('affilinet_update_price') != 'no') {
                                            $shops[$key]['price'] = $price;
                                            $shops[$key]['price_old'] = $old_price;
                                            at_write_api_log('affilinet', $product->ID, '(' . $key . ') updated price from ' . $old_price . ' to ' . $price);
                                        }

                                        // update url
                                        if ($link != $old_link && get_option('affilinet_update_url') != 'no') {
                                            $shops[$key]['link'] = $link;
                                            at_write_api_log('affilinet', $product->ID, '(' . $key . ') changed affilinet url');
                                        }

                                        // update shop id
                                        if ($shopid && ($shopid != $old_shopid)) {
                                            update_post_meta($product->ID, 'affilinet_shopid', $shopid);
                                        }
                                    }

                                }
                            } catch (\Exception $e) {
                                continue;
                            }
                        }
                    }

                    update_field('product_shops', $shops, $product->ID);
                    update_post_meta($product->ID, ANET_METAKEY_LAST_UPDATE, time());
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    do_action('at_affilinet_update');

    at_write_api_log('affilinet', 'system', 'end cron');

    exit();
}
