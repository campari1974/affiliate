<?php
use EcAdcell\Program;
use EcAdcell\ProgramList;
use EcAdcell\Promotion;
use EcAdcell\PromotionList;

add_action('wp_ajax_at_adcell_promo', 'at_adcell_promo');
add_action('wp_ajax_adcell_api_promo', 'at_adcell_promo');
function at_adcell_promo() {
    $programId = (isset($_GET['program']) ? $_GET['program'] : '');

    if (!$programId || $programId == 'undefined')
        die('Error');

    $api = new EcAdcell\Adcell();

    $promotionList = $api->getPromotionTypeCsv(array(
        'programIds' => array($programId)
    ));

    $promotionIds = array();

    /** @var Promotion $promo */
    foreach ($promotionList as $promo) {
        $promotionIds[] = $promo->getId();
    }
    if ($promotionList) {
        $result = array('status' => '200', 'items' => array());
        foreach ($promotionList as $promo) {
            $last_update_obj = ($promo->getChangeTime() ? $promo->getChangeTime() : '');
            if (is_a($last_update_obj, 'DateTime')) {
                $last_update = $last_update_obj->format('d.m.Y H:i:s');
            } else {
                $last_update = 'k.A.';
            }
            $result['items'][] = array(
                'id' => $promo->getId(),
                'last_update' => $last_update
            );
        }
        echo json_encode($result);
    }

    exit();
}