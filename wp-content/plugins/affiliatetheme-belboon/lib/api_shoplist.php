<?php
add_action('wp_ajax_belboon_api_shoplist', 'at_belboon_shoplist');
add_action('wp_ajax_at_belboon_shoplist', 'at_belboon_shoplist');
function at_belboon_shoplist() {
    $result = array();
    $api = new Endcore\Belboon\Belboon();
    $platform = (isset($_GET['platform']) ? $_GET['platform'] : '');

    $feeds = $api->getFeeds($platform);

    foreach ($feeds as $feed) {
        $result['items'][] = array(
            'id' => $feed->getId(),
            'md5' => md5($feed->getName()),
            'name' => $feed->getName(),
        );
    }

    echo json_encode($result);

    exit();
}