<?php
/*
 * VARS
 */
$product_shop = get_field('product_shops');
$product_external_target = (get_field('product_external_target', 'option') ? get_field('product_external_target', 'option') : '_blank');

if($product_shop) {
    $i=0;
    ?>
    <div class="product-comparebox">
        <p class="h2">
            <?php if(count($product_shop) > 2) { ?>
                <a href="#price-compare" class="pull-right smoothscroll" data-offset="60" title="<?php _e('Zum Preisvergleich', 'affiliatetheme'); ?>">
                    <?php printf(__('Alle (%s) anzeigen', 'affiliatetheme'), count($product_shop)); ?>
                </a>
            <?php } ?>

            <?php _e('Preisvergleich', 'affiliatetheme'); ?>
        </p>

        <table class="table table-product table-compare">
            <?php foreach($product_shop as $item) {
                $shop = (isset($item['shop']) ? $item['shop'] : '');

                if($i > 2)
                    break;
                ?>
                <tr>
                    <td>
                        <a class="shop-link-ext" href="<?php echo get_product_link($post->ID, $i); ?>" target="<?php echo $product_external_target; ?>" rel="nofollow">
                            <?php
                            if(!$shop || $shop == '') {
                                echo ''; // @TODO: Fallback
                            } else {
                                if(has_post_thumbnail($shop->ID)) {
                                    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($shop->ID), 'shop_table' );
                                    ?>
                                    <amp-img src="<?php echo $thumb[0]; ?>" width="<?php echo $thumb[1]; ?>" height="<?php echo $thumb[2]; ?>"></amp-img>
                                    <?php
                                } else {
                                    echo $shop->post_title;
                                }
                            }
                            ?>
                        </a>
                    </td>
                    <td>
                        <div class="product-price">
                            <p class="price"><?php echo get_product_price($post->ID, $i, true, true); ?></p>
                            <?php
                            do_action('at_product_before_price_hint');
                            echo get_product_price_hint($post->ID, $i);
                            do_action('at_product_after_price_hint');
                            ?>
                        </div>
                    </td>
                    <td><?php echo get_product_button($post->ID, $i, 'buy', 'btn-sm btn-block', true); ?></td>
                </tr>
                <?php
                $i++;
            } ?>
        </table>
    </div>

    <?php
}
?>