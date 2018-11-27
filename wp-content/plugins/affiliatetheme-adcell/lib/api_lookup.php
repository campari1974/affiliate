<?php
use EcAdcell\Item;
use EcAdcell\Search;

add_action('wp_ajax_at_adcell_lookup', 'at_adcell_lookup');
add_action('wp_ajax_adcell_api_lookup', 'at_adcell_lookup');
function at_adcell_lookup() {
    $lookup = new Search();

    $id = $_GET['id'];
    $program = $_GET['program'];
    $item = $lookup->getItemByUniqueId($id);

    if ($item instanceof Item) {
        $productid = $item->getArticleNumber();
        $ean = $item->getEan();
        $title = $item->getName();
        $price = $item->getPrice();
        $currency = $item->getCurrency();
        $url = $item->getUrl();
        $description = $item->getShortDescription();
        $shop_id = $item->getPromotionId();
        $shop_name = at_adcell_get_program_by_id($program);
        ?>
        <div class="container">
            <form action="" id="import-product">
                <div class="row">
                    <div class="form-group col-xs-12">
                        <label><?php _e('Titel', 'affiliatetheme-adcell'); ?></label>
                        <input type="text" id="title" name="title" class="form-control" value="<?php echo $title ?>"/>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('ID', 'affiliatetheme-adcell'); ?></label>
                        <input type="text" id="id" name="id" class="form-control" value="<?php echo $id; ?>" readonly/>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('Artikelnummer', 'affiliatetheme-adcell'); ?></label>
                        <input type="text" id="productid" name="productid" class="form-control" value="<?php echo $productid; ?>" readonly/>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('Bewertung', 'affiliatetheme-adcell'); ?></label>
                        <?php echo at_get_product_rating_list(); ?>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('Anzahl der Bewertungen', 'affiliatetheme-adcell'); ?></label>
                        <input type="text" id="rating_cnt" name="rating_cnt" class="form-control" value="0"/>
                    </div>

                    <div class="form-group col-xs-3">
                        <label><?php _e('Preis', 'affiliatetheme-adcell'); ?></label>
                        <input type="text" id="price" name="price" class="form-control" value="<?php echo $price; ?>" readonly/>
                    </div>
                </div>

                <?php
                /*
                 * Description
                 */
                if ('1' == get_option('adcell_import_description')) { ?>
                    <h3><?php _e('Beschreibung', 'affiliatetheme-adcell'); ?></h3>
                    <textarea name="description" class="widefat product-description" rows="5"><?php echo $description; ?></textarea>
                <?php } ?>

                <?php
                /*
                * Taxonomien
                */
                if (get_products_multiselect_tax_form()) {
                    echo '<h3>' . __('Taxonomien', 'affiliatetheme-adcell') . '</h3>' . get_products_multiselect_tax_form();
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
                if ($item->hasImage()) {
                    $images = array($item->getImage());
                    $i = 1;
                    ?>
                    <h3><?php _e('Produktbild(er)', 'affiliatetheme-adcell'); ?>
                        <small class="alignright"><input type="checkbox" name="selectall" class="select-all"/>
                           <?php _e('Alle Bilder überspringen', 'affiliatetheme-adcell'); ?>
                        </small>
                    </h3>
                    <div class="row product-images">
                        <?php
                        foreach ($images as $image) {
                            $image_info = explode('/', $image);
                            $image_info = array_pop($image_info);
                            $image_info = pathinfo($image_info);
                            $image_filename = sanitize_title($title . '-' . $i);
                            $image_ext = $image_info['extension'];
                            ?>

                            <div class="image col-sm-4" data-item="<?php echo $i; ?>">
                                <div class="image-wrapper">
                                    <img src="<?php echo $image; ?>" class="img-responsive"/>
                                </div>
                                <div class="image-info">
                                    <div class="form-group small">
                                        <label><?php _e('Bildname', 'affiliatetheme-adcell'); ?></label>
                                        <input type="text" name="image[<?php echo $i; ?>][filename]" data-url="<?php echo $image; ?>"  id="image[<?php echo $i; ?>][filename]" value="<?php echo $image_filename; ?>"/>
                                        .<?php echo $image_ext; ?>
                                    </div>

                                    <div class="form-group small">
                                        <label><?php _e('ALT-Tag', 'affiliatetheme-adcell'); ?></label>
                                        <input type="text" name="image[<?php echo $i; ?>][alt]" id="image[<?php echo $i; ?>][alt]" value=""/>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group small">
                                            <label><?php _e('Artikelbild', 'affiliatetheme-adcell'); ?></label>
                                            <input type="checkbox" name="image[<?php echo $i; ?>][thumb]" value="true" class="unique" <?php if ($i == 1) echo 'checked'; ?>/>
                                        </div>
                                    </div>

                                    <div class="col-xs-6">
                                        <div class="form-group small">
                                            <label><?php _e('Überspringen', 'affiliatetheme-adcell'); ?></label>
                                            <input type="checkbox" name="image[<?php echo $i; ?>][exclude]" value="true" class="disable-this"/>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="image[<?php echo $i; ?>][url]" value="<?php echo $image; ?>"/>
                            </div>
                            <?php
                            $i++;
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <input type="hidden" name="ean" value="<?php echo $ean; ?>"/>
                            <input type="hidden" name="currency" value="<?php echo $currency; ?>"/>
                            <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>"/>
                            <input type="hidden" name="shop_name" value="<?php echo $shop_name; ?>"/>
                            <input type="hidden" name="url" value="<?php echo $url; ?>"/>
                            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce("at_adcell_import_wpnonce"); ?>"/>
                            <input type="hidden" name="action" value="at_adcell_import"/>
                            <input type="hidden" name="mass" value="false"/>
                            <button type="submit" id="import" name="import" class="single-import-product button button-primary">
                                <?php _e('Importieren', 'affiliatetheme-adcell'); ?>
                            </button>
                            <button type="submit" id="tb-close" class="button" onclick="self.parent.tb_remove();return false">
                                <?php _e('Schließen', 'affiliatetheme-adcell'); ?>
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