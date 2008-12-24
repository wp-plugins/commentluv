jQuery.noConflict();
function commentluv(cl_settings){
	jQuery(document).ready(function() {
		parentformname = jQuery('#IDCommentNewThreadText').parents("form").attr("id");
		if(cl_settings[0]==""){
			cl_settings[0]=parentformname;
		}
		var cl_badge=cl_settings[7];
		var checked=cl_settings[9];
		// put clone of text area after label for url
				
		if(jQuery('#IDCommentsNewThreadListItem1 a').attr("href").indexOf("people/0") > 0){
			jQuery('label[for="txtURLNewThread"]').after(jQuery('#IDCommentNewThreadText').clone().attr({id:"CLIDCommentNewThreadText"}));
			jQuery('#IDCommentNewThreadText').hide();
		}
		jQuery('#IDCommentsNewThreadListLinkl a').change(function(){
			jQuery('#IDCommentNewThreadText').show();
		});
		// add the commentluv pull down box and gubbins
		jQuery('#CLIDCommentNewThreadText').after(cl_settings[12]+'<div id="mylastpostbox"><div style="float:left"><input type="checkbox" id="luv" '+cl_settings[9]+'/></div><div style="float:left"><span id="mylastpost" style="clear: both"><a href="http://www.commentluv.com">'+cl_badge+'</a></span>' + '<br/><select name="lastposts" id="lastposts"></select></div></div>');
		//hide the gubbins
		jQuery('#lastposts').hide();
		jQuery('#CLIDCommentNewThreadText').show();
		// click tracking
		if(cl_settings[10]=="1"){
			var cl_member_id=cl_settings[14];
			var cl_version=cl_settings[15];
			jQuery('.idc-c-t em a').click(function(){
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
		// event to wait for to fire fetch of posts
		jQuery('#CLIDCommentNewThreadText').focus(function(){
			cl_dostuff(cl_settings);
		});
		// event for submit form
		jQuery('#IDNewThreadSubmitLI').click(function(){
			cl_addstuff();
		});
		jQuery('#lastposts').change(function(){
			jQuery('option').remove(":contains('"+cl_settings[5]+"'");
			var url = jQuery(this).val();
			var title = jQuery('#lastposts option:selected').text();
			jQuery('#mylastpost a').replaceWith('<a href="' + url + '">' + title + '</a>');
			jQuery('#cl_post').val('<a href="' + url + '">' + title + '</a>');
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
			var check=jQuery('#IDCommentsNewThreadListItem1 a').attr("href").indexOf("people/0");
			if(check > 0){
				// not logged in to intense debate so set check to url field
				check = jQuery('#txtURLNewThread').val();
			} else {
				// is logged in using Intense username
				check=jQuery('#IDCommentsNewThreadListItem1 a').attr("href");
			}
			// return if no url or checkbox is unticked or is admin
			if(!check || !jQuery('#luv').is(":checked") ) {  return } //|| cl_settings[13]
			var xyz=check;
			var name=jQuery('#IDCommentsNewThreadListItem1 a').text();
			var url="http://www.commentluv.com/commentluvinc/ajaxcl8254.php?url="+xyz+"&version=" + cl_version +"&callback=?";
			jQuery.getJSON(url,function(data){
				jQuery('#lastposts').empty();
				jQuery.each(data.links, function(i,item){
					jQuery('#lastposts').append('<option value="'+data.links[i].url+'">'+data.links[i].title+'</option>');
				});
				jQuery('#lastposts').append('<option value="0" selected=selected>'+cl_settings[5]+'</option>');
				jQuery('#lastposts').fadeIn(1000);
				jQuery('#mylastpost').html('<em><a href="' + data.links[0].url + '">' + data.links[0].title + '</a></em>').fadeIn(1000);
				if(jQuery('#luv').is(":checked")){
					jQuery('#cl_post').val('<a href="' + data.links[0].url + '">' + data.links[0].title + '</a>');
				}
			});
			jQuery('#txtURLNewThread').change(function(){
				if(jQuery('#luv').is(":checked") && jQuery('#cl_post').val()!=""){
					jQuery('#lastposts').empty();
					cl_dostuff(cl_settings);
				}
			});
			jQuery('#CLIDCommentNewThreadText').unbind();
		}

		function cl_addstuff(){
			if(jQuery('#mylastpost a').attr("href").indexOf("commentluv.com/error") < 0){
				jQuery('#IDCommentNewThreadText').val(jQuery('#CLIDCommentNewThreadText').val() + "\n\n<em>" + jQuery('#txtNameNewThread').val() + "'s Recent post..." + jQuery('#mylastpost em').html());
			} else {
				jQuery('#IDCommentNewThreadText').val(jQuery('#CLIDCommentNewThreadText').val());
			}
			jQuery('#CLIDCommentNewThreadText').hide();
			jQuery('#mylastpostbox').hide();
			jQuery('#IDCommentNewThreadText').show();
		}
	})
}