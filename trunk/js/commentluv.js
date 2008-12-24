jQuery.noConflict();
function commentluv(cl_settings){
	jQuery(document).ready(function() {
		var formObj = jQuery("textarea[name='" + cl_settings[3] + "']").parents("form"); // get form object that is parent of textarea named "comment"
		if(cl_settings[0]==""){
			cl_settings[0]=formObj;
		} else {
			formObj = '#' + cl_settings[0];
		}
		// auto set url, comment and author field
	
		var urlObj = jQuery("input[name='" + cl_settings[2] + "']",formObj);
		var comObj = jQuery("textarea[name='" + cl_settings[3] + "']",formObj);
		var autObj = jQuery("input[name='" + cl_settings[1] + "']",formObj);
		var cl_badge=cl_settings[7];
		var checked=cl_settings[9];
		jQuery(formObj).after(cl_settings[12]+'<div id="mylastpostbox"><div style="float:left"><input type="checkbox" id="luv" '+cl_settings[9]+'/></div><div style="float:left"><span id="mylastpost" style="clear: both"><a href="http://www.commentluv.com">'+cl_badge+'</a></span>' + '<br/><select name="lastposts" id="lastposts"></select></div></div><div style="clear:both"></div>');
		jQuery(formObj).append('<input type="hidden" id="cl_post" name="cl_post"></input>');
		jQuery('#lastposts').hide();
		if(cl_settings[10]=="1"){
			var cl_member_id=cl_settings[14];
			var cl_version=cl_settings[15];
			// do click on last blog post link, store click, show click, open in new window
			jQuery('abbr em a').click(function(){
				var url=jQuery(this).attr('href');
				var thelinkobj=jQuery(this);
				jQuery(thelinkobj).attr('target','_blank');
				var addit=url + "&cl_member_id=" + cl_member_id + "&callback=?";
				var clurl="http://www.commentluv.com/commentluvinc/ajaxcl_click821.php?url=" + addit;
				jQuery.getJSON(clurl,function(data) {
					jQuery.each(data.msg,function(i,item) {
						jQuery(thelinkobj).text(data.msg[i].text + jQuery(thelinkobj).text());})
						return true;
				}); 
			});
		}
		jQuery(comObj).focus(function(){
			cl_dostuff(cl_settings);
		});
		jQuery('#lastposts').change(function(){
			jQuery('option').remove(":contains('"+cl_settings[5]+"'");
			var url = jQuery(this).val();
			var title = jQuery('#lastposts option:selected').text();
			jQuery('#mylastpost a').replaceWith('<a href="' + url + '">' + title + '</a>');
			jQuery('#cl_post').val('<a href="' + url + '">' + title + '</a>');
		});
		jQuery(urlObj).change(function(){
			if(jQuery('#luv').is(":checked") && jQuery('#cl_post').val()!=""){
				jQuery('#lastposts').empty();
				cl_dostuff(cl_settings);
				jQuery(comObj).unbind();
				//jQuery(cl_settings[3]).bind('focus',cl_dostuff(cl_settings));
			}
		});
		jQuery('#luv').click(function(){
			if(jQuery(this).is(":checked")){
				// was unchecked, now is checked
				jQuery('#cl_post').val(jQuery('#mylastpost abbr em').html());
				jQuery('#lastposts').attr("disabled", false);
				jQuery('#mylastpost abbr em').fadeTo("slow", 1);

			} else {
				// was checked, user unchecked it so empty hidden field in form
				jQuery('#cl_post').val("");
				jQuery('#lastposts').attr("disabled", true);
				jQuery('#mylastpost abbr em').fadeTo("slow", 0.33);
			}
		});

		function cl_dostuff(cl_settings){
			var check=jQuery(urlObj).val();
			// return if no url or checkbox is unticked or is admin
			if(!check || !jQuery('#luv').is(":checked") || cl_settings[13]) { return }
			var xyz=jQuery(urlObj).val();
			var name=jQuery(autObj).val();
			var url="http://www.commentluv.com/commentluvinc/ajaxcl8254.php?url="+xyz+"&version=" + cl_version +"&callback=?";
			jQuery.getJSON(url,function(data){
				jQuery.each(data.links, function(i,item){
					jQuery('#lastposts').append('<option value="'+data.links[i].url+'">'+data.links[i].title+'</option>');
				});
				jQuery('#lastposts').append('<option value="0" selected=selected>'+cl_settings[5]+'</option>');
				jQuery('#lastposts').fadeIn(1000);
				jQuery('#mylastpost').html('<abbr><em><a href="' + data.links[0].url + '">' + data.links[0].title + '</a></em></abbr>').fadeIn(1000);
				if(jQuery('#luv').is(":checked")){
					jQuery('#cl_post').val('<a href="' + data.links[0].url + '">' + data.links[0].title + '</a>');
				}

			});
			jQuery(comObj).unbind();
			
		}
	});
}