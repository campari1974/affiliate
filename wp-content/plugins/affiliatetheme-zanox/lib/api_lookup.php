<?php
add_action('wp_ajax_zanox_api_lookup', 'at_zanox_lookup');
add_action('wp_ajax_at_zanox_lookup', 'at_zanox_lookup');
function at_zanox_lookup() {
	$productId = isset($_GET['id']) ? (string)$_GET['id'] : '';

	$api = new \EcZanox\Zanox();

	$products = $api->getProduct($productId);

	foreach ($products as $product) {
		$id = $product->getId();
		$title = $product->getName();
		$price = $product->getPrice();
		$currency = $product->getCurrency();
		$images = $product->getImages();
		$ean = $product->getEan();
		$url = $product->getUrl();
		$description = $product->getDescription();
		$shop = $product->getProgram();
		$shop_id = md5($shop);

		break;
	}
	?>
	<div class="container">
		<form action="" id="import-product">
			<div class="row">
				<div class="form-group col-xs-12">
					<label><?php _e('Titel', 'affiliatetheme-zanox'); ?></label>
					<input type="text" id="title" name="title" class="form-control" value="<?php echo esc_html($title); ?>"/>
				</div>

				<div class="form-group col-xs-3">
					<label><?php _e('EAN', 'affiliatetheme-zanox'); ?></label>
					<input type="text" id="ean" name="ean" class="form-control" value="<?php echo $ean; ?>" readonly/>
				</div>

				<div class="form-group col-xs-3">
					<label><?php _e('ID', 'affiliatetheme-zanox'); ?></label>
					<input type="text" id="id" name="id" class="form-control" value="<?php echo $id; ?>" readonly/>
				</div>

				<div class="form-group col-xs-3">
					<label><?php _e('Bewertung', 'affiliatetheme-zanox'); ?></label>
					<?php echo at_get_product_rating_list(); ?>
				</div>

				<div class="form-group col-xs-3">
					<label><?php _e('Preis', 'affiliatetheme-zanox'); ?></label>
					<input type="text" id="price" name="price" class="form-control" value="<?php echo $price; ?>" readonly/>
				</div>
			</div>

			<?php
			/*
			 * Description
			 */
			if ('1' == get_option('zanox_import_description')) { ?>
				<h3><?php _e('Beschreibung', 'affiliatetheme-zanox'); ?></h3>
				<textarea name="description" class="widefat product-description" rows="5"><?php echo $description; ?></textarea>
			<?php } ?>

			<?php
			/*
			* Taxonomien
			*/
			if (get_products_multiselect_tax_form()) {
				echo '<h3>' . __('Taxonomien', 'affiliatetheme-zanox') . '</h3>' . get_products_multiselect_tax_form();
			}
			
			/*
			 * Existrierende Produkte
			 */
			if (at_get_existing_products()) {
				echo '<h3>' . __('Existierendes Produkt aktualisieren', 'affiliatetheme-zanox') . '</h3>' . at_get_existing_products(true);
			}

			/*
			* Product Images
			*/
			if ($images) {
				$i = 1;
				?>
				<h3><?php _e('Produktbild(er)', 'affiliatetheme-zanox'); ?></h3>
				<div class="row">
					<?php foreach ($images as $image) {
						$image_info = explode('/', $image);
						$image_info = array_pop($image_info);
						$image_info = pathinfo($image_info);
						$image_filename = sanitize_title($title);
						$image_ext = $image_info['extension'];
						?>
						<div class="image col-sm-4" data-item="<?php echo $i; ?>">
							<div class="image-wrapper">
								<img src="<?php echo $image; ?>" class="img-responsive"/>
							</div>
							<div class="image-info">
								<div class="form-group small">
									<label><?php _e('Bildname', 'affiliatetheme-zanox'); ?></label>
									<input type="text" name="images[<?php echo $i; ?>][filename]" data-url="<?php echo $image; ?>" id="images[<?php echo $i; ?>][filename]" value="<?php echo $image_filename . '-' . $i; ?>"/>
									.<?php echo $image_ext; ?>
								</div>

								<div class="form-group small">
									<label><?php _e('ALT-Tag', 'affiliatetheme-zanox'); ?></label>
									<input type="text" name="images[<?php echo $i; ?>][alt]" id="images[<?php echo $i; ?>][alt]" value=""/>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-6">
									<div class="form-group small">
										<label><?php _e('Artikelbild', 'affiliatetheme-zanox'); ?></label>
										<input type="checkbox" name="images[<?php echo $i; ?>][thumb]" value="true" class="unique" checked/>
									</div>
								</div>

								<div class="col-xs-6">
									<div class="form-group small">
										<label><?php _e('Überspringen', 'affiliatetheme-zanox'); ?></label>
										<input type="checkbox" name="images[<?php echo $i; ?>][exclude]" value="true" class="disable-this"/>
									</div>
								</div>
							</div>
							<input type="hidden" name="images[<?php echo $i; ?>][url]" value="<?php echo $image; ?>"/>
						</div>
						<?php
						$i++;
					} ?>
				</div>
			<?php } ?>

			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<input type="hidden" name="currency" value="<?php echo $currency; ?>"/>
						<input type="hidden" name="url" value="<?php echo $url; ?>"/>
						<input type="hidden" name="ean" value="<?php echo $ean; ?>"/>
						<input type="hidden" name="program" value="<?php echo $shop; ?>"/>
						<input type="hidden" name="program_id" value="<?php echo $shop_id; ?>"/>
						<input type="hidden" name="func" value=""/>
						<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce("at_zanox_import_wpnonce"); ?>"/>
						<input type="hidden" name="action" value="zanox_api_import"/>
						<input type="hidden" name="mass" value="false"/>
						<button type="submit" id="import" name="import" class="single-import-product button button-primary">
							<?php _e('Importieren', 'affiliatetheme-zanox'); ?>
						</button>
						<button type="submit" id="tb-close" class="button" onclick="self.parent.tb_remove();return false">
							<?php _e('Schließen', 'affiliatetheme-zanox'); ?>
						</button>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php
	exit();
}