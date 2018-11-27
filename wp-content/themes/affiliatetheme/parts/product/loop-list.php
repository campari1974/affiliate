<?php
global $o_list, $product_button_detail_hide, $product_button_buy_hide;
$args = array(
	'class' 		=> 'img-responsive product-img',
);
$product_fakeshop = at_is_fake_product($post->ID);
$product_image_link = get_field('product_image_link', 'options');
if(!$o_list) {
	$hide_detail_button = $product_button_detail_hide;
	$hide_buy_button = $product_button_buy_hide;
} else {
	$hide_detail_button = '';
	$hide_buy_button = '';
}
?>
<div class="thumbnail thumbnail-<?php echo $post->ID ?> product-list">
	<div class="caption">
        <?php
        if($highlight_text = get_product_highlight_text($post->ID))
            echo '<span class="badge-at pull-right">' . $highlight_text . '</span>';
        ?>

		<a title="<?php the_title(); ?>" href="<?php echo (('1' == $product_fakeshop) ? get_product_link($post->ID) : get_permalink()); ?>" class="product-title" <?php echo get_product_link_params($post->ID, $product_fakeshop); ?>>
            <?php the_title(); ?>
        </a>
		
		<hr class="hidden-xs">
		
		<div class="row">
			<div class="col-md-3 col-sm-6">
				<div class="img-list-wrapper">
					<a title="<?php the_title(); ?>" href="<?php echo (('1' == $product_fakeshop || '1' == $product_image_link) ? get_product_link($post->ID) : get_permalink()); ?>" <?php echo get_product_link_params($post->ID, ($product_image_link == '1' ? $product_image_link : $product_fakeshop)); ?>>
						<?php echo at_post_thumbnail($post->ID, 'product_list', $args); ?>
					</a>
				</div>
			</div>
			
			<div class="col-md-3 col-md-push-6 col-sm-6">
				<?php get_template_part('parts/product/code', 'price'); ?>
				<div class="product-rating"><?php echo get_product_rating($post->ID); ?></div>
				<hr class="hidden-xs">

				<?php
				do_action('at_product_list_before_buttons');
				echo get_product_button($post->ID, 0, 'detail', 'btn-block', true, false, $hide_detail_button);
				echo get_product_button($post->ID, 0, 'buy', 'btn-block', true, false, $hide_buy_button);
				do_action('at_product_list_after_buttons');
				?>
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-md-6 col-md-pull-3 hidden-xs">
				<?php get_template_part('parts/product/code', 'details'); ?>
			</div>
		</div>
	</div>
</div>