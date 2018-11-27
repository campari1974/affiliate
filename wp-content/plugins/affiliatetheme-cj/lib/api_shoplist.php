<?php
add_action('wp_ajax_at_cj_shoplist', 'at_cj_shoplist');
add_action('wp_ajax_cj_api_shoplist', 'at_cj_shoplist');
function at_cj_shoplist() {
	$result = array();
	$api = new Endcore\Api\CommissionJunction();

	/** @var \Endcore\Result\ShopList $shops */
	$shops = $api->getShopList();
	//var_dump($shops);
    $current_shop = get_option('cj_shop');

	/** @var \Endcore\Shop $shop */
	if($shops->{'advertiser-id'}!= null)
    {
        //var_dump($shops);
        $current = 'false';
        $current_shop = 0;
        if ($current_shop == $shops->{'advertiser-id'})
            $current = 'true';

        $result['items'][] = array(
            'id' => $shops->{'advertiser-id'},
            'name' => $shops->{'advertiser-name'},
        );

    }
    else foreach ($shops as $shop) {
        $current = 'false';
        $current_shop = 0;
        if ($current_shop == $shop->{'advertiser-id'})
            $current = 'true';

        if ($shop->{'advertiser-id'} != null) {
            $result['items'][] = array(
                'id' => $shop->{'advertiser-id'},
                'name' => $shop->{'advertiser-name'},
            );
        }
    }
    echo json_encode($result);

	exit();
}