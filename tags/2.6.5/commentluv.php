<?php /*
Plugin Name: commentluv
Plugin URI: http://www.commentluv.com/download/ajax-commentluv-installation/
Description: Plugin to show a link to the last post from the commenters blog in their comment. Just activate and it's ready. Will parse a feed from most sites that have a feed location specified in its head html. See the <a href="options-general.php?page=commentluv">Settings Page</a> for styling and text output options.
Version: 2.6.5
Author: Andy Bailey
Author URI: http://www.fiddyp.co.uk/

*********************************************************************
You can now edit the options from the dashboard
*********************************************************************
updates:
2.6.5 - 16 jan 09 - magic fix for updates on dashboard.
2.6.4 - 15 jan 09 - removed name/id pull down boxes. now use only name value (all fields have a name value by default)
2.6.3 - 5 jan 09 - fix for intense debate. pointed to by http://dannybrown.me
2.6.2 - 26 dec 08 minor bug with update to codex
2.6.1 - 21 Dec 08 - add intense debate
2.6 - 6 dec 08 - separate settings page. compatibility with 2.7
21 nov 08 - use new javascript so set form field values as just the names instead of [textarea[name='comment'] just use comment
2.5.5 - 31 oct 08 - add function for wp_prototype_before_jquery for added compatibility
2.5.4 - 6 oct 08 - changed included tip.php so it has curl too. added version number to url for easier remote file functions. and changed commentluv.js to prevent
double firing of cl_dostuff (Marco Luthe from http://www.saphod.net/ (is a geek!))
		changed tip.php so it can work with curl or iframe so everyone can use it. thanks espen from http://www.espeniversen.com/ for testing
2.5.3 - fix for clicktracking off. needs quotes around the 1 too! prettied up default styling for abbr em and fixed an error with click tracking not adding member id by moving the click function to inline with commentluv.js
2.5.2 - 4 oct 08 - oh dear silly me, forgot to enclose an ==TRUE with quotes. (line 302) heart info box now can be switched off. thanks Ute from http://www.utes-dezines.de for you feedback.
2.5.1 - fix for pesky IE and Chrome
2.5 2nd oct 08 - fix for the people that can rtfm and added checkbox for traditonal users to be happy. show badge but no action for admin (again for the !rtfm's)
2.2   1st Oct 08 - the big fat update! fix all images for WP2.5 - 2.5.2 and added the luvheart info box option and made ready for luvcontests..
2.1.7 28/9/8 change defaults from ID to name as that seems to be more prevalant in themes. Added constants compatibility to admin function
added sanitize function for troublesome special characters in text output
2.1.6 27/9/8 make compatible for less that wp 2.5.1
2.1.5 25/9/8 do the features ever stop?? Marco says we should had some option for html to be added before button and delete old selects if change url. done!
also I am a dumbo for using wp_enqueue_script in the function called by wp_head, it should be before that. so sorry everyone! (retiredat47.com)
2.1.4 25/9/8 enqueue script and give choice to place button where someone wants it thanks Marco! http://www.saphod.net/
2.1.3 24/9/8 fix for older than 2.6 to use wp_plugin_dir and remove quick fix for imwithjoe (it was the ID's joe!)
and make inline javascript valid xhtml. Validates!
2.1.2 24/9/8 Allow user to choose no image or use text instead. Try to run cl_dostuff if url field already filled (fix for imwithjoe.com)
2.11 23/9/8 Use better constant to specify image url in javascript (http://indigospot.com) and moved button to below comment text area
2.1 23/9/8 Change to final remote file location and updated readme and download pages
2.0b r13 - 22/9/8 logged on users now can get luv (missed out URL field ID doh!) www.macbrosplace.com
2.0b r12 - 21/9/8 remove onsubmit event from jquery script and leave it to wordpress to insert the value. Have to
add one more filter hook for the pre-comment save and do all the fandanglery there. (issue reported by http://www.macbrosplace.com)
2.0b r11 - 20/9/8 ooh, I put in click tracking for admin if it is enabled
2.0b r11 - 20/9/8 add text to options page and apostrophe junkies fix (http://weblog.biznz.net)  :-)
2.0b r10 - 16/9/8 new remote files for compatible click tracking and remote fetching
2.0b r9 - 15/9/8 adjust form so user can choose between ID or name for their comment form fields (thanks ovidiu! http://zice.ro)
2.0b r8 - 14/9/8 compatible with brians threaded comments. (override submit function so it activates addthelink()
2.0b r7 - 13/9/8 much better way for the link to be added to the textarea. Also dont add if there is an existing last blog post in the text area
2.0b r6 - 13/9/8 fixed for blank url. made click tracking optional and added warning to settings page about using ID only
2.0b r5 - 11/9/8 field for verification or site id code, tracking of links, new remote acl script, message on error
2.0b r4 - 10/9/8 Changed code slightly to allow tracking of commentluv links. Updated text output option if plugin is less than version 204
2.0b r4 - 9/9/8 added more style to head to make select box 300px and made check before comment is returned to submit so the image isn't included if no post found. Updated lastposts.click to lasposts.change to make chrome work with change of entry
2.0b r3 - 7/9/8 changed remote script address (to adapt to database writes made in remote script)
2.0b r2 - 6/9/8 changed jquery so it doesn't select all selects - jQuery('#lastposts option:selected').text();
2.0b r2 - 5/9/8 change remote script to commentluv.com so it can use the database
2.0b r2 - 4/9/8 add whitelist options filter (http://mu.wordpress.org/forums/topic.php?id=9210)
2.0b r2 - 4/9/8 specific to MU
2.0b r1 - 4/9/8 tidy up options saving (testing by Ovidiu http://pacura.ru)
2.0b r1 - 3/9/8 use text given in settings page for inserted text
2.0b r1 - 3/9/8 make compatible with logged in user
2.0b r1 - 1/9/8 convert to AJAX enabled!!
1.98 - 210808 small change to label style
1.97 - change check for link returned
1.96 - check url to make sure it isn't pointing to a single file
1.95 - add referrer to curl options for new database options for remotecl6
1.94 - fix, style in settings wouldn't save
1.93 - change styling of comment
1.92 - fix case of 1 being output for feed return value
1.91 - fix option change for character encoding (forgot to add extra option to hidden field in option page html)
1.9 - changed retrieve url to fiddyp.com site because of hosting problem with commentluv.com
1.8 - added option to specify encoding of output - thanks me
1.7 - added steroids to the feed fetching routine, now no need to do all the fandangles of trying
to determine feed location and tidying up crappy characters. Now, output is in utf-8 with all
special characters staying put! thanks http://blog.mukispace.com
1.6 - make under comment and style in head xhtml valid - thanks http://www.untwistedvortex.com
1.5 - stupid urlencode.. pah
1.4 - some reports of code being passed back so check for more than 250 characters in returned string (quick fix only) thanks mama druid (http://www.mamadruid.com
found the problem! it was new server not executing php5 files... had to change url in plugin and upload standard php file to server
1.3 - clean returned link so it can't be used for msql injection
1.2 - add option for styling... thanks Jenny from http://thesocalledme.net
1.1 - add options page to allow for user to change displayed messages etc
1.0 - took ajax off and moved to remote find feed on commentluv.com
0.999 - need to take off internal feed parser due to excessive use of wp_options table
0.998 - gimme some AJAX! added div id "here" to below checkbox for showing the last post found by ajax
0.997 - add bit to allow user to change message by editing source code
0.996 - removed [noluv] and replaced with checkbox on form
0.995 - add option to not get feed if user enters [noluv] (thanks http://www.blogherald.com)
0.994 - added an option to read the feed output by my own routine to curl the users page
0.993 - added check for web-log: addition by Edward De Leau of http://edward.de.leau.net/
0.992 - detect trailing slash on author url and act accordingly
0.991 - move curl check to higher up the process so the plugin doesn't take longer than necessary
0.99 - allow for styles to be applied to last blog post text
0.98 - added ability to allow commenter to switch debug on within comments by putting [debugon] in the content
0.97 - add support for typepad and blogspt own domain blogs and more header alternate links and raised the priority on action so other plugins can play with the comment afterwards for nofollow
0.96 - handle the author url more efficiently
0.95 - rewrite of feed finding and parsing and added a timout increase to the magpie rss parsing function
0.94 - fix: not parsing some feedburner feeds that have an extra subdomain on the url
0.93 - use wp internal function to parse feed and improve find feed location
0.92 - update comments
0.91 - fix: compatibility with some other comment enhancing plugins so the link isn't repeated
0.9 - now wont output emptry string if no last post found (blogspot blog with own domain)
0.8 - now prevents parsing on a trackback, pingback or admin comment
0.71 - trying to prevent showing last post on trackbacks
0.7 - prevented admin from having feed parsed when replying to comments
(thanks thesmocklady.com/blog/)
0.6 - fixed problem where it wouldn't find the feed if the blog was in a subdirectory
(found by http://thesmocklady.com/blog/)
0.51 - fiddled with timeout. Some feeds were not showing due to it taking too long to load the
commenter's page. Testing done by thedivanetwork.com and lalla-mira.com/. Thanks!
0.5 - typepad,blogspot,wordpress all working, tries to find a feedburner feed in the authors page
if it's not found in a default location. pretty robust, can now work with script links to
feedburner.
0.4 - try and find users feed if they don't have a default wordpress/blogger/typepad blog
0.3 - works with typepad blogs feed, default and feedburner
0.2 - works with feedburner feed for wordpress and blogger default location
0.1 - works with wordpress default feed at default location


*/



