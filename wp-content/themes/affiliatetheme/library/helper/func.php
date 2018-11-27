<?php
/**
 * Diverse Hilfsfunktionen
 * 
 * @author		Christian Lang
 * @version		1.2
 * @category	helper
 */

/**
 * Thumbnail Support
 */
add_theme_support( 'post-thumbnails' );
add_image_size('at_large', 1140, 600, true);
add_image_size('at_large_9', 848, 446, true);
add_image_size('at_thumbnail', 360, 189, true);
add_image_size('at_thumbnail_9', 263, 138, true);

if ( ! function_exists( 'at_debug' ) ) {
	/**
	 * at_debug function.
	 *
	 * @param  arraa $var
	 * @return string
	 */
	function at_debug($var) {
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}
}

if ( ! function_exists( 'at_post_thumbnail' ) ) {
	/**
	 * at_post_thumbnail function.
	 *
	 * @param  boolean $post_id
	 * @param  string $size (default: thumbnail)
	 * @param  array $args
	 * @return string
	 */
	function at_post_thumbnail($post_id, $size = 'thumbnail', $args = array()) {
		$output = '';

		if (isset($args['sidebar']) && ('right' == $args['sidebar'] || 'left' == $args['sidebar'])) {
			$size .= '_9';
		}

		if (has_post_thumbnail($post_id)) {
			if(isset($args['sidebar'])) {
				unset($args['sidebar']);
			}

			if(isset($args['sidebar_size'])) {
				unset($args['sidebar_size']);
			}

			$output = get_the_post_thumbnail($post_id, $size, $args);
		} else {
			if ('1' != get_field('blog_placeholders', 'option') && 'post' == get_post_type($post_id)) {
				return $output;
			}

			$sizes = at_get_image_sizes();
			$current_size = $sizes[$size]['width'] . 'x' . ($sizes[$size]['height'] != '0' ? $sizes[$size]['height'] : $sizes[$size]['width']);

			// check uploaded placeholders
			$placeholder_img = get_field('placeholder_' . $current_size, 'option');
			if($placeholder_img && is_array($placeholder_img)) {
				$url = $placeholder_img['url'];
			} else {
				$url = esc_url(get_template_directory_uri()) . '/_/img/placeholder-' . $current_size . '.jpg';
			}
			
			$output = '<img src="' . apply_filters('at_post_thumbnail_placeholder', $url, $post_id, $size, $args) . '" width="' . $sizes[$size]['width'] . '" height="' . ($sizes[$size]['height'] != '0' ? $sizes[$size]['height'] : $sizes[$size]['width']) . '" alt="' . get_the_title($post_id) . '"';
			if (isset($args['class']))
				$output .= ' class="' . $args['class'] . '"';
			$output .= '/>';
		}

		return $output;
	}
}

if ( ! function_exists( 'endcore_excerpt' ) ) {
	/**
	 * endcore_excerpt function.
	 * @deprecated since 1.2.5
	 *
	 * @param  int $limit
	 * @return string
	 */
	function endcore_excerpt($limit) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt) >= $limit) {
			array_pop($excerpt);
			$excerpt = implode(" ", $excerpt) . '...';
		} else {
			$excerpt = implode(" ", $excerpt);
		}
		$excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
		return $excerpt;
	}
}

if ( ! function_exists( 'at_excerpt' ) ) {
	/**
	 * at_excerpt function.
	 *
	 * @param  int $limit
	 * @return string
	 */
	function at_excerpt($limit) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt) >= $limit) {
			array_pop($excerpt);
			$excerpt = implode(" ", $excerpt) . '...';
		} else {
			$excerpt = implode(" ", $excerpt);
		}
		$excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
		return $excerpt;
	}
}

