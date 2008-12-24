// JavaScript Document
// tool tips on last blog posts
jQuery(document).ready(function(){
	jQuery("abbr em a").each(function(i){
		jQuery(this).after(" <a class='jTip' id='"+i+"' name='My other posts' href='http://localhost/wordpress/wp-content/plugins/commentluv/include/tip.php?width=375&url=" +jQuery(this).attr('href')+"'>#</a>");
	});
});