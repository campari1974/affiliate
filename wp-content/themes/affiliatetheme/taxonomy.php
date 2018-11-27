<?php 
get_header(); 

/*
 * VARS
 */
global $grid_col, $query_string, $orderby, $order;
parse_str($query_string, $args);

$queried_object = get_queried_object();
$term_id = $queried_object->term_id;
$taxonomy = get_current_product_tax();
$sidebar = (isset($taxonomy['sidebar']) ? $taxonomy['sidebar'] : 'none');
$sidebar_size = (isset($taxonomy['sidebar_size']) ? $taxonomy['sidebar_size'] : '8_4');
$layout = (isset($taxonomy['layout']) ? $taxonomy['layout'] : 'list');
$userfilter = (isset($taxonomy['userfilter']) ? $taxonomy['userfilter'] : '');
$grid_col = apply_filters('at_product_taxonomy_grid_col', ('none' == $sidebar ? '3' : '4'));

// mobile fallback grid-hover
if(wp_is_mobile() && ($layout == 'grid-hover')) {
	$layout = 'grid';
}

$orderby = (isset($taxonomy['orderby']) ? $taxonomy['orderby'] : '');
$order = (isset($taxonomy['order']) ? $taxonomy['order'] : '');
$posts_per_page = (isset($taxonomy['posts_per_page']) ? $taxonomy['posts_per_page'] : 12);

if($orderby) {
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

if($order) {
	$args['order'] = $order;
}

$args['posts_per_page'] = $posts_per_page;

$args = apply_filters('at_set_product_taxonomy_query', $args, $taxonomy, $term_id);

/*
 * SIDEBAR
 */
$sidebar_size_arr = (!is_array($sidebar_size) ? explode('_', $sidebar_size) : array(0 => '', 1 => ''));
$s_content = (is_array($sidebar_size_arr) ? $sidebar_size_arr[0] : '8');
$s_sidebar = (is_array($sidebar_size_arr) ? $sidebar_size_arr[1] : '4');
if('left' == $sidebar) {
    $s_content .= ' col-sm-push-' . ($sidebar_size_arr[1] ? $sidebar_size_arr[1] : '4');
    $s_sidebar .= ' col-sm-pull-' . ($sidebar_size_arr[0] ? $sidebar_size_arr[0] : '8');
}

$sidebar_size_arr['content'] = $s_content;
$sidebar_size_arr['sidebar'] = $s_sidebar;

/*
 * ACF FIELDS
 */
$taxonomy_headline = get_field('taxonomy_headline', $taxonomy['slug'] . '_' . $term_id);
$taxonomy_image = get_field('taxonomy_image', $taxonomy['slug'] . '_' . $term_id);
$taxonomy_second_description = get_field('taxonomy_second_description', $taxonomy['slug'] . '_' . $term_id);
?>

<div id="main" class="<?php echo get_section_layout_class('content'); ?>">
	<div class="container">
		<div class="row">
            <div class="col-sm-<?php if($sidebar == 'none') : echo '12'; else: echo $sidebar_size_arr['content']; endif; ?>">
				<div id="content">
					<?php
					$headline = ($taxonomy_headline ? $taxonomy_headline : single_cat_title('', false));
					echo apply_filters('at_taxonomy_page_title', '<h1>' . $headline . '</h1>', $taxonomy, $term_id);

					if(!is_paged()) {
						if ($taxonomy_image) {
							echo '<img src="' . $taxonomy_image['url'] . '" width="' . $taxonomy_image['width'] . '" height="' . $taxonomy_image['height'] . '" alt="' . single_cat_title('', false) . ' Logo" class="taximage img-responsive alignleft">';
						}
						if (category_description()) {
							echo category_description() . '<div class="clearfix"></div><hr>';
						}

						echo '<div class="clearfix"></div>';
					}
					
					get_template_part('parts/product/code', 'filter-args');

					query_posts($args);
					if (have_posts()) :
						if('1' == $userfilter)
							get_template_part('parts/product/code', 'filter');

						if('grid' == $layout || 'grid-hover' == $layout) echo '<div class="row">';
						
						while (have_posts()) : the_post();
							get_template_part('parts/product/loop', $layout); 
						endwhile;

						if('grid' == $layout || 'grid-hover' == $layout) echo '</div>';
						echo pagination(3); 
					endif;

					if($taxonomy_second_description && !is_paged()) { echo'<hr>' . $taxonomy_second_description; }
					?>
				</div>
			</div>

            <?php if('left' == $sidebar || 'right' == $sidebar) { ?>
                <div class="col-sm-<?php echo $sidebar_size_arr['sidebar']; ?>">
                    <div id="sidebar">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            <?php } ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
