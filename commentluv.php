<?php /* CommentLuv 2.7
Plugin Name: CommentLuv
Plugin URI: http://comluv.com/download/commentluv-wordpress/
Description: Plugin to show a link to the last post from the commenters blog by parsing the feed at their given URL when they leave a comment. Rewards your readers and encourage more comments.
Version: 2.7.62
Author: Andy Bailey
Author URI: http://fiddyp.co.uk/
*/
// Avoid name collision
if (! class_exists ( 'commentluv' )) {
	// let class begin
	class commentluv {
		//localization domain
		var $plugin_domain = 'commentluv';
		var $plugin_url;
		var $db_option = 'commentluv_options';
		var $cl_version = 276;
		var $api_url;

		//initialize the plugin
		function commentluv() {
			global $wp_version, $pagenow;
			// pages where commentluv needs translation
			$local_pages = array ('plugins.php', 'commentluv.php' );
			// check if translation needed on current page
			if (in_array ( $pagenow, $local_pages ) || in_array ( $_GET ['page'], $local_pages )) {
				$this->handle_load_domain ();
			}
			$exit_msg = __ ( 'CommentLuv requires Wordpress 2.6.5 or newer.', $this->plugin_domain ) . '<a href="http://codex.wordpress.org/Upgrading_Wordpress">' . __ ( 'Please Update!', $this->plugin_domain ) . '</a>';

			// can you dig it?
			if (version_compare ( $wp_version, "2.6.5", "<" )) {
				exit ( $exit_msg ); // no diggedy
			}

			// action hooks
			$this->plugin_url = trailingslashit ( WP_PLUGIN_URL . '/' . dirname ( plugin_basename ( __FILE__ ) ) );
			$this->api_url = 'http://api.comluv.com/cl_api/commentluvapi.php';
			add_action ( 'admin_menu', array (&$this, 'admin_menu' ) );
			add_action ( 'template_redirect', array (&$this, 'commentluv_scripts' ) ); // template_redirect always called when page is displayed to user
			add_action ( 'wp_head', array (&$this, 'commentluv_style' ) ); // add style sheet to header
			add_action ( 'wp_set_comment_status', array (&$this, 'update_cl_status' ), 1, 3 ); // call when status of comment gets changed
			add_action ( 'comment_post', array (&$this, 'update_cl_status' ), 2, 3 ); // call when comment gets posted
			add_action ( 'comment_form', array (&$this, 'add_fields' ) ); // add hidden fields during comment form display time
			add_filter ( 'plugin_action_links', array (&$this, 'commentluv_action' ), - 10, 2 ); // add a settings page link to the plugin description. use 2 for allowed vars
			add_filter ( 'comment_text', array (&$this, 'do_shortcode' ), 10 ); // replace inserted data with hidden span on display time of comment
			add_filter ( 'pre_comment_content', array (&$this, 'cl_post' ), 10 ); // extract extra fields data and insert data to end of comment
		}

		// hook the options page
		function admin_menu() {
			$menutitle = '<img src="' . $this->plugin_url . 'images/littleheart.gif" alt=""/> ';
			$menutitle .= 'CommentLuv';
			add_options_page ( 'CommentLuv Settings', $menutitle, 8, basename ( __FILE__ ), array (&$this, 'handle_options' ) );
		}
		// add the settings link
		function commentluv_action($links, $file) {
			$this_plugin = plugin_basename ( __FILE__ );
			if ($file == $this_plugin) {
				$links [] = "<a href='options-general.php?page=commentluv.php'>" . '<img src="' . $this->plugin_url . 'images/littleheart.gif" alt=""/> ' . __ ( 'Settings', $this->plugin_domain ) . "</a>";
			}
			return $links;
		}
		// hook the template_redirect for inserting style and javascript (using wp_head would make it too late to add dependencies)
		function commentluv_scripts() {
			// only load scripts if on a single page
			if (is_single ()) {
				wp_enqueue_script ( 'jquery' );
				global $wp_version;
				// see if hoverintent library is already included (2.7 >)
				if (version_compare ( $wp_version, "2.8", "<" )) {
					wp_enqueue_script ( 'hoverIntent', '/' . PLUGINDIR . '/' . dirname ( plugin_basename ( __FILE__ ) ) . '/js/hoverIntent.js', array ('jquery' ) );
				} else {
					wp_enqueue_script ( 'hoverIntent', '/' . WPINC . '/js/hoverIntent.js', array ('jquery' ) );
				}
				wp_enqueue_script ( 'commentluv', $this->plugin_url . 'js/commentluv.js', array ('jquery' ) );
				// get options
				$options = $this->get_options ();
				foreach ( $options as $key => $value ) {
					$$key = $value;
				}
				// prepare options
				$default_on = $default_on == 'on' ? 'checked' : '';
				// untick the box if user is admin
				global $user_ID;
				if ($user_ID) {
					if (current_user_can ( 'create_users' )) {
						$default_on = '';
					}
				}
				$badge = $this->plugin_url . "images/" . $badge;
				$badge_text = $options ['badge'] == 'text' ? 'on' : '';
				// get permalink for refer value
				$refer_page = get_permalink();
				// insert options to header
				wp_localize_script ( 'commentluv', 'cl_settings', array ('name' => $author_name, 'url' => $url_name, 'comment' => $comment_name, 'email' => $email_name, 'prepend' => $prepend, 'badge' => $badge,
											 'show_text' => $show_text, 'badge_text' => $badge_text, 'heart_tip' => $heart_tip, 'default_on' => $default_on, 'select_text' => $select_text,
											 'cl_version' => $this->cl_version, 'images' => $this->plugin_url . 'images/', 'api_url' => $this->api_url, 'refer' => $refer_page ) );
			}
		}
		// hook the head function for adding stylesheet
		function commentluv_style() {
			echo '<link rel="stylesheet" href="' . $this->plugin_url . 'style/cl_style.css" type="text/css" />';
		}

		// get plugin options
		function get_options() {
			// default values
			$options = array ('comment_text' => '[name]&#180;s last [type] ..[lastpost]', 'select_text' => 'choose a different post to show', 'default_on' => 'on', 'heart_tip' => 'on', 'use_template' => '', 'badge' => 'CL91x17-white2.gif', 'show_text' => 'CommentLuv Enabled', 'author_name' => 'author', 'url_name' => 'url', 'comment_name' => 'comment', 'email_name' => 'email', 'prepend' => '' );
			// get saved options unless reset button was pressed
			$saved = '';
			if (! isset ( $_POST ['reset'] )) {
				$saved = get_option ( $this->db_option );
			}

			// assign values
			if (! empty ( $saved )) {
				foreach ( $saved as $key => $option ) {
					$options [$key] = $option;
				}
			}
			// update the options if necessary
			if ($saved != $options) {
				update_option ( $this->db_option, $options );
			}
			// return the options
			return $options;
		}

		// handle saving and displaying options
		function handle_options() {
			$options = $this->get_options ();
			if (isset ( $_POST ['submitted'] )) {

				// initialize the error class
				$errors = new WP_Error ( );

				// check security
				check_admin_referer ( 'commentluv-nonce' );

				$options = array ();
				$options ['comment_text'] = htmlspecialchars ( $_POST ['cl_comment_text'] );
				$options ['select_text'] = htmlspecialchars ( $_POST ['cl_select_text'] );
				$options ['default_on'] = $_POST ['cl_default_on'];
				$options ['heart_tip'] = $_POST ['cl_heart_tip'];
				$options ['badge'] = $_POST ['cl_badge'];
				$options ['show_text'] = htmlspecialchars ( $_POST ['cl_show_text'] );
				$options ['prepend'] = htmlspecialchars ( $_POST ['cl_prepend'] );
				$options ['author_name'] = $_POST ['cl_author_name'];
				$options ['url_name'] = $_POST ['cl_url_name'];
				$options ['comment_name'] = $_POST ['cl_comment_name'];
				$options ['email_name'] = $_POST ['cl_email_name'];
				$options ['use_template'] = $_POST['cl_use_template'];

				// check for errors
				if (count ( $errors->errors ) > 0) {
					echo '<div class="error"><h3>';
					_e ( 'There were errors with your chosen settings', $this->plugin_domain );
					echo '</h3>';
					foreach ( $errors->get_error_messages () as $message ) {
						echo $message;
					}
					echo '</div>';
				} else {
					//every-ting cool mon
					update_option ( $this->db_option, $options );
					echo '<div class="updated fade"><p>Plugin settings saved.</p></div>';
				}

			}
			// loop through each option and assign it as key=value
			foreach ( $options as $key => $value ) {
				$$key = $value;
			}
			// set value to checked if option is on (for showing correct status of checkbox and radio button in settings page)
			$default_on = $options ['default_on'] == 'on' ? 'checked' : '';
			$heart_tip = $options ['heart_tip'] == 'on' ? 'checked' : '';
			$badge1 = $options ['badge'] == 'ACL88x31-black2.gif' ? 'checked="checked"' : '';
			$badge2 = $options ['badge'] == 'ACL88x31-white2.gif' ? 'checked="checked"' : '';
			$badge3 = $options ['badge'] == 'CL91x17-black2.gif' ? 'checked="checked"' : '';
			$badge4 = $options ['badge'] == 'CL91x17-white2.gif' ? 'checked="checked"' : '';
			$badge5 = $options ['badge'] == 'nothing.gif' ? 'checked="checked"' : '';
			$use_template = $options ['use_template'] == 'on' ? 'checked="checked"' : '';
			$badge_text = $options ['badge'] == 'text' ? 'checked="checked"' : '';

			// url for form submit
			$action_url = $_SERVER ['REQUEST_URI'];
			include ('commentluv-manager.php');
		}
		// shortcode for showing badge and drop down box
		function display_badge() {
			if (is_single ()) {
				global $badgeshown;
				$options = get_option ( $this->db_option );
				// choose as image or as text
				$badge_text = $options ['badge'] == 'text' ? 'on' : '';
				$default_on = $options ['default_on'] == 'on' ? 'checked="checked"' : '';
				// untick the box if user is admin
				global $user_ID;
				if ($user_ID) {
					if (current_user_can ( 'create_users' )) {
						$default_on = '';
					}
				}
				$options ['badge'] = $this->plugin_url . 'images/' . $options ['badge'];
				if ($badge_text == '') {
					$badge = '<a href="http://comluv.com" target="_blank"><img src="' . $options ['badge'] . '" border="0" alt="' . $options ['show_text'] . '" title="' . $options ['show_text'] . '"/></a>';
				} else {
					$badge = '<a href="http://comluv.com" target="_blank">' . $options ['show_text'] . '</a>';
				}
				if($options['prepend']){
					$prepend = stripslashes($options['prepend']);
					$decodeprepend = htmlspecialchars_decode_own($prepend);
				}
				echo '<div id="commentluv">' . $decodeprepend . '<input type="checkbox" id="doluv" name="doluv" ' . $default_on . ' style="width:25px;"></input><span id="mylastpost" style="clear: both">' . $badge . '</span><img class="clarrow" id="showmore" src="' . $this->plugin_url . 'images/down-arrow.gif" alt="show more" style="display:none;"/></div><div id="lastposts" style="display: none;"></div>';
				$badgeshown = TRUE;
			}
		}
		// hook the comment form to add fields for url for logged in users
		function add_fields($id) {
			$options = get_option ( $this->db_option );
			$cl_author_id = $options ['author_name'];
			$cl_site_id = $options ['url_name'];

			if (is_user_logged_in ()) {
				// get options values and insert as hidden fields
				global $userdata;
				get_currentuserinfo ();
				$author = $userdata->display_name;
				$userid = $userdata->ID;
				$url = $userdata->user_url;
				// check for MU blog
				if (function_exists ( 'is_site_admin' )) {
					if (! $url || $url == "http://") {
						$userbloginfo = get_blogs_of_user ( $userid, 1 );
						$url = $userbloginfo [1]->siteurl;
					}
				}

				echo "<input type='hidden' id='$cl_author_id' name='$cl_author_id' value='$author' />";
				echo "<input type='hidden' id='$cl_site_id' name='$cl_site_id' value='$url' />";
			}
			// add hidden fields for holding information about type,choice,html and request for every user
			echo '<input type="hidden" name="cl_type" />';
			echo '<input type="hidden" name="choice_id" />';
			echo '<input type="hidden" name="request_id" />';
			echo '<input type="hidden" name="cl_post" id="cl_post"/>';
			// check if using php call comments.php or not
			global $badgeshown;
			if ($options ['use_template'] == '' && !$badgeshown) {
				$this->display_badge ();
			}
			return $id;
		}

		// hook the pre_comment_content to add the link
		function cl_post($commentdata) {
			if (isset ( $_POST ['cl_post'] ) && $_POST ['request_id'] != '' && is_numeric ( $_POST ['choice_id'] ) && isset ( $_POST ['cl_type'] )) {
				// get values posted
				$luvlink = $_POST ['cl_post'];
				if (strstr ( $luvlink, "commentluv.com/error-check" ) || $_POST ['request_id'] == 0) {
					return $commentdata;
				}
				$request_id = $_POST ['request_id'];
				$choice_id = $_POST ['choice_id'];
				$cl_type = $_POST ['cl_type'];
				// convert data to put into comment content
				$options = get_option ( $this->db_option );
				$prepend_text = $options ['comment_text'];
				$search = array ('[name]', '[type]', '[lastpost]' );
				$replace = array ($_POST ["{$options['author_name']}"], $cl_type, $luvlink );
				$inserted = str_replace ( $search, $replace, $prepend_text );
				// insert identifying data and insert text/link to end of comment
				$commentdata .= "\n.-= $inserted =-.";
				// tell comluv that the comment was submitted
				$luvlink = stripslashes ( $luvlink );
				$thelinkstart = strpos ( $luvlink, '="' );
				$cutit = substr ( $luvlink, $thelinkstart + 2 );
				$hrefend = strpos ( $cutit, '"' );
				$thelink = substr ( $cutit, 0, $hrefend );
				// got the url, construct url to tell comluv
				$url = $this->api_url . "?type=approve&request_id=$request_id&post_id=$choice_id&url=$thelink&refer=".get_permalink();
				$content = $this->call_comluv ( $url );
			}
			return $commentdata;
		}
		// hook the set comment status action
		function update_cl_status($cid, $status) {
			// get comment stuff from id
			
			if ($status != 'spam') {
				if ($status != 'delete') {
					$status = 'approve';
				}
				$comment = get_comment ( $cid );
				if (strpos ( $comment->comment_content, ".-=" )) {
					// comment can be approved or deleted in the comluv db
					$url = $this->api_url . "?type={$status}&url=";
					// get the link
					$commentcontent = $comment->comment_content;
					$start = $this->my_strrpos( $commentcontent, '.-=' );
					$thelink = substr ( $commentcontent, $start + 4, strlen ( $commentcontent ) - $start - 5 );
					$hrefstart = strpos ( $thelink, '="' );
					$cutit = substr ( $thelink, $hrefstart + 2 );
					$hrefend = strpos ( $cutit, '"' );
					$thelink = substr ( $cutit, 0, $hrefend );
					// get comment date
					$date = $comment->comment_date_gmt;
					// construct url with added params for approving comment to comluv
					$url .= $thelink . "&comment_date=$date&version=" . $this->cl_version;
					// call the url ..
					$content = $this->call_comluv ( $url );
				} // end if comment content contains a .-=
			}
		}
		// use my own shortcode that was inserted at submission time and hide the params
		function do_shortcode($commentcontent) {
			$options = get_option ( $this->db_option );
			if (strpos ( $commentcontent, ".-=" ) && strpos ( $commentcontent, "=-." )) {
				$last_pos = $this->my_strrpos ( $commentcontent, ".-=" ); // position number for last occurence of .-=
				$beforecltext = substr ( $commentcontent, 0, $last_pos ); // get text before last position of .-=
				$cltext = substr ( $commentcontent, $last_pos ); // get the bit between .-= and =-.
				$cltext = str_replace ( array (".-=", "=-." ), array ('<span class="cluv">', '' ), $cltext ); // replace .-= with span and chop off last =-.
				$commentcontent = $beforecltext . $cltext;
				// do heart info
				if ($options ['heart_tip'] == 'on') {
					$commentcontent .= '<span class="heart_tip_box"><img class="heart_tip" alt="My ComLuv Profile" border="0" width="16" height="14" src="' . $this->plugin_url . 'images/littleheart.gif"/></span>';
				}
				$commentcontent .= '</span>';
			}

			// remove old codes
			if (strpos ( $commentcontent, "[rq=" ) && strpos ( $commentcontent, "[/rq]" )) {
				// get bit that was added
				$start = strpos ( $commentcontent, '[rq=' );
				$end = strpos ( $commentcontent, '[/rq]' ) + 5;
				$params = substr ( $commentcontent, $start, $end - $start );
				global $comment;
				$author_name = $comment->comment_author;
				$author_url = $comment->comment_author_url;
				// get array of params
				$params_arr = explode ( ",", substr ( $params, 4, - 6 ) );
				// get and prepare the text specified by the user
				$prepend_text = $options ['comment_text'];
				$search = array ('[name]', '[type]', '[lastpost]' );
				$replace = array ($author_name, $params_arr [2], '' );
				$inserted = '<span class="cluv">';
				$inserted .= str_replace ( $search, $replace, $prepend_text );
				$commentcontent = str_replace ( $params, $inserted, $commentcontent );
				if ($options ['heart_tip'] == 'on') {
					$commentcontent .= '<span class="heart_tip_box"><img class="heart_tip" alt="My ComLuv Profile" border="0" width="16" height="14" src="' . $this->plugin_url . 'images/littleheart.gif"/></span>';
				}
				$commentcontent .= '</span>';
			}
			return $commentcontent;
		}

		// set up default values
		function install() {
			// set default options
			$this->get_options ();
		}

		// Localization support
		function handle_load_domain() {
			// get current language
			$locale = get_locale ();

			// locate translation file
			$mofile = WP_PLUGIN_DIR . '/' . plugin_basename ( dirname ( __FILE__ ) ) . '/lang/' . $this->plugin_domain . '-' . $locale . '.mo';

			// load translation
			load_textdomain ( $this->plugin_domain, $mofile );
		}
		// call home to tell about comment submission or status
		function call_comluv($url) {
			if (function_exists ( "curl_init" )) {
				//setup curl values
				$curl = curl_init ();
				curl_setopt ( $curl, CURLOPT_URL, $url );
				curl_setopt ( $curl, CURLOPT_HEADER, 0 );
				curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, TRUE );
				curl_setopt ( $curl, CURLOPT_TIMEOUT, 7 );
				$content = curl_exec ( $curl );
				if (! curl_error ( $curl )) {
					if (function_exists ( json_decode )) {
						$data = json_decode ( $content );
						if ($data->status != 200) {
							// unsuccessful confirmation.
							// have a tantrum here if you want.
						}
					}
					curl_close ( $curl );

				}
			} elseif (ini_get ( 'allow_url_fopen' )) {
				$content = @file_get_contents ( $url );
			}
			return $content;
		}
		// find last occurrence of string in string (for php 4)
		function my_strrpos($haystack, $needle, $offset = 0) {
			// same as strrpos, except $needle can be a string
			// http://www.webmasterworld.com/forum88/10570.htm
			$strrpos = false;
			if (is_string ( $haystack ) && is_string ( $needle ) && is_numeric ( $offset )) {
				$strlen = strlen ( $haystack );
				$strpos = strpos ( strrev ( substr ( $haystack, $offset ) ), strrev ( $needle ) );
				if (is_numeric ( $strpos )) {
					$strrpos = $strlen - $strpos - strlen ( $needle );
				}
			}
			return $strrpos;
		}

	}
}

// start commentluv class engines
if (class_exists ( 'commentluv' )) :
$badgeshown=FALSE;
$commentluv = new commentluv ( );

// confirm warp capability
if (isset ( $commentluv )) {
	// engage
	register_activation_hook ( __FILE__, array (&$commentluv, 'install' ) );

}


endif;


// function for template call
function cl_display_badge() {
	$temp = new commentluv ( );
	$temp->display_badge ();
}

function htmlspecialchars_decode_own($string,$style=ENT_COMPAT)
{
	$translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
	if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
	return strtr($string,$translation);
}
?>