//************ you shouldn't edit below this line!*******************************

// action hooks

add_action('admin_menu', 'show_cl_options');
add_action('wp_head','cl_style_script');
add_action('comment_form','cl_add_fields');
add_filter('preprocess_comment','cl_post',0);
add_filter('whitelist_options','commentluv_alter_whitelist_options');
add_filter('plugin_action_links', 'commentluv_action', -10, 2);
register_activation_hook(__FILE__, 'commentluv_activation');

if (!function_exists('wp_prototype_before_jquery')) {
	function wp_prototype_before_jquery( $js_array ) {
		if ( false === $jquery = array_search( 'jquery', $js_array ) )
			return $js_array;
	
		if ( false === $prototype = array_search( 'prototype', $js_array ) )
			return $js_array;
	
		if ( $prototype < $jquery )
			return $js_array;
	
		unset($js_array[$prototype]);
	
		array_splice( $js_array, $jquery, 0, 'prototype' );
	
		return $js_array;
	}
	
	add_filter( 'print_scripts_array', 'wp_prototype_before_jquery' );
}

wp_enqueue_script('jquery');


function commentluv_action($links, $file) {
	// adds the link to the settings page in the plugin list page
	if ($file == plugin_basename(dirname(__FILE__).'/commentluv.php'))
	$links[] = "<a href='options-general.php?page=commentluv/commentluv.php'>" . __('Settings', 'CommentLuv') . "</a>";
	return $links;
}

