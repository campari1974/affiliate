<?php
add_action('wp_ajax_at_affilinet_lookup', 'at_affilinet_lookup');
add_action('wp_ajax_affilinet_api_lookup', 'at_affilinet_lookup');
function at_affilinet_lookup() {
	$api = new Endcore\Api\Affilinet();

	$id = $_GET['id'];
	$item = $api->lookupProduct($id);


	if ($item) {
		$item = $item->current();
		$productid = $item->getArticleNumber();
		$ean = $item->getEan();
		$title = $item->getName();
		$price = $item->getPrice();
		$currency = $item->getCurrency();
		$image = $item->getImage();
		$shop_id = $item->getShopId();
		$shop_name = $item->getShopName();
		$url = $item->getUrl();
		$description = $item->getDescription();
		?>
		<div class="container">
			<form action="" id="import-product">
				<div class="row">
					<div class="form-group col-xs-12">
						<label><?php _e('Titel', 'affiliatetheme-affilinet'); ?></label>
						<input type="text" id="title" name="title" class="form-control" value="<?php echo esc_html($title); ?>"/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('ID', 'affiliatetheme-affilinet'); ?></label>
						<input type="text" id="id" name="id" class="form-control" value="<?php echo $id; ?>" readonly/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('EAN', 'affiliatetheme-affilinet'); ?></label>
						<input type="text" id="ean_tmp" name="ean_tmp" class="form-control" value="<?php echo ($ean ? $ean : '-') ; ?>" readonly/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('Artikelnummer', 'affiliatetheme-affilinet'); ?></label>
						<input type="text" id="productid" name="productid" class="form-control" value="<?php echo $productid; ?>" readonly/>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('Bewertung', 'affiliatetheme-affilinet'); ?></label>
						<?php echo at_get_product_rating_list(); ?>
					</div>

					<div class="form-group col-xs-3">
						<label><?php _e('Preis', 'affiliatetheme-affilinet'); ?></label>
						<input type="text" id="price" name="price" class="form-control" value="<?php echo $price; ?>" readonly/>
					</div>
				</div>

				<?php
				/*
				 * Description
				 */
				if ('1' == get_option('affilinet_import_description')) { ?>
					<h3><?php _e('Beschreibung', 'affiliatetheme-affilinet'); ?></h3>
					<textarea name="description" class="widefat product-description" rows="5"><?php echo $description; ?></textarea>
				<?php
				}

                /*
                 * Produkteigenschaften
                 */
                $properties = $item->getProperties();
                if(is_array($properties)) { ?>
                    <h3><?php _e('Produkteigenschaften', 'affiliatetheme-affilinet'); ?></h3>
                    <div id="item-attributes">
                        <div id="attributes-headline">
                            <h3 id="item-attributes-headline"><?php _e('Produkteigenschaften anzeigen','affiliatetheme-affilinet')?></h3>
                        </div>
                        <div id="attribute-content" class="inside acf-fields -top" style="display: none;">
                            <?php
                            $counter = 0;
                            $selector = new acf_field_field_selector();
                            $fields = $selector->get_selectable_item_fields(null,true);
                            $selectable = $selector->get_items("", $fields);
                            $groups = acf_get_field_groups(array('post_type' => 'product'));

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
                                                            if ($property->PropertyValue != "") {
                                                                ?>
                                                                <option value="<?php echo $property->PropertyValue ?>"><?php echo $property->PropertyName . " - " . $property->PropertyValue ?></option>
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
                            }
                            ?>
                        </div>
                    </div>
                    <script type="text/javascript">
                        var attdiv = document.getElementById('attributes-headline');
                        attdiv.onclick = function(){
                            var content = document.getElementById('attribute-content');
                            var headline = document.getElementById('item-attributes-headline');
                            if(content.style.display == 'none') {
                                content.style.display = 'block';
                                headline.innerHTML = '<?php _e('Produkteigenschaften ausblenden','affiliatetheme-affilinet'); ?>';
                                headline.classList.toggle('open');
                            } else {
                                content.style.display = 'none';
                                headline.innerHTML = '<?php _e('Produkteigenschaften anzeigen','affiliatetheme-affilinet'); ?>';
                                headline.classList.toggle('open');
                            }
                        }
                    </script>
                <?php
                }

				/*
				* Taxonomien
				*/
				if (get_products_multiselect_tax_form()) {
					echo '<h3>' . __('Taxonomien', 'affiliatetheme-affilinet') . '</h3>' . get_products_multiselect_tax_form(true, array(), $properties);
				}

				/*
                 * Existierende Produkte
                 */
				if (at_get_existing_products()) {
					echo '<h3>' . __('Existierendes Produkt aktualisieren', 'affiliatetheme-affilinet') . '</h3>' . at_get_existing_products(true);
				}

				/*
				* Product Image
				*/
				$image_info = explode('/', $image);
				$image_info = array_pop($image_info);
				$image_info = pathinfo($image_info);
				$image_filename = sanitize_title($title);
				$image_ext = $image_info['extension'];
				$i = 0;
				?>
				<h3><?php _e('Produktbild', 'affiliatetheme-affilinet'); ?></h3>
				<div class="row">
					<div class="image col-sm-4" data-item="<?php echo $i; ?>">
						<div class="image-wrapper"><img src="<?php echo $image; ?>" class="img-responsive"/></div>
						<div class="image-info">
							<div class="form-group small">
								<label><?php _e('Bildname', 'affiliatetheme-affilinet'); ?></label>
								<input type="text" name="image[<?php echo $i; ?>][filename]" data-url="<?php echo $image; ?>" id="image[<?php echo $i; ?>][filename]" value="<?php echo $image_filename; ?>"/>
								.<?php echo $image_ext; ?>
							</div>

							<div class="form-group small">
								<label><?php _e('ALT-Tag', 'affiliatetheme-affilinet'); ?></label>
								<input type="text" name="image[<?php echo $i; ?>][alt]" id="image[<?php echo $i; ?>][alt]" value=""/>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group small"><label><?php _e('Artikelbild', 'affiliatetheme-affilinet'); ?></label>
									<input type="checkbox" name="image[<?php echo $i; ?>][thumb]" value="true" class="unique" checked/>
								</div>
							</div>

							<div class="col-xs-6">
								<div class="form-group small"><label><?php _e('Überspringen', 'affiliatetheme-affilinet'); ?></label>
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
							<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce("at_affilinet_import_wpnonce"); ?>"/>
							<input type="hidden" name="action" value="at_affilinet_import"/>
							<input type="hidden" name="mass" value="false"/>
							<button type="submit" id="import" name="import" class="single-import-product button button-primary">
								<?php _e('Importieren', 'affiliatetheme-affilinet'); ?>
							</button>
							<button type="submit" id="tb-close" class="button" onclick="self.parent.tb_remove();return false">
								<?php _e('Schließen', 'affiliatetheme-affilinet'); ?>
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