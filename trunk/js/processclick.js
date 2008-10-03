function processclick(cl_member_id){
	var url=jQuery(this).attr('href');
	var thelinkobj=jQuery(this);
	var cl_member_id="";
	var addit=url + "&cl_member_id=" + cl_member_id + "&callback=?";
	var clurl="http://www.commentluv.com/commentluvinc/ajaxcl_click821.php?url=" + addit;
	jQuery.getJSON(clurl,function(data) {
		jQuery.each(data.msg,function(i,item) {
			jQuery(thelinkobj).text(data.msg[i].text);})
			window.location=url;
	}); return false;}