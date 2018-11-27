<?php
$args = array(
	'class' 		=> 'img-responsive product-img',
);
$product_fakeshop = get_field('product_fakeshop', 'option');
$permalink = get_permalink($post->ID);

if(get_post_type($post->ID) == 'product' && '1' == $product_fakeshop) {
	$permalink = get_product_link($post->ID);
}
?>
<div class="thumbnail product-list">
	<div class="caption">
        <a title="<?php the_title(); ?>" href="<?php echo $permalink; ?>" class="product-title" <?php echo get_product_link_params($post->ID, $product_fakeshop); ?>>
            <?php the_title(); ?>
        </a>
		
		<hr class="hidden-xs">

		<p class="post-meta">
			<span class="post-meta-author">
				<?php echo __('Gefunden in', 'affiliatetheme') . ' <strong>' . at_post_type_label(get_post_type($post->ID)) . '</strong>'; ?>
			</span>
		</p>

		<div class="row">
			<div class="col-md-3 col-sm-6">
				<div class="img-list-wrapper">
					<a title="<?php the_title(); ?>" href="<?php echo $permalink; ?>" class="product-title" <?php echo get_product_link_params($post->ID, $product_fakeshop); ?>>
						<?php echo at_post_thumbnail($post->ID, 'at_thumbnail', $args); ?>
					</a>
				</div>
			</div>

			<?php the_excerpt() ?>
		</div>
	</div>
</div>