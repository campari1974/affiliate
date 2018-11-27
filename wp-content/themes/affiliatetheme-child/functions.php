<?php
/*
 * DO NOT EDIT THESE 4 LINES !
 */
add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme' );
function enqueue_parent_theme() {
	wp_enqueue_style('parent-theme', get_template_directory_uri() . '/style.css', array('boostrap'));
}

/*
 * Own Functions ... 
 */