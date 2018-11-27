var globalRequest = 0;

jQuery(document).ready(function() {
    // checkAdblock
    checkAdblock();

    // checkConnection
    checkConnection();

    //getProgramList();

    // searchActions
    jQuery('input#search').bind('keyup', function(event) {
        if (event.keyCode == 13) {
            jQuery('#at-import-window input#page').val('1');
            searchAction();
        }
    });
    jQuery('#search-link').bind('click', function(event) {
        jQuery('#at-import-window input#page').val('1');
        searchAction();

        event.preventDefault();
    });

    // singleImportAction
    jQuery('.single-import-product:not(.noevent)').live('click', function(event) {
        singleImportAction(this);

        event.preventDefault();
    });

    // quickImportAction
    jQuery('.quick-import').live('click', function(event) {
        var id = jQuery(this).attr('data-id');

        quickImportAction(id);

        event.preventDefault();
    });

    // massImportAction
    jQuery('.mass-import:not(.noevent)').live('click', function(event) {
        massImportAction(this);

        event.preventDefault();
    });

    // getProgramList
    jQuery('#at-import-window select#adspaces').change(function() {
        if(jQuery(this).val() != "") {
            getProgramList();
        } else {
            jQuery('#at-import-window select#program').html('<option value="">-</option>');
        }
    });

    /*
     * Stuff
     */
    jQuery("input[type=checkbox].unique").live('click', function() {
        if(jQuery("input[type=checkbox].unique").length > 1) {
            jQuery("input[type=checkbox].unique").each(function() {
                jQuery(this)[0].checked = false;
            });
            jQuery(this)[0].checked = true;
        }
    });

    jQuery("input[type=checkbox].disable-this").live('click', function() {
        if(jQuery(this).attr('checked')){
            jQuery(this).closest('.image').css('opacity', '0.5');
        } else {
            jQuery(this).closest('.image').css('opacity', '1');
        }
    });

    // pagination
    jQuery('.next-page').bind('click', function(event) {
        var current_page = parseInt(jQuery('#page').val());
        var max_pages = parseInt(jQuery('#max-pages').val());

        jQuery(this).attr('disabled', true);

        if(current_page < max_pages) {
            jQuery('#page').val(current_page + 1);
        }
        searchAction();
    });
    jQuery('.prev-page').bind('click', function(event) {
        var current_page = parseInt(jQuery('#page').val());
        var max_pages = parseInt(jQuery('#max-pages').val());

        jQuery(this).attr('disabled', true);

        if(current_page <= max_pages) {
            jQuery('#page').val(current_page - 1);
        }
        searchAction();
    });

    // clear API Log
    jQuery('.clear-api-log').click(function(e) {
        var btn = jQuery(this);
        var type = jQuery(this).data('type');
        var hash = jQuery(this).data('hash');

        jQuery(btn).attr('disabled', true).append(' <i class="fa fa-circle-o-notch fa-spin"></i>');

        jQuery.ajax({
            url: ajaxurl,
            dataType: 'json',
            type: 'GET',
            data: "action=at_api_clear_log&hash="+hash+"&type="+type,
            success: function(data){
                jQuery(btn).attr('disabled', false).find('i').remove();

                if(data['status'] == 'success') {
                    jQuery('table.apilog tbody').html('');
                }
            },
            error: function() {
                jQuery(btn).attr('disabled', false).find('i').remove();
            }
        });

        e.preventDefault();
    });

    // api Tabs
	jQuery("#at-api-tabs a.nav-tab").click(function(e){
		jQuery("#at-api-tabs a").removeClass("nav-tab-active");
		jQuery(".at-api-tab").removeClass("active");

		var a = jQuery(this).attr("id").replace("-tab","");
		jQuery("#"+a).addClass("active");
		jQuery(this).addClass("nav-tab-active");	
	});

    jQuery(document).ready(function(e) {
        var a=window.location.hash.replace("#top#","");
        (""==a||"#_=_"==a) &&(a=jQuery(".at-api-tab").attr("id")),jQuery('#at-api-tabs a').removeClass('nav-tab-active'),jQuery('.at-api-tab').removeClass('active'),jQuery("#"+a).addClass("active"),jQuery("#"+a+"-tab").addClass("nav-tab-active");
    })

    // Buttons
    jQuery(function($) {
        $(document).ajaxStop(function() {
            jQuery('#search-link').attr('disabled', false).find('.fa-spin').remove();
            jQuery('.next-page, .prev-page').attr('disabled', false);
        });
    });
});

/*
 * Function:
 * checkConnection
 */
