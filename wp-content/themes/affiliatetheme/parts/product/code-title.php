<h1 class="product-title"><?php the_title(); ?></h1>

<?php if(get_product_rating($post->ID)) { ?>
    <div class="product-rating">
        <?php
        echo get_product_rating($post->ID);
        if(get_product_rating_cnt($post->ID)) {
            echo '<small>(' . get_product_rating_cnt($post->ID) . ')</small>';
        }
        ?>
    </div>
<?php } ?>