<?php
/**
 * Tradedoubler API - Diverse Hilfsfunktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @updated     2016/08/16
 */

if ( ! function_exists( 'tradedoubler_array_insert' ) ) {
    /**
     * tradedoubler_array_insert
     * @deprecated since 1.1.4
     *
     */
    function tradedoubler_array_insert(&$array, $position, $insert) {
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

if ( ! function_exists( 'at_tradedoubler_array_insert' ) ) {
    /**
     * tradedoubler_array_insert
     *
     * Array helper
     * @param   array $array
     * @param   int $position
     * @param   int $insert
     * @return  -
     */
    function at_tradedoubler_array_insert(&$array, $position, $insert) {
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

if ( ! function_exists( 'at_tradedoubler_add_as_portal' ) ) {
    /**
     * at_tradedoubler_add_as_portal
     *
     * Add Tradedoubler to Product Portal Dropdown
     */
    add_filter('at_add_product_portal', 'at_tradedoubler_add_as_portal', 10, 2);
    function at_tradedoubler_add_as_portal($choices)
    {
        $choices['tradedoubler'] = __('Tradedoubler', 'affiliatetheme-tradedoubler');
        return $choices;
    }
}

if ( ! function_exists( 'at_tradedoubler_add_field_portal_id' ) ) {
    /**
     * at_tradedoubler_add_field_portal_id
     *
     * Add Tradedoubler Fields to Products
     */
    add_filter('at_add_product_fields', 'at_tradedoubler_add_field_portal_id', 10, 2);
    function at_tradedoubler_add_field_portal_id($fields) {
        $new_field[] = array(
            'key' => 'field_59520e853d305',
            'label' => __('Tradedoubler ID', 'affiliatetheme-tradedoubler'),
            'name' => 'tradedoubler_id',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_553b83de246bb',
                        'operator' => '==',
                        'value' => 'tradedoubler',
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

        at_tradedoubler_array_insert($fields['fields'][4]['sub_fields'], 7, $new_field);
        return $fields;
    }
}

if ( ! function_exists( 'at_tradedoubler_overwrite_product_button_short_text' ) ) {
    /**
     * at_tradedoubler_overwrite_product_button_short_text
     *
     * Overwrite Product Button Text (short)
     */
    add_filter('at_product_api_button_short_text', 'at_tradedoubler_overwrite_product_button_short_text', 10, 5);
    function at_tradedoubler_overwrite_product_button_short_text($var = '', $product_portal = '', $product_shop = '', $pos = '', $short = false) {
        if ('tradedoubler' == $product_portal && 'buy' == $pos) {
            $var = (get_option('tradedoubler_buy_short_button') ? get_option('tradedoubler_buy_short_button') : __('Kaufen', 'affiliatetheme-tradedoubler'));

            if ($product_shop) {
                $var = sprintf($var, $product_shop->post_title);
            }
        }

        return $var;
    }
}

if ( ! function_exists( 'at_tradedoubler_overwrite_product_button_text' ) ) {
    /**
     * at_tradedoubler_overwrite_product_button_text
     *
     * Overwrite Product Button Text
     */
    add_filter('at_product_api_button_text', 'at_tradedoubler_overwrite_product_button_text', 10, 5);
    function at_tradedoubler_overwrite_product_button_text($var = '', $product_portal = '', $product_shop = '', $pos = '', $short = false) {
        if ('tradedoubler' == $product_portal && 'buy' == $pos) {
            $var = (get_option('tradedoubler_buy_button') ? get_option('tradedoubler_buy_button') : __('Jetzt bei %s kaufen', 'affiliatetheme-tradedoubler'));

            if ($product_shop) {
                $var = sprintf($var, $product_shop->post_title);
            }
        }

        return $var;
    }
}

if ( ! function_exists( 'at_tradedoubler_add_subid_to_links' ) ) {
    /**
     * at_tradedoubler_add_subid_to_links
     *
     * Add Sub-ID to outgoing Links
     */
    add_filter('at_get_product_link', 'at_tradedoubler_add_subid_to_links', 10, 7);
    function at_tradedoubler_add_subid_to_links($product_link, $post_id, $shop_id, $clean, $product_shop, $product_url, $product_cloak) {
        $product_portal = (isset($product_shop[$shop_id]['portal']) ? $product_shop[$shop_id]['portal'] : '');

        if ($product_portal == 'tradedoubler') {
            if ($product_cloak && false == $clean) {
                return $product_link;
            }

            $tradedoubler_subid = apply_filters('at_tradedoubler_subid', get_option('tradedoubler_subid'), $product_link, $post_id, $shop_id);

            if ($tradedoubler_subid) {
                $product_link = $product_link . '&subid=' . $tradedoubler_subid;
            }
        }

        return $product_link;
    }
}

if ( ! function_exists( 'at_tradedoubler_compare_box' ) ) {
    /**
     * at_tradedoubler_compare_box
     *
     * Add Meta-Box to Product Page
     */
    add_action('add_meta_boxes', 'at_tradedoubler_compare_box');
    function at_tradedoubler_compare_box() {
        add_meta_box(
            'tradedoubler_price_compare',
            '<span class="dashicons dashicons-search"></span> ' . __('Tradedoubler Preisvergleich', 'affiliatetheme-tradedoubler'),
            'at_tradedoubler_compare_box_callback',
            'product'
        );
    }
}

if ( ! function_exists( 'at_tradedoubler_compare_box_callback' ) ) {
    /**
     * at_tradedoubler_compare_box_callback
     *
     * Add Meta-Box Content
     */
    function at_tradedoubler_compare_box_callback($post) {
        $ean = get_post_meta($post->ID, 'product_ean', true);
        ?>

        <div id="at-import-page" class="at-import-page-tradedoubler" data-url="<?php echo admin_url(); ?>" data-import-nonce="<?php echo wp_create_nonce("at_tradedoubler_import_wpnonce"); ?>">
            <div class="alert alert-info api-alert">
                <span class="dashicons dashicons-megaphone"></span>
                <p><?php _e('Du kannst mit Hilfe des Preisvergleiches weitere Preise aus verschiedenen Shops zu diesem Produkt hinzufügen. Suche entweder nach der EAN oder einem Keyword und importiere weitere Preise. <br>
                Die neuen Preise werden sofort im oberen Feld hinzugefügt. Bitte speichere das Produkt wenn du fertig bist.', 'affiliatetheme-tradedoubler'); ?></p>
            </div>

            <div class="form-container">
                <div class="form-group">
                    <label for="scompare_ean"><?php _e('EAN', 'affiliatetheme-tradedoubler'); ?></label>
                    <input type="text" name="tradedoubler_compare_ean" id="tradedoubler_compare_ean" value="<?php echo $ean; ?>">
                </div>

                <div class="form-group">
                    <label for="compare_query"><?php _e('Keyword', 'affiliatetheme-tradedoubler'); ?></label>
                    <input type="text" name="tradedoubler_compare_query" id="tradedoubler_compare_query">
                </div>

                <a href="#"
                   class="acf-button blue button tradedoubler-price-compare"><?php _e('Preisvergleich ausführen', 'affiliatetheme-tradedoubler'); ?></a>
            </div>
        </div>

        &nbsp;

        <div id="at-import-window">
            <table class="wp-list-table widefat fixed products">
                <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Alle auswählen', 'affiliatetheme-tradedoubler'); ?></label
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th scope="col" id="productid" class="manage-column column-productid">
                        <span><?php _e('Artikelnummer', 'affiliatetheme-tradedoubler'); ?></span>
                    </th>
                    <th scope="col" id="ean" class="manage-column column-ean">
                        <span><?php _e('EAN', 'affiliatetheme-tradedoubler'); ?></span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title">
                        <span><?php _e('Name', 'affiliatetheme-tradedoubler'); ?></span>
                    </th>
                    <th scope="col" id="shop" class="manage-column column-title">
                        <span><?php _e('Shop', 'affiliatetheme-tradedoubler'); ?></span>
                    </th>
                    <th scope="col" id="price" class="manage-column column-price">
                        <span><?php _e('Preis', 'affiliatetheme-tradedoubler'); ?></span>
                    </th>
                    <th scope="col" id="actions" class="manage-column column-action" style="">
                        <span><?php _e('Aktion', 'affiliatetheme-tradedoubler'); ?></span>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <a href="#" class="mass-import button button-primary"><?php _e('Ausgewählte Produkte importieren', 'affiliatetheme-tradedoubler'); ?></a>
                    </td>
                </tr>
                </tfoot>
                <tbody id="resultstradedoubler"></tbody>
            </table>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                // tradedoublersearchAction
                jQuery('.at-import-page-tradedoubler').bind('keydown', function (event) {
                    if (event.keyCode == 13) {
                        tradedoublersearchAction();
                        event.preventDefault();
                    }
                });
                jQuery('.at-import-page-tradedoubler .tradedoubler-price-compare').click(function (event) {
                    tradedoublersearchAction();
                    event.preventDefault();
                });

                // tradedoublerQuickImportAction
                jQuery('.tradedoubler-quick-import').live('click', function (event) {
                    var id = jQuery(this).attr('id');
                    tradedoublerQuickImportAction(id);

                    event.preventDefault();
                });

                // tradedoublerMassImportAction
                jQuery('.at-import-page-tradedoubler .mass-import').live('click', function (event) {
                    tradedoublerMassImportAction(this);

                    event.preventDefault();
                });
            });

            var tradedoublersearchAction = function () {
                var target = jQuery('.at-import-page-tradedoubler .tradedoubler-price-compare');
                var ean = jQuery('.at-import-page-tradedoubler #tradedoubler_compare_ean').val();
                var query = jQuery('.at-import-page-tradedoubler #tradedoubler_compare_query').val();
                var action = (query.length < 3)?'at_tradedoubler_search_ean':'at_tradedoubler_search';
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
                                html += '<tr class="item success" id="'+data['items'][x].id+'">';
                                html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-'+data['items'][x].id+' name="item[]" data-id="'+data['items'][x].id+'" disabled="disabled"></th>';
                            }else {
                                html += '<tr class="item" id="'+data['items'][x].id+'">';
                                html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-'+data['items'][x].id+' name="item[]" data-id="'+data['items'][x].id+'" ></th>';
                            }
                            html += '<td class="productid">' + data['items'][x].productid + '</td>';
                            html += '<td class="ean">' + (data['items'][x].sku ? data['items'][x].sku : '-') + '</td>';
                            html += '<td class="title"><a href="' + data['items'][x].url + '" target="_blank">' + data['items'][x].name + '</a></td>';
                            html += '<td class="shop">' + data['items'][x].shop +"\n"+data['items'][x].shopname+ '</td>';
                            html += '<td class="price">' + data['items'][x].price + '</td>';
                            if (data['items'][x].exists != "false") {
                                html += '<td class="action"></td>';
                            } else {
                                html += '<td class="action"><a href="#" title="Quickimport" class="tradedoubler-quick-import" id="'+data['items'][x].id+'"><i class="fa fa-bolt"></i></a></td>';
                            }
                            html += '</tr>';
                        }
                    } else {
                        html += '<tr><td colspan="5"><?php _e('Es wurde kein Produkt gefunden', 'affiliatetheme-tradedoubler'); ?></td></tr>';
                    }
                }).always(function () {
                    jQuery(target).attr('disabled', false).removeClass('noevent').find('i').remove();
                    jQuery('#at-import-window tbody#resultstradedoubler').html(html);
                });
            }

            var tradedoublerQuickImportAction = function (id, mass, i, max_items) {
                mass = mass || false;
                max_items = max_items || "0";
                i = i || "1";
                console.log("quickimport");
                console.log(id);
                var target = jQuery('#results .item[id='+id+']').find(".action a.tradedoubler-quick-import");
                var ajax_loader = jQuery('.at-ajax-loader');
                var post_id = '<?php echo $post->ID; ?>';
                var nonce = jQuery('.at-import-page-tradedoubler').attr('data-import-nonce');

                jQuery(target).append(' <i class="fa fa-circle-o-notch fa-spin"></i>').addClass('noevent');

                jQuery.ajaxQueue({
                    url: ajaxurl,
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        action: 'at_tradedoubler_add_acf',
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
                            var tradedoublerIDfield = 'acf-field_557c01ea87000-'+field_id+'-field_59520e853d305';
                            var shopfield = 'acf-field_557c01ea87000-'+field_id+'-field_557c058187007-input';
                            var urlfield = 'acf-field_557c01ea87000-'+field_id+'-field_553b834c246b9';
                            jQuery("#"+pricefield).val(shopinfo['price']);
                            jQuery("#"+currencyfield).val(shopinfo['currency']);
                            jQuery("#"+portalfield).val(shopinfo['portal']);
                            jQuery("#"+tradedoublerIDfield).val(shopinfo['metakey']);
                            jQuery("#"+shopfield).val(shopinfo['shop']);
                            jQuery("#"+urlfield).val(shopinfo['link']);
                            window.onbeforeunload = function(event){
                                var leaverid = jQuery(event.target.activeElement).context.id;
                                if (leaverid != 'publish') return true;
                            }
                            console.log("timeout, text:"+shopinfo['shopname']);
                            setTimeout(function(){jQuery('.select2-chosen').last().text(shopinfo['shopname']);},1000);
                            jQuery(target).hide();
                            jQuery('body table.products tr[id=' + id + ']').addClass('success');
                            jQuery('body table.products tr[id=' + id + '] .check-column input[type=checkbox]').attr('disabled', 'disabled');
                            jQuery('body table.products tr[id=' + id + '] .action i').removeClass('fa-plus-circle').addClass('fa-check').closest('a').removeClass('quick-import');
                        }
                    }
                });
            };

            var tradedoublerMassImportAction = function (target) {
                var max_items = jQuery('#results .item:not(".success") .check-column input:checkbox:checked').length;
                var i = 1;

                jQuery('#resultstradedoubler .item:not(".success") .check-column input:checkbox:checked').each(function () {
                    var id = jQuery(this).attr('data-id');
                    tradedoublerQuickImportAction(id, true, i, max_items);
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

if ( ! function_exists('at_tradedoubler_notices') ) {
    /**
     * at_tradedoubler_notices function.
     *
     */
    add_action('admin_notices', 'at_tradedoubler_notices');
    function at_tradedoubler_notices() {
        if ((isset($_GET['page']) && $_GET['page'] == 'endcore_api_tradedoubler')) {
            // check php version
            if(version_compare(PHP_VERSION, '5.3.0', '<')) {
                ?>
                <div class="notice notice-error">
                    <p><?php printf(__('Achtung: Um dieses Plugin zu verwenden benötigst du mindestens PHP Version 5.3.x. Derzeit verwendest du Version %s.', 'affiliatetheme-tradedoubler'), PHP_VERSION); ?></p>
                </div>
                <?php
            }

            // check curl
            if(extension_loaded('curl') != function_exists('curl_version')) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('Um dieses Plugin zu verwenden benötigst du cURL. <a href="http://php.net/manual/de/book.curl.php" taget="_blank">Hier</a> findest du mehr Informationen darüber. Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-tradedoubler'); ?></p>
                </div>
                <?php
            }

            // check allow_url_fopen
            if(ini_get('allow_url_fopen') == false) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('Achtung: Du hast allow_url_fopen deaktiviert. Bitte kontaktiere deinen Administrator.', 'affiliatetheme-tradedoubler'); ?></p>
                </div>
                <?php
            }

            // check soap
            if(extension_loaded('soap') == false) {
                ?>
                <div class="error" id="required-by-plugin">
                    <p><?php _e('Um dieses Plugin zu verwenden benötigst du SOAP. <a href="http://php.net/manual/en/book.soap.php" taget="_blank">Hier</a> findest du mehr Informationen darüber. Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-tradedoubler'); ?></p>
                </div>
                <?php
            }
        }
    }
}

if(!function_exists('at_tradedoubler_curlURL')){
    /**
     * sends a request to the API using curl to send the developer key
     * @param $url string the API request
     * @return string the answer from the API
     */
    function at_tradedoubler_curl_URL($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Send authorization header with the Tradedoubler ID. Without this, the query won't work
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.get_option('tradedoubler_devkey')));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

if(!function_exists('at_tradedoubler_getTotalPages')){
    /**
     * sends a request to the API using curl to send the developer key
     * @param $url the API request
     * @return the answer from the API
     */
    function at_tradedoubler_getTotalPages($items){
        if($items->products->{'@attributes'}->{'total-matched'}== 0) return 0;
        return ceil($items->products->{'@attributes'}->{'total-matched'}/$items->products->{'@attributes'}->{'records-returned'});
    }
}

if(!function_exists('at_tradedoubler_getCategory')){
    /**
     * sends a request to the API using curl to send the developer key
     * @param $url the API request
     * @return the answer from the API
     */
    function at_tradedoubler_getCategory($string){
        $last_part =substr(strrchr($string, ">"),1);
        if($last_part) return $last_part;
        return "";
    }
}

if(!function_exists('at_tradedoubler_get_categories_recursive')){
    /**
     * @param $subTree
     * @param $depth
     * @return array
     */
    function at_tradedoubler_get_categories_recursive($subTree,$depth){
        $results = array();
            if($subTree['id'])
              $results[$subTree['id']] = $subTree['name'];
            if ($depth < 1) {
                foreach($subTree['subCategories'] as $subcategory)
                $results += at_tradedoubler_get_categories_recursive($subcategory, $depth + 1);
            }
        return $results;
    }
}

if ( ! function_exists( 'at_tradedoubler_multiselect_tax_form_dropdown' ) ) {
    /**
     * at_affilinet_multiselect_tax_form_dropdown
     *
     * Add a dropdown of all available values;
     */
    add_filter('at_mutltiselect_tax_form_product_dropdown', 'at_tradedoubler_multiselect_tax_form_dropdown', 10, 3);
    function at_tradedoubler_multiselect_tax_form_dropdown($output, $properties, $tax)
    {

        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (preg_match('/tradedoubler/',$actual_link)) {
            if (!empty($properties)) {
                $output .= '<select id="attributes-select-' . $tax . '" name="taxonomy-select-dropdown-' . $tax . '">';
                $output .= '<option value=""> -</option>';
                foreach ($properties as $property) {
                    if ($property['value'] != "") {
                        $output .= '<option value="' . $property['value'] . '">' . $property['name'] . " - " . $property['value'] . '</option>';
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


if (! function_exists('at_tradedoubler_get_umlaute_array')){
    function at_tradedoubler_get_umlaute_array(){
        return array('Ă'=>'ß',
            ''=>'ä',
            'Ăś'=>'ö',
            'Ăź'=>'ü',
            'Ă'=>'Ä',
            ''=>'Ö',
            ''=>'Ü',
        );
    }
}

if ( ! function_exists( 'at_tradedoubler_fix_umlaute' ) ) {
    /**
     * at_tradedoubler_fix_umlaute
     *
     * Ersetzt alle kaputten Umlaute eines Strings durch die korrekten Zeichen
     *
     * @param $string
     * @return mixed
     */
    function at_tradedoubler_fix_umlaute($string){
        foreach (at_tradedoubler_get_umlaute_array() as $key=>$value){
            $string = str_replace($key,$value,$string);
        }
        return $string;
    }
}