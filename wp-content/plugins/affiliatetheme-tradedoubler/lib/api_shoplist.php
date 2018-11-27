<?php
add_action('wp_ajax_at_tradedoubler_shoplist', 'at_tradedoubler_shoplist');
add_action('wp_ajax_tradedoubler_api_shoplist', 'at_tradedoubler_shoplist');
function at_tradedoubler_shoplist() {
	$result = array();
	$api = new Endcore\Api\Tradedoubler();

	$feeds = $api->getShopList();
	if(empty($feeds['feeds'])){
	    echo new \Endcore\Api\JsonResponse("No Feeds for this token");
	    die();
    }
    $current_shop = get_option('tradedoubler_shop');

    foreach ($feeds['feeds'] as $feed) {
        $current = 'false';
        $current_shop = 0;
        if ($current_shop == $feed['feedId'])
            $current = 'true';

        $result['items'][] = array(
            'id' => $feed['feedId'],
            'name' => $feed['name'],
        );
    }
    echo json_encode($result);

	exit();
}