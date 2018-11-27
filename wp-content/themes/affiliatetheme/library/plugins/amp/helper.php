<?php
/**
 * Hilfsfunktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	amp
 */

/**
 * add amp product support
 */
add_action( 'amp_init', 'at_amp_add_product_cpt' );
function at_amp_add_product_cpt() {
    add_post_type_support( 'product', AMP_QUERY_VAR );
}

/**
 * set amp product template
 */
add_filter( 'amp_post_template_file', 'at_amp_set_product_template', 10, 3 );
function at_amp_set_product_template( $file, $type, $post ) {
    if ( 'single' === $type && 'product' === $post->post_type ) {
        $file = dirname( __FILE__ ) . '/templates/single-product.php';
    }
    return $file;
}

/**
 * add amp css
 */
add_action( 'amp_post_template_css', 'at_amp_product_css_styles' );
function at_amp_product_css_styles( $amp_template ) {
    ?>
	.clearfix{clear:both;}
	body { color: #6f7479; }
	.amp-wp-content { color: #6f7479; }
	.amp-wp-title { color: #101820; margin-bottom: 20px; }
	a, a:visited { color: #c01313; }
	a:hover, a:active, a:focus { color: #c62a2a; }
	.amp-wp-meta, .amp-wp-meta a { color: #6f7479; }
	nav.amp-wp-title-bar { background: #c01313; }
	nav.amp-wp-title-bar div { color: #fff; }
	nav.amp-wp-title-bar a { color: #fff; }
	.product-thumbnail img { max-width: 100%; height: auto; }
	@media (min-width: 480px) {
		.product-thumbnail, .product-buybox { float: left; display: inline-block;}
		.product-thumbnail { width: 30%; margin-right: 5%; }
		.product-buybox { width: 65%; }
		.product-price { margin-top: 20px; }
	}
	@media(max-width: 479px) {
		.product-thumbnail, .product-buybox { display: block; width: auto; }
		.product-thumbnail { text-align: center; margin-right: 0; }
	}
	.product-buybox p { margin: 0; line-height: 1; }
	.product-buybox	.price { margin-bottom: 10px; color: #7ab317; font-size: 2rem; font-weight: 700; }
	.product-buybox .price del { color: #c01313; font-weight: 400; font-size: 0.5em; position: relative; top: -1em;  }
	.product-buybox .price-hint { font-size: 0.7rem; color: #9fa2a5; }
	.product-buybox .btn-buy { margin-top: 10px; padding: 10px 16px; display: block; text-decoration: none; border-radius: 5px; text-align: center; color: #fff; background: #f4a033; }
	.table-details { margin-top: 20px; width: 100%; border: 1px solid #eee; }
	.table-details td { border-bottom: 1px solid #eee; padding: 3px 6px; }
	.table-details tr:last-of-type td { border: none; }
	.buybox-footer .product-buybox { float: none; background: #fafafa; border: 2px solid #eee; display: block; padding: 15px; width: calc( 100% - 34px); }
	.amp-footer{margin-top:60px;text-align:right;}
	.amp-footer ul li{list-style:none;display:inline-block;margin-left:15px;}
    <?php
}