if ( ! function_exists( 'get_image_sizes' ) ) {
	/**
	 * get_image_sizes function.
	 * @desc get all image size
	 * @deprecated since 1.2.5
	 *
	 * @param  string $size
	 * @return array
	 */
	function get_image_sizes($size = '') {
		global $_wp_additional_image_sizes;

		$sizes = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();

		foreach ($get_intermediate_image_sizes as $_size) {
			if (in_array($_size, array('thumbnail', 'medium', 'large'))) {
				$sizes[$_size]['width'] = get_option($_size . '_size_w');
				$sizes[$_size]['height'] = get_option($_size . '_size_h');
				$sizes[$_size]['crop'] = (bool)get_option($_size . '_crop');
			} elseif (isset($_wp_additional_image_sizes[$_size])) {
				$sizes[$_size] = array(
					'width' => $_wp_additional_image_sizes[$_size]['width'],
					'height' => $_wp_additional_image_sizes[$_size]['height'],
					'crop' => $_wp_additional_image_sizes[$_size]['crop']
				);
			}
		}

		if ($size) {
			if (isset($sizes[$size])) {
				return $sizes[$size];
			} else {
				return false;
			}
		}

		return $sizes;
	}
}

if ( ! function_exists( 'at_get_image_sizes' ) ) {
	/**
	 * at_get_image_sizes function.
	 * @desc get all image size
	 *
	 * @param  string $size
	 * @return array
	 */
	function at_get_image_sizes($size = '') {
		global $_wp_additional_image_sizes;

		$sizes = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();

		foreach ($get_intermediate_image_sizes as $_size) {
			if (in_array($_size, array('thumbnail', 'medium', 'large'))) {
				$sizes[$_size]['width'] = get_option($_size . '_size_w');
				$sizes[$_size]['height'] = get_option($_size . '_size_h');
				$sizes[$_size]['crop'] = (bool)get_option($_size . '_crop');
			} elseif (isset($_wp_additional_image_sizes[$_size])) {
				$sizes[$_size] = array(
					'width' => $_wp_additional_image_sizes[$_size]['width'],
					'height' => $_wp_additional_image_sizes[$_size]['height'],
					'crop' => $_wp_additional_image_sizes[$_size]['crop']
				);
			}
		}

		if ($size) {
			if (isset($sizes[$size])) {
				return $sizes[$size];
			} else {
				return false;
			}
		}

		return $sizes;
	}
}

if ( ! function_exists( 'at_phone_detect' ) ) {
	/**
	 * at_phone_detect function.
	 *
	 * @return boolean
	 */
	function at_phone_detect() {
		$detect = new Mobile_Detect;
		if ($detect->isMobile() && !$detect->isTablet()) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'at_tablet_detect' ) ) {
	/**
	 * at_tablet_detect function.
	 *
	 * @return boolean
	 */
	function at_tablet_detect() {
		$detect = new Mobile_Detect;

		if ($detect->isTablet()) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'at_post_type_label' ) ) {
	/**
	 * at_post_type_label function.
	 *
	 * @return string
	 */
	function at_post_type_label($slug) {
		$object = get_post_type_object($slug);

		if($object) {
			return $object->labels->name;
		}

		return false;
	}
}

if ( ! function_exists( 'at_clean_data' ) ) {
	/**
	 * at_clean_data function.
	 *
	 * @return string
	 */
	function at_clean_data($data) {
		if(is_array($data)) {
			$data = $data[0];
		}
		
		return htmlspecialchars($data);
	}
}

/**
 * fix description editor
 */
add_action('admin_enqueue_scripts', 'at_tax_description_editor_fix');
function at_tax_description_editor_fix($pagehook) {
	global $post_type;
	$pages = array( 'edit-tags.php' );
	if ( in_array( $pagehook, $pages ) ) {
		add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
	}
}


/**
 * search-box markup for google
 */
