<?php
/**
 * CJ API - Diverse Hilfsfunktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @updated     2016/08/16
 */

if ( ! function_exists( 'cj_array_insert' ) ) {
    /**
     * cj_array_insert
     * @deprecated since 1.1.4
     *
     */
    function cj_array_insert(&$array, $position, $insert) {
        if (!is_array($array))
            return;

        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }
}

if ( ! function_exists( 'at_cj_array_insert' ) ) {
    /**
     * cj_array_insert
     *
     * Array helper
     * @param   array $array
     * @param   int $position
     * @param   int $insert
     * @return  -
     */
    function at_cj_array_insert(&$array, $position, $insert) {
        if (!is_array($array))
            return;

        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }
}

if ( ! function_exists( 'at_cj_add_as_portal' ) ) {
    /**
     * at_cj_add_as_portal
     *
     * Add CJ Affiliate to Product Portal Dropdown
     */
    add_filter('at_add_product_portal', 'at_cj_add_as_portal', 10, 2);
    function at_cj_add_as_portal($choices)
    {
        $choices['cj'] = __('CJ Affiliate', 'affiliatetheme-cj');
        return $choices;
    }
}

if ( ! function_exists( 'at_cj_add_field_portal_id' ) ) {
    /**
     * at_cj_add_field_portal_id
     *
     * Add CJ Affiliate Fields to Products
     */
    add_filter('at_add_product_fields', 'at_cj_add_field_portal_id', 10, 2);
    function at_cj_add_field_portal_id($fields) {
        $new_field[] = array(
            'key' => 'field_59369c3676420',
            'label' => __('CJ ID', 'affiliatetheme-cj'),
            'name' => 'cj_id',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_553b83de246bb',
                        'operator' => '==',
                        'value' => 'cj',
                    ),
                ),
            ),
            'wrapper' => array(
                'width' => 25,
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
            'readonly' => 0,
            'disabled' => 0,
        );

        at_cj_array_insert($fields['fields'][4]['sub_fields'], 7, $new_field);
        return $fields;
    }
}

if ( ! function_exists( 'at_cj_overwrite_product_button_short_text' ) ) {
    /**
     * at_cj_overwrite_product_button_short_text
     *
     * Overwrite Product Button Text (short)
     */
    add_filter('at_product_api_button_short_text', 'at_cj_overwrite_product_button_short_text', 10, 5);
    function at_cj_overwrite_product_button_short_text($var = '', $product_portal = '', $product_shop = '', $pos = '', $short = false) {
        if ('cj' == $product_portal && 'buy' == $pos) {
            $var = (get_option('cj_buy_short_button') ? get_option('cj_buy_short_button') : __('Kaufen', 'affiliatetheme-cj'));

            if ($product_shop) {
                $var = sprintf($var, $product_shop->post_title);
            }
        }

        return $var;
    }
}

if ( ! function_exists( 'at_cj_overwrite_product_button_text' ) ) {
    /**
     * at_cj_overwrite_product_button_text
     *
     * Overwrite Product Button Text
     */
    add_filter('at_product_api_button_text', 'at_cj_overwrite_product_button_text', 10, 5);
    function at_cj_overwrite_product_button_text($var = '', $product_portal = '', $product_shop = '', $pos = '', $short = false) {
        if ('cj' == $product_portal && 'buy' == $pos) {
            $var = (get_option('cj_buy_button') ? get_option('cj_buy_button') : __('Jetzt bei %s kaufen', 'affiliatetheme-cj'));

            if ($product_shop) {
                $var = sprintf($var, $product_shop->post_title);
            }
        }

        return $var;
    }
}

if ( ! function_exists( 'at_cj_add_subid_to_links' ) ) {
    /**
     * at_cj_add_subid_to_links
     *
     * Add Sub-ID to outgoing Links
     */
    add_filter('at_get_product_link', 'at_cj_add_subid_to_links', 10, 7);
    function at_cj_add_subid_to_links($product_link, $post_id, $shop_id, $clean, $product_shop, $product_url, $product_cloak) {
        $product_portal = (isset($product_shop[$shop_id]['portal']) ? $product_shop[$shop_id]['portal'] : '');

        if ($product_portal == 'cj') {
            if ($product_cloak && false == $clean) {
                return $product_link;
            }

            $cj_subid = apply_filters('at_cj_subid', get_option('cj_subid'), $product_link, $post_id, $shop_id);

            if ($cj_subid) {
                $product_link = $product_link . '&subid=' . $cj_subid;
            }
        }

        return $product_link;
    }
}

