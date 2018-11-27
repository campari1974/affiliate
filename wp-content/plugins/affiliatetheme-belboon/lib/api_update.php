<?php
if(get_option('belboon_user') != "" &&  get_option('belboon_password') != "") {
    if( !wp_next_scheduled( 'affiliatetheme_belboon_api_update', $args = array('hash' => BBOON_CRON_HASH))) {
        wp_schedule_event(time(), 'hourly', 'affiliatetheme_belboon_api_update', $args = array('hash' => BBOON_CRON_HASH));
    }
} else {
    wp_clear_scheduled_hook('affiliatetheme_belboon_api_update', $args = array('hash' => BBOON_CRON_HASH));
}

add_action('wp_ajax_at_belboon_update', 'at_belboon_update');
add_action('wp_ajax_nopriv_at_belboon_update', 'at_belboon_update');
add_action('wp_ajax_belboon_api_update', 'at_belboon_update');
add_action('wp_ajax_nopriv_belboon_api_update', 'at_belboon_update');
add_action('affiliatetheme_belboon_api_update', 'at_belboon_update');
function at_belboon_update($args = array()) {
    $hash = BBOON_CRON_HASH;
    $check_hash = ($args ? $args : (isset($_GET['hash']) ? $_GET['hash'] : ''));

    if($check_hash != $hash) {
        wp_clear_scheduled_hook('affiliatetheme_belboon_api_update', $args = array('hash' => $check_hash));
        die('Security check failed.');
    }

    global $wpdb;

    $products = $wpdb->get_results(
        $wpdb->prepare(
            "
                SELECT DISTINCT p.ID FROM {$wpdb->posts} p
                LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                LEFT JOIN {$wpdb->postmeta} a ON p.ID = a.post_id
                WHERE a.meta_key = '%s' AND (a.meta_value+3600 < UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) OR a.meta_id IS NULL) AND pm.meta_key LIKE '%s' AND p.post_type = '%s' AND p.post_status != 'trash'
                LIMIT 0,999
            ",
            BBOON_METAKEY_LAST_UPDATE, 'product_shops_%_' . BBOON_METAKEY_ID, 'product'
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
            BBOON_METAKEY_LAST_UPDATE, 'product_shops_%_' . BBOON_METAKEY_ID, 'product'
        )
    );

    $products = array_merge($products, $wlProducts);

    at_write_api_log('belboon', 'system', 'start cron');

    if ($products) {
        $api = new Endcore\Belboon\Belboon();

        foreach ($products as $product) {
            try {
                $shops = (get_field('product_shops', $product->ID) ? get_field('product_shops', $product->ID) : array());

                if($shops) {
                    foreach($shops as $key => $val) {
                        if($val['portal'] == 'belboon') { // check if belboon product
                            try {
                                $item = $api->getProductById($val[BBOON_METAKEY_ID]);
                                if ($item) {
                                    $item = $item->current();

                                    $product_shops = get_field('product_shops', $product->post_id);
                                    $product_index = getRepeaterRowID($product_shops, BBOON_METAKEY_ID, $product->product_id);

                                    if(false !== $product_index) {
                                        $old_ean = get_post_meta($product->ID, 'product_ean', true);
                                        $ean = $item->getEan();
                                        $old_price = $item->getOldprice();
                                        $price_tmp = $val['price'];
                                        $price = $item->getPrice();
                                        $old_link = $val['link'];
                                        $link = $item->getUrl();

                                        // update ean
                                        if($ean && $ean != $old_ean && get_option('belboon_update_ean') != 'no') {
                                            update_post_meta($product->ID, 'product_ean', $ean);
                                            at_write_api_log('belboon', $product->ID, '(' . $key . ') updated ean from ' . $old_ean . ' to ' . $ean);
                                        }

                                        // update price
                                        if($price != $price_tmp && get_option('belboon_update_price') != 'no') {
                                            $shops[$key]['price'] = $price;
                                            $shops[$key]['price_old'] = $old_price;
                                            at_write_api_log('belboon', $product->ID, 'updated price from ' . $price_tmp . ' to ' . $price);
                                        }

                                        // update url
                                        if ($link != $old_link && get_option('belboon_update_url') != 'no') {
                                            $shops[$key]['link'] = $link;
                                            at_write_api_log('belboon', $product->ID, '(' . $key . ') changed belboon url');
                                        }


                                        update_post_meta($product->post_id, 'last_product_price_check', time());
                                    }
                                }

                            } catch (\Exception $e) {
                                $product_found = true;
                                if($e->getMessage() == "No Products found."){
                                    $product_found = false;
                                }
                            } finally {
                                if(!$product_found) {
                                    if (get_option('belboon_check_product_unique') != 'no') {
                                        if (get_option('belboon_check_product_unique') == 'log') {
                                            at_write_api_log('belboon', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from belboon feed! please check this product!');
                                        } else if (get_option('belboon_check_product_unique') == 'draft') {
                                            $args = array(
                                                'ID' => $product->ID,
                                                'post_status' => 'draft'
                                            );
                                            wp_update_post($args);

                                            at_write_api_log('belboon', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from belboon feed! changed status to draft!');
                                        } else if (get_option('belboon_check_product_unique') == 'remove') {
                                            unset($shops[$key]);
                                            update_post_meta($product->ID, get_field('product_shops', $product->ID), $shops);

                                            at_write_api_log('belboon', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from belboon feed! removed price from price comparison!');
                                        }
                                    }
                                }
                            }
                        }

                        update_field('product_shops', $shops, $product->ID);
                        update_post_meta($product->ID, BBOON_METAKEY_LAST_UPDATE, time());
                    }
                }

            } catch (\Exception $e) {
                continue;
            }
        }
    }

    do_action('at_belboon_update');

    at_write_api_log('belboon', 'system', 'end cron');

    exit();
}
