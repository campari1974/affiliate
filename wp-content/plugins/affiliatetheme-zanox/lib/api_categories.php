<?php
add_action('wp_ajax_zanox_api_categories', 'at_zanox_categories');
add_action('wp_ajax_at_zanox_categories', 'at_zanox_categories');
function at_zanox_categories() {
    $programId = isset($_GET['progid']) ? (int)$_GET['progid'] : null;

    if ($programId === null) {
        throw new \Exception('Missing argument: progid.');
    }

    $api = new \EcZanox\Zanox();

    $categories = $api->getMerchantCategories($programId);

    $result = array();
    $result['items'] = array();

    foreach ($categories as $category) {
        $result['items'][] = $category->getOriginalData() ?: null;
    }

    $result['extras'] = $categories->getExtras();

    echo json_encode($result);

    exit();
}