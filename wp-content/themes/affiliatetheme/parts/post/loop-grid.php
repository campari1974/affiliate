<?php
/*
 * VARS
 */
global $sidebar, $sidebar_size;
$args = array(
	'class' 		=> 'img-responsive post-thumbnail',
	'sidebar' 		=> (isset($sidebar) ? $sidebar : ''),
	'sidebar_size' 	=> (isset($sidebar_size['sidebar']) ? $sidebar_size['sidebar'] : '')
);
?>
<div class="col-sm-4">
	<article <?php post_class('post-grid'); ?>>
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php echo at_post_thumbnail($post->ID, 'at_thumbnail', $args); ?>
		</a>

		<h2>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_title(); ?>
			</a>
		</h2>

		<div class="clearfix"></div>
	</article>
</div>