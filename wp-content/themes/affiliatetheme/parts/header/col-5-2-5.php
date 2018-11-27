<?php if (has_nav_menu('nav_main') || has_nav_menu('nav_main')) { ?>
	<nav id="navigation" role="navigation" class="<?php echo get_section_layout_class('nav'); ?>">
		<div class="navbar navbar-xcore navbar-5-2-5 <?php if('1' == get_field('design_nav_hover', 'option')) echo 'navbar-hover'; ?>">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="<?php echo esc_url( home_url() ); ?>" title="<?php bloginfo('name'); ?>" class="navbar-brand <?php if(at_get_logo(false)) echo 'navbar-brand-logo'; ?>">
						<?php echo at_get_logo(true, false); ?>
					</a>
				</div>
				<div class="collapse navbar-collapse">
					<?php 
					if(has_nav_menu('nav_main')) {
					wp_nav_menu( 
							array( 
								'menu' => 'main_nav',
								'menu_class' => 'nav navbar-nav navbar-left',
								'theme_location' => 'nav_main',
								'container' => 'false',
								'depth' => '4',
								'walker' => new description_walker()
							)
						); 
					}
					
					if(has_nav_menu('nav_main_second')) {
						wp_nav_menu( 
							array( 
								'menu' => 'nav_main_second',
								'menu_class' => 'nav navbar-nav navbar-right',
								'theme_location' => 'nav_main_second',
								'container' => 'false',
								'depth' => '4',
								'walker' => new description_walker()
							)
						); 
					}
					?>
					
					<?php if(at_header_nav_searchform()) get_template_part('parts/header/nav', 'searchform'); ?>
				</div>
			</div>
		</div>
	</nav>
<?php } else { ?>
	<?php if(is_user_logged_in()) { ?><div class="container"><div class="alert alert-warning" style="margin: 20px 0;"><?php _e('<strong>Hinweis:</strong> Navigation nicht gesetzt!', 'affiliatetheme'); ?></div></div><?php } ?>
<?php } ?>