add_action( 'wp_footer', 'at_schema_search_box', 133337 );
function at_schema_search_box (){
	if ( is_front_page() ) {
		echo PHP_EOL . '<script type="application/ld+json">' . PHP_EOL;
		echo '{' . PHP_EOL;
		echo '  "@context": "http://schema.org",' . PHP_EOL;
		echo '  "@type": "WebSite",' . PHP_EOL;
		echo '  "url": "' . get_site_url() . '/",' . PHP_EOL;
		echo '  "potentialAction": {' . PHP_EOL;
		echo '    "@type": "SearchAction",' . PHP_EOL;
		echo '    "target": "' . get_home_url() . '/?s={search_term}",' . PHP_EOL;
		echo '    "query-input": "required name=search_term"' . PHP_EOL;
		echo '  }' . PHP_EOL;
		echo '}' . PHP_EOL;
		echo '</script>' . PHP_EOL;
	}
}

/**
 * Single article markup
 */
if(!function_exists('at_schema_single_article')) {
	add_action( 'wp_footer', 'at_schema_single_article', 133337 );
	function at_schema_single_article() {
		if ( is_single() && 'post' == get_post_type() ) {
			global $post;
			$logo = get_field('design_logo', 'option');
			$title = get_the_title();
			$author = esc_attr(get_the_author());
			$date = get_the_date('Y-m-d H:i:s');
			$date_modified = get_the_modified_date('Y-m-d H:i:s');
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
			$url = get_permalink();

			echo PHP_EOL . '<script type="application/ld+json">' . PHP_EOL;
			echo '{' . PHP_EOL;
			echo '"@context": "http://schema.org/",' . PHP_EOL;
			echo '"@type": "Article",' . PHP_EOL;
			echo '"name": "' . $title . '",' . PHP_EOL;
			echo '"headline": "' . $title . '",' . PHP_EOL;
			echo '"author": {"@type" : "Person", "name" : "' . $author . '"},' . PHP_EOL;
			echo '"publisher": {"@type" : "Organization", "name" : "' . get_bloginfo('name') . '"' . ($logo ? ',"logo" : {"@type" : "ImageObject", "url" : "' . $logo["url"] . '", "width" : "' . $logo["width"] . 'px", "height" : "' . $logo["height"] . 'px"}' : '') . '},' . PHP_EOL;
			echo '"datePublished": "' . $date . '",' . PHP_EOL;
			echo '"dateModified": "' . $date_modified . '",' . PHP_EOL;
			echo '"mainEntityOfPage": "' . $url . '"' . PHP_EOL;
			echo($image ? ',"image": {"@type" : "ImageObject", "url" : "' . $image[0] . '", "width" : "' . $image[1] . 'px", "height" : "' . $image[2] . 'px"}' . PHP_EOL : '');
			echo '}' . PHP_EOL;
			echo '</script>' . PHP_EOL;
		}
	}
}

add_action( 'after_setup_theme', 'at_theme_updater', 20);
function at_theme_updater() {
	require( get_template_directory() . '/library/plugins/edd/at-updater.php' );
}

add_action( 'admin_notices', 'at_theme_licence_notice' );
function at_theme_licence_notice() {
	$status = get_option('affiliatetheme_license_key_status');

	if('valid' == $status)
		return;

	if('' == $status && !$status) {
		?>
		<div class="error">
			<p><?php printf(__('Bitte trage <a href="%s">hier</a> eine Lizenz ein um das Theme nutzen zu können.', 'affiliatetheme-backend'), admin_url('themes.php?page=affiliatetheme-license')); ?></p>
		</div>
		<?php
	}

	if('invalid' == $status) {
		?>
		<div class="error">
			<p><?php printf(__('Es scheint so als wäre deine Lizenz nicht gültig. Prüfe deine Lizenz bitte <a href="%s">hier</a>.', 'affiliatetheme-backend'), admin_url('themes.php?page=affiliatetheme-license')); ?></p>
		</div>
		<?php
	}

	if('site_inactive' == $status) {
		?>
		<div class="error">
			<p><?php printf(__('Es scheint so als wäre deine aktuelle Seite nicht aktiviert. Prüfe deine Lizenz bitte <a href="%s">hier</a>.', 'affiliatetheme-backend'), admin_url('themes.php?page=affiliatetheme-license')); ?></p>
		</div>
		<?php
	}
}

