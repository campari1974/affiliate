<?php
/*
 * Template Name: Filter
 */
get_header();
global $grid_col, $layout;

/*
 * VARS
 */
$sidebar = at_get_sidebar('product', 'filter');
$sidebar_size = at_get_sidebar_size('product', 'filter');
$layout = (get_field('product_filter_layout', 'option') ? get_field('product_filter_layout', 'option') : 'list');
$posts_per_page = (get_field('product_filter_posts_per_page', 'option') ? get_field('product_filter_posts_per_page', 'option') : '');
$orderby = (get_field('product_filter_orderby', 'option') ? get_field('product_filter_orderby', 'option') : '');
$order = (get_field('product_filter_order', 'option') ? get_field('product_filter_order', 'option') : '');
$grid_col = apply_filters('at_product_filter_grid_col', ('none' == $sidebar ? '3' : '4'));

/*
 * FILTER ARGS
 */
$args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args['post_type'] = 'product';

if($_GET) {
	get_template_part('parts/product/code', 'filter-args');

	// args
	foreach($_GET as $key => $val) {
		if (!$val || $key == 'layout' || $key == 'orderby') continue;

		if(is_array($val)) {
			$val = $val[0];
		}

		if (taxonomy_exists($key)) {
			/*
             * Taxonomy
             */
			$args['tax_query'][] = array(
				'taxonomy' => $key,
				'field' => 'slug',
				'terms' => array($val),
			);

		} else if ($key == 'product_rating') {
			$value = explode(',',$val);
			$args['meta_query'][] = array(
				'key'			=> $key,
				'value'			=> $value,
				'compare'		=> 'BETWEEN',
				'type'			=> 'NUMERIC'
			);
		} else if ($key == 'price') {
			$value = explode(',',$val);
			$args['meta_query'][] = array(
				'key'			=> 'product_shops_0_price',
				'value'			=> $value,
				'compare'		=> 'BETWEEN',
				'type'			=> 'NUMERIC'
			);
		} else {
			/*
			 * Customfield
			 */
			$value = explode(',',$val);

			if(is_array($value) && count($value) > 1) {
				$args['meta_query'][] = array(
					'key'			=> $key,
					'value'			=> $value,
					'compare'		=> 'BETWEEN',
					'type'			=> 'NUMERIC'
				);
			} else {
				$args['meta_query'][] = array(
					'key'			=> $key,
					'value'			=> $val,
					'compare'		=> 'LIKE'
				);
			}
		}
	}
}

if($posts_per_page) {
	$args['posts_per_page'] = $posts_per_page;
}

if($orderby && !isset($_GET['orderby'])) {
	if($orderby == 'price') {
		$args['meta_key'] = 'product_shops_0_price';
		$args['orderby'] = 'meta_value_num';
	} else if($orderby == 'rating') {
		$args['meta_key'] = 'product_rating';
		$args['orderby'] = 'meta_value_num';
	} else {
		$args['orderby'] = $orderby;
	}
}

if($order && !isset($_GET['orderby'])) {
	$args['order'] = $order;
}

$args = apply_filters('at_set_product_filter_query', $args);
?>

<div id="main" class="<?php echo get_section_layout_class('content'); ?>">
	<div class="container">
		<div class="row">
			<div class="col-sm-<?php if($sidebar == 'none') : echo '12'; else: echo $sidebar_size['content']; endif; ?>">
				<div id="content">
					<?php $products = new WP_Query($args); ?>

					<h1><?php printf( __( 'Ihre Suche ergab <span class="highlight">%s</span> Treffer', 'affiliatetheme' ), $products->found_posts ); ?></h1>

					<?php
					if(at_get_social('page') && ('top' == at_get_social_pos('page') || 'both' == at_get_social_pos('page')))
						get_template_part('parts/stuff/code', 'social');

					if ($products->have_posts()) :
						global $o_list;
						$o_list = true;
						
						if('1' == get_field('product_filter_userfilter', 'option'))
							get_template_part('parts/product/code', 'filter');

						if('grid' == $layout) echo '<div class="row">';

						while ($products->have_posts()) : $products->the_post();
							get_template_part('parts/product/loop', $layout);
						endwhile;

						if('grid' == $layout) echo '</div>';
						echo pagination(3);
					else: ?>
						<p><?php _e('Es wurden keine Produkte gefunden.', 'affiliatetheme'); ?></p>
					<?php endif;

					if(at_get_social('page') && ('bottom' == at_get_social_pos('page') || 'both' == at_get_social_pos('page')))
						get_template_part('parts/stuff/code', 'social');
					?>
				</div>
			</div>

			<?php if('left' == $sidebar || 'right' == $sidebar) { ?>
				<div class="col-sm-<?php echo $sidebar_size['sidebar']; ?>">
					<div id="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
