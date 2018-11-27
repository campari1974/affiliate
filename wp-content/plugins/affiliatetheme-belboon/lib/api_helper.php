<?php
/**
 * Belboon API - Diverse Hilfsfunktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @updated     2016/08/15
 */

if ( ! function_exists( 'bboon_array_insert' ) ) {
    /**
     * bboon_array_insert
     * @deprecated since 1.2.1
     *
     */
    function bboon_array_insert(&$array, $position, $insert) {
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

if ( ! function_exists( 'at_bboon_array_insert' ) ) {
    /**
     * at_bboon_array_insert
     *
     * Array helper
     * @param   array $array
     * @param   int $position
     * @param   int $insert
     * @return  -
     */
    function at_bboon_array_insert(&$array, $position, $insert) {
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

if ( ! function_exists( 'at_add_belboon_as_portal' ) ) {
    /**
     * at_add_belboon_as_portal
     *
     * Add Belboon to Product Portal Dropdown
     */
    add_filter('at_add_product_portal', 'at_add_belboon_as_portal', 10, 2);
    function at_add_belboon_as_portal($choices)
    {
        $choices['belboon'] = 'Belboon';
        return $choices;
    }
}

if ( ! function_exists( 'at_add_belboon_field_portal_id' ) ) {
    /**
     * at_add_belboon_field_portal_id
     *
     * Add Belboon Fields to Products
     */
    add_filter('at_add_product_fields', 'at_add_belboon_field_portal_id', 10, 2);
    function at_add_belboon_field_portal_id($fields) {
        $new_field[] = array(
            'key' => 'field_553b84842983d',
            'label' => __('Belboon ID', 'affiliatetheme-belboon'),
            'name' => 'belboon_id',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_553b83de246bb',
                        'operator' => '==',
                        'value' => 'belboon',
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

        at_bboon_array_insert($fields['fields'][4]['sub_fields'], 7, $new_field);
        return $fields;
    }
}

if ( ! function_exists( 'at_belboon_overwrite_product_button_short_text' ) ) {
    /**
     * at_belboon_overwrite_product_button_short_text
     *
     * Overwrite Product Button Text (short)
     */
    add_filter('at_product_api_button_short_text', 'at_belboon_overwrite_product_button_short_text', 10, 5);
    function at_belboon_overwrite_product_button_short_text($var = '', $product_portal = '', $product_shop = '', $pos = '', $short = false) {
        if ('belboon' == $product_portal && 'buy' == $pos) {
            $var = (get_option('belboon_buy_short_button') ? get_option('belboon_buy_short_button') : __('Kaufen', 'affiliatetheme-belboon'));

            if ($product_shop) {
                $var = sprintf($var, $product_shop->post_title);
            }
        }

        return $var;
    }
}

if ( ! function_exists( 'at_belboon_overwrite_product_button_text' ) ) {
    /**
     * at_belboon_overwrite_product_button_text
     *
     * Overwrite Product Button Text
     */
    add_filter('at_product_api_button_text', 'at_belboon_overwrite_product_button_text', 10, 5);
    function at_belboon_overwrite_product_button_text($var = '', $product_portal = '', $product_shop = '', $pos = '', $short = false) {

        if ('belboon' == $product_portal && 'buy' == $pos) {
            $var = (get_option('belboon_buy_button') ? get_option('belboon_buy_button') : __('Jetzt bei %s kaufen', 'affiliatetheme-belboon'));

            if ($product_shop) {
                $var = sprintf($var, $product_shop->post_title);
            }
        }

        return $var;
    }
}

if ( ! function_exists('at_belboon_notices') ) {
    /**
     * at_belboon_notices function.
     *
     */
    add_action('admin_notices', 'at_belboon_notices');
    function at_belboon_notices() {
        if ((isset($_GET['page']) && $_GET['page'] == 'endcore_api_belboon')) {
            // check php version
            if(version_compare(PHP_VERSION, '5.3.0', '<')) {
                ?>
                <div class="notice notice-error">
                    <p><?php printf(__('Achtung: Um dieses Plugin zu verwenden benötigst du mindestens PHP Version 5.3.x. Derzeit verwendest du Version %s.', 'affiliatetheme-belboon'), PHP_VERSION); ?></p>
                </div>
                <?php
            }

            // check curl
            if(extension_loaded('curl') != function_exists('curl_version')) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('Um dieses Plugin zu verwenden benötigst du cURL. <a href="http://php.net/manual/de/book.curl.php" taget="_blank">Hier</a> findest du mehr Informationen darüber. Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-belboon'); ?></p>
                </div>
                <?php
            }

            // check allow_url_fopen
            if(ini_get('allow_url_fopen') == false) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('Achtung: Du hast allow_url_fopen deaktiviert. Bitte kontaktiere deinen Administrator.', 'affiliatetheme-belboon'); ?></p>
                </div>
                <?php
            }

            // check soap
            if(extension_loaded('soap') == false) {
                ?>
                <div class="error" id="required-by-plugin">
                    <p><?php _e('Um dieses Plugin zu verwenden benötigst du SOAP. <a href="http://php.net/manual/en/book.soap.php" taget="_blank">Hier</a> findest du mehr Informationen darüber. Kontaktiere im Zweifel deinen Systemadministrator.', 'affiliatetheme-belboon'); ?></p>
                </div>
                <?php
            }
        }
    }
}

if ( ! function_exists( 'at_belboon_compare_box' ) ) {
    /**
     * at_belboon_compare_box
     *
     * Add Meta-Box to Product Page
     */
    add_action('add_meta_boxes', 'at_belboon_compare_box');
    function at_belboon_compare_box() {
        add_meta_box(
            'belboon_price_compare',
            '<span class="dashicons dashicons-search"></span> ' . __('Belboon Preisvergleich', 'affiliatetheme-belboon'),
            'at_belboon_compare_box_callback',
            'product'
        );
    }
}

if ( ! function_exists( 'at_belboon_compare_box_callback' ) ) {
    /**
     * at_belboon_compare_box_callback
     *
     * Add Meta-Box Content
     */
    function at_belboon_compare_box_callback($post) {
        $ean = get_post_meta($post->ID, 'product_ean', true);
        ?>

        <div id="at-import-page" class="at-import-page-belboon" data-url="<?php echo admin_url(); ?>" data-import-nonce="<?php echo wp_create_nonce("at_belboon_import_wpnonce"); ?>">
            <div class="alert alert-info api-alert">
                <span class="dashicons dashicons-megaphone"></span>
                <p><?php _e('Du kannst mit Hilfe des Preisvergleiches weitere Preise aus verschiedenen Shops zu diesem Produkt hinzufügen. Suche entweder nach der EAN oder einem Keyword und importiere weitere Preise. <br>
                Die neuen Preise werden sofort im oberen Feld hinzugefügt. Bitte speichere das Produkt wenn du fertig bist.', 'affiliatetheme-belboon'); ?></p>
            </div>

            <div class="form-container">
                <div class="form-group">
                    <label for="scompare_ean"><?php _e('EAN', 'affiliatetheme-belboon'); ?></label>
                    <input type="text" name="belboon_compare_ean" id="belboon_compare_ean" value="<?php echo $ean; ?>">
                </div>

                <div class="form-group">
                    <label for="compare_query"><?php _e('Keyword', 'affiliatetheme-belboon'); ?></label>
                    <input type="text" name="belboon_compare_query" id="belboon_compare_query">
                </div>

                <a href="#"
                   class="acf-button blue button belboon-price-compare"><?php _e('Preisvergleich ausführen', 'affiliatetheme-belboon'); ?></a>
            </div>
        </div>

        &nbsp;

        <div id="at-import-window" class="at-import-window-belboon">
            <table class="wp-list-table widefat fixed products">
                <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Alle auswählen', 'affiliatetheme-belboon'); ?></label
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th scope="col" id="productid" class="manage-column column-productid">
                        <span><?php _e('ID', 'affiliatetheme-belboon'); ?></span>
                    </th>
                    <th scope="col" id="productid" class="manage-column column-productid">
                        <span><?php _e('Artikelnummer', 'affiliatetheme-belboon'); ?></span>
                    </th>
                    <th scope="col" id="title" class="manage-column column-title">
                        <span><?php _e('Name', 'affiliatetheme-belboon'); ?></span>
                    </th>
                    <th scope="col" id="price" class="manage-column column-price">
                        <span><?php _e('Preis', 'affiliatetheme-belboon'); ?></span>
                    </th>
                    <th scope="col" id="actions" class="manage-column column-action" style="">
                        <span><?php _e('Aktion', 'affiliatetheme-belboon'); ?></span>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <a href="#" class="mass-import button button-primary"><?php _e('Ausgewählte Produkte importieren', 'affiliatetheme-belboon'); ?></a>
                    </td>
                </tr>
                </tfoot>
                <tbody id="resultsbelboon"></tbody>
            </table>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                // belboonsearchAction
                jQuery('.at-import-page-belboon').bind('keydown', function (event) {
                    if (event.keyCode == 13) {
                        belboonsearchAction();
                        event.preventDefault();
                    }
                });
                jQuery('.at-import-page-belboon .belboon-price-compare').click(function (event) {
                    belboonsearchAction();
                    event.preventDefault();
                });

                // belboonQuickImportAction
                jQuery('.belboon-quick-import').live('click', function (event) {
                    var id = jQuery(this).attr('data-id');

                    belboonQuickImportAction(id);

                    event.preventDefault();
                });

                // belboonMassImportAction
                jQuery('.at-import-window-belboon .mass-import').live('click', function (event) {
                    belboonMassImportAction(this);

                    event.preventDefault();
                });
            });

            var belboonsearchAction = function () {
                var target = jQuery('.at-import-page-belboon .belboon-price-compare');
                var ean = jQuery('.at-import-page-belboon #belboon_compare_ean').val();
                var query = jQuery('.at-import-page-belboon #belboon_compare_query').val();
                var query = (query.length < 3)?ean:query;
                var html = '';

                jQuery(target).append(' <i class="fa fa-circle-o-notch fa-spin"></i>').attr('disabled', true).addClass('noevent');

                jQuery.ajax({
                    url: ajaxurl,
                    dataType: 'json',
                    type: 'GET',
                    data: {action: 'at_belboon_search', q:query, order:'ASC',p:1}
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
                            html += '<td class="id">' + data['items'][x].id + '</td>';
                            html += '<td class="productid">' + data['items'][x].productid + '</td>';
                            html += '<td class="title"><a href="' + data['items'][x].url + '" target="_blank">' + data['items'][x].name + '</a></td>';
                            html += '<td class="price">' + data['items'][x].price + '</td>';
                            if (data['items'][x].exists != "false") {
                                html += '<td class="action"></td>';
                            } else {
                                html += '<td class="action"><a href="#" title="Quickimport" class="belboon-quick-import" data-id="' + data['items'][x].id + '"><i class="fa fa-bolt"></i></a></td>';
                            }
                            html += '</tr>';
                        }
                    } else {
                        html += '<tr><td colspan="7"><?php _e('Es wurde kein Produkt gefunden', 'affiliatetheme-belboon'); ?></td></tr>';
                    }
                }).always(function () {
                    jQuery(target).attr('disabled', false).removeClass('noevent').find('i').remove();
                    jQuery('#at-import-window tbody#resultsbelboon').html(html);
                });
            }

            var belboonQuickImportAction = function ( id, mass, i, max_items) {
                mass = mass || false;
                max_items = max_items || "0";
                i = i || "1";
                console.log("quickimport");
                console.log(id);
                var target = jQuery('#results .item[data-id=' + id + ']').find(".action a.belboon-quick-import");
                var ajax_loader = jQuery('.at-ajax-loader');
                var post_id = '<?php echo $post->ID; ?>';
                var nonce = jQuery('.at-import-page-belboon').attr('data-import-nonce');

                jQuery(target).append(' <i class="fa fa-circle-o-notch fa-spin"></i>').addClass('noevent');

                jQuery.ajaxQueue({
                    url: ajaxurl,
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        action: 'at_belboon_add_acf',
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
                            var priceoldfield = 'acf-field_557c01ea87000-'+field_id+'-field_553c9582146b5';
                            var currencyfield = 'acf-field_557c01ea87000-'+field_id+'-field_553b82b5246b6';
                            var portalfield = 'acf-field_557c01ea87000-'+field_id+'-field_553b83de246bb';
                            var belboonIDfield = 'acf-field_557c01ea87000-'+field_id+'-field_553b84842983d';
                            var shopfield = 'acf-field_557c01ea87000-'+field_id+'-field_557c058187007-input';
                            var urlfield = 'acf-field_557c01ea87000-'+field_id+'-field_553b834c246b9';
                            jQuery("#"+pricefield).val(shopinfo['price']);
                            jQuery("#"+priceoldfield).val(shopinfo['price_old']);
                            jQuery("#"+currencyfield).val(shopinfo['currency']);
                            jQuery("#"+portalfield).val(shopinfo['portal']);
                            jQuery("#"+belboonIDfield).val(shopinfo['metakey']);
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


            var belboonMassImportAction = function (target) {
                var max_items = jQuery('#results .item:not(".success") .check-column input:checkbox:checked').length;
                var i = 1;

                jQuery('#resultsbelboon .item:not(".success") .check-column input:checkbox:checked').each(function () {
                    var id = jQuery(this).val();
                    belboonQuickImportAction(id, true, i, max_items);
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