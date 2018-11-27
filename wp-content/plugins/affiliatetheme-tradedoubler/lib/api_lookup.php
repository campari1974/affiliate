<?php
add_action('wp_ajax_at_tradedoubler_lookup', 'at_tradedoubler_lookup');
add_action('wp_ajax_tradedoubler_api_lookup', 'at_tradedoubler_lookup');
function at_tradedoubler_lookup() {
	$api = new Endcore\Api\Tradedoubler();

	$id = $_GET['id'];
	$items = $api->lookupProduct($id);


    if($items['productHeader']['totalHits']) {

        $item = $items['products'][0];
        $ean = '';
        if(isset($item['identifiers']['ean'])){
            $ean = $item['identifiers']['ean'];
        }
        foreach($item['fields'] as $field){
            if(strtolower($field['name']) == 'ean'){
                $ean = $field['value'];
            }
        }
        $offer = $item['offers'][0];
        $prices = $offer['priceHistory'];
        $timestamp_newest = 0;
        $price_arr = $prices[0];
        foreach($prices as $price_tmp){
            if($price_tmp['date'] > $timestamp_newest){
                $price_arr = $price_tmp['price'];
                $timestamp_newest = $price_tmp['date'];
            }
        }
        $id = $offer['id'];
        $title = $item['name'];
        $image = $item['productImage']['url'];
        $description = $item['description'];
        $price = ($price_arr['value'] != '0')?$price_arr['value']: 'kA';
        $shop_id = $offer['feedId'];
        $shop_name = $offer['programName'];
        $url = $offer['productUrl'];
        $currency = $price_arr['currency'];
		$image = is_string($image)?$image:'';
		//var_dump($shop_name);
		//var_dump($description);
		?>
		<div class="container">
			<form action="" id="import-product">
				<div class="row">
					<div class="form-group col-xs-12">
						<label><?php _e('Titel', 'affiliatetheme-tradedoubler'); ?></label>
						<input type="text" id="title" name="title" class="form-control" value="<?php echo esc_html($title); ?>"/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('ID', 'affiliatetheme-tradedoubler'); ?></label>
						<input type="text" id="id" name="id" class="form-control" value="<?php echo $id; ?>" readonly/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('EAN', 'affiliatetheme-tradedoubler'); ?></label>
						<input type="text" id="ean_tmp" name="ean_tmp" class="form-control" value="<?php echo ($ean ? $ean : '-') ; ?>" readonly/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('Artikelnummer', 'affiliatetheme-tradedoubler'); ?></label>
						<input type="text" id="productid" name="productid" class="form-control" value="<?php echo $ean; ?>" readonly/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('Preis', 'affiliatetheme-tradedoubler'); ?></label>
						<input type="text" id="price" name="price" class="form-control" value="<?php echo $price; ?>" readonly/>
					</div>
				</div>

                <div class="row">
                    <div class="form-group col-xs-3">
                        <label><?php _e('Bewertung', 'affiliatetheme-tradedoubler'); ?></label>
                        <?php echo at_get_product_rating_list(); ?>
                    </div>
                </div>

				<?php

				if ('1' == get_option('tradedoubler_import_description')) { ?>
					<h3><?php _e('Beschreibung', 'affiliatetheme-tradedoubler'); ?></h3>
					<textarea name="description" class="widefat product-description" rows="5"><?php echo $description; ?></textarea>
				<?php } ?>


                <?php
                /*
                 * Produkteigenschaften
                 */
                $properties = $item['fields'];
                if(is_array($properties)) {?>
                    <div id="item-attributes">
                        <div id="attributes-headline">
                            <h3 id="item-attributes-headline"><?php _e('Produkteigenschaften anzeigen','affiliatetheme-amazon')?></h3>
                        </div>
                        <div id="attribute-content" class="inside acf-fields -top" style="display: none;">

                            <?php $counter = 0;
                            $selector = new acf_field_field_selector();
                            $fields = $selector->get_selectable_item_fields(null,true);
                            $selectable = $selector->get_items("", $fields);
                            $groups = acf_get_field_groups();
                            foreach($groups as $group) {
                                $hasselectable = false;
                                foreach($selectable as $field) {
                                    if($field['group']['ID'] == $group['ID']) {
                                        $hasselectable = true;
                                    }
                                }
                                if($hasselectable) {
                                    ?>
                                    <div class="acf-field" id="attributes-group-<?php echo $group['key']; ?>">
                                    <h3 id="attributes-group-headline-<?php echo $group['key']; ?>"><?php echo $group['title']; ?></h3>
                                    <?php
                                }

                                foreach($selectable as $field){
                                    if($field['group']['ID'] == $group['ID']) {
                                        ?>

                                        <div class="form-group acf-<?php echo $group['key']?> " style="display: none;">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>
                                                    <input type="text" id="attributes-input-<?php echo $counter ?>" name="<?php echo $field['name'] ?>" value="" class="form-control"/>
                                                </div>

                                                <div class="col-sm-8">
                                                    <label for="attributes-select-<?php echo $counter ?>"><?php _e('Wert aus dem Feed auswählen', 'affiliatetheme-affilinet'); ?></label>
                                                    <select id="attributes-select-<?php echo $counter ?>" class="form-control">
                                                        <option value="">-</option>
                                                        <?php
                                                        foreach ($properties as $property) {
                                                            if ($property['value'] != "") {
                                                                ?>
                                                                <option value="<?php echo $property['value'] ?>"><?php echo $property['name'] . " - " . $property['value'] ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <script type="text/javascript">
                                                var select = document.getElementById('attributes-select-<?php echo $counter?>');
                                                select.onchange = function () {
                                                    var input = document.getElementById('attributes-input-<?php echo $counter?>');
                                                    input.value = this.value;
                                                }
                                                var attdiv = document.getElementById('attributes-group-headline-<?php echo $group['key']; ?>');
                                                attdiv.onclick = function(){
                                                    this.classList.toggle('open');

                                                    var content = document.getElementsByClassName('form-group acf-<?php echo $group['key']?>');

                                                    if(content[0].style.display == 'none') {
                                                        for(var i=0;i<content.length;i++)
                                                            content[i].style.display = 'block';
                                                    } else {
                                                        for(var i=0;i<content.length;i++)
                                                            content[i].style.display = 'none';
                                                    }
                                                }

                                            </script>
                                        </div>
                                        <?php $counter++;
                                    }
                                }

                                if($hasselectable) {
                                    ?>
                                    </div>
                                    <?php
                                }
                            }?>
                        </div>
                    </div>
                    <script type="text/javascript">
                        var attdiv = document.getElementById('attributes-headline');
                        attdiv.onclick = function(){
                            var content = document.getElementById('attribute-content');
                            var headline = document.getElementById('item-attributes-headline');
                            if(content.style.display == 'none'){
                                content.style.display = 'block';
                                headline.innerHTML = '<?php _e('Produkteigenschaften ausblenden','affiliatetheme-affilinet')?>';
                            }else
                            {
                                content.style.display = 'none';
                                headline.innerHTML = '<?php _e('Produkteigenschaften anzeigen','affiliatetheme-affilinet')?>';
                            }
                        }
                    </script>
                <?php } ?>


				<?php
                /*
                 * Taxonomien
                 */
				if (get_products_multiselect_tax_form()) {
					echo '<h3>' . __('Taxonomien', 'affiliatetheme-tradedoubler') . '</h3>' . get_products_multiselect_tax_form(true,array(),$item['fields']);
				}


				if (at_get_existing_products()) {
					echo '<h3>' . __('Existierendes Produkt aktualisieren', 'affiliatetheme-tradedoubler') . '</h3>' . at_get_existing_products(true);
				}


				$image_info = explode('/', $image);
				$image_info = array_pop($image_info);
				$image_info = pathinfo($image_info);
				$image_filename = sanitize_title($title);
				$image_ext = $image_info['extension'];
				$i = 0;
				?>
				<h3><?php _e('Produktbild', 'affiliatetheme-tradedoubler'); ?></h3>
				<div class="row">
					<div class="image col-sm-4" data-item="<?php echo $i; ?>">
						<div class="image-wrapper"><img src="<?php echo $image; ?>" class="img-responsive"/></div>
						<div class="image-info">
							<div class="form-group small">
								<label><?php _e('Bildname', 'affiliatetheme-tradedoubler'); ?></label>
								<input type="text" name="image[<?php echo $i; ?>][filename]" data-url="<?php echo $image; ?>" id="image[<?php echo $i; ?>][filename]" value="<?php echo $image_filename; ?>"/>
								.<?php echo $image_ext; ?>
							</div>

							<div class="form-group small">
								<label><?php _e('ALT-Tag', 'affiliatetheme-tradedoubler'); ?></label>
								<input type="text" name="image[<?php echo $i; ?>][alt]" id="image[<?php echo $i; ?>][alt]" value=""/>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group small"><label><?php _e('Artikelbild', 'affiliatetheme-tradedoubler'); ?></label>
									<input type="checkbox" name="image[<?php echo $i; ?>][thumb]" value="true" class="unique" checked/>
								</div>
							</div>

							<div class="col-xs-6">
								<div class="form-group small"><label><?php _e('Überspringen', 'affiliatetheme-tradedoubler'); ?></label>
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
							<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce("at_tradedoubler_import_wpnonce"); ?>"/>
							<input type="hidden" name="action" value="at_tradedoubler_import"/>
							<input type="hidden" name="mass" value="false"/>
							<button type="submit" id="import" name="import" class="single-import-product button button-primary">
								<?php _e('Importieren', 'affiliatetheme-tradedoubler'); ?>
							</button>
							<button type="submit" id="tb-close" class="button" onclick="self.parent.tb_remove();return false">
								<?php _e('Schließen', 'affiliatetheme-tradedoubler'); ?>
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