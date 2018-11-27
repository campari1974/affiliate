<?php
/**
 * affilinet API - Diverse Hilfsfunktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @updated     2016/08/16
 */

if ( ! function_exists( 'anet_array_insert' ) ) {
    /**
     * anet_array_insert
     * @deprecated since 1.1.4
     *
     */
    function anet_array_insert(&$array, $position, $insert) {
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

if ( ! function_exists( 'at_anet_array_insert' ) ) {
    /**
     * anet_array_insert
     *
     * Array helper
     * @param   array $array
     * @param   int $position
     * @param   int $insert
     * @return  -
     */
    function at_anet_array_insert(&$array, $position, $insert) {
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

if ( ! function_exists( 'at_affilinet_add_as_portal' ) ) {
    /**
     * at_affilinet_add_as_portal
     *
     * Add Affilinet to Product Portal Dropdown
     */
    add_filter('at_add_product_portal', 'at_affilinet_add_as_portal', 10, 2);
    function at_affilinet_add_as_portal($choices)
    {
        $choices['affilinet'] = __('Affilinet', 'affiliatetheme-affilinet');
        return $choices;
    }
}

if ( ! function_exists( 'at_affilinet_add_field_portal_id' ) ) {
    /**
     * at_affilinet_add_field_portal_id
     *
     * Add Affilinet Fields to Products
     */
    add_filter('at_add_product_fields', 'at_affilinet_add_field_portal_id', 10, 2);
    function at_affilinet_add_field_portal_id($fields) {
        $new_field[] = array(
            'key' => 'field_553b8484246bd',
            'label' => __('Affilinet ID', 'affiliatetheme-affilinet'),
            'name' => 'affilinet_id',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_553b83de246bb',
                        'operator' => '==',
                        'value' => 'affilinet',
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

        at_anet_array_insert($fields['fields'][4]['sub_fields'], 7, $new_field);
        return $fields;
    }
}

if ( ! function_exists( 'at_affilinet_overwrite_product_button_short_text' ) ) {
    /**
     * at_affilinet_overwrite_product_button_short_text
     *
     * Overwrite Product Button Text (short)
     */
    add_filter('at_product_api_button_short_text', 'at_affilinet_overwrite_product_button_short_text', 10, 5);
    function at_affilinet_overwrite_product_button_short_text($var = '', $product_portal = '', $product_shop = '', $pos = '', $short = false) {
        if ('affilinet' == $product_portal && 'buy' == $pos) {
            $var = (get_option('affilinet_buy_short_button') ? get_option('affilinet_buy_short_button') : __('Kaufen', 'affiliatetheme-affilinet'));

            if ($product_shop) {
                $var = sprintf($var, $product_shop->post_title);
            }
        }

        return $var;
    }
}

if ( ! function_exists( 'at_affilinet_overwrite_product_button_text' ) ) {
    /**
     * at_affilinet_overwrite_product_button_text
     *
     * Overwrite Product Button Text
     */
    add_filter('at_product_api_button_text', 'at_affilinet_overwrite_product_button_text', 10, 5);
    function at_affilinet_overwrite_product_button_text($var = '', $product_portal = '', $product_shop = '', $pos = '', $short = false) {
        if ('affilinet' == $product_portal && 'buy' == $pos) {
            $var = (get_option('affilinet_buy_button') ? get_option('affilinet_buy_button') : __('Jetzt bei %s kaufen', 'affiliatetheme-affilinet'));

            if ($product_shop) {
                $var = sprintf($var, $product_shop->post_title);
            }
        }

        return $var;
    }
}

if ( ! function_exists( 'at_affilinet_add_subid_to_links' ) ) {
    /**
     * at_affilinet_add_subid_to_links
     *
     * Add Sub-ID to outgoing Links
     */
    add_filter('at_get_product_link', 'at_affilinet_add_subid_to_links', 10, 7);
    function at_affilinet_add_subid_to_links($product_link, $post_id, $shop_id, $clean, $product_shop, $product_url, $product_cloak) {
        $product_portal = (isset($product_shop[$shop_id]['portal']) ? $product_shop[$shop_id]['portal'] : '');

        if ($product_portal == 'affilinet') {
            if ($product_cloak && false == $clean) {
                return $product_link;
            }

            $affilinet_subid = apply_filters('at_affilinet_subid', get_option('affilinet_subid'), $product_link, $post_id, $shop_id);

            if ($affilinet_subid) {
                $product_link = $product_link . '&subid=' . $affilinet_subid;
            }
        }

        return $product_link;
    }
}

if ( ! function_exists( 'at_affilinet_compare_box' ) ) {
    /**
     * at_affilinet_compare_box
     *
     * Add Meta-Box to Product Page
     */
    add_action('add_meta_boxes', 'at_affilinet_compare_box');
    function at_affilinet_compare_box() {
        add_meta_box(
            'affilinet_price_compare',
            '<span class="dashicons dashicons-search"></span> ' . __('Affilinet Preisvergleich', 'affiliatetheme-affilinet'),
            'at_affilinet_compare_box_callback',
            'product'
        );
    }
}

if ( ! function_exists( 'at_affilinet_compare_box_callback' ) ) {
    /**
     * at_affilinet_compare_box_callback
     *
     * Add Meta-Box Content
     */
    function at_affilinet_compare_box_callback($post) {
        $ean = get_post_meta($post->ID, 'product_ean', true);
        ?>
        <div id="at-import-page" class="at-import-page-affilinet" data-url="<?php echo admin_url(); ?>" data-import-nonce="<?php echo wp_create_nonce("at_affilinet_import_wpnonce"); ?>">
            <div class="alert alert-info api-alert">
                <span class="dashicons dashicons-megaphone"></span>
                <p><?php _e('Du kannst mit Hilfe des Preisvergleiches weitere Preise aus verschiedenen Shops zu diesem Produkt hinzufügen. Suche entweder nach der EAN oder einem Keyword und importiere weitere Preise. <br>
                Die neuen Preise werden sofort im oberen Feld hinzugefügt. Bitte speichere das Produkt wenn du fertig bist.', 'affiliatetheme-affilinet'); ?></p>
            </div>

            <div class="form-container">
                <div class="form-group">
                    <label for="compare_ean"><?php _e('EAN', 'affiliatetheme-affilinet'); ?></label>
                    <input type="text" name="compare_ean" id="compare_ean" value="<?php echo $ean; ?>">
                </div>

                <div class="form-group">
                    <label for="compare_query"><?php _e('Keyword', 'affiliatetheme-affilinet'); ?></label>
                    <input type="text" name="compare_query" id="compare_query">
                </div>

                <div class="form-group">
                    <label for="compare_shop"><?php _e('Shop', 'affiliatetheme-affilinet'); ?></label>
                    <select name="compare_shop[]" id="compare_shop" multiple="multiple">
                        <option>-</option>
                    </select>
                </div>

                <a href="#" class="acf-button blue button affilinet-price-compare"><?php _e('Preisvergleich ausführen', 'affiliatetheme-affilinet'); ?></a>
            </div>
        </div>

        &nbsp;

        <div id="at-import-window">
            <table class="wp-list-table widefat fixed products">
                <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Alle auswählen', 'affiliatetheme-affilinet'); ?></label
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th scope="col" id="productid" class="manage-column column-productid">
                        <span><?php _e('Artikelnummer', 'affiliatetheme-affilinet'); ?></span>
                    </th>
                    <th scope="col" id="image" class="manage-column column-image">
                        <span><?php _e('Vorschau', 'affiliatetheme-affilinet'); ?></span>
                    </th>
                    <th scope="col" id="ean" class="manage-column column-ean">
                        <span><?php _e('EAN', 'affiliatetheme-affilinet'); ?></span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title">
                        <span><?php _e('Name', 'affiliatetheme-affilinet'); ?></span>
                    </th>
                    <th scope="col" id="shop" class="manage-column column-title">
                        <span><?php _e('Shop', 'affiliatetheme-affilinet'); ?></span>
                    </th>
                    <th scope="col" id="price" class="manage-column column-price">
                        <span><?php _e('Preis', 'affiliatetheme-affilinet'); ?></span>
                    </th>
                    <th scope="col" id="actions" class="manage-column column-action" style="">
                        <span><?php _e('Aktion', 'affiliatetheme-affilinet'); ?></span>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <a href="#" class="mass-import button button-primary"><?php _e('Ausgewählte Produkte importieren', 'affiliatetheme-affilinet'); ?></a>
                    </td>
                </tr>
                </tfoot>
                <tbody id="results"></tbody>
            </table>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                // ShopList
                jQuery.ajax({
                    url: ajaxurl,
                    dataType: 'json',
                    type: 'GET',
                    data: "action=at_affilinet_shoplist",
                    success: function(data){
                        if(!data['message']) {
                            var html = '<option value="">-</option>';
                            for (var x in data['items']) {
                                html += '<option value="' + data['items'][x]['id'] + '" >' + data['items'][x]['name'] + '</option>';
                            }
                            jQuery('.at-import-page-affilinet select#compare_shop').html(html);
                        }
                    },
                    error: function() {
                    }
                });

                // searchAction
                jQuery('.at-import-page-affilinet').bind('keydown', function (event) {
                    if (event.keyCode == 13) {
                        searchAction();
                        event.preventDefault();
                    }
                });
                jQuery('#at-import-page .affilinet-price-compare').click(function (event) {
                    searchAction();
                    event.preventDefault();
                });

                // quickImportAction
                jQuery('.quick-import').live('click', function (event) {
                    var id = jQuery(this).attr('data-id');

                    quickImportAction(id);

                    event.preventDefault();
                });

                // massImportAction
                jQuery('.mass-import').live('click', function (event) {
                    massImportAction(this);

                    event.preventDefault();
                });
            });

            var searchAction = function () {
                var target = jQuery('#at-import-page .affilinet-price-compare');
                var ean = jQuery('#at-import-page #compare_ean').val();
                var query = jQuery('#at-import-page #compare_query').val();
                var shopId = jQuery('#at-import-page #compare_shop').val();
                var html = '';

                jQuery(target).append(' <i class="fa fa-circle-o-notch fa-spin"></i>').attr('disabled', true).addClass('noevent');

                jQuery.ajax({
                    url: ajaxurl,
                    dataType: 'json',
                    type: 'GET',
                    data: {action: 'at_affilinet_search_ean', ean: ean, query: query, shopId : shopId}
                }).done(function (data) {

                    if (data['items']) {
                        for (var x in data['items']) {
                            if (data['items'][x].exists != "false") {
                                html += '<tr class="item success" data-id="' + data['items'][x].id + '">';
                                html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-' + data['items'][x].id + ' name="item[]" value="' + data['items'][x].id + '" disabled="disabled"></th>';
                            } else {
                                html += '<tr class="item" data-id="' + data['items'][x].id + '">';
                                html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-' + data['items'][x].id + ' name="item[]" value="' + data['items'][x].id + '"></th>';
                            }
                            html += '<td class="productid">' + data['items'][x].productid + '</td>';
                            html += '<td class="image"><img src="'+data['items'][x].image+'"></td>';
                            html += '<td class="ean">' + (data['items'][x].ean ? data['items'][x].ean : '-') + '</td>';
                            html += '<td class="title"><a href="' + data['items'][x].url + '" target="_blank">' + data['items'][x].name + '</a></td>';
                            html += '<td class="shop">' + data['items'][x].shop + '</td>';
                            html += '<td class="price">' + data['items'][x].price + '</td>';
                            if (data['items'][x].exists != "false") {
                                html += '<td class="action"></td>';
                            } else {
                                html += '<td class="action"><a href="#" title="Quickimport" class="quick-import" data-id="' + data['items'][x].id + '"><i class="fa fa-bolt"></i></a></td>';
                            }
                            html += '</tr>';
                        }
                    } else {
                        html += '<tr><td colspan="5"><?php _e('Es wurde kein Produkt gefunden', 'affiliatetheme-affilinet'); ?></td></tr>';
                    }
                }).always(function () {
                    jQuery(target).attr('disabled', false).removeClass('noevent').find('i').remove();
                    jQuery('#at-import-window tbody#results').html(html);
                });
            }

            var quickImportAction = function (id, mass, i, max_items) {
                mass = mass || false;
                max_items = max_items || "0";
                i = i || "1";

                var target = jQuery('#results .item[data-id=' + id + ']').find(".action a.quick-import");
                var ajax_loader = jQuery('.at-ajax-loader');
                var id = jQuery(target).attr('data-id');
                var post_id = '<?php echo $post->ID; ?>';
                var nonce = jQuery('#at-import-page').attr('data-import-nonce');

                jQuery(target).append(' <i class="fa fa-circle-o-notch fa-spin"></i>').addClass('noevent');

                jQuery.ajaxQueue({
                    url: ajaxurl,
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        action: 'at_affilinet_add_acf',
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

                            var shopinfo = data['shop_info'];


                            jQuery('[data-key="field_557c01ea87000"] .acf-input .acf-actions [data-event="add-row"]').trigger('click');
                            var field_id = jQuery('div[data-key="field_557c01ea87000"] tr.acf-row').not('div[data-key="field_557c01ea87000"] tr.acf-clone').last().attr('data-id');

                            var pricefield = 'acf-field_557c01ea87000-'+field_id+'-field_553b8257246b5';
                            var currencyfield = 'acf-field_557c01ea87000-'+field_id+'-field_553b82b5246b6';
                            var portalfield = 'acf-field_557c01ea87000-'+field_id+'-field_553b83de246bb';
                            var affilinetIDfield = 'acf-field_557c01ea87000-'+field_id+'-field_553b8484246bd';
                            var shopfield = 'acf-field_557c01ea87000-'+field_id+'-field_557c058187007-input';
                            var urlfield = 'acf-field_557c01ea87000-'+field_id+'-field_553b834c246b9';
                            jQuery("#"+pricefield).val(shopinfo['price']);
                            jQuery("#"+currencyfield).val(shopinfo['currency']);
                            jQuery("#"+portalfield).val(shopinfo['portal']);
                            jQuery("#"+affilinetIDfield).val(shopinfo['metakey']);
                            jQuery("#"+shopfield).val(shopinfo['shop']);
                            jQuery("#"+urlfield).val(shopinfo['link']);
                            window.onbeforeunload = function(event){
                                var leaverid = jQuery(event.target.activeElement).context.id;
                                if (leaverid != 'publish') return true;
                            }
                            console.log("timeout, text:"+shopinfo['shopname']);
                            setTimeout(function(){jQuery('.select2-chosen').last().text(shopinfo['shopname'])},1000);
                            jQuery(target).hide();
                            jQuery('body table.products tr[data-id=' + id + ']').addClass('success');
                            jQuery('body table.products tr[data-id=' + id + '] .check-column input[type=checkbox]').attr('disabled', 'disabled');
                            jQuery('body table.products tr[data-id=' + id + '] .action i').removeClass('fa-plus-circle').addClass('fa-check').closest('a').removeClass('quick-import');
                        }
                    }
                });
            };

            var massImportAction = function (target) {
                var max_items = jQuery('#results .item:not(".success") .check-column input:checkbox:checked').length;
                var i = 1;

                jQuery('#results .item:not(".success") .check-column input:checkbox:checked').each(function () {
                    var id = jQuery(this).val();
                    quickImportAction(id, true, i, max_items);
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

if ( ! function_exists('at_affilinet_notices') ) {
    /**
     * at_affilinet_notices function.
     *
     */
    add_action('admin_notices', 'at_affilinet_notices');
    function at_affilinet_notices() {
        if ((isset($_GET['page']) && $_GET['page'] == 'endcore_api_affilinet')) {
            // check php version
            if(version_compare(PHP_VERSION, '5.3.0', '<')) {
                ?>
                <div class="notice notice-error">
                    <p><?php printf(__('Achtung: Um dieses Plugin zu verwenden benötigst du mindestens PHP Version 5.3.x. Derzeit verwendest du Version %s.', 'affiliatetheme-affilinet'), PHP_VERSION); ?></p>
                </div>
                <?php
            }

            // check curl
            if(extension_loaded('curl') != function_exists('curl_version')) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('Um dieses Plugin zu verwenden benötigst du cURL. <a href="http://php.net/manual/de/book.curl.php" taget="_blank">Hier</a> findest du mehr Informationen darüber. Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-affilinet'); ?></p>
                </div>
                <?php
            }

            // check allow_url_fopen
            if(ini_get('allow_url_fopen') == false) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('Achtung: Du hast allow_url_fopen deaktiviert. Bitte kontaktiere deinen Administrator.', 'affiliatetheme-affilinet'); ?></p>
                </div>
                <?php
            }

            // check soap
            if(extension_loaded('soap') == false) {
                ?>
                <div class="error" id="required-by-plugin">
                    <p><?php _e('Um dieses Plugin zu verwenden benötigst du SOAP. <a href="http://php.net/manual/en/book.soap.php" taget="_blank">Hier</a> findest du mehr Informationen darüber. Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-affilinet'); ?></p>
                </div>
                <?php
            }
        }
    }
}

if ( ! function_exists( 'at_affilinet_multiselect_tax_form_dropdown' ) ) {
    /**
     * at_affilinet_multiselect_tax_form_dropdown
     *
     * Add a dropdown of all available values;
     */
    add_filter('at_mutltiselect_tax_form_product_dropdown', 'at_affilinet_multiselect_tax_form_dropdown', 20, 3);
    function at_affilinet_multiselect_tax_form_dropdown($output, $properties, $tax)
    {

        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (preg_match('/affilinet/',$actual_link)) {
            if (is_array($properties)) {
                $output .= '<select id="attributes-select-' . $tax . '" name="taxonomy-select-dropdown-' . $tax . '">';
                $output .= '<option value=""> -</option>';
                foreach ($properties as $property) {
                    if ($property->PropertyValue != "") {
                        $output .= '<option value="' . $property->PropertyValue . '">' . $property->PropertyName . " - " . $property->PropertyValue . '</option>';
                    }
                }
                $output .= '</select>';
                $output .= '<script type="text/javascript">';
                $output .= "var select = document.getElementsByName('taxonomy-select-dropdown-" . $tax . "')[document.getElementsByName('taxonomy-select-dropdown-" . $tax . "').length-1];";
                $output .= "select.onchange = function () {";
                $output .= "var input = document.getElementsByName('tax[" . $tax . "][]')[document.getElementsByName('tax[" . $tax . "][]').length-1];";
                $output .= "input.value = this.value; ";
                $output .= "} </script>";
            }


        }
        return $output;
    }
}

if ( ! function_exists('at_affilinet_validate_ean')){
    function at_affilinet_validate_ean($barcode){
        // check to see if barcode is 13 digits long
        if (!preg_match("/^[0-9]{13}$/", $barcode)) {
            return false;
        }

        $digits = $barcode;

        // 1. Add the values of the digits in the
        // even-numbered positions: 2, 4, 6, etc.
        $even_sum = $digits[1] + $digits[3] + $digits[5] +
            $digits[7] + $digits[9] + $digits[11];

        // 2. Multiply this result by 3.
        $even_sum_three = $even_sum * 3;

        // 3. Add the values of the digits in the
        // odd-numbered positions: 1, 3, 5, etc.
        $odd_sum = $digits[0] + $digits[2] + $digits[4] +
            $digits[6] + $digits[8] + $digits[10];

        // 4. Sum the results of steps 2 and 3.
        $total_sum = $even_sum_three + $odd_sum;

        // 5. The check character is the smallest number which,
        // when added to the result in step 4, produces a multiple of 10.
        $next_ten = (ceil($total_sum / 10)) * 10;
        $check_digit = $next_ten - $total_sum;

        // if the check digit and the last digit of the
        // barcode are OK return true;
        if ($check_digit == $digits[12]) {
            return true;
        }

        return false;
    }
}