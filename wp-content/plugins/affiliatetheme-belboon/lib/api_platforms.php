<?php
add_action('wp_ajax_belboon_api_platforms', 'at_belboon_platforms');
add_action('wp_ajax_at_belboon_platforms', 'at_belboon_platforms');
function at_belboon_platforms() {
    $result = array();
    $api = new Endcore\Belboon\Belboon();

    $platforms = $api->getPlatforms();

    foreach ($platforms as $platform) {
        $result['items'][] = array(
            'id' => $platform->getPlatformId(),
            'md5' => md5($platform->getName()),
            'name' => $platform->getName(),
        );
    }

    echo json_encode($result);

    exit();
}