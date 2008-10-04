<?php /*
Plugin Name: commentluv
Plugin URI: http://www.commentluv.com/download/ajax-commentluv-installation/
Description: Plugin to show a link to the last post from the commenters blog in their comment. Just activate and it's ready. Will parse a feed from most sites that have a feed location specified in its head html. See the <a href="options-general.php?page=commentluv">Settings Page</a> for styling and text output options.
Version: 2.5.2
Author: Andy Bailey
Author URI: http://www.fiddyp.co.uk/

*********************************************************************
You can now edit the options from the dashboard
*********************************************************************
updates:
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
register_activation_hook(__FILE__, 'commentluv_activation');
wp_enqueue_script('jquery');

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

function commentluv_activation(){
	// set version for future releases if they need to change a value
	$version=get_option('cl_version');
	if($version<252){
		update_option('cl_version','252');
	}
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

//function for menu
function show_cl_options() {
	commentluv_alter_whitelist_options("");
	// Add a new submenu under Options:
	add_options_page('CommentLuv', 'CommentLuv', 8, 'commentluv', 'cl_options_page');
	add_option('cl_comment_text','[name]&#180;s last blog post..[lastpost]');
	add_option('cl_default_on','TRUE');
	add_option('cl_heart_tip','TRUE');
	add_option('cl_style','border:1px solid; display:block; padding:4px;');
	add_option('cl_author_id','author');
	add_option('cl_site_id','url');
	add_option('cl_comment_id','comment');
	add_option('cl_commentform_id','#commentform');
	add_option('cl_badge','ACL88x31-white.gif');
	add_option('cl_member_id','');
	add_option('cl_author_type','name');
	add_option('cl_url_type','name');
	add_option('cl_textarea_type','name');
	add_option('cl_click_track','on');
	add_option('cl_showtext','CommentLuv Enabled');
	add_option('cl_badge_pos','');
	add_option('cl_prepend','');
	commentluv_activation();
	add_option('cl_version','252');
	add_option('cl_select_text','choose a different post to show');
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

	// construct selector string based on ID or name (ternery yey!)
	$comment_selector= (get_option('cl_textarea_type')=="name")? "\"textarea[name='$cl_comment_id']\"" : "'#$cl_comment_id'";
	$author_selector=  (get_option('cl_author_type')=="name")? "\"input[name='$cl_author_id']\"" : "'#$cl_author_id'";
	$url_selector=  (get_option('cl_url_type')=="name")? "\"input[name='$cl_site_id']\"" : "'#$cl_site_id'";

	$cl_comment_text=get_option('cl_comment_text');
	$cl_default_on=get_option('cl_default_on');
	$cl_badge=get_option('cl_badge');
	$cl_member_id=get_option('cl_member_id');
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
		echo '<!-- Styling and script added by commentluv 2.5.2 http://www.commentluv.com -->';
		echo '<style type="text/css">abbr em{'.get_option('cl_style').'} #lastposts { width: 300px; } </style>';
		echo "\n<script type=\"text/javascript\" src=\"".WP_PLUGIN_URL."/commentluv/js/commentluv.js\"></script>";
		if(get_option('cl_click_track')=="on"){
			echo "\n<script type=\"text/javascript\" src=\"".WP_PLUGIN_URL."/commentluv/js/processclick.js\"></script>\n";
		}
		if(get_option('cl_heart_tip')=="TRUE"){
			echo "<link rel=\"stylesheet\" href=\"".WP_PLUGIN_URL."/commentluv/include/tipstyle.css\" type=\"text/css\" />\n";
			echo "<script type=\"text/javascript\"><!--//--><![CDATA[//><!--\n";
			echo "jQuery(document).ready(function(){\n".
			"jQuery(\"abbr em a\").each(function(i){\n".
			"jQuery(this).after(' <a class=\'jTip\' id=\'' + i + '\' name=\'My CommentLuv Profile\' href=\'".
			WP_PLUGIN_URL."/commentluv/include/tip.php?width=375&url=' + jQuery(this).attr('href') +'\'><img src=\'".
			WP_PLUGIN_URL."/commentluv/images/littleheart.png\' alt=\'#\' /></a>');\n".
			"});\n".
			"JT_init();});\n";
			echo "//--><!]]></script>\n";
			echo "<script type='text/javascript' src='".WP_PLUGIN_URL."/commentluv/js/jtip.js'></script>\n";
		}
		echo "<script type=\"text/javascript\"><!--//--><![CDATA[//><!--";
		echo "\n var cl_settings=new Array();\n";
		echo "cl_settings[0]=\"$append_id\";\n";
		echo "cl_settings[1]=$author_selector;\n";
		echo "cl_settings[2]=$url_selector;\n";
		echo "cl_settings[3]=$comment_selector;\n";

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
			echo "cl_settings[13]=\"admin\";";
		}
		echo "commentluv(cl_settings);\n";

		echo "//--><!]]></script>";

		echo '<!-- end commentluv  http://www.fiddyp.co.uk -->';
		$cl_script_added=1;
	}

}
// function to add menu page under options

function cl_options_page(){
	// Pre-2.6 compatibility
	if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

	commentluv_setup();
	if(get_option('cl_reset')=="on"){
		update_option('cl_comment_text','[name]&#180;s last blog post..[lastpost]');
		update_option('cl_default_on','TRUE');
		update_option('cl_heart_tip','TRUE');
		update_option('cl_style','border:1px solid; display:block; padding:4px;');
		update_option('cl_author_id','author');
		update_option('cl_site_id','url');
		update_option('cl_comment_id','comment');
		update_option('cl_commentform_id','');
		update_option('cl_badge','ACL88x31-white.gif');
		update_option('cl_author_type','name');
		update_option('cl_url_type','name');
		update_option('cl_textarea_type','name');
		update_option('cl_click_track','on');
		update_option('cl_showtext','CommentLuv Enabled');
		update_option('cl_reset','off');
		update_option('cl_badge_pos','');
		update_option('cl_prepend','');
		update_option('cl_select_text','Choose a different post to show');
	}
	?>
<div class="wrap">

	<form method="post" action="options.php" id="options">
	<?php 
	if(function_exists('wpmu_create_blog'))
	wp_nonce_field('commentluv-options');
	else
	wp_nonce_field('update-options');
?>
	<h2><?php _e('CommentLuv Wordpress Plugin','commentluv')?></h2>
	<p><?php _e('This plugin takes the url from the comment form and tries to parse the feed of the site and display the last entry made','commentluv')?></p>
	<p><?php _e('If you have any questions, comments or if you have a good idea that you would like to see in the next version of CommentLuv, please visit','commentluv')?> <a href="http://www.fiddyp.co.uk" target="_blank">FiddyP Blog</a> <?php _e('or','commentluv')?> <a href="http://www.fiddyp.co.uk/support/"><?php _e('support forum','commentluv')?></a> <?php _e('and let me know','commentluv')?>.</p>
	<h3><?php _e('Options','commentluv')?></h3>
	<p><?php _e('Enter the text you want displayed in the comment.','commentluv')?></p>
	<table class="form-table">
  <tr>
    <td colspan="2">
      <input class="form-table" name="cl_comment_text" value="<?php echo get_option('cl_comment_text');?>">
    </td>
    </tr>
    <tr>
    <td colspan="2">
    <?php _e('Text displayed in the select box','commentluv');?>
      <input class="form-table" name="cl_select_text" value="<?php echo get_option('cl_select_text');?>">
    </td>
    </tr>
  <tr>
    <td width="29%"><?php _e('Choose to have CommentLuv on by default?','commentluv')?></td>
    <td width="71%"><select name="cl_default_on">
      <option <?php if(get_option('cl_default_on')=="TRUE") {echo "selected=selected";}?> >TRUE</option>
      <option <?php if(get_option('cl_default_on')=="FALSE") { echo "selected=selected";}?> >FALSE</option>
    </select></td>
  </tr>
  <tr>
    <td width="29%"><?php _e('Choose to have CommentLuv Info box?','commentluv')?></td>
    <td width="71%"><select name="cl_heart_tip">
      <option <?php if(get_option('cl_heart_tip')=="TRUE") {echo "selected=selected";}?> >TRUE</option>
      <option <?php if(get_option('cl_heart_tip')=="FALSE") { echo "selected=selected";}?> >FALSE</option>
    </select></td>
  </tr>
  </table>
  <h3><?php _e('Styling')?></h3>
  <p><?php _e('Wordpress doesn\'t allow a class to be applied to a paragraph in the comment area so we have to wrap the last blog post text in nested tags and apply styling to that instead.','commentluv')?></p>
  <p><?php _e('Enter css styling to apply to comment','commentluv')?></strong> (<em><?php _e('inserted as','commentluv')?></em> &lt;style type="text/css"&gt;abbr em { border:2px; etc }&lt;/style&gt;)</p>
  <table class="form-table">
  <tr> 
    <td valign="top" colspan="2"><input class="form-table" name="cl_style" value="<?php echo get_option('cl_style');?>"></td>
  </tr>
  </table>
  <h3><?php _e('Comment Form Identification','commentluv')?></h3>
<p><?php _e('Enter the ID or NAME value for the input fields on your comment form.','commentluv')?></p>
<p><?php _e('Check your comment form fields to see if they use ID= or NAME= and select the appropriate type below','commentluv')?><br/>
<?php _e('Visit CommentLuv.com if you need instructions','commentluv')?></p>
  <table class="form-table">
  <tr>
    <td><?php _e('Authors Name field ID','commentluv')?></td>
    <td><select name="cl_author_type">
    	<option <?php if(get_option('cl_author_type')=="ID" ){echo "selected=selected";}?> >ID</option>
    	<option <?php if(get_option('cl_author_type')=="name") {echo "selected=selected";}?> >name</option>
    	</td>
    <td><input name="cl_author_id" value="<?php echo get_option('cl_author_id');?>"></td>
  </tr>
  <tr>
    <td><?php _e('Authors URL field ID','commentluv')?></td>
    <td><select name="cl_url_type">
    	<option <?php if(get_option('cl_url_type')=="ID") {echo "selected=selected";}?> >ID</option>
    	<option <?php if(get_option('cl_url_type')=="name") {echo "selected=selected";}?> >name</option>
    	</td>
    <td><input name="cl_site_id" value="<?php echo get_option('cl_site_id');?>"></td>
  </tr>
  <tr>
    <td><?php _e('Comment Text Area ID','commentluv')?></td>
    <td><select name="cl_textarea_type">
    	<option <?php if(get_option('cl_textarea_type')=="ID") {echo "selected=selected";}?> >ID</option>
    	<option <?php if(get_option('cl_textarea_type')=="name" ){echo "selected=selected";}?> >name</option>
    	</td>
    <td><input name="cl_comment_id" value="<?php echo get_option('cl_comment_id');?>"></td>
  </tr>
</table>
<h3><?php _e('Display Badge','commentluv')?></h3>
<p>Many thanks to <a href="http://byteful.com">Byteful Traveller</a> for creating these images.</p>
	<table class="form-table">
	<tr>
      <td><?php _e('Choose badge to display','commentluv')?> </td>
      <?php $badge=get_option('cl_badge');?>
        <td><label><input type="radio" <?php if($badge=="CL91x17-white.gif"){echo "checked ";}?> name="cl_badge" value="CL91x17-white.gif"><img src="<?php echo WP_PLUGIN_URL;?>/commentluv/CL91x17-white.gif"/></label></td>
        <td><label><input type="radio" <?php if($badge=="CL91x17-black.gif"){echo "checked ";}?> name="cl_badge" value="CL91x17-black.gif"><img src="<?php echo WP_PLUGIN_URL;?>/commentluv/CL91x17-black.gif"/></label></td>
		<td><label><input type="radio" <?php if($badge=="ACL88x31-white.gif"){echo "checked ";}?> name="cl_badge" value="ACL88x31-white.gif"><img src="<?php echo WP_PLUGIN_URL;?>/commentluv/ACL88x31-white.gif"/></label></td>
		<td><label><input type="radio" <?php if($badge=="ACL88x31-black.gif"){echo "checked ";}?> name="cl_badge" value="ACL88x31-black.gif"><img src="<?php echo WP_PLUGIN_URL;?>/commentluv/ACL88x31-black.gif"/></label></td>
		<td><label><input type="radio" <?php if($badge=="nothing.gif"){echo "checked ";}?> name="cl_badge" value="nothing.gif"><?php _e('Show nothing','commentluv')?></label></td>
  </tr></table>
  <table class="form-table">
  <tr><td><label><input type="radio" <?php if($badge=="text"){echo "checked ";}?> name="cl_badge" value="text"><?php _e('Show text','commentluv')?></label> <input class="form-table" type="text" name="cl_showtext" value="<?php echo get_option('cl_showtext');?>"></input></td><td></td><td><label><?php _e('Append badge to (DIV or object ID) optional','commentluv')?><input class="form-table" type="text" name="cl_badge_pos" value="<?php echo get_option('cl_badge_pos');?>"></input></td><td></td><td><label><?php _e('Prepend html before badge or text (optional)','commentluv')?></label><input class="form-table" type="text" name="cl_prepend" value="<?php echo htmlspecialchars(get_option('cl_prepend'));?>"></input></tr>
    </table>
    <h3><?php _e('CommentLuv Member ID','commentluv')?></h3>
    <p><?php _e('If you register your site for free at','commentluv')?> <a href="http://www.commentluv.com">CommentLuv.com</a> <?php _e('you will be able to open up lots of features that are for members only like link tracking so you can see which of the comments you make on CommentLuv blogs are getting the last blog post clicked. Do NOT enter a number if you do not have one','commentluv')?></p>
    <table class="form-table">
    <tr><td><?php _e('Your CommentLuv.com member ID','commentluv')?></td>
	<td><input name="cl_member_id" value="<?php echo get_option('cl_member_id');?>"></td>
    </tr>
    <tr><td><?php _e('Enable click tracking?','commentluv')?></td>
    <td><input type="checkbox" name="cl_click_track" <?php if(get_option('cl_click_track')=="on"){echo "checked";};?> /></td>
    </tr>
    </table>
	  <input type="hidden" name="page_options" value="cl_comment_text,cl_default_on,cl_style,cl_author_id,cl_site_id,cl_comment_id,cl_commentform_id,cl_badge,cl_member_id,cl_click_track,cl_form_type,cl_author_type,cl_url_type,cl_textarea_type,cl_reset,cl_showtext,cl_badge_pos,cl_prepend,cl_select_text,cl_heart_tip" />
	  <input type="hidden" name="action" value="update" />
	  <input type="hidden" name="option_page" value="commentluv" />
	  <p><input type="checkbox" name="cl_reset"/><?php _e('Reset to Default Settings','commentluv')?>
	<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update Options') ?>" /></p>
	</form>
<p>Andy Bailey<br/>
Fiddyp.co.uk
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="root@teamplaylotto.com">
<input type="hidden" name="item_name" value="CommentLuv">
<input type="hidden" name="no_shipping" value="0">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="lc" value="GB">
<input type="hidden" name="bn" value="PP-DonationsBF">
<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>

</div>
 <?php }


?>