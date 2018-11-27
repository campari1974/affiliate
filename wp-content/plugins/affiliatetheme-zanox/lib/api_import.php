<?php
add_action('wp_ajax_zanox_api_import', 'at_zanox_import');
add_action('wp_ajax_at_zanox_import', 'at_zanox_import');
function at_zanox_import() {
	global $wpdb;
	$nonce = $_POST['_wpnonce'];

	if (!wp_verify_nonce($nonce, 'at_zanox_import_wpnonce')) {
		die('Security Check failed');
	}

	// vars
	$id = $_POST['id'];
	$taxs = isset($_POST['tax']) ? $_POST['tax'] : array();

	if (isset($_POST['func']) && ($_POST['func'] == 'quick-import')) {
		// quick import
		$api = new \EcZanox\Zanox();
		$products = $api->getProduct($id);

		foreach ($products as $product) {
			$title = $product->getName();
			$price = $product->getPrice();
			$currency = $product->getCurrency();
			$pimages = $product->getImages();
			$images = array();
			$ean = $product->getEan();
			$url = $product->getUrl();
			$shop = $product->getProgram();
			$shop_id = md5($shop);
			$rating = '';

			if ($pimages) {
				foreach ($pimages as $image) {
					$images[1]['filename'] = sanitize_title($title);
					$images[1]['alt'] = $title;
					$images[1]['url'] = $image;
					$images[1]['thumb'] = 'true';
				}
			}

			if ('1' == get_option('zanox_import_description')) {
				$description = $product->getDescription();
			}

			break;
		}
	} else {
		// normal import
		$title = $_POST['title'];
		$price = floatval($_POST['price']);
		$currency = (('EUR' == $_POST['currency']) ? 'euro' : $_POST['currency']);
		$rating = $_POST['rating'];
		$images = $_POST['images'];
		$ean = $_POST['ean'];
		$url = $_POST['url'];
		$shop = $_POST['program'];
		$shop_id = $_POST['program_id'];

		if ('1' == get_option('zanox_import_description') && isset($_POST['description'])) {
			$description = $_POST['description'];
		}
	}

	// append
	$append = (isset($_POST['ex_page_id']) ? $_POST['ex_page_id'] : '');
	if(!$append && $ean) {
		$append = at_get_product_id_by_ean($ean);
	}	
	// start import
	if(false == ($check = at_get_product_id_by_metakey('product_shops_%_' . ZANOX_METAKEY_ID, $id, '='))) {
		// try to append product
		if ($append) {
			// product already exists, append
			$product_shops = get_field('product_shops', $append);
			$product_index = getRepeaterRowID($product_shops, 'ID', at_get_shop_id($shop_id), true);

			if (!$product_index) {
				$shop_info = get_field('field_557c01ea87000', $append);
				$shop_info[] = array(
					'price' => $price,
					'price_old' => '',
					'currency' => $currency,
					'portal' => 'zanox',
					ZANOX_METAKEY_ID => $id,
					'shop' => at_get_shop_id($shop_id, $shop, true),
					'link' => $url,
				);
				update_field('field_557c01ea87000', $shop_info, $append);

				at_write_api_log('zanox', $append, 'extended product successfully');

				$output['rmessage']['success'] = 'true';
				$output['rmessage']['post_id'] = $append;
			} else {
				$output['rmessage']['success'] = 'false';
				$output['rmessage']['reason'] = __('Dieses Produkt existiert bereits.', 'affiliatetheme-zanox');
				$output['rmessage']['post_id'] = $append;
			}
		} else {
			$args = array(
				'post_title' => $title,
				'post_status' => (get_option('zanox_post_status') ? get_option('zanox_post_status') : 'publish'),
				'post_type' => 'product',
				'post_content' => (isset($description) ? $description : '')
			);

			$post_id = wp_insert_post($args);
			if ($post_id) {
				// customfields
				update_post_meta($post_id, 'last_product_price_check', '0');
				update_post_meta($post_id, ZANOX_METAKEY_LAST_UPDATE, '0');
				update_post_meta($post_id, 'product_rating', $rating);
				update_post_meta($post_id, 'product_ean', $ean);

				$shop_info[] = array(
					'price' => $price,
					'currency' => $currency,
					'portal' => 'zanox',
					ZANOX_METAKEY_ID => $id,
					'shop' => at_get_shop_id($shop_id, $shop, true),
					'link' => $url,
				);
				update_field('field_557c01ea87000', $shop_info, $post_id);

				// taxonomies
				if ($taxs) {
					foreach ($taxs as $key => $value) {
						wp_set_object_terms($post_id, $value, $key, true);
					}
				}

				// product image
				if ($images) {
					$attachments = array();

					foreach ($images as $image) {
						$image_filename = sanitize_title($image['filename']);
						$image_alt = (isset($image['alt']) ? $image['alt'] : '');
						$image_url = $image['url'];
						$image_thumb = (isset($image['thumb']) ? $image['thumb'] : '');
						$image_exclude = (isset($image['exclude']) ? $image['exclude'] : '');

						if ("true" == $image_exclude)
							continue;

						if ("true" == $image_thumb) {
							$att_id = at_attach_external_image($image_url, $post_id, true, $image_filename, array('post_title' => $image_alt));
							update_post_meta($att_id, '_wp_attachment_image_alt', $image_alt);
						} else {
							$att_id = at_attach_external_image($image_url, $post_id, false, $image_filename, array('post_title' => $image_alt));
							update_post_meta($att_id, '_wp_attachment_image_alt', $image_alt);
							$attachments[] = $att_id;
						}

						if ($attachments)
							update_field('field_553b84fb117b1', $attachments, $post_id);
					}
				}

				at_write_api_log('zanox', $post_id, 'imported product successfully');
			}

			$output['rmessage']['success'] = 'true';
			$output['rmessage']['post_id'] = $post_id;
		}
	} else {
		$output['rmessage']['success'] = 'false';
		$output['rmessage']['reason'] = __('Dieses Produkt existiert bereits.', 'affiliatetheme-zanox');
		$output['rmessage']['post_id'] = $check;
	}

	do_action('at_zanox_import');

	sleep(1);

	echo json_encode($output);
	exit();
}
?>