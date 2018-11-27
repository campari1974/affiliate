<?php

use EcAdcell\Search;

if(get_option('adcell_user') != "" &&  get_option('adcell_password') != "") {
    if( !wp_next_scheduled( 'affiliatetheme_adcell_api_update', $args = array('hash' => ADCELL_CRON_HASH))) {
        wp_schedule_event(time(), 'hourly', 'affiliatetheme_adcell_api_update', $args = array('hash' => ADCELL_CRON_HASH));
    }
} else {
    wp_clear_scheduled_hook('affiliatetheme_adcell_api_update', $args = array('hash' => ADCELL_CRON_HASH));
}

add_action('wp_ajax_at_adcell_update', 'at_adcell_update');
add_action('wp_ajax_nopriv_at_adcell_update', 'at_adcell_update');
add_action('wp_ajax_adcell_api_update', 'at_adcell_update');
add_action('wp_ajax_nopriv_adcell_api_update', 'at_adcell_update');
add_action('affiliatetheme_adcell_api_update', 'at_adcell_update');

function at_adcell_update($args = array()) {
    $hash = ADCELL_CRON_HASH;
    $check_hash = ($args ? $args : (isset($_GET['hash']) ? $_GET['hash'] : ''));

    if($check_hash != $hash) {
        wp_clear_scheduled_hook('affiliatetheme_adcell_api_update', $args = array('hash' => $check_hash));

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
            ADCELL_METAKEY_LAST_UPDATE, 'product_shops_%_' . ADCELL_METAKEY_ID, 'product'
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
            ADCELL_METAKEY_LAST_UPDATE, 'product_shops_%_' . ADCELL_METAKEY_ID, 'product'
        )
    );

    $products = array_merge($products, $wlProducts);
    //print_r($products); die();

    at_write_api_log('adcell', 'system', 'start cron');

    if ($products) {

        foreach ($products as $product) {

            try {
                // ProductShops
                $shops = (get_field('product_shops', $product->ID) ? get_field('product_shops', $product->ID) : array());

                if($shops) {


                    foreach($shops as $key => $val) {
                        if($val['portal'] == 'adcell') {// check if adcell product
                            try {
                                $lookup = new Search();
                                $item = $lookup->getItemByUniqueId($val[ADCELL_METAKEY_ID]);

                                if ($item->getUrl() != null) {
                                    $old_ean = get_post_meta($product->ID, 'product_ean', true);
                                    $ean = $item->getEan();
                                    $old_price = ($val['price'] ? $val['price'] : '');
                                    $price = ($item->getPrice() ? $item->getPrice() : '');
                                    $old_link = ($val['link'] ? $val['link'] : '');
                                    $link = ($item->getUrl() ? $item->getUrl() : '');

                                    // update ean
                                    if ($ean && $ean != $old_ean && get_option('adcell_update_ean') != 'no') {
                                        update_post_meta($product->ID, 'product_ean', $ean);
                                        at_write_api_log('adcell', $product->ID, '(' . $key . ') updated ean from ' . $old_ean . ' to ' . $ean);
                                    }

                                    // update price
                                    if ($price != $old_price && get_option('adcell_update_price') != 'no') {
                                        $shops[$key]['price'] = $price;
                                        $shops[$key]['price_old'] = $old_price;
                                        at_write_api_log('adcell', $product->ID, '(' . $key . ') updated price from ' . $old_price . ' to ' . $price);
                                    }

                                    // update url
                                    if ($link != $old_link && get_option('adcell_update_url') != 'no') {
                                        $shops[$key]['link'] = $link;
                                        at_write_api_log('adcell', $product->ID, '(' . $key . ') changed adcell url');
                                    }
                                } else {
                                    if (get_option('adcell_check_product_unique') == 'log') {
                                        at_write_api_log('adcell', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from adcell feed! please check this product!');
                                    } else if (get_option('adcell_check_product_unique') == 'draft') {
                                        $args = array(
                                            'ID' => $product->ID,
                                            'post_status' => 'draft'
                                        );
                                        wp_update_post($args);

                                        at_write_api_log('adcell', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from adcell feed! changed status to draft!');
                                    } else if (get_option('adcell_check_product_unique') == 'remove') {
                                            unset($shops[$key]);
                                            update_post_meta($product->ID, get_field('product_shops', $product->ID), $shops );

                                            at_write_api_log('adcell', $product->ID, '(' . $key . ') <span style="color:red;">Warning</span>: product removed from adcell feed! removed this price from the price comparison!');
                                        }
                                    }
                            } catch (\Exception $e) {
                                update_post_meta($product->ID, ADCELL_METAKEY_LAST_UPDATE, time());
                                if (505 === $e->getCode()) {
                                    at_write_api_log('adcell', $product->ID, 'product completed');
                                } else {
                                    at_write_api_log('adcell', $product->ID, 'product not available');
                                }
                                continue;
                            }
                        }

                    }
                    update_field('product_shops', $shops, $product->ID);
                    update_post_meta($product->ID, ADCELL_METAKEY_LAST_UPDATE, time());
                }

            } catch (\Exception $e) {
                    continue;
            }
        }
    }

    at_write_api_log('adcell', 'system', 'end cron');

    exit();
}