var checkConnection = function() {
    jQuery('#search-link').attr('disabled', true).append(' <i class="fa fa-circle-o-notch fa-spin"></i>').after(' <small class="status-after" style="margin: 5px;display: inline-block;">' + zanox_vars.connection + '</small>');

    var resultContainer = jQuery('#checkConnection');

    if (globalRequest == 1) {
        return;
    }

    globalRequest = 1;
    jQuery.ajax({
        url: ajaxurl,
        dataType: 'json',
        type: 'GET',
        data: "action=at_zanox_adspaces",
        success: function(data){
            if(!data['message']) {
                var html = '';
                html += '<option value="">-</option>';
                for (var x in data['items']) {
                    html += '<option value="' + data['items'][x]['id'] + '" ' + (('true2' == data['items'][x]['current']) ? 'selected' : '') + '>' + data['items'][x]['name'] + '</option>';
                }
                jQuery('#at-import-window select#adspaces').html(html);

                resultContainer.fadeOut('fast', function() {
                    resultContainer.append('<div class="updated"><p>' + zanox_vars.connection_ok + '</p></div>');
                    resultContainer.fadeIn('fast');
                });

                //jQuery('#search-link').attr('disabled', false);
                jQuery('.status-after').remove();
            } else {
                resultContainer.fadeOut('fast', function() {
                    resultContainer.append('<div class="error"><p>' + zanox_vars.connection_error + ' <i>'+data['message']+'</i></p></div>');
                    resultContainer.fadeIn('fast');
                });

                jQuery('.status-after').remove();
            }
        },
        error: function() {
            resultContainer.fadeOut('fast', function() {
                resultContainer.append('<div class="error"><p>' + zanox_vars.connection_error + ' <i>'+data['message']+'</i></p></div>');
                resultContainer.fadeIn('fast');
            });

            jQuery('.status-after').remove();
        },
    });
};

/*
 * Function:
 * searchAction
 */
var searchAction = function() {
    if(jQuery('#search-link').prop('disabled')) {
        return
    }

    var q = jQuery('#at-import-window #search').val();
    var adspace = jQuery('#at-import-window #adspaces').val();
    var program = jQuery('#at-import-window #program').val();
    var min_price = jQuery('#at-import-window #min_price').val();
    var max_price = jQuery('#at-import-window #max_price').val();
    var items = jQuery('#at-import-window #items').val();
    var page = jQuery('#at-import-window #page').val();
    var resultContainer = jQuery('#at-import-window #results');

    jQuery('#search-link').attr('disabled', true).append(' <i class="fa fa-circle-o-notch fa-spin"></i>');

    globalRequest = 1;
    jQuery.ajax({
        url: ajaxurl,
        dataType: 'json',
        type: 'GET',
        data: "action=at_zanox_products&q=" + q + "&adspace=" + adspace + "&program=" + program + "&min_price=" + min_price + "&max_price=" + max_price + "&items=" + items + "&p=" + page,
        success: function(data){
			if(!data) {
				html = '<tr class="item error" data-id="">';
				html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-1 name="item[]" value="0" disabled="disabled"></th>';
				html += '<td colspan="7">' + zanox_vars.no_products_found+ '</td>';
				html += '</tr>';
				resultContainer.append(html);

				jQuery('table.products tfoot .taxonomy-select').fadeOut();
				
				return;
			}
			
            var totalpages = data['extras'].pages;
            jQuery('#info-title').html('<h4>Es wurde(n) '+totalpages+' Seite(n) gefunden.</h4>');
            jQuery('#max-pages').val(totalpages);
            if(totalpages == 1) {
                jQuery('.page-links').hide();
            } else if(totalpages > 1) {
                jQuery('.page-links').show();
                if(page == 1) { jQuery('.page-links .prev-page').hide(); } else if(page > 1) { jQuery('.page-links .prev-page').show(); }
                if(page >= totalpages) { jQuery('.page-links .next-page').hide(); } else { jQuery('.page-links .next-page').show(); }
            }

            globalRequest = 0;
            resultContainer.fadeOut('fast', function() {
                resultContainer.html('');

                if(data['extras'].items != null) {
                    for (var x in data['items']) {
                        if (!data['items'][x].price)
                            data['items'][x].price = 'kA';

                        if (!data['items'][x].img)
                            data['items'][x].img = 'assets/images/no.gif';

                        var html = '';

                        if(data['items'][x].exists != "false") {
                            html += '<tr class="item success" data-id="'+data['items'][x].id+'">';
                            html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-'+data['items'][x].id+' name="item[]" value="'+data['items'][x].id+'" disabled="disabled"></th>';
                        } else {
                            html += '<tr class="item" data-id="'+data['items'][x].id+'">';
                            html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-'+data['items'][x].id+' name="item[]" value="'+data['items'][x].id+'"></th>';
                        }
                        html += '<td class="id">'+data['items'][x].id+'</td>';
						if(data['items'][x].images) {
							if(data['items'][x].images.medium !== undefined) {
								html += '<td class="image"><img src="'+data['items'][x].images.medium+'"></td>';
							} else if(data['items'][x].images.small !== undefined) {
								html += '<td class="image"><img src="'+data['items'][x].images.small+'"></td>';
							} else if(data['items'][x].images.large !== undefined) {
								html += '<td class="image"><img src="'+data['items'][x].images.large+'"></td>';
							} else {
								html += '<td class="image">' + zanox_vars.no_image + '</td>';
							}
						} else {
							html += '<td class="image">' + zanox_vars.no_image + '</td>';
						}
                        html += '<td class="title"><a href="'+data['items'][x].url+'" target="_blank">'+data['items'][x].name+'</a></td>';
                        html += '<td class="description">'+data['items'][x].description+'</td>';
                        html += '<td class="price">'+data['items'][x].price+'</td>';
                        if(data['items'][x].exists != "false") {
                            html += '<td class="action"><a href="' + jQuery('#at-import-page').attr('data-url') + 'post.php?post=' + data['items'][x].exists + '&action=edit" target="_blank" title="' + zanox_vars.edit + '"><i class="fa fa-edit"></i></a></td>';
                        } else {
                            html += '<td class="action"><a href="' + ajaxurl + '?action=at_zanox_lookup&id=' + data['items'][x].id + '&height=700&width=820" class="thickbox" title="' + zanox_vars.import + '"><i class="fa fa-plus-circle"></i></a> <a href="#" title="Quickimport" class="quick-import" data-id="'+data['items'][x].id+'"><i class="fa fa-bolt"></i></a></td>';
                        }
                        html += '</tr>';

                        resultContainer.append(html);
                        jQuery('table.products tfoot .taxonomy-select').fadeIn();
                    }
                } else {
                    html += '<tr class="item error" data-id="">';
                    html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-1 name="item[]" value="0" disabled="disabled"></th>';
                    html += '<td colspan="7">' + zanox_vars.no_products_found + '</td>';
                    html += '</tr>';
                    resultContainer.append(html);

                    jQuery('table.products tfoot .taxonomy-select').fadeOut();
                }

                resultContainer.fadeIn('fast');
            });
        }
    });
};

