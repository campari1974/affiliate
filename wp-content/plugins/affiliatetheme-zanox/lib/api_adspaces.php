<?php
add_action('wp_ajax_zanox_api_adspaces', 'at_zanox_adspaces');
add_action('wp_ajax_at_zanox_adspaces', 'at_zanox_adspaces');
function at_zanox_adspaces() {
    $api = new \EcZanox\Zanox();

    $adSpaces = $api->getAdspaces();

    $result = array();
    $result['items'] = array();

    foreach ($adSpaces as $adspace) {
        $result['items'][] = array(
            'id' => $adspace->getId(),
            'name' => $adspace->getName(),
        );
    }

    $result['extras'] = $adSpaces->getExtras();

    echo json_encode($result);

    exit();
}