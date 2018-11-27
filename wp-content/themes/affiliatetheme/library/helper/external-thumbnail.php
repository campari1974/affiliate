<?php
/**
 * External Post Thumbnail
 *
 * @author      Christian Lang
 * @version     1.0
 * @category    helper
 */

function xcore_ept_is_image( $url ) {
	$ext = array( 'jpeg', 'jpg', 'gif', 'png' );
	$info = (array) pathinfo( parse_url( $url, PHP_URL_PATH ) );
	return isset( $info['extension'] ) && in_array( strtolower( $info['extension'] ), $ext, TRUE );
}

add_filter( 'admin_post_thumbnail_html', 'xcore_ept_field' );
function xcore_ept_field( $html ) {
	global $post;

	if(get_post_type() == 'product') {
		$value = (get_post_meta($post->ID, '_thumbnail_ext_url', TRUE) ? get_post_meta($post->ID, '_thumbnail_ext_url', TRUE) : "");
		$nonce = wp_create_nonce('thumbnail_ext_url_' . $post->ID . get_current_blog_id());
		$html .= '<input type="hidden" name="thumbnail_ext_url_nonce" value="' . esc_attr($nonce) . '">';
		$html .= '<div><p>' . __('- oder - ', 'affiliatetheme-backend') . '</p>';
		$html .= '<p>' . __('verwende ein externes Bild:', 'affiliatetheme-backend') . '</p>';
		$html .= '<p><input type="url" name="thumbnail_ext_url" value="' . $value . '"  class="widefat" placeholder="http://"></p>';
		if (!empty($value) && xcore_ept_is_image($value)) {
			$html .= '<p><img style="max-width:150px;height:auto;" src="' . esc_url($value) . '"></p>';
		}
		$html .= '</div>';
	}

	return $html;
}

add_action( 'save_post', 'xcore_ept_save', 10, 2 );
function xcore_ept_save( $pid, $post ) {
	$cap = $post->post_type === 'product' ? 'edit_page' : 'edit_post';
	if (
		! current_user_can( $cap, $pid )
		|| ! post_type_supports( $post->post_type, 'thumbnail' )
		|| defined( 'DOING_AUTOSAVE' )
	) {
		return;
	}
	$action = 'thumbnail_ext_url_' . $pid . get_current_blog_id();
	$nonce = filter_input( INPUT_POST,  'thumbnail_ext_url_nonce', FILTER_SANITIZE_STRING );
	$url = filter_input( INPUT_POST,  'thumbnail_ext_url', FILTER_VALIDATE_URL );
	if (
		empty( $nonce )
		|| ! wp_verify_nonce( $nonce, $action )
		|| ( ! empty( $url ) && ! xcore_ept_is_image( $url ) )
	) {
		return;
	}
	if ( ! empty( $url ) ) {
		update_post_meta( $pid, '_thumbnail_ext_url', esc_url($url) );
		if ( ! get_post_meta( $pid, '_thumbnail_id', TRUE ) ) {
			update_post_meta( $pid, '_thumbnail_id', 'by_url' );
		}
	} elseif ( get_post_meta( $pid, '_thumbnail_ext_url', TRUE ) ) {
		delete_post_meta( $pid, '_thumbnail_ext_url' );
		if ( get_post_meta( $pid, '_thumbnail_id', TRUE ) === 'by_url' ) {
			delete_post_meta( $pid, '_thumbnail_id' );
		}
	}
}

add_filter( 'post_thumbnail_html', 'xcore_ept_markup', 10, PHP_INT_MAX );
function xcore_ept_markup( $html, $post_id ) {
	$dsgvo_external_images_proxy = get_field('dsgvo_external_images_proxy', 'options');

	if($dsgvo_external_images_proxy) {
		$url_check =  get_post_meta( $post_id, '_thumbnail_ext_url', TRUE );

		if ( empty( $url_check ) ) {
			return $html;
		}

		$url =  at_external_images_get_url($post_id);
		if ( empty( $url ) ) {
			return $html;
		}
	} else {
		$url =  get_post_meta( $post_id, '_thumbnail_ext_url', TRUE );
		if ( empty( $url ) || ! xcore_ept_is_image( $url ) ) {
			return $html;
		}
	}

	$alt = get_post_field( 'post_title', $post_id );
	$attr = array( 'alt' => $alt );
	$attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, NULL );
	$attr = array_map( 'esc_attr', $attr );
	$html = sprintf( '<img src="%s"', esc_url($url) );
	foreach ( $attr as $name => $value ) {
		$html .= " $name=" . '"' . $value . '"';
	}
	$html .= ' />';
	return $html;
}

add_action( 'init', 'at_external_images_script_rewrite' );
function at_external_images_script_rewrite() {
	global $wp_rewrite;
	$slug = apply_filters('at_external_images_script_slug', 'at-get-img');
	add_rewrite_rule( '^'.$slug.'/([^/]*)/([^/]*)', 'index.php?'.$slug.'=$matches[1]&image_id=$matches[2]&title=$matches[3]', 'top' );
}

add_action( 'query_vars', 'at_external_images_script_cloak_query_vars' );
function at_external_images_script_cloak_query_vars( $query_vars ) {
	$slug = apply_filters('at_external_images_script_slug', 'at-get-img');

	$query_vars[] = $slug;
	$query_vars[] = 'image_id';
	return $query_vars;
}

add_action( 'parse_request', 'at_external_images_script_out_parse_request' );
function at_external_images_script_out_parse_request( $wp ) {
	$slug = apply_filters('at_external_images_script_slug', 'at-get-img');

	if ( array_key_exists($slug, $wp->query_vars) ) {
		get_template_part('parts/stuff/page', 'external-image');
		exit();
	}
}

function at_external_images_get_url($post_id = 0, $image_id = 0) {
	$slug = apply_filters('at_external_images_script_slug', 'at-get-img');

	return home_url($slug . '/' . $post_id . '/' . $image_id . '/' . sanitize_title(get_the_title($post_id)) . '.jpg');
}

function at_external_images_get_image_url($post_id = 0, $image_id = 0) {
	if($post_id && $image_id) {
		$product_gallery_external = get_field('product_gallery_external', $post_id);

		if($product_gallery_external && isset($product_gallery_external[$image_id-1]['url'])) {
			return $product_gallery_external[$image_id-1]['url'];
		}
	} else if($post_id) {
		$url =  get_post_meta($post_id, '_thumbnail_ext_url', TRUE);
		if ( ! empty( $url ) && xcore_ept_is_image( $url ) ) {
			return $url;
		}
	}

	return false;
}
?>