/*
 * Function:
 * singleImportAction
 */
var singleImportAction = function(target) {
    jQuery(target).append(' <i class="fa fa-circle-o-notch fa-spin"></i>').addClass('noevent');
    var data = jQuery( 'form#import-product' ).serialize();
    jQuery.ajax({
        url: ajaxurl,
        dataType: 'json',
        type: 'POST',
        data: data,
        success: function(data){
            jQuery(target).find('i').remove();
            var id = jQuery('#TB_ajaxContent #id').val();

            if(data['rmessage']['success'] == "false") {
                jQuery(target).after('<div class="error">'+data['rmessage']['reason']+'</div>');
                jQuery(target).append(' <i class="fa fa-exclamation-triangle"></i>').attr('disabled', true);
            } else if(data['rmessage']['success'] == "true") {
                jQuery(target).hide();
                jQuery(target).after('<a class="button button-primary" href="' + jQuery('#at-import-page').attr('data-url') + 'post.php?post='+data['rmessage']['post_id']+'&action=edit"><i class="fa fa-pencil"></i> Produkt bearbeiten</a>');
                jQuery('body table.products tr[data-id='+id+']').addClass('success');
                jQuery('body table.products tr[data-id='+id+'] .check-column input[type=checkbox]').attr('disabled', 'disabled');
                jQuery('body table.products tr[data-id='+id+'] .action i').removeClass('fa-plus-circle').addClass('fa-check-circle');
            }
        }
    });
};

/*
 * Function:
 * quickImportAction
 */