// Add the administrative settings to the "Settings" menu by calling manage_page function
function show_cl_options() {
	if ( function_exists( 'add_submenu_page' ) ) {
		add_options_page( 'CommentLuv', 'CommentLuv', 8, __FILE__, 'commentluv_manage_page' );
	}
}
// Include the Manager page from file in plugin directory
function commentluv_manage_page() {
	include(dirname(__FILE__).'/commentluv-manager.php' );
}
// clean input string
function cleanInput($input) {
	return htmlentities(trim($input), ENT_QUOTES);
}

// sanatize function
function sanitize($input) {
	if (is_array($input)) {
		foreach($input as $var=>$val) {
			$output[$var] = sanitize($val);
		}
	}
	else {
		if (get_magic_quotes_gpc()) {
			$input = stripslashes($input);
		}
		$input  = cleanInput($input);
		$output = mysql_real_escape_string($input);
	}
	return $output;
}

// make compatible with Mu
function commentluv_alter_whitelist_options($whitelist) {
	if(is_array($whitelist)) {
		$option_array = array('commentluv' => array('cl_comment_text','cl_default_on','cl_style','cl_author_id','cl_site_id','cl_comment_id','cl_commentform_id','cl_badge','cl_member_id','cl_click_track','cl_author_type','cl_url_type','cl_textarea_type','cl_reset','cl_showtext','cl_badge_pos','cl_prepend','cl_heart_tip'));
		$whitelist = array_merge($whitelist,$option_array);
	}
	return $whitelist;
}

