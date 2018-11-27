<?php
add_action('wp_ajax_belboon_api_lookup', 'at_belboon_lookup');
add_action('wp_ajax_at_belboon_lookup', 'at_belboon_lookup');
function at_belboon_lookup() {
    $api = new Endcore\Belboon\Belboon();

    $id = $_GET['id'];
    $items = $api->getProductById($id);


    if ($items) {
        $item = $items->current();
        $html = '';

        $productid = $item->getArticleNumber();
        $ean = $item->getEan();
        $title = $item->getName();
        $price = $item->getPrice();
        $price_old = $item->getOldprice();
        $currency = $item->getCurrency();
        $image = $item->getBigImage();
        $shop_id = $item->getShopId();
        $shop_name = $item->getShopName();
        $url = $item->getUrl();
        $description = $item->getDescription();
        ?>
        <div class="container">
            <form action="" id="import-product">
                <div class="row">
                    <div class="form-group col-xs-12">
                        <label><?php _e('Titel', 'affiliatetheme-belboon'); ?></label>
                        <input type="text" id="title" name="title" class="form-control" value="<?php echo esc_html($title); ?>"/>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('EAN', 'affiliatetheme-belboon'); ?></label>
                        <input type="text" id="ean" name="ean" class="form-control" value="<?php echo $ean; ?>" readonly/>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('ID', 'affiliatetheme-belboon'); ?></label>
                        <input type="text" id="id" name="id" class="form-control" value="<?php echo $id; ?>" readonly/>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('Artikelnummer', 'affiliatetheme-belboon'); ?></label>
                        <input type="text" id="productid" name="productid" class="form-control" value="<?php echo $productid; ?>" readonly/>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('Bewertung', 'affiliatetheme-belboon'); ?></label>
                        <?php echo at_get_product_rating_list(); ?>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('Anzahl der Bewertungen', 'affiliatetheme-belboon'); ?></label>
                        <input type="text" id="rating_cnt" name="rating_cnt" class="form-control" value="1"/>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('Preis', 'affiliatetheme-belboon'); ?></label>
                        <input type="text" id="price" name="price" class="form-control" value="<?php echo $price; ?>" readonly/>
                    </div>
                </div>

                <?php
                /*
                 * Description
                 */
                if ('1' == get_option('belboon_import_description')) { ?>
                    <h3><?php _e('Beschreibung', 'affiliatetheme-belboon'); ?></h3>
                    <textarea name="description" class="widefat product-description" rows="5"><?php echo $description; ?></textarea>
                <?php } ?>

                <?php
                /*
                * Taxonomien
                */
                if (get_products_multiselect_tax_form()) {
                    echo '<h3>' . __('Taxonomien', 'affiliatetheme-belboon') . '</h3>' . get_products_multiselect_tax_form();
                }

                /*
                 * Existrierende Produkte
                 */
                if (at_get_existing_products()) {
                    echo '<h3>' . __('Existierendes Produkt aktualisieren', 'affiliatetheme-belboon') . '</h3>' . at_get_existing_products(true);
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

                <h3><?php _e('Produktbild', 'affiliatetheme-belboon'); ?></h3>
                <div class="row">
                    <div class="image col-sm-4" data-item="<?php echo $i; ?>">
                        <div class="image-wrapper"><img src="<?php echo $image; ?>" class="img-responsive"/></div>
                        <div class="image-info">
                            <div class="form-group small">
                                <label><?php _e('Bildname', 'affiliatetheme-belboon'); ?></label>
                                <input type="text" name="image[<?php echo $i; ?>][filename]" data-url="<?php echo $image; ?>" id="image[<?php echo $i; ?>][filename]" value="<?php echo $image_filename; ?>"/>
                                .<?php echo $image_ext; ?>
                            </div>

                            <div class="form-group small">
                                <label><?php _e('ALT-Tag', 'affiliatetheme-belboon'); ?></label>
                                <input type="text" name="image[<?php echo $i; ?>][alt]" id="image[<?php echo $i; ?>][alt]" value=""/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group small"><label><?php _e('Artikelbild', 'affiliatetheme-belboon'); ?></label>
                                    <input type="checkbox" name="image[<?php echo $i; ?>][thumb]" value="true" class="unique" checked/>
                                </div>
                            </div>

                            <div class="col-xs-6">
                                <div class="form-group small"><label><?php _e('Überspringen', 'affiliatetheme-belboon'); ?></label>
                                    <input type="checkbox" name="image[<?php echo $i; ?>][exclude]" value="true" class="disable-this"/>
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
                            <input type="hidden" name="shop_id" value="<?php echo md5($shop_name); ?>"/>
                            <input type="hidden" name="shop_name" value="<?php echo $shop_name; ?>"/>
                            <input type="hidden" name="price_old" value="<?php echo $price_old; ?>"/>
                            <input type="hidden" name="url" value="<?php echo $url; ?>"/>
                            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce("at_belboon_import_wpnonce"); ?>"/>
                            <input type="hidden" name="action" value="belboon_api_import"/>
                            <input type="hidden" name="mass" value="false"/>
                            <button type="submit" id="import" name="import" class="single-import-product button button-primary">
                                <?php _e('Importieren', 'affiliatetheme-belboon'); ?>
                            </button>
                            <button type="submit" id="tb-close" class="button" onclick="self.parent.tb_remove();return false">
                                <?php _e('Schließen', 'affiliatetheme-belboon'); ?>
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