if ( ! function_exists( 'at_cj_compare_box' ) ) {
    /**
     * at_cj_compare_box
     *
     * Add Meta-Box to Product Page
     */
    add_action('add_meta_boxes', 'at_cj_compare_box');
    function at_cj_compare_box() {
        add_meta_box(
            'cj_price_compare',
            '<span class="dashicons dashicons-search"></span> ' . __('CJ Affiliate Preisvergleich', 'affiliatetheme-cj'),
            'at_cj_compare_box_callback',
            'product'
        );
    }
}

if ( ! function_exists( 'at_cj_compare_box_callback' ) ) {
    /**
     * at_cj_compare_box_callback
     *
     * Add Meta-Box Content
     */
    function at_cj_compare_box_callback($post) {
        $ean = get_post_meta($post->ID, 'product_ean', true);
        ?>

        <div id="at-import-page" class="at-import-page-cj" data-url="<?php echo admin_url(); ?>" data-import-nonce="<?php echo wp_create_nonce("at_cj_import_wpnonce"); ?>">
            <div class="alert alert-info api-alert">
                <span class="dashicons dashicons-megaphone"></span>
                <p><?php _e('Du kannst mit Hilfe des Preisvergleiches weitere Preise aus verschiedenen Shops zu diesem Produkt hinzufügen. Suche entweder nach der EAN oder einem Keyword und importiere weitere Preise. <br>
                Die neuen Preise werden sofort im oberen Feld hinzugefügt. Bitte speichere das Produkt wenn du fertig bist.', 'affiliatetheme-cj'); ?></p>
            </div>

            <div class="form-container">
                <div class="form-group">
                    <label for="scompare_ean"><?php _e('EAN', 'affiliatetheme-cj'); ?></label>
                    <input type="text" name="cj_compare_ean" id="cj_compare_ean" value="<?php echo $ean; ?>">
                </div>

                <div class="form-group">
                    <label for="compare_query"><?php _e('Keyword', 'affiliatetheme-cj'); ?></label>
                    <input type="text" name="cj_compare_query" id="cj_compare_query">
                </div>

                <a href="#"
                   class="acf-button blue button cj-price-compare"><?php _e('Preisvergleich ausführen', 'affiliatetheme-cj'); ?></a>
            </div>
        </div>

        &nbsp;

        <div id="at-import-window">
            <table class="wp-list-table widefat fixed products">
                <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Alle auswählen', 'affiliatetheme-cj'); ?></label
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th scope="col" id="productid" class="manage-column column-productid">
                        <span><?php _e('Artikelnummer', 'affiliatetheme-cj'); ?></span>
                    </th>
                    <th scope="col" id="ean" class="manage-column column-ean">
                        <span><?php _e('EAN', 'affiliatetheme-cj'); ?></span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title">
                        <span><?php _e('Name', 'affiliatetheme-cj'); ?></span>
                    </th>
                    <th scope="col" id="shop" class="manage-column column-title">
                        <span><?php _e('Shop', 'affiliatetheme-cj'); ?></span>
                    </th>
                    <th scope="col" id="price" class="manage-column column-price">
                        <span><?php _e('Preis', 'affiliatetheme-cj'); ?></span>
                    </th>
                    <th scope="col" id="actions" class="manage-column column-action" style="">
                        <span><?php _e('Aktion', 'affiliatetheme-cj'); ?></span>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <a href="#" class="mass-import button button-primary"><?php _e('Ausgewählte Produkte importieren', 'affiliatetheme-cj'); ?></a>
                    </td>
                </tr>
                </tfoot>
                <tbody id="resultscj"></tbody>
            </table>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                // cjsearchAction
                jQuery('.at-import-page-cj').bind('keydown', function (event) {
                    if (event.keyCode == 13) {
                        cjsearchAction();
                        event.preventDefault();
                    }
                });
                jQuery('.at-import-page-cj .cj-price-compare').click(function (event) {
                    cjsearchAction();
                    event.preventDefault();
                });

                // cjQuickImportAction
                jQuery('.cj-quick-import').live('click', function (event) {
                    var id = jQuery(this).attr('sku');
                    var shopid = jQuery(this).attr('adid');
                    cjQuickImportAction(shopid,id);

                    event.preventDefault();
                });

                // CjMassImportAction
                jQuery('.at-import-page-cj .mass-import').live('click', function (event) {
                    CjMassImportAction(this);

                    event.preventDefault();
                });
            });

            var cjsearchAction = function () {
                var target = jQuery('.at-import-page-cj .cj-price-compare');
                var ean = jQuery('.at-import-page-cj #cj_compare_ean').val();
                var query = jQuery('.at-import-page-cj #cj_compare_query').val();
                var action = (query.length < 3)?'at_cj_search_ean':'at_cj_search';
                ean = (query.length <3)?ean:'';
                var html = '';

                jQuery(target).append(' <i class="fa fa-circle-o-notch fa-spin"></i>').attr('disabled', true).addClass('noevent');

                jQuery.ajax({
                    url: ajaxurl,
                    dataType: 'json',
                    type: 'GET',
                    data: {action: action, ean: ean, query: query, q:query}
                }).done(function (data) {

                    if (data['items']) {
                        for (var x in data['items']) {
                            if (data['items'][x].exists != "false") {
                                html += '<tr class="item success" sku="'+data['items'][x].productid+'" shop="'+data['items'][x].shop+'">';
                                html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-'+data['items'][x].id+' name="item[]" sku="'+data['items'][x].productid+'" shop="'+data['items'][x].shop+'" disabled="disabled"></th>';
                            }else {
                                html += '<tr class="item" sku="'+data['items'][x].productid+'" shop="'+data['items'][x].shop+'">';
                                html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-'+data['items'][x].id+' name="item[]" sku="'+data['items'][x].productid+'" shop="'+data['items'][x].shop+'"></th>';
                            }
                            html += '<td class="productid">' + data['items'][x].productid + '</td>';
                            html += '<td class="ean">' + (data['items'][x].sku ? data['items'][x].sku : '-') + '</td>';
                            html += '<td class="title"><a href="' + data['items'][x].url + '" target="_blank">' + data['items'][x].name + '</a></td>';
                            html += '<td class="shop">' + data['items'][x].shop +"\n"+data['items'][x].shopname+ '</td>';
                            html += '<td class="price">' + data['items'][x].price + '</td>';
                            if (data['items'][x].exists != "false") {
                                html += '<td class="action"></td>';
                            } else {
                                html += '<td class="action"><a href="#" title="Quickimport" class="cj-quick-import" adid="'+data['items'][x].shop+'"sku="'+data['items'][x].productid+'"><i class="fa fa-bolt"></i></a></td>';
                            }
                            html += '</tr>';
                        }
                    } else {
                        html += '<tr><td colspan="5"><?php _e('Es wurde kein Produkt gefunden', 'affiliatetheme-cj'); ?></td></tr>';
                    }
                }).always(function () {
                    jQuery(target).attr('disabled', false).removeClass('noevent').find('i').remove();
                    jQuery('#at-import-window tbody#resultscj').html(html);
                });
            }

            var cjQuickImportAction = function (shopid, id, mass, i, max_items) {
                mass = mass || false;
                max_items = max_items || "0";
                i = i || "1";
                console.log("quickimport");
                console.log(shopid);
                console.log(id);
                var target = jQuery('#results .item[sku='+id+'][adid='+shopid+']').find(".action a.cj-quick-import");
                var ajax_loader = jQuery('.at-ajax-loader');
                var post_id = '<?php echo $post->ID; ?>';
                var nonce = jQuery('.at-import-page-cj').attr('data-import-nonce');

                jQuery(target).append(' <i class="fa fa-circle-o-notch fa-spin"></i>').addClass('noevent');

                jQuery.ajaxQueue({
                    url: ajaxurl,
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        action: 'at_cj_import',
                        id: id,
                        ex_page_id: post_id,
                        func: 'quick-import',
                        '_wpnonce': nonce
                    },
                    success: function (data) {
                        jQuery(target).find('i').remove();

                        if (data['rmessage']['success'] == "false") {
                            jQuery(target).after('<div class="error">' + data['rmessage']['reason'] + '</div>');
                            jQuery(target).append(' <i class="fa fa-exclamation-triangle"></i>').attr('disabled', true);
                        } else if (data['rmessage']['success'] == "true") {
                            jQuery(target).hide();
                            console.log("quickimportresult");
                            console.log(shopid);
                            console.log(id);
                            jQuery('body table.products tr[sku='+id+'][shop='+shopid+']').addClass('success');
                            jQuery('body table.products tr[sku='+id+'][shop='+shopid+'] .check-column input[type=checkbox]').attr('disabled', 'disabled');
                            jQuery('body table.products tr[sku='+id+'][shop='+shopid+'] .action i').removeClass('fa-plus-circle').addClass('fa-check').closest('a').removeClass('quick-import');
                        }
                    }
                });
            };

            var CjMassImportAction = function (target) {
                var max_items = jQuery('#results .item:not(".success") .check-column input:checkbox:checked').length;
                var i = 1;

                jQuery('#resultscj .item:not(".success") .check-column input:checkbox:checked').each(function () {
                    var id = jQuery(this).attr('sku');
                    var shopid = jQuery(this).attr('shop');
                    cjQuickImportAction(shopid, id, true, i, max_items);
                    i++;
                });
            };

            // jQuery Queue
            (function ($) {
                var ajaxQueue = $({});
                $.ajaxQueue = function (ajaxOpts) {
                    var oldComplete = ajaxOpts.complete;
                    ajaxQueue.queue(function (next) {
                        ajaxOpts.complete = function () {
                            if (oldComplete) oldComplete.apply(this, arguments);
                            next();
                        };
                        $.ajax(ajaxOpts);
                    });
                };
            })(jQuery);
        </script>
        <?php
    }
}