var quickImportAction = function(id, mass, i, max_items) {
    mass = mass || false;
    i = i || "1";
    max_items = max_items || "0";

    var target = jQuery('#results .item[data-id=' + id + ']').find(".action a.quick-import");
    var ajax_loader = jQuery('.at-ajax-loader');
    var id = jQuery(target).attr('data-id');
    var nonce = jQuery('#at-import-page').attr('data-import-nonce');
    var data = {action : 'at_zanox_import', id : id, func : 'quick-import', '_wpnonce' : nonce};
    var tax_data = {};

    /*
     * Check Taxonomies
     */
    var taxonomy_selects = jQuery('.tabwrapper .at-api-tab#search table.products tfoot .taxonomy-select');
    if(taxonomy_selects.length) {
        var tax_data = {};
        jQuery(taxonomy_selects).find('select').each(function(item) {
            var key = jQuery(this).attr('name');
            var value = jQuery(this).val();
            tax_data[key] = value;
        });
        jQuery(taxonomy_selects).find('input').each(function(item) {
            var key = jQuery(this).attr('name');
            var value = jQuery(this).val();

            if(value.length != 0 && key != undefined) {
                if(jQuery.isArray(tax_data[key])) {
                    tax_data[key].push(value);
                } else {
                    tax_data[key] = value;
                }
            }
        });

        jQuery.extend(data, tax_data);
    }

    jQuery(target).append(' <i class="fa fa-circle-o-notch fa-spin"></i>').addClass('noevent');

    jQuery.ajaxQueue({
        url: ajaxurl,
        dataType: 'json',
        type: 'POST',
        data: data,
        success: function(data){
            jQuery(target).find('i').remove();

            if(data['rmessage']['success'] == "false") {
                jQuery(target).after('<div class="error">' + data['rmessage']['reason'] + '</div>');
                jQuery(target).append(' <i class="fa fa-exclamation-triangle"></i>').attr('disabled', true);
            } else if(data['rmessage']['success'] == "true") {
                jQuery(target).hide();
                jQuery('body table.products tr[data-id=' + id + ']').addClass('success');
                jQuery('body table.products tr[data-id=' + id + '] .check-column input[type=checkbox]').attr('disabled', 'disabled');
                jQuery('body table.products tr[data-id=' + id + '] .action i').removeClass('fa-plus-circle').addClass('fa fa-edit').closest('a').removeClass('thickbox').attr('target', '_blank').attr('href', jQuery('#at-import-page').attr('data-url') + 'post.php?post='+data['rmessage']['post_id']+'&action=edit');
            }

            if(mass == true) {
                var curr = parseInt(jQuery(ajax_loader).find('.progress-bar').attr('data-item'));
                if(curr == 0) {
                    curr = 1;
                }

                var procentual = (100/max_items)*curr;
                var procentual_fixed =  procentual.toFixed(2);
                jQuery(ajax_loader).find('.progress-bar').css('width', procentual+'%').html(procentual_fixed+'%');
                jQuery(ajax_loader).find('.progress-bar').attr('data-item', curr+1);
                jQuery(ajax_loader).find('.current').html(curr+1);

                if(i >= max_items) {
                    jQuery(ajax_loader).removeClass('active');
                }
            }
        }
    });
};

/*
 * Function
 * massImportAction
 */
var massImportAction = function(target) {
    var max_items = jQuery('#results .item:not(".success") .check-column input:checkbox:checked').length;
    var ajax_loader = jQuery('.at-ajax-loader');
    var i = 1;

    // break if no product checked
    if(max_items < 1) {
        return false;
    }

    jQuery(ajax_loader).find('span.current').html('0');
    jQuery(ajax_loader).find('.progress-bar').css('width', '0%').html('0%').attr('data-item', 0);
    jQuery(ajax_loader).addClass('active').find('p').html(zanox_vars.import_count + ' ' + max_items);

    jQuery('#results .item:not(".success") .check-column input:checkbox:checked').each(function () {
        var id = jQuery(this).val();
        quickImportAction(id, true, i, max_items);
        i++;
    });
};

/*
 * Function
 * getProgramList
 */
var getProgramList = function() {
    var spaceid = jQuery('#at-import-window select#adspaces').val();

    jQuery.ajax({
        url: ajaxurl,
        dataType: 'json',
        type: 'GET',
        data: "action=at_zanox_programs&spaceid=" + spaceid,
        success: function(data){
            var html = '';
            html += '<option value="">-</option>';
            for (var x in data['items']) {
                html += '<option value="' + data['items'][x]['id'] + '">' + data['items'][x]['name'] + '</option>';
            }
            jQuery('#at-import-window select#program').html(html);
        }
    });
}

/*
 * Function
 * checkAdblock
 */
var checkAdblock = function() {
    setTimeout(function() {
        if(!document.getElementsByClassName) return;
        var ads = document.getElementsByClassName('afs_ads'),
            ad  = ads[ads.length - 1];

        if(!ad
            || ad.innerHTML.length == 0
            || ad.clientHeight === 0) {
            jQuery('#checkConnection').append('<div class="alert alert-danger">' + zanox_vars.adblocker_hint + '</div>');
        } else {
            ad.style.display = 'none';
        }

    }, 2000);
}

/*
 * Select2 for Taxonomy-Selects
 */
jQuery(document).ready(function() {
    jQuery("table .taxonomy-select select").select2();
    jQuery("table .taxonomy-select .col-xs-6").unwrap().removeClass('col-xs-6');
});

/*
 * Function
 * jQuery Queue
 */
(function($) {
    var ajaxQueue = $({});
    $.ajaxQueue = function(ajaxOpts) {
        var oldComplete = ajaxOpts.complete;
        ajaxQueue.queue(function(next) {
            ajaxOpts.complete = function() {
                if (oldComplete) oldComplete.apply(this, arguments);
                next();
            };
            $.ajax(ajaxOpts);
        });
    };
})(jQuery);