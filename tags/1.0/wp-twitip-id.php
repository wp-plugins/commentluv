<?php
/*
Plugin Name: WP Twitip ID
Plugin URI: http://www.fiddyp.co.uk/wp-twitip-id-plugin-add-a-twitter-field-to-your-comment-form-easily/
Description: Adds another field to the comment form to allow the user to include their twitter id. Inspired by @problogger post http://tinyurl.com/6gns3f and kicked up the bum by @styletime.
Version: 1.0
Author: Andy Bailey
Author URI: http://www.fiddyp.co.uk

    Copyright (C) <2009>  <Andy Bailey>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

v0.1 21 Nov 08 - @styletime DM received, plugin idea accepted. WTH do I do now? :-)
v0.2 22 Nov 08 - changed paramters of template call, use image, text, user to output linked image or text using anchor or output @username only or return as variable type of string
v0.3 - added image for default usage (in case people don't know to go to the settings page)
v0.4 23 Nov 08 - change .js.php file to not add slashes. Added nolabel checkbox for themes without a label set for the fields
v0.5 updated way js file calls wp-load.php by sending a GET value with path from inside plugin
v0.6 24 nov 08 - added rel="nofollow" to links and used safer way of including javascript.
v0.7 27 nov 08 - update post meta (thanks @saphod), append atfemail to email key in post meta for easy removing in the future (database clean)
v0.8 28 nov 08 - added nojava option for people adding field manually to comments.php
v0.9 29 nov 08 - slight change to js for cookie field (don't comma the form obj) uses blur now instead of change. Add tab index bit
v1.0 11 feb 09 - add a new table for storing twitter id's for email address. only 1 comment needed for storing the id and it shows on all future comments
				and added the atf_slashit and atf_deslashit for storing html in wp_options properly 
				and added "auto" as an output for displaying Twitter: @commentluv
				a BIG THANKS to @clearskynet for helping me with the table creation!
*/

function atf_install() {
   global $wpdb;
   $table_name = $wpdb->prefix . "wptwitipid";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      $sql = "CREATE TABLE " . $table_name . " (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      email varchar(120) NOT NULL,
      twitid varchar(120) NOT NULL,
      PRIMARY KEY  (id),
      KEY (email)
      );";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);   
   }
}
function atfplugin_action($links, $file) {
	// adds the link to the settings page in the plugin list page
	if ($file == plugin_basename(dirname(__FILE__).'/wp-twitip-id.php'))
	$links[] = "<a href='options-general.php?page=wp-twitip-id/wp-twitip-id.php'>" . __('Settings', 'WP-Twitip-ID') . "</a>";
	return $links;
}
function addtwitterfieldadminpage() {
	// Add the administrative settings to the "Settings" menu by calling manage_page function
	if ( function_exists( 'add_submenu_page' ) ) {
		add_options_page( 'WP Twitip ID', 'WP Twitip ID', 8, __FILE__, 'addtwitterfield_manage_page' );
	}
}
function addtwitterfield_manage_page() {
	// Include the Manager page from file in plugin directory
	include(dirname(__FILE__).'/wp-twitip-id-manager.php' );
}
// add slashes to html if magic quotes is not on
function atf_slashit($stringvar){
    if (!get_magic_quotes_gpc()){
        $stringvar = addslashes($stringvar);
    }
    return $stringvar;
} 
// remove slashes if magic quotes is on
function atf_deslashit($stringvar){
    if (1 == get_magic_quotes_gpc()){
        $stringvar = stripslashes($stringvar);
    }
    return $stringvar;
} 
function addtwitterfieldscript(){
	// add the script reference to the header
	// Pre-2.6 compatibility
	if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
	global $atf_head_done;
	if(!$atf_head_done){
		echo '<script type="text/javascript" src="'.WP_PLUGIN_URL.'/'.
		dirname( plugin_basename(__FILE__)).'/js/addtwitterfield.js"></script>';
		$afterID=str_replace("#","",get_option('atf_afterID'));
		$prehtml=get_option('atf_prehtml');
		$psthtml=get_option('atf_psthtml');
		$field_class=get_option('atf_field_class');
		$hasnolabel=get_option('atf_nolabels');
		$swaplabel=get_option('atf_swap');
		$atf_nojava=get_option('atf_nojava');
		echo "<!-- added by wp-twitip-id by Andy Bailey -->\n";
		echo "<script type=\"text/javascript\">";
		echo "addtwitterfield('$afterID',\"$prehtml\",\"$psthtml\",'$field_class','$hasnolabel','$swaplabel','$atf_nojava');</script>";
		$atf_head_done=1;
	}
}
// plugin activate
function addtwitterfield_activation(){
	add_option('atf_afterID','url');
	add_option('atf_prehtml','<br/>');
	add_option('atf_psthtml',atf_slashit('<label for="atf_twitter_id">Twitter ID</label><br/>'));
	add_option('atf_field_class','textarea');
	add_option('atf_image_url',WP_PLUGIN_URL.'/'.dirname( plugin_basename(__FILE__)).'/twitter_48.png');
	add_option('atf_nolabels','off');
	add_option('atf_swap','off');
	add_option('atf_nojava','off');
    add_option("atf_db_version", "1.0");
	atf_install();
}
// save the twitter id with the author email in the post meta
function addtwitterfieldmeta($comment_data){
	// get twitter id from post variables
	$twitter=$_POST['atf_twitter_id'];
	// access db
	global $wpdb;
	$table_name = $wpdb->prefix . "wptwitipid";
	if($twitter){
		// chop off any @ symbol or http twitter address
		$find=array("@","http://twitter.com/");
		$replace=array("","");
		$twitterid = str_replace($find,$replace,$twitter);

		// store twitter id wptiwitpid
		$exists = $wpdb->get_var("SELECT twitid FROM $table_name WHERE email = '".$comment_data['comment_author_email']."'");
		if(!$exists){
			// none yet, insert
			$wpdb->query("INSERT INTO $table_name (email, twitid) VALUES ('".mysql_real_escape_string($comment_data['comment_author_email'])."','".mysql_real_escape_string($twitterid)."');");
			return $comment_data;
		} 
		if ($twitterid != $exists){
			//exists but is different so update to new value
			$query="UPDATE $table_name SET twitid = '".mysql_real_escape_string($twitterid)."' WHERE email = '".mysql_real_escape_string($comment_data['comment_author_email'])."'";
			$wpdb->query($query);
		}

	}
	return $comment_data;
}

