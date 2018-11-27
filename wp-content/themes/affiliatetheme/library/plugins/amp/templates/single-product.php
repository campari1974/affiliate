<!doctype html>
<html amp>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,minimal-ui">
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,400italic,700,700italic|Open+Sans:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <?php do_action( 'amp_post_template_head', $this ); ?>

    <style amp-custom>
        <?php $this->load_parts( array( 'style' ) ); ?>
        <?php do_action( 'amp_post_template_css', $this ); ?>
    </style>
</head>
<body>
<nav class="amp-wp-title-bar">
    <div>
        <a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
            <?php $site_icon_url = $this->get( 'site_icon_url' ); ?>
            <?php if ( $site_icon_url ) : ?>
                <amp-img src="<?php echo esc_url( $site_icon_url ); ?>" width="32" height="32" class="amp-wp-site-icon"></amp-img>
            <?php endif; ?>
            <?php echo esc_html( $this->get( 'blog_name' ) ); ?>
        </a>
    </div>
</nav>
<div class="amp-wp-content">
    <h1 class="amp-wp-title"><?php echo esc_html( $this->get( 'post_title' ) ); ?></h1>
    <?php
    if(has_post_thumbnail()) {
        echo '<div class="product-thumbnail">';
            $image = get_the_post_thumbnail($post->ID, 'product_list', array('class' => 'img-responsive'));
            $image = str_replace('<img', '<amp-img', $image);
            $image = str_replace('</img', '</amp-img', $image);
            echo $image;
        echo '</div>';
    }

    if(show_price_compare($post->ID, 'top'))
        get_template_part('parts/product/code', 'compare-box-amp');
    else
        get_template_part('parts/product/code', 'buybox');

    echo '<div class="clearfix"></div>';

    get_template_part('parts/product/code', 'details-amp');

    if($this->get( 'post_amp_content' )) {
        echo '<h2> ' . __('Beschreibung', 'affiliatetheme') . '</h2>';
        echo $this->get( 'post_amp_content' );

        if(show_price_compare($post->ID, 'bottom')) {
            get_template_part('parts/product/code', 'compare-amp');
        } else {
            echo '<div class="buybox-footer">';
                get_template_part('parts/product/code', 'buybox');
            echo '</div>';
        }
    }
    ?>

    <div class="amp-footer">
        <?php
        if (has_nav_menu('nav_footer')) {
            echo '<hr>';
            wp_nav_menu(
                array(
                    'menu' => 'footer_nav', /* menu name */
                    'menu_class' => 'list-inline',
                    'theme_location' => 'nav_footer', /* where in the theme it's assigned */
                    'container' => 'false', /* container class */
                    'depth' => '2', /* suppress lower levels for now */
                    'walker' => new description_walker()
                )
            );
        }
        ?>
    </div>
</div>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>
</html>