<?php
global $args, $layout;

// filter
if(isset($_GET['layout'])) {
    $layout = $_GET['layout'];
}

if(isset($_GET['orderby'])) {
    if($_GET['orderby'] == 'a-z') {
        $orderby = 'title';
        $order = 'asc';
    } else if($_GET['orderby'] == 'z-a') {
        $orderby = 'title';
        $order = 'desc';
    } else if($_GET['orderby'] == 'date') {
        $orderby = 'date';
        $order = 'desc';
    } else if($_GET['orderby'] == 'price-asc') {
        $orderby = 'meta_value_num';
        $order = 'asc';
        $args['meta_key'] = 'product_shops_0_price';
    } else if($_GET['orderby'] == 'price-desc') {
        $orderby = 'meta_value_num';
        $order = 'desc';
        $args['meta_key'] = 'product_shops_0_price';
    } else if($_GET['orderby'] == 'rating') {
        $orderby = 'meta_value_num';
        $order = 'desc';
        $args['meta_key'] = 'product_rating';
    }

    $args['orderby'] = $orderby;
    $args['order'] = $order;
}