if ( ! function_exists('at_cj_notices') ) {
    /**
     * at_cj_notices function.
     *
     */
    add_action('admin_notices', 'at_cj_notices');
    function at_cj_notices() {
        if ((isset($_GET['page']) && $_GET['page'] == 'endcore_api_cj')) {
            // check php version
            if(version_compare(PHP_VERSION, '5.3.0', '<')) {
                ?>
                <div class="notice notice-error">
                    <p><?php printf(__('Achtung: Um dieses Plugin zu verwenden benötigst du mindestens PHP Version 5.3.x. Derzeit verwendest du Version %s.', 'affiliatetheme-cj'), PHP_VERSION); ?></p>
                </div>
                <?php
            }

            // check curl
            if(extension_loaded('curl') != function_exists('curl_version')) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('Um dieses Plugin zu verwenden benötigst du cURL. <a href="http://php.net/manual/de/book.curl.php" taget="_blank">Hier</a> findest du mehr Informationen darüber. Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-cj'); ?></p>
                </div>
                <?php
            }

            // check allow_url_fopen
            if(ini_get('allow_url_fopen') == false) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('Achtung: Du hast allow_url_fopen deaktiviert. Bitte kontaktiere deinen Administrator.', 'affiliatetheme-cj'); ?></p>
                </div>
                <?php
            }

            // check soap
            if(extension_loaded('soap') == false) {
                ?>
                <div class="error" id="required-by-plugin">
                    <p><?php _e('Um dieses Plugin zu verwenden benötigst du SOAP. <a href="http://php.net/manual/en/book.soap.php" taget="_blank">Hier</a> findest du mehr Informationen darüber. Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-cj'); ?></p>
                </div>
                <?php
            }
        }
    }
}

if(!function_exists('at_cj_curlURL')){
    /**
     * sends a request to the API using curl to send the developer key
     * @param $url the API request
     * @return the answer from the API
     */
    function at_cj_curl_URL($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Send authorization header with the CJ ID. Without this, the query won't work
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.get_option('cj_devkey')));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

if(!function_exists('at_cj_getTotalPages')){
    /**
     * sends a request to the API using curl to send the developer key
     * @param $url the API request
     * @return the answer from the API
     */
    function at_cj_getTotalPages($items){
        if($items->products->{'@attributes'}->{'total-matched'}== 0) return 0;
        return ceil($items->products->{'@attributes'}->{'total-matched'}/$items->products->{'@attributes'}->{'records-returned'});
    }
}

if(!function_exists('at_cj_getCategory')){
    /**
     * sends a request to the API using curl to send the developer key
     * @param $url the API request
     * @return the answer from the API
     */
    function at_cj_getCategory($string){
        $last_part =substr(strrchr($string, ">"),1);
        if($last_part) return $last_part;
        return "";
    }
}