// localization - oo-er!
$commentluv_domain = 'commentluv';
$commentluv_is_setup = 0;
$cl_script_added=0;

function commentluv_setup()
{
	global $commenluv_domain, $commentluv_is_setup;

	if($commentluv_is_setup) {
		return;
	}

	load_plugin_textdomain($commentluv_domain, PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)), dirname(plugin_basename(__FILE__)));
	$commentluv_is_setup=1;
}

// add fields for registered user so ajaxcl will work for logged on users
function cl_add_fields($id){
	if (is_user_logged_in()){
		// get options values and insert as hidden fields
		global $userdata;
		get_currentuserinfo();
		$author=$userdata->display_name;
		$userid=$userdata->ID;
		// check for MU blog
		if( function_exists( 'is_site_admin' ) ) {
			global $wpdb;
			$row = $wpdb->get_row("SELECT * FROM wp_blogs WHERE blog_id=$userid");
			$url = $row->domain.$row->path;
		}else {
			$url=$userdata->user_url;
		}

		$cl_author_id=get_option('cl_author_id');
		$cl_site_id=get_option('cl_site_id');

		echo "<input type='hidden' id='$cl_author_id' name='$cl_author_id' value='$author' />";
		echo "<input type='hidden' id='$cl_site_id' name='$cl_site_id' value='$url' />";
	}
	return $id;
}

function cl_post($comment_data){
	// insert the last link to the end of the comment and save to db by returning $comment_data
	$cl_post=$_POST['cl_post'];
	if($cl_post){
		// if there's something there do something with it!
		if(strstr($cl_post,"commentluv.com/error") || substr($cl_post,10,1)=="0"){
			return $comment_data;
		}
		$cl_comment_text=str_replace("'","&#180;",get_option('cl_comment_text'));
		// change output text to that set in the options page
		$search=array('[name]','[lastpost]');
		$replace=array($comment_data['comment_author'],$cl_post);
		$cl_comment_text=str_replace($search,$replace,$cl_comment_text);
		$comment_data['comment_content'].="\n\n<abbr><em>$cl_comment_text</abbr></em>";
		return $comment_data;
	} else {
		return $comment_data;
	}
}


