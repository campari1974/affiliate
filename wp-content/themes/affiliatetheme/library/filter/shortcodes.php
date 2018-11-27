<?php
add_shortcode('at_filter', 'endcore_at_filter_shortcode');
/*
 * Hidden Shortcode
 */
function endcore_at_filter_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(		
		'id' 			=> '',
		'in_sidebar'	=> 'false',
        'class'			=> ''
	), $atts));
	
	if(is_admin()) {
		return;
	}

	if(!$id) {
		return;
	}

	$sidebar = false;
	if($in_sidebar == 'true') {
	    $sidebar = true;
    }
	
	$filter = new AT_Filter($id, $sidebar);
	
	ob_start();
	$filter->build();
	return ob_get_clean();
}