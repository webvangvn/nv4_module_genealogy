/**
 * @Project NUKEVIET 4.x
 * @Author webvang (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2015 J&A.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11 - 10 - 2015 5 : 12
 */

function split(val) {
	return val.split(/,\s*/);
}

function extractLast(term) {
	return split(term).pop();
}


$("#titlelength").html($("#idtitle").val().length);
$("#idtitle").bind("keyup paste", function() {
	$("#titlelength").html($(this).val().length);
});

$("#descriptionlength").html($("#description").val().length);
$("#description").bind("keyup paste", function() {
	$("#descriptionlength").html($(this).val().length);
});

$(document).ready(function() {
	$("input[name='fids[]']").click(function() {
		var fid = $("input:radio[name=fid]:checked").val();
		var radios_fid = $("input:radio[name=fid]");
		var fids = [];
		$("input[name='fids[]']").each(function() {
			if ($(this).prop('checked')) {
				$("#famright_" + $(this).val()).show();
				fids.push($(this).val());
			} else {
				$("#famright_" + $(this).val()).hide();
				if ($(this).val() == fid) {
					radios_fid.filter("[value=" + fid + "]").prop("checked", false);
				}
			}
		});

		if (fids.length > 1) {
			for ( i = 0; i < fids.length; i++) {
				$("#famright_" + fids[i]).show();
			};
			fid = parseInt($("input:radio[name=fid]:checked").val() + "");
			if (!fid) {
				radios_fid.filter("[value=" + fids[0] + "]").prop("checked", true);
			}
		}
	});
	$("#publ_date,#exp_date").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
		buttonImageOnly : true
	});


	$("#keywords-search").bind("keydown", function(event) {
		if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
			event.preventDefault();
		}

        if(event.keyCode==13){
            var keywords_add= $("#keywords-search").val();
            keywords_add = trim( keywords_add );
            if( keywords_add != '' ){
                nv_add_element( 'keywords', keywords_add, keywords_add );
                $(this).val('');
            }
            return false;
    	}

	}).autocomplete({
		source : function(request, response) {
			$.getJSON(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=tagsajax", {
				term : extractLast(request.term)
			}, response);
		},
		search : function() {
			// custom minLength
			var term = extractLast(this.value);
			if (term.length < 2) {
				return false;
			}
		},
		focus : function() {
		  //no action
		},
		select : function(event, ui) {
			// add placeholder to get the comma-and-space at the end
			if(event.keyCode!=13){
	            nv_add_element( 'keywords', ui.item.value, ui.item.value );
	            $(this).val('');
	           }
            return false;
		}
	});

    $("#keywords-search").blur(function() {
		// add placeholder to get the comma-and-space at the end
        var keywords_add= $("#keywords-search").val();
        keywords_add = trim( keywords_add );
        if( keywords_add != '' ){
            nv_add_element( 'keywords', keywords_add, keywords_add );
            $(this).val('');
        }
        return false;
	});
    $("#keywords-search").bind("keyup", function(event) {
		var keywords_add= $("#keywords-search").val();
        if(keywords_add.search(',') > 0 )
        {
            keywords_add = keywords_add.split(",");
            for (i = 0; i < keywords_add.length; i++) {
                var str_keyword = trim( keywords_add[i] );
                if( str_keyword != '' ){
                    nv_add_element( 'keywords', str_keyword, str_keyword );
                }
            }
            $(this).val('');
        }
        return false;
	});

	// hide message_body after the first one
	$(".message_list .message_body:gt(1)").hide();

	// hide message li after the 5th
	$(".message_list li:gt(5)").hide();

	// toggle message_body
	$(".message_head").click(function() {
		$(this).next(".message_body").slideToggle(500);
		return false;
	});

	// collapse all messages
	$(".collpase_all_message").click(function() {
		$(".message_body").slideUp(500);
		return false;
	});

	// Show all messages
	$(".show_all_message").click(function() {
		$(".message_body").slideDown(1000);
		return false;
	});
	
});

function nv_add_element( idElment, key, value ){
   var html = "<span title=\"" + value + "\" class=\"uiToken removable\" ondblclick=\"$(this).remove();\">" + value + "<input type=\"hidden\" value=\"" + key + "\" name=\"" + idElment + "[]\" autocomplete=\"off\"><a onclick=\"$(this).parent().remove();\" href=\"javascript:void(0);\" class=\"remove uiCloseButton uiCloseButtonSmall\"></a></span>";
    $("#" + idElment).append( html );
	return false;
}