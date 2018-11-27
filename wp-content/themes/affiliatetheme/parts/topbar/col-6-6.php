<section id="topbar" class="<?php echo get_section_layout_class('topbar'); ?>">
	<div class="container">
		<div class="row">
			<?php if(at_topbar_structure() == 'nl_tr') { ?>
				<div class="col-sm-6">
					<?php 
					if (has_nav_menu('nav_topbar')) { 
						wp_nav_menu( 
							array( 
								'menu' => 'topbar_nav',
								'menu_class' => 'list-inline',
								'theme_location' => 'nav_topbar',
								'container' => 'false',
								'depth' => '1',
								'walker' => new description_walker()
							)
						); 
					}
					?>
				</div>
			<?php } ?>
			
			<div class="col-sm-6">
				<?php 
				if(get_field('design_topbar_text', 'option')) 
					echo '<p>'.get_field('design_topbar_text', 'option').'</p>';
				?>
			</div>
			
			<?php if(at_topbar_structure() == 'tl_nr') { ?>
				<div class="col-sm-6">
					<?php 
					if (has_nav_menu('nav_topbar')) { 
						wp_nav_menu( 
							array( 
								'menu' => 'topbar_nav',
								'menu_class' => 'list-inline pull-right',
								'theme_location' => 'nav_topbar',
								'container' => 'false',
								'depth' => '1',
								'walker' => new description_walker()
							)
						); 
					}
					?>
				</div>
			<?php } ?>
		</div>
	</div>
</section>