add_action( 'after_setup_theme', 'at_theme_deregister', 20);
function at_theme_deregister() {
	$status = get_option('affiliatetheme_license_key_status');

	if('valid' == $status || 'expired' == $status)
		return;

	remove_action('init', 'product_register');
	remove_action('init', 'shop_register');
}

function requestUriAddGetParams(array $params) {
	global $_REQUEST;

	$params = array_merge($_GET, $params);

	if(isset($_REQUEST['REQUEST_URI'])) {
		$parseRes = parse_url($_REQUEST['REQUEST_URI']);
		return $parseRes['path'] . '?' . http_build_query($params);
	}

	return '?' . http_build_query($params);
}

if ( ! function_exists( 'at_media_allow_svg' ) ) {
	/**
	 * at_media_allow_svg function.
	 *
	 * @return string
	 */
	add_filter('upload_mimes', 'at_media_allow_svg');
	function at_media_allow_svg($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
}

if ( ! function_exists( 'at_get_post_id' ) ) {
	/**
	 * at_get_post_id function.
	 *
	 * @return int
	 */
	function at_get_post_id() {
		global $post;

		$post_id = '';

		if(is_tax() || is_category() || is_tag()) {
			$queried_object = get_queried_object();
			if($queried_object->term_id) {
				$post_id = $queried_object->term_id;
			}
		} else if(is_home()) {
			$post_id = get_option('page_for_posts');
		} else {
			if($post) {
				$post_id = $post->ID;
			}
		}

		return $post_id;
	}
}

if ( ! function_exists( 'at_get_current_url' ) ) {
	/**
	 * at_get_current_url function.
	 *
	 * @return string
	 */
	function at_get_current_url() {
		$url = home_url(add_query_arg(null, null));
		return $url;
	}
}

if ( ! function_exists( 'at_get_star_rating' ) ) {
	/**
	 * at_get_star_rating function.
	 *
	 * @return string
	 */
	function at_get_star_rating($value) {
		$value = floor($value * 2) / 2;

		$full = '<i class="fa fa-star"></i>';
		$half = '<i class="fa fa-star-half-o"></i>';
		$empty = '<i class="fa fa-star-o"></i>';
		$output = '';
		$max = 5;

		if ($value) {
			$rating_arr = explode('.', $value);
			if ($rating_arr) {
				if (isset($rating_arr[1]) && $rating_arr[1] > 0)
					$rating_arr[1] = '1';
				else
					$rating_arr[1] = '0';

				/*
				 * FULL
				 */
				$output .= str_repeat($full, $rating_arr[0]);

				/*
				 * HALF
				 */

				if (isset($rating_arr[1]) && '0' != $rating_arr[1])
					$output .= $half;

				/*
				 * EMTPY
				 */
				if (($max - $rating_arr[0]) >= '1')
					$output .= str_repeat($empty, $max - ($rating_arr[0] + $rating_arr[1]));
			}
		} else {
			$output .= str_repeat($empty, 5);
		}

		return $output;
	}
}

if ( ! function_exists( 'at_hex2rgb' ) ) {
	/**
	 * at_hex2rgb function.
	 *
	 * @return string
	 */
	function at_hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if (strlen($hex) == 3) {
			$r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
			$g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
			$b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
		} else {
			$r = hexdec(substr($hex, 0, 2));
			$g = hexdec(substr($hex, 2, 2));
			$b = hexdec(substr($hex, 4, 2));
		}
		$rgb = $r . ',' . $g . ',' . $b;
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
	}
}

