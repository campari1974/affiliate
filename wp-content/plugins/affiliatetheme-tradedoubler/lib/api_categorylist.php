<?php
add_action('wp_ajax_at_tradedoubler_categorylist', 'at_tradedoubler_categorylist');
add_action('wp_ajax_tradedoubler_api_categorylist', 'at_tradedoubler_categorylist');
function at_tradedoubler_categorylist() {
	$result = array();
	$api = new Endcore\Api\Tradedoubler();
    $categories = $api->getCategoryList();
    foreach($categories as $key=>$value){
        $result['items'][]=array(
            'id' => $key,
            'name' => $value,
        );
    }
    usort($result['items'], function($a, $b){
        return strcasecmp($a,$b);
    });
    echo json_encode($result);
	exit();
}