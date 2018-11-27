<?php
$structure = get_field('design_header_structure_col_12', 'option');
if($structure != 'banner') {
	echo '<div class="container">';
	if ("" == get_field('design_header_text', 'option')) { ?>
		<div class="row">
			<div class="col-sm-4">
				<a href="<?php echo esc_url(home_url()) ?>" title="<?php bloginfo('name'); ?>" class="brand"><?php echo at_get_logo(true); ?></a>
			</div>

			<div class="col-sm-8">
				<?php the_field('design_header_textarea', 'option'); ?>
			</div>
		</div>
	<?php } else { ?>
		<a href="<?php echo esc_url(home_url()) ?>" title="<?php bloginfo('name'); ?>"
		   class="brand"><?php echo at_get_logo(true); ?></a>
	<?php }
	echo '</div>';
} else {
	$image = get_field('design_banner', 'option');
	if($image) {
		?>
		<a href="<?php echo esc_url(home_url()) ?>" title="<?php bloginfo('name'); ?>" class="brand-banner">
			<img src="<?php echo $image['url']; ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title']; ?>" class="img-responsive img-banner"/>
		</a>
		<?php
	}
}
?>
	
<?php if (has_nav_menu('nav_main')) { ?>
	<nav id="navigation" role="navigation" class="<?php echo get_section_layout_class('nav'); ?>">
		<div class="navbar navbar-xcore navbar-12 <?php if('1' == get_field('design_nav_hover', 'option')) echo 'navbar-hover'; ?>">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="<?php echo esc_url( home_url() ) ?>" title="<?php bloginfo('name'); ?>" class="navbar-brand visible-xs">
						<?php echo apply_filters('at_set_navigation_brand', get_bloginfo('name')); ?>
					</a>
				</div>
				<div class="collapse navbar-collapse">
					<?php wp_nav_menu( 
						array( 
							'menu' => 'main_nav',
							'menu_class' => 'nav navbar-nav navbar-left',
							'theme_location' => 'nav_main',
							'container' => 'false',
							'depth' => '4',
							'walker' => new description_walker()
						)
					); ?>
					
					<?php if(at_header_nav_searchform()) get_template_part('parts/header/nav', 'searchform'); ?>
				</div>
			</div>
		</div>
	</nav>
<?php } else { ?>
	<?php if(is_user_logged_in()) { ?><div class="container"><div class="alert alert-warning" style="margin: 20px 0;"><?php _e('<strong>Hinweis:</strong> Navigation nicht gesetzt!', 'affiliatetheme'); ?></div></div><?php } ?>
<?php } ?>
