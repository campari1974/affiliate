jQuery(document).ready(function(){
    //Produktdaten Import
    jQuery(".at-productdata-import-thickbox").addClass("thickbox");
    jQuery(".at-productdata-import-thickbox").attr("href", ajaxurl + '?action=at_product_data_import_thickbox&width=800&height=720&post_id=' + jQuery("#product_data_import_post_id").val());

    jQuery(".at-testscores-import-thickbox").addClass("thickbox");
    jQuery(".at-testscores-import-thickbox").attr("href",ajaxurl + '?action=at_testscores_import_thickbox&width=800&height=720&post_id=' + jQuery("#testscores_import_post_id").val());

    jQuery(".at-update-product-data").live("click",function(event){
        saveProductData(this);
        event.preventDefault();
    });

    jQuery(".at-update-testscores").live("click",function(event){
        saveTestScores(this);
        event.preventDefault();
    });

    jQuery(window).live('tb_unload', function(){
        console.log("reloading");
        window.location.reload();
    });

    jQuery(".at-productdata-generate-fields").live("click",function(){
        generateFields(this);
        event.preventDefault();
    });

    jQuery(".at-productdata-get-thickbox-content").live("click",function(button){
        jQuery(".at-import-thickbox-result").hide();
        jQuery(button).parent().find(".spinner").addClass("is-active");
        var source = jQuery("#at-productdata-source").val();
        source = source.trim();
        var post_id = jQuery("#at-productdata-postid").val();
        jQuery.ajax({
            url: ajaxurl,
            dataType: 'json',
            type: 'POST',
            data: {action:'at_product_data_import_form', post_id:post_id, url:source},
            success: function (data) {
                jQuery(button).parent().find(".spinner").removeClass("is-active");
                jQuery(".at-import-thickbox-result").html(data);
                jQuery(".at-import-thickbox-result").show();
            },
            error: function (data) {
                jQuery(button).parent().find(".spinner").removeClass("is-active");
                jQuery(".at-import-thickbox-result").html(data.responseText);
                jQuery(".at-import-thickbox-result").show();
            },
        });
    })

    jQuery(".at-testscores-get-thickbox-content").live("click",function(button){
        jQuery(".at-import-thickbox-result").hide();
        jQuery(button).parent().find(".spinner").addClass("is-active");
        var source = jQuery("#at-testscores-source").val();
        source = source.trim();
        var post_id = jQuery("#at-testscores-postid").val();
        jQuery.ajax({
            url: ajaxurl,
            dataType: 'json',
            type: 'POST',
            data: {action:'at_testscores_import_form', post_id:post_id, url:source},
            success: function (data) {
                jQuery(button).parent().find(".spinner").removeClass("is-active");
                jQuery(".at-import-thickbox-result").html(data);
                jQuery(".at-import-thickbox-result").show();
            },
            error: function (data) {
                jQuery(button).parent().find(".spinner").removeClass("is-active");
                jQuery(".at-import-thickbox-result").html(data.responseText);
                jQuery(".at-import-thickbox-result").show();
            },
        });
    })

});

var saveProductData = function(button){
    jQuery(".import-product-data-result").hide();
    jQuery(button).parent().find(".spinner").addClass("is-active");
    var data = jQuery('form#at_product_data_import_form').serialize();
    jQuery.ajax({
        url: ajaxurl,
        dataType: 'json',
        type: 'POST',
        data: data,
        success: function (data) {
            jQuery(button).parent().find(".spinner").removeClass("is-active");
            if(!data['error']) {
                jQuery(".import-product-data-result").html(data['success']);
                jQuery(".import-product-data-result").show();
                window.location.reload(true);
            } else {
                jQuery(".import-product-data-result").html(data['error']);
                jQuery(".import-product-data-result").show();
            }
        },
        error: function () {
            jQuery(button).parent().find(".spinner").removeClass("is-active");
        },
    });
};

var generateFields = function(button){
    jQuery(".at-import-thickbox-result").hide();
    jQuery(button).parent().find(".spinner").addClass("is-active");
    var source = jQuery("#at-productdata-source").val();
    source = source.trim();
    jQuery.ajax({
        url: ajaxurl,
        dataType: 'json',
        type: 'POST',
        data: {
          action:'at_product_data_generate_fields',
            url: source,
        },
        success: function (data) {
            jQuery(button).parent().find(".spinner").removeClass("is-active");
            if(!data['error']) {
                window.location.reload(true);
            } else {
                jQuery(".at-import-thickbox-result").html(data['error']);
                jQuery(".at-import-thickbox-result").show();
            }
        },
        error: function () {
            jQuery(button).parent().find(".spinner").removeClass("is-active");
        },
    });
};

var saveTestScores = function(button){
    jQuery(".import-testscores-result").hide();
    jQuery(button).parent().find(".spinner").addClass("is-active");
    var data = jQuery('form#at_testscores_import_form').serialize();
    jQuery.ajax({
        url: ajaxurl,
        dataType: 'json',
        type: 'POST',
        data: data,
        success: function (data) {
            jQuery(button).parent().find(".spinner").removeClass("is-active");
            if(!data['error']) {
                jQuery(".import-testscores-result").html(data['success']);
                jQuery(".import-testscores-result").show();
                window.location.reload(true);
            } else {
                jQuery(".import-testscores-result").html(data['error']);
                jQuery(".import-testscores-result").show();
            }
        },
        error: function () {
            jQuery(button).parent().find(".spinner").removeClass("is-active");
        },
    });
};