if ( ! function_exists( 'at_get_terms_hierarchical' ) ) {
	/**
	 * at_get_terms_hierarchical function.
	 *
	 * @return string
	 */
	function at_get_terms_hierarchical($terms, $output = '', $parent_id = 0, $level = 0, $current_term = '') {
		$outputTemplate = '<option value="%ID%"%SELECTED%>%PADDING%%NAME%</option>';

		foreach ($terms as $term) {
			if ($parent_id == $term->parent) {
				//Replacing the template variables
				$itemOutput = str_replace('%ID%', $term->slug, $outputTemplate);
				$itemOutput = str_replace('%PADDING%', str_pad('', $level * 12, '&nbsp;&nbsp;&nbsp;'), $itemOutput);
				$itemOutput = str_replace('%NAME%', $term->name, $itemOutput);

				if ($current_term == $term->slug) {
					$itemOutput = str_replace('%SELECTED%', ' selected', $itemOutput);
				} else {
					$itemOutput = str_replace('%SELECTED%', '', $itemOutput);
				}

				$output .= $itemOutput;
				$output = at_get_terms_hierarchical($terms, $output, $term->term_id, $level + 1, $current_term);
			}
		}
		return $output;
	}
}

if ( ! function_exists( 'at_attribute_array_html' ) ) {
	/**
	 * at_attribute_array_html function.
	 *
	 * @return string
	 */
	function at_attribute_array_html($attributes) {
		$attributes_html = '';

		if ($attributes) {
			foreach ($attributes as $k => $v) {
				if ($v) {
					$attributes_html .= $k . '="' . implode($v, ' ') . '" ';
				}
			}
		}

		return $attributes_html;
	}
}

if ( ! function_exists( 'at_clean_url' ) ) {
	/**
	 * at_clean_url function.
	 *
	 * @return string
	 */
	function at_clean_url($value) {
		$value = str_replace(array('http://', 'https://'), '', $value);

		return $value;
	}
}

if ( ! function_exists( 'at_teaser_has_elements' ) ) {
	/**
	 * at_teaser_has_elements function.
	 *
	 * @return boolean
	 */
	function at_teaser_has_elements($el) {
		if($el) {
			foreach($el as $item) {
				if($item['background'] || $item['image'] || $item['image_smartphone'] || $item['text']) {
					return true;
				}
			}
		}

		return false;
	}
}

if ( ! function_exists( 'at_product_review_calculate_summary' ) ) {
	/**
	 * at_product_review_calculate_summary function.
	 *
	 * @return string
	 */
	function at_product_review_calculate_summary($post_id, $ratings = array(), $style = '')
	{
		if (!$post_id) {
			return false;
		}

		$summary = '';
		$rounded_to = apply_filters('at_product_review_summary_rounded', 2, $post_id);

		if(!$style) {
			$style = get_field('product_review_style', $post_id);
		}

		if(empty($ratings)) {
			$ratings = get_field('product_review_ratings', $post_id);

			if(empty($ratings)) {
				return false;
			}
		}

		if ($style == 'procentual') {
			foreach ($ratings as $rating) {
				$summary = floatval($summary) +  floatval($rating['value']);
			}
			$summary = $summary / count($ratings);
		}

		if ($style == 'number') {
			$summary = '';
			foreach ($ratings as $rating) {
				$summary =  floatval($summary) +  floatval($rating['value']);
			}
			$summary = round($summary / count($ratings), $rounded_to);
			$summary = $summary;
		}

		return apply_filters('at_product_review_calculate_summary', $summary, $post_id, $ratings, $style);
	}
}

if ( ! function_exists('at_php_version_notices') ) {
    /**
     * at_php_version_notices function.
     *
     */
    add_action('admin_notices', 'at_php_version_notices');
    function at_php_version_notices() {
		// check php version
		if(version_compare(PHP_VERSION, '5.5.0', '<')) {
			?>
			<div class="notice notice-error">
				<p><?php printf(__('Achtung: Um das Affiliate Theme fehlerfrei nutzen zu können, benötigst du mindestens PHP Version 5.5.x. Derzeit verwendest du Version %s.', 'affiliatetheme-backend'), PHP_VERSION); ?></p>
			</div>
			<?php
		}  
    }
}

