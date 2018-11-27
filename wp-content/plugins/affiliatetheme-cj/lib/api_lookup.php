<?php
add_action('wp_ajax_at_cj_lookup', 'at_cj_lookup');
add_action('wp_ajax_cj_api_lookup', 'at_cj_lookup');
function at_cj_lookup() {
	$api = new Endcore\Api\CommissionJunction();

	$adid = $_GET['adid'];
	$sku = $_GET['sku'];
	$item = $api->lookupProduct($adid,$sku);

	if ($item) {
	    $item = $item->products->product;
		$id = $item->{'ad-id'};
		$ean = $item->sku;
		$title = $item->name;
        $price = ($item->price != '0')?$item->price: 'kA';
		$currency = $item->currency;
		$image = is_string($item->{'image-url'})?$item->{'image-url'}:'';
		$shop_id = $item->{'advertiser-id'};
		$shop_name = $item->{'advertiser-name'};
		//var_dump($shop_name);
		$url = $item->{'buy-url'};
		$description = $item->description;
		//var_dump($description);
		?>
		<div class="container">
			<form action="" id="import-product">
				<div class="row">
					<div class="form-group col-xs-12">
						<label><?php _e('Titel', 'affiliatetheme-cj'); ?></label>
						<input type="text" id="title" name="title" class="form-control" value="<?php echo esc_html($title); ?>"/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('ID', 'affiliatetheme-cj'); ?></label>
						<input type="text" id="id" name="id" class="form-control" value="<?php echo $id; ?>" readonly/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('EAN', 'affiliatetheme-cj'); ?></label>
						<input type="text" id="ean_tmp" name="ean_tmp" class="form-control" value="<?php echo ($ean ? $ean : '-') ; ?>" readonly/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('Artikelnummer', 'affiliatetheme-cj'); ?></label>
						<input type="text" id="productid" name="productid" class="form-control" value="<?php echo $ean; ?>" readonly/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('Preis', 'affiliatetheme-cj'); ?></label>
						<input type="text" id="price" name="price" class="form-control" value="<?php echo $price; ?>" readonly/>
					</div>
				</div>

                <div class="row">
                    <div class="form-group col-xs-3">
                        <label><?php _e('Bewertung', 'affiliatetheme-cj'); ?></label>
                        <?php echo at_get_product_rating_list(); ?>
                    </div>
                </div>

				<?php

				if ('1' == get_option('cj_import_description')) { ?>
					<h3><?php _e('Beschreibung', 'affiliatetheme-cj'); ?></h3>
					<textarea name="description" class="widefat product-description" rows="5"><?php echo $description; ?></textarea>
				<?php } ?>

				<?php

				if (get_products_multiselect_tax_form()) {
					echo '<h3>' . __('Taxonomien', 'affiliatetheme-cj') . '</h3>' . get_products_multiselect_tax_form();
				}


				if (at_get_existing_products()) {
					echo '<h3>' . __('Existierendes Produkt aktualisieren', 'affiliatetheme-cj') . '</h3>' . at_get_existing_products(true);
				}


				$image_info = explode('/', $image);
				$image_info = array_pop($image_info);
				$image_info = pathinfo($image_info);
				$image_filename = sanitize_title($title);
				$image_ext = $image_info['extension'];
				$i = 0;
				?>
				<h3><?php _e('Produktbild', 'affiliatetheme-cj'); ?></h3>
				<div class="row">
					<div class="image col-sm-4" data-item="<?php echo $i; ?>">
						<div class="image-wrapper"><img src="<?php echo $image; ?>" class="img-responsive"/></div>
						<div class="image-info">
							<div class="form-group small">
								<label><?php _e('Bildname', 'affiliatetheme-cj'); ?></label>
								<input type="text" name="image[<?php echo $i; ?>][filename]" data-url="<?php echo $image; ?>" id="image[<?php echo $i; ?>][filename]" value="<?php echo $image_filename; ?>"/>
								.<?php echo $image_ext; ?>
							</div>

							<div class="form-group small">
								<label><?php _e('ALT-Tag', 'affiliatetheme-cj'); ?></label>
								<input type="text" name="image[<?php echo $i; ?>][alt]" id="image[<?php echo $i; ?>][alt]" value=""/>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group small"><label><?php _e('Artikelbild', 'affiliatetheme-cj'); ?></label>
									<input type="checkbox" name="image[<?php echo $i; ?>][thumb]" value="true" class="unique" checked/>
								</div>
							</div>

							<div class="col-xs-6">
								<div class="form-group small"><label><?php _e('Überspringen', 'affiliatetheme-cj'); ?></label>
									<input type="checkbox" name="image[<?php echo $i; ?>][exclude]"  value="true" class="disable-this"/>
								</div>
							</div>
						</div>
						<input type="hidden" name="image[<?php echo $i; ?>][url]" value="<?php echo $image; ?>"/>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<input type="hidden" name="ean" value="<?php echo $ean; ?>"/>
							<input type="hidden" name="currency" value="<?php echo $currency; ?>"/>
							<input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>"/>
							<input type="hidden" name="shop_name" value="<?php echo $shop_name; ?>"/>
							<input type="hidden" name="url" value="<?php echo $url; ?>"/>
							<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce("at_cj_import_wpnonce"); ?>"/>
							<input type="hidden" name="action" value="at_cj_import"/>
							<input type="hidden" name="mass" value="false"/>
							<button type="submit" id="import" name="import" class="single-import-product button button-primary">
								<?php _e('Importieren', 'affiliatetheme-cj'); ?>
							</button>
							<button type="submit" id="tb-close" class="button" onclick="self.parent.tb_remove();return false">
								<?php _e('Schließen', 'affiliatetheme-cj'); ?>
							</button>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php
	}

	exit();
}