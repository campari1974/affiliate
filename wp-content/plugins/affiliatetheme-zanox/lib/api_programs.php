<?php
add_action('wp_ajax_zanox_api_programs', 'at_zanox_programs');
add_action('wp_ajax_at_zanox_programs', 'at_zanox_programs');
function at_zanox_programs() {
    $spaceId = isset($_GET['spaceid']) ? (int)$_GET['spaceid'] : null;

    $api = new \EcZanox\Zanox();

    $applicatiions = $api->getProgrammApplications($spaceId);

    $result = array();
    $result['items'] = array();

    foreach ($applicatiions as $applicatiion) {
        $program = $applicatiion->getProgram();
        if ($program->isActive()) {
            $result['items'][] = array(
                'id' => $program->getId(),
                'name' => $program->getName()
            );
        }
    }
    $result['extras'] = $applicatiions->getExtras();

    echo json_encode($result);

    exit();
}