// add style to head
function cl_style_script(){
	// for lesser Wp than 2.6
	// Pre-2.6 compatibility
	if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
	global $cl_script_added;

	if ($cl_script_added) {
		return;
	}

	$cl_comment_id=get_option('cl_comment_id');
	$cl_author_id=get_option('cl_author_id');
	$cl_site_id=get_option('cl_site_id');

	$cl_version=get_option('cl_version');
	$cl_comment_text=get_option('cl_comment_text');
	$cl_default_on=get_option('cl_default_on');
	$cl_badge=get_option('cl_badge');
	$cl_member_id=get_option('cl_member_id');
	if(!$cl_member_id){
		$cl_member_id="0";
	}
	$plugin_dir = basename(dirname(__FILE__));

	if($cl_badge=='text'){
		$cl_badge_val=get_option('cl_showtext');
	} else {
		$cl_badge_val="<img src=\"".WP_PLUGIN_URL."/commentluv/$cl_badge\"/>";
	}
	// check if user has set append ID differently
	$append_id=get_option('cl_badge_pos');

	// start the javascript output
	if(is_single()) {
		echo '<!-- Styling and script added by commentluv 2.65 http://www.commentluv.com -->';
		
		echo '<style type="text/css">abbr em{'.get_option('cl_style').'} #lastposts { width: 300px; } </style>';
		echo "\n<script type=\"text/javascript\" src=\"".WP_PLUGIN_URL."/commentluv/js/commentluv";
		if(function_exists(id_menu_items)) { echo "ID";} //get_option('cl_intense')=="on")
		echo ".js\"></script>";
		if(get_option('cl_heart_tip')=="TRUE"){
			echo "<link rel=\"stylesheet\" href=\"".WP_PLUGIN_URL."/commentluv/include/tipstyle.css\" type=\"text/css\" />\n";
			echo "<script type=\"text/javascript\"><!--//--><![CDATA[//><!--\n";
			echo "jQuery(document).ready(function(){\n";
			(function_exists(id_menu_items)) ? $selector = ".idc-c-t" : $selector = "abbr";
			echo "jQuery(\"$selector em a\").each(function(i){\n".
			"jQuery(this).after(' <a class=\'jTip\' id=\'' + i + '\' name=\'My CommentLuv Profile\' href=\'".
			WP_PLUGIN_URL."/commentluv/include/tip.php?width=375&url=' + jQuery(this).attr('href') +'\'><img src=\'".
			WP_PLUGIN_URL."/commentluv/images/littleheart.png\' alt=\'#\' border=\'0\' /></a>');\n".
			"});\n".
			"JT_init();});\n";
			echo "//--><!]]></script>\n";
			echo "<script type='text/javascript' src='".WP_PLUGIN_URL."/commentluv/js/jtip.js'></script>\n";
		}
		echo "<script type=\"text/javascript\"><!--//--><![CDATA[//><!--";
		echo "\n var cl_settings=new Array();\n";
		echo "cl_settings[0]=\"$append_id\";\n";
		echo "cl_settings[1]=\"$cl_author_id\";\n";
		echo "cl_settings[2]=\"$cl_site_id\";\n";
		echo "cl_settings[3]=\"$cl_comment_id\";\n";

		// select text
		$cl_select_text=get_option('cl_select_text');
		echo "cl_settings[5]=\"$cl_select_text\";\n";
		echo "cl_settings[6]=\"$cl_badge\";\n";
		echo "cl_settings[7]=\"".addslashes($cl_badge_val)."\";\n";
		echo "cl_settings[8]=\"".addslashes($cl_badge_val)."\";\n";
		if($cl_default_on=="TRUE"){
			$cl_default_on="checked";
		} else {
			$cl_default_on="";
		}
		echo "cl_settings[9]=\"$cl_default_on\";\n";
		//click track
		if(get_option('cl_click_track')=="on") {
			$cl_click_track=1;
		} else {
			$cl_click_track=0;
		}
		echo "cl_settings[10]=\"$cl_click_track\";\n";
		echo "cl_settings[11]=\"$cl_heart_tip\";\n";
		// optional prepend
		$cl_prepend=addslashes(get_option('cl_prepend'));
		echo "cl_settings[12]=\"$cl_prepend\";\n";
		// switch off for admin
		if(current_user_can('edit_users')){
			echo "cl_settings[13]=\"admin\";\n";
		}
		echo "cl_settings[14]=\"$cl_member_id\";\n";
		echo "cl_settings[15]=\"$cl_version\";\n";
		echo "commentluv(cl_settings);\n";

		echo "//--><!]]></script>";

		echo '<!-- end commentluv  http://www.commentluv.com -->';
		$cl_script_added=1;
	}

}

?>