if ( ! function_exists('at_gutenberg_can_edit_post_types') ) {
    /**
     * at_gutenberg_can_edit_post_types function.
     *
     * Prevent Gutenberg to load on Pages, Products and Shops. Prepare for WP 5.0
     *
     */
    add_filter('gutenberg_can_edit_post_type', 'at_gutenberg_can_edit_post_types', 10, 2);
    function at_gutenberg_can_edit_post_types($can_edit, $post_type) {
        if (in_array($post_type, array('product', 'shop'))) {
            return false;
        }

        return $can_edit;
    }
}

if ( ! function_exists('at_ga_optout_script') ) {
	/**
	 * Added GA Optout Script to header
	 */
	add_action('wp_head', 'at_ga_optout_script');
	function at_ga_optout_script() {
		$dsgvo_google_opt_out = get_field('dsgvo_google_opt_out', 'options');

		if(!$dsgvo_google_opt_out) {
			return;
		}

		$dsgvo_google_opt_out_uaid = get_field('dsgvo_google_opt_out_uaid', 'options');

		if(!$dsgvo_google_opt_out_uaid) {
			return;
		}

		$dsgvo_google_opt_out_alert = (get_field('dsgvo_google_opt_out_alert', 'options') ? get_field('dsgvo_google_opt_out_alert', 'options') : 'Das Tracking durch Google Analytics wurde in Ihrem Browser für diese Website deaktiviert.');
		?>
		<script type="text/javascript">
			var at_gaProperty = '<?php echo $dsgvo_google_opt_out_uaid; ?>';
			var at_disableStr = 'ga-disable-' + at_gaProperty;

			if (document.cookie.indexOf(at_disableStr + '=true') > -1) {
				window[at_disableStr] = true;
			}

			function at_gaOptout() {
				document.cookie = at_disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
				window[at_disableStr] = true;
				alert('<?php echo $dsgvo_google_opt_out_alert; ?>');
			}
		</script>
		<?php
	}
}

if ( ! function_exists('at_custom_validate_comment') ) {
	/**
	 * Added custom privacy hint as required
	 */
	add_action('pre_comment_on_post', 'at_custom_validate_comment');
	function at_custom_validate_comment() {
		$dsgvo_comment_privacy_hint = get_field('dsgvo_comment_privacy_hint', 'options');
		
		if(!$dsgvo_comment_privacy_hint) {
			return;
		}
		
		if(is_user_logged_in()) {
			return;
		}
		
		if( empty( $_POST['user_check']) || empty( $_POST['user_check'])  ) {
			$dsgvo_comment_privacy_hint_validate = get_field('dsgvo_comment_privacy_hint_validate', 'options');
			wp_die( $dsgvo_comment_privacy_hint_validate );
		}
	}
}

if ( ! function_exists('at_gpdr_cookie_bar') ) {
	/**
	 * Added cookie bar
	 */
	add_action('wp_footer', 'at_gpdr_cookie_bar', 9999);
	function at_gpdr_cookie_bar() {	
		$dsgvo_cookie_bar = get_field('dsgvo_cookie_bar', 'options');
		
		if(!$dsgvo_cookie_bar) {
			return;
		}
		
		$dsgvo_cookie_bar_text = get_field('dsgvo_cookie_bar_text', 'options');
		$dsgvo_cookie_bar_button = get_field('dsgvo_cookie_bar_button', 'options');
		?>
		<div class="cookie-bar">
			<div class="container">
				<div class="media">
					<div class="media-body">
						<p><?php echo $dsgvo_cookie_bar_text; ?></p>
					</div>
					<div class="media-right media-middle">
						<?php do_action('at_cookie_notice_before_button'); ?>
						<a href="#" id="cookie_btn" class="btn btn-buy"><?php echo $dsgvo_cookie_bar_button; ?></a>
						<?php do_action('at_cookie_notice_after_button'); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	} 
}