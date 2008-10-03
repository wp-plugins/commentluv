function commentluv(cl_settings){
	jQuery(document).ready(function() {
		parentformname = jQuery(cl_settings[3]).parents("form").attr("id");
		if(!cl_settings[0]){
			cl_settings[0]=parentformname;
		}
		var cl_badge=cl_settings[7];
		var checked=cl_settings[9];
		jQuery('#'+cl_settings[0]).after(cl_settings[12]+'<div id="mylastpostbox"><div style="float:left"><input type="checkbox" id="luv" '+cl_settings[9]+'/></div><div style="float:left"><span id="mylastpost" style="clear: both"><a href="http://www.commentluv.com">'+cl_badge+'</a></span>' + '<br/><select name="lastposts" id="lastposts"></select></div></div>');
		jQuery('#'+parentformname).append('<input type="hidden" id="cl_post" name="cl_post"></input>');
		jQuery('#lastposts').hide();
		if(cl_settings[10]){
			jQuery('abbr em a').click(processclick);
		}
		jQuery(cl_settings[3]).focus(function(){
			cl_dostuff(cl_settings);
		});
		jQuery('#lastposts').change(function(){
			jQuery('option').remove(":contains('"+cl_settings[5]+"'");
			var url = jQuery(this).val();
			var title = jQuery('#lastposts option:selected').text();
			jQuery('#mylastpost a').replaceWith('<a href="' + url + '">' + title + '</a>');
			jQuery('#cl_post').val('<a href="' + url + '">' + title + '</a>');
		});
		jQuery(cl_settings[2]).change(function(){ 
			if(jQuery('#luv').is(":checked")){
				jQuery('#lastposts').empty(); 
				jQuery(cl_settings[3]).bind('focus',cl_dostuff(cl_settings));
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
			var check=jQuery(cl_settings[2]).val();
			// return if no url or checkbox is unticked or is admin
			if(!check || !jQuery('#luv').is(":checked") || cl_settings[13]) { return }
			var xyz=jQuery(cl_settings[2]).val();
			var name=jQuery(cl_settings[1]).val();
			var url="http://www.commentluv.com/commentluvinc/ajaxcl821.php?url="+xyz+"&callback=?";
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
				jQuery(cl_settings[3]).unbind();
			});
		}
	});
}