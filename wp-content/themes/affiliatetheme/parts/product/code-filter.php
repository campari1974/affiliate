<?php
/*
 * VARS
 */
global $layout, $args, $orderby, $order;

if(isset($_GET['orderby'])) {
    $orderby = $_GET['orderby'];
} else {
    switch($orderby) {
        case 'price' :
            if($order == 'asc')
                $orderby = 'price-asc';
            else
                $orderby = 'price-desc';
            break;

        case 'name' :
            if($order == 'asc')
                $orderby = 'a-z';
            else
                $orderby = 'z-a';
            break;
    }
}

if(isset($_GET['order'])) {
    $order = $_GET['order'];
}
?>
<div class="result-filter">
    <div class="row">
        <div class="col-sm-6 hidden-xs">
            <ul class="list-inline">
                <li><span class="result-title"><?php _e('Ansicht:', 'affiliatetheme'); ?></li>
                <li>
                    <a class="btn btn-link <?php echo ($layout == 'grid' ? 'active' : ''); ?>" title="<?php _e('Gridansicht', 'affiliatetheme'); ?>" href="<?php echo requestUriAddGetParams(array('layout' => 'grid')); ?>">
                        <i class="fa fa-th"></i>
                    </a>
                </li>
                <li>
                    <a class="btn btn-link <?php echo ($layout == 'list' ? 'active' : ''); ?>" title="<?php _e('Listenansicht', 'affiliatetheme'); ?>" href="<?php echo requestUriAddGetParams(array('layout' => 'list')); ?>">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>
            </ul>

        </div>
        <div class="col-xs-12 col-sm-6 orderby">
            <select name="orderby" id="orderby" onchange="" class="form-control">
                <option value="date" <?php selected('date', ($orderby ? $orderby : ''), true); ?>><?php _e('Neuheiten', 'affiliatetheme'); ?></option>
                <option value="rating" <?php selected('rating', ($orderby ? $orderby : ''), true); ?>><?php _e('Beliebtheit', 'affiliatetheme'); ?></option>
                <option value="price-asc" <?php selected('price-asc', ($orderby ? $orderby : ''), true); ?>><?php _e('Preis (aufsteigend)', 'affiliatetheme'); ?></option>
                <option value="price-desc" <?php selected('price-desc', ($orderby ? $orderby : ''), true); ?>><?php _e('Preis (absteigend)', 'affiliatetheme'); ?></option>
                <option value="a-z" <?php selected('a-z', ($orderby ? $orderby : ''), true); ?>><?php _e('Name (aufsteigend)', 'affiliatetheme'); ?></option>
                <option value="z-a" <?php selected('z-a', ($orderby ? $orderby : ''), true); ?>><?php _e('Name (absteigend)', 'affiliatetheme'); ?></option>
            </select>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(function(){
        jQuery(".result-filter select#orderby").change(function(){
            window.location = updateURLParameter(window.location.href, 'orderby',this.value);
        });
    });
</script>

<hr>
