<?php
global $grid_col;
?>
<div class="col-xs-12 col-sm-6 col-sm-4 col-lg-<?php echo (("" != $grid_col) ? $grid_col : '3'); ?>">
    <div class="thumbnail product-grid">
        <div class="caption">
            <div class="img-grid-wrapper">
                <a title="<?php the_title(); ?>" href="<?php echo get_permalink(); ?>">
                    <?php echo at_post_thumbnail($post->ID, 'product_grid', array('class' => 'img-responsive product-img')); ?>
                </a>
            </div>
            <a title="<?php the_title(); ?>" href="<?php echo get_permalink(); ?>" class="product-title"><?php the_title(); ?></a>
        </div>
    </div>
</div>