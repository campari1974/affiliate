<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php wp_title(); ?></title>
		<?php wp_head(); ?>
	</head>
	
	<body <?php body_class(); ?>>
		<a href="#content" class="sr-only sr-only-focusable">Skip to main content</a>
		<div id="<?php echo get_wrapper_id(); ?>">
			<?php 
			if(at_get_topbar())
				get_template_part('parts/topbar/col', '6-6'); 
			?>
			
			<header id="header" class="<?php echo get_section_layout_class('header', true); ?>">
				<?php
				get_template_part('parts/header/col', at_header_structure());
				?>
			</header>
			
			<?php
			do_action( 'at_notices' );

			if(at_teaser_hide() != "1" && !is_search()) {
				global $indicator, $arrows, $interval, $fade, $images;
				$post_id = 0;
				
				if(is_tax() || is_category() || is_tag()) {
					$queried_object = get_queried_object();
					$post_id = $queried_object;
				} else if(is_home() || is_archive()) {
					$post_id = get_option('page_for_posts');
				} else if(is_page() || is_single()) {
					if($post) {
						$post_id = $post->ID;
					}
				}

				if(is_404()) {
					$post_id = 0;
				}

				if($post_id) {
					$indicator = get_field('teaser_indicator', $post_id);
					$arrows = get_field('teaser_arrows', $post_id);
					$fade = get_field('teaser_fade', $post_id);
					$interval = get_field('teaser_interval', $post_id);
					$images = get_field('teaser_image', $post_id);

					get_template_part('parts/teaser/code', 'teaser');
				}
			}

			if (function_exists('yoast_breadcrumb') && ('after_nav' == get_field('design_breadcrumbs_pos', 'option'))) {
				if(yoast_breadcrumb("", "", false)) {
					?>
					<section id="breadcrumbs" class="<?php echo get_section_layout_class('breadcrumbs'); ?>">
						<div class="container">
							<?php
							yoast_breadcrumb('<p>', '</p>');
							?>
						</div>
					</section>
				<?php
				}
			}

			do_action('at_before_content');
			?>