<?php
/**
 * Generelle Funktionen vor ACF
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

add_filter('acf/settings/load_json', 'at_product_data_acf_json_load_point');
function at_product_data_acf_json_load_point( $paths ) {
    unset($paths[0]);
    $uploaddir = wp_upload_dir();
    $path = $uploaddir['basedir'] . '/acf-json';
    $paths[] = $path;
    return $paths;
}

add_filter('acf/settings/save_json', 'at_product_data_acf_json_save_point');
function at_product_data_acf_json_save_point( $path ) {
    $uploaddir = wp_upload_dir();
    $path = $uploaddir['basedir'] . '/acf-json';
    return $path;
}