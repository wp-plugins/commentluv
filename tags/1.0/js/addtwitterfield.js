// javascript for wp-twitip-id 1.0
function addtwitterfield (atf_afterID,atf_prehtml,atf_psthtml,atf_field_class,atf_nolabels,atf_swaplabel,atf_nojava){
	// use jquery to add a field to the comment form
	jQuery.noConflict();
	jQuery(document).ready(function(){
		if(jQuery("#" + atf_afterID).length < 1) { return }
		if(atf_nojava != "on"){
			// get tabindex
			var tabIndex = parseInt(jQuery("#" + atf_afterID).attr("tabindex")) + 1;
			// add the field
			var formObj = jQuery("textarea[name='comment']").parents("form"); // get form object that is parent of textarea named "comment"
			if(atf_nolabels!="on" && atf_swaplabel != "on") {
				var objafter = "label[for='" + atf_afterID + "']";
			} else {
				var objafter = "#" + atf_afterID;
			}
			
			jQuery(objafter,formObj).after(atf_prehtml + "<input type=\"text\" name=\"atf_twitter_id\" class=\"" + atf_field_class + "\" tabindex=\"" + tabIndex + "\" ></input>" + atf_psthtml);
		}
		// monitor twitter field so can be added to cookie
		var fieldobj = jQuery("input[name='atf_twitter_id']");
		jQuery(fieldobj).blur(function(){
			atf_createCookie('atf_cookie',jQuery(this).val(),60);
		});
		// add value if cookie exists
		var x = atf_readCookie('atf_cookie');
		if(x) {
			jQuery(fieldobj).val(x);
		}

	});

	// cookie stuff mmmmmm cookies....
	//http://www.quirksmode.org/js/cookies.html
	function atf_createCookie(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else { var expires = ""; }
		document.cookie = name+"="+value+expires+"; path=/";
	}

	function atf_readCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') {c = c.substring(1,c.length);}
			if (c.indexOf(nameEQ) == 0){ return c.substring(nameEQ.length,c.length);}
		}
		return null;
	}

	function atf_eraseCookie(name) {
		createCookie(name,"",-1);
	}
}