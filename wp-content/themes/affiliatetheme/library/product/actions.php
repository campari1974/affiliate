<?php
/**
 * WP Actions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	product
 */

/**
 * at_redirect_fake_product
 *
 */
add_action('template_redirect', 'at_redirect_fake_product');
function at_redirect_fake_product() {
    global $post;

    if (!is_singular('product'))
        return;

    if(at_is_fake_product($post->ID)) {
        $url = get_field('product_fake_redirect', $post->ID);

        if($url) {
            if(is_user_logged_in()) {
                add_action('at_notices', 'at_redirect_fake_product_notice');
            } else {
                wp_redirect($url, 301);
                exit;
            }
        }
    }
}

/**
 * at_delete_api_log
 *
 */
add_action('wp_ajax_at_api_clear_log', 'at_delete_api_log');
function at_delete_api_log() {
    $check_hash = (isset($_GET['hash']) ? $_GET['hash'] : '');
    $type = (isset($_GET['type']) ? $_GET['type'] : '');

    switch($type) {
        case 'amazon':
            $hash = AWS_CRON_HASH;
            break;

        case 'affilinet':
            $hash = ANET_CRON_HASH;
            break;

        case 'zanox':
            $hash = ZANOX_CRON_HASH;
            break;

        case 'belboon':
            $hash = BBOON_CRON_HASH;
            break;

        case 'ebay':
            $hash = EBAY_CRON_HASH;
            break;

        case 'adcell':
            $hash = ADCELL_CRON_HASH;
            break;

        case 'cj':
            $hash = CJ_CRON_HASH;
            break;
			
		case 'rakuten':
            $hash = RAKUTEN_CRON_HASH;
            break;

        case 'tradetracker':
            $hash = TRADETRACKER_CRON_HASH;
            break;

        case 'tradedoubler':
            $hash = TRADEDOUBLER_CRON_HASH;
            break;

        case 'daisycon':
            $hash = DAISYCON_CRON_HASH;
            break;

        case 'financeads':
            $hash = FINANCEADS_CRON_HASH;
            break;

        default:
            $hash = '';
            break;
    }
    
    if($check_hash != $hash || !isset($_GET['type']))
        die('Security check failed.');

    update_option('at_'.$type.'_api_log', array());

    $status = array('status' => 'success');

    echo json_encode($status);

    exit();
}

/**
 * at_schema_single_product
 *
 */
add_action( 'wp_footer', 'at_schema_single_product', 133337 );
function at_schema_single_product (){
    if ( is_single() && 'product' == get_post_type() ) {
        if(get_field('product_schema_org_data', 'options')) {
            return;
        }

        global $post;
        $title = get_the_title();
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(),'large', true);
        $rating = get_field('product_rating');
        $shop_info = get_field('product_shops');

        if(is_array($shop_info)) {
            echo PHP_EOL . '<script type="application/ld+json">' . PHP_EOL;
            echo '{' . PHP_EOL;
            echo '"@context": "http://schema.org/",' . PHP_EOL;
            echo '"@type": "Product",' . PHP_EOL;
            echo '"name": "' . $title . '",' . PHP_EOL;
            echo($image ? '"image": "' . $image[0] . '",' . PHP_EOL : '');

            if ($rating && get_product_rating($post->ID)) {
                $rating_cnt = (get_field('product_rating_cnt') ? get_field('product_rating_cnt') : '1');
                echo '"aggregateRating": {' . PHP_EOL;
                echo '"@type": "AggregateRating",' . PHP_EOL;
                echo '"ratingValue": "' . $rating . '",' . PHP_EOL;
                echo($rating_cnt ? '"reviewCount": "' . $rating_cnt . '"' . PHP_EOL : '');
                echo '},' . PHP_EOL;
            }
            if ($shop_info[0]['currency'] == 'euro') {
                $shop_info[0]['currency'] = 'EUR';
            }

            echo '"offers": {' . PHP_EOL;
            echo '"@type": "Offer",' . PHP_EOL;
            echo(isset($shop_info[0]['currency']) ? '"priceCurrency": "' . $shop_info[0]['currency'] . '",' . PHP_EOL : '');
            echo(isset($shop_info[0]['price']) ? '"price": "' . $shop_info[0]['price'] . '",' . PHP_EOL : '');
            echo '"availability": "http://schema.org/InStock"' . PHP_EOL;
            echo '}' . PHP_EOL;
            echo '}' . PHP_EOL;
            echo '</script>' . PHP_EOL;
        }
    }
}