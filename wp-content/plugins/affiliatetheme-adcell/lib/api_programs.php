<?php
use EcAdcell\Program;
use EcAdcell\ProgramList;
use EcAdcell\Promotion;
use EcAdcell\PromotionList;

add_action('wp_ajax_at_adcell_programs', 'at_adcell_programs');
add_action('wp_ajax_adcell_api_programs', 'at_adcell_programs');
function at_adcell_programs() {
    $api = new EcAdcell\Adcell();

    /** @var ProgramList $programs */
    $programs = $api->getProgramAccepted();

    $result = array();
    $result['items'] = array();

    /** @var Program $program */
    foreach ($programs as $program) {
        $result['items'][] = array(
            'id' => $program->getId(),
            'name' => $program->getName()
        );
    }

    echo json_encode($result);

    exit();
}

function at_adcell_get_program_by_id($program_id) {
    $api = new EcAdcell\Adcell();

    /** @var ProgramList $programs */
    $programs = $api->getProgramAccepted();

    $items = array();

    /** @var Program $program */
    foreach ($programs as $program) {
        $items[$program->getId()] = $program->getName();
    }

    if(isset($items[$program_id])) {
        return $items[$program_id];
    }

    return false;
}