function wp_twitip_id_show ($type){
	// display the twitter id associated with this comment
	// get post id
	global $wpdb;
	$table_name = $wpdb->prefix . "wptwitipid";
	// get comment
	global $comment;
	$email = $comment->comment_author_email;
	// get data from settings
	$image_url = get_option('atf_image_url');
	$anchor_text = get_option('atf_anchor_text');
	// construct url
	$url="http://twitter.com/";
	// output if twitter id asociated with users email on this post
	if($twitter = $wpdb->get_var("SELECT twitid FROM $table_name WHERE email ='$email'")){
		// twitter id exists for this email
		if($type == "image" && $image_url){
			echo '<span class="twitter_id"><a rel="nofollow" target="_blank" href="',$url,$twitter,'"><img src="',$image_url,'" alt="twitter" title="',$anchor_text,'"/></a></span>';
		} elseif(!$image_url){
			$type="text";
		}
		if($type == "text"){
			if(!$anchor_text) {
				$anchor_text="Click to follow me on twitter";
			}
			echo '<span class="twitter_id"><a rel="nofollow" target="_blank" href="',$url,$twitter,'">',$anchor_text,'</a></span>';
		}
		if($type == "user"){
			echo "@",$twitter;
		}
		if($type == "return"){
			return $twitter;
		}
		if($type == "auto") {
			echo '<span class="twitter_id">Twitter: <a rel="nofollow" target="_blank" href="',$url,$twitter,'">@',$twitter,'</a></span>';
		}
	}

	return ;
}
// it all starts here you know.
$atf_head_done=0;

// filters and actions
add_filter('plugin_action_links', 'atfplugin_action', -10, 2);
add_action( 'admin_menu', 'addtwitterfieldadminpage' );
add_action('wp_head','addtwitterfieldscript');
add_action('preprocess_comment','addtwitterfieldmeta',0);

register_activation_hook(__FILE__, 'addtwitterfield_activation');


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
?>