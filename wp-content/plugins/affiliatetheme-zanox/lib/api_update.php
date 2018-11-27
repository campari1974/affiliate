<?php
if(get_option('zanox_connect_id') != "" &&  get_option('zanox_secret_key') != "") {
    if( !wp_next_scheduled( 'affiliatetheme_zanox_api_update', $args = array('hash' => ZANOX_CRON_HASH))) {
        wp_schedule_event(time(), 'hourly', 'affiliatetheme_zanox_api_update', $args = array('hash' => ZANOX_CRON_HASH));
    }
} else {
    wp_clear_scheduled_hook('affiliatetheme_zanox_api_update', $args = array('hash' => ZANOX_CRON_HASH));
}
add_action('wp_ajax_at_zanox_update', 'at_zanox_update');
add_action('wp_ajax_nopriv_at_zanox_update', 'at_zanox_update');
add_action('wp_ajax_zanox_api_update', 'at_zanox_update');
add_action('wp_ajax_nopriv_zanox_api_update', 'at_zanox_update');
add_action('affiliatetheme_zanox_api_update', 'at_zanox_update');
function at_zanox_update($args = array()) {
    global $wpdb;

    $hash = ZANOX_CRON_HASH;
    $check_hash = ($args ? $args : (isset($_GET['hash']) ? $_GET['hash'] : ''));

    if($check_hash != $hash) {
        wp_clear_scheduled_hook('affiliatetheme_zanox_api_update', $args = array('hash' => $check_hash));
        die('Security check failed.');
    }

    $api = new \EcZanox\Zanox();

    $products = $wpdb->get_results(
        $wpdb->prepare(
            "
                SELECT DISTINCT p.ID FROM {$wpdb->posts} p
                LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                LEFT JOIN {$wpdb->postmeta} a ON p.ID = a.post_id
                WHERE a.meta_key = '%s' AND (a.meta_value+3600 < UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) OR a.meta_id IS NULL) AND pm.meta_key LIKE '%s' AND p.post_type = '%s' AND p.post_status != 'trash'
                LIMIT 0,999
            ",
            ZANOX_METAKEY_LAST_UPDATE, 'product_shops_%_' . ZANOX_METAKEY_ID, 'product'
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
            ZANOX_METAKEY_LAST_UPDATE, 'product_shops_%_' . ZANOX_METAKEY_ID, 'product'
        )
    );

    $products = array_merge($products, $wlProducts);
	
    at_write_api_log('zanox', 'system', 'start cron');

    if ($products) {
        foreach ($products as $product) {
            // ProductShops			
            $shops = (get_field('product_shops', $product->ID) ? get_field('product_shops', $product->ID) : array());
            if ($shops) {
                foreach ($shops as $key => $val) {
                    if ($val['portal'] == 'zanox') { // check if zanox product
                        if (strlen($val[ZANOX_METAKEY_ID]) != 32) {
                            continue;
                        }
						
                        $items = $api->getProduct($val[ZANOX_METAKEY_ID]);
                        if ($items) {
                            foreach ($items as $item) {
                                $old_price = ($val['price'] ? $val['price'] : '');
                                $price = $item->getPrice();
                                $old_link = ($val['link'] ? $val['link'] : '');
                                $link = $item->getUrl();

                                break;
                            }

                            try {
                                // update price
                                if ($price != $old_price && get_option('zanox_update_price') != 'no') {
                                    $shops[$key]['price'] = $price;
                                    $shops[$key]['price_old'] = $old_price;
                                    at_write_api_log('zanox', $product->ID, '(' . $key . ') updated price from ' . $old_price . ' to ' . $price);
                                }

                                // update url
                                if ($link != $old_link && get_option('zanox_update_url') != 'no') {
                                    $shops[$key]['link'] = $link;
                                    at_write_api_log('zanox', $product->ID, '(' . $key . ') changed zanox url');
                                }
                            } catch (\Exception $e) {
                                at_write_api_log('zanox', $product->post_id, 'error fetching product');
                            }
                        }else{
                            if(get_option('zanox_check_product_unique') != 'no') {
                                if(get_option('zanox_check_product_unique') == 'log') {
                                    at_write_api_log('zanox', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from zanox feed! please check this product!');
                                } else if(get_option('zanox_check_product_unique') == 'draft') {
                                    $args = array(
                                        'ID' => $product->ID,
                                        'post_status' => 'draft'
                                    );
                                    wp_update_post($args);

                                    at_write_api_log('zanox', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from zanox feed! changed status to draft!');
                                }
                                else if(get_option('zanox_check_product_unique') == 'remove') {
                                    unset($shops[$key]);
                                    update_post_meta($product->ID, get_field('product_shops', $product->ID), $shops );
                                    at_write_api_log('zanox', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from zanox feed! removed price from price comparison!');
                                }
                            }
                        }
                    }
                }

                update_field('product_shops', $shops, $product->ID);
                update_post_meta($product->ID, ZANOX_METAKEY_LAST_UPDATE, time());
            }
        }
    }

    do_action('at_zanox_update');

    at_write_api_log('zanox', 'system', 'end cron');

    exit();
}