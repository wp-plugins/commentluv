<?php /*
Plugin Name: Commentluv
Plugin URI: http://www.fiddyp.co.uk/commentluv-wordpress-plugin/
Description: Plugin to show a link to the last post from the commenters blog in their comment. Just activate and it's ready. Currently parses with wordpress, blogspot, typepad and blogs that have a feed link in the head section of their page.
Version: 1.5
Author: Andy Bailey
Author URI: http://www.fiddyp.co.uk/

*********************************************************************
You can change the message that is displayed under this change log...
*********************************************************************
updates:
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

// hooks, call comment_luv function just before comment is posted . gets passed array of comment fields
// hooks, call add_text when comment form is shown, gets passed id of post
add_filter('preprocess_comment','comment_luv',0);
add_action('comment_form','add_text');
add_action('admin_menu', 'show_cl_options');
add_action('wp_head','cl_style');


//function for menu
function show_cl_options() {
    // Add a new submenu under Options:
    add_options_page('CommentLuv', 'CommentLuv', 8, 'commentluv', 'cl_options_page');
    add_option('cl_comment_text','[name]s last blog post..[lastpost]');
    add_option('cl_under_comment','Enable [commentluv] v1.3 which will try and parse your last blog post, please be patient while it finds it for you');
    add_option('cl_default_on','TRUE');
    add_option('cl_style','border:1px solid; display:block; padding:4px;');
        
}

// add style to head
function cl_style(){
	echo '<!-- Styling added by CommentLuv http://www.fiddyp.co.uk -->';
	echo '<style>.commentlist abbr{'.get_option('cl_style').'}</style>';
}
// function to add menu page under options

function cl_options_page(){
	?>
	<div class="wrap">
	<form method="post" action="options.php" id="options">
	<?php wp_nonce_field('update-options') ?>
	<h2>CommentLuv Wordpress Plugin</h2>
	<em>Beta testing version</em>
	<p>This plugin takes the url from the comment form and tries to parse the feed of the site and display the last entry made</p>
	<p>If you have any questions or comment, please visit <a href="http://www.fiddyp.co.uk" target="_blank">FiddyP Blog</a> and leave a comment</p>
	
	<p><strong>Enter the text you want displayed in the comment. &lt;abbr> &lt;/abbr> allows you to style the display.</strong>
	<input class="form-table" name="cl_comment_text" value="<?php echo get_option('cl_comment_text');?>"></input>
	<p>You can use:
	<br/>[name] to display author name
	<br/>[site] to display their site url
	<br />[lastpost] to display the named link to their last post</p></p>
	
	<p><strong>Enter the text to appear beneath the comment form informing the user that you are using CommentLuv</strong>
	<input class="form-table" name="cl_under_comment" value="<?php echo get_option('cl_under_comment');?>"></input></p>
	<p>Choose to have CommentLuv on by default?</p>
	<select name="cl_default_on">
	<option <?php if(get_option('cl_default_on')=="TRUE") {echo "selected=selected";}?> >TRUE</option>
	<option <?php if(get_option('cl_default_on')=="FALSE") { echo "selected=selected";}?> >FALSE</option>
	</select>
	<p>Enter css styling to apply to &lt;abbr> tag</p>
	<input class="form-table" name="cl_style" value="<?php echo get_option('cl_style');?>"></input>
	
	<input type="hidden" name="page_options" value="cl_comment_text,cl_under_comment,cl_default_on" />
	
	<input type="hidden" name="action" value="update" />
	<p class="submit"><input type="submit" name="Submit" value="<?php _e('Update Options') ?>" /></p>
	</form></div>
 <?php }
 
 
// function to add text to bottom of form field
function add_text($id){
$cl_under_comment=get_option('cl_under_comment');
$cl_under_comment=str_replace('[commentluv]','<a href="http://www.fiddyp.co.uk/commentluv-wordpress-plugin/">CommentLuv</a>',$cl_under_comment);	

	echo "<input name='luv' id='luv' value='luv' type='checkbox' style='width: auto;'";
	if(get_option('cl_default_on')=="TRUE") { echo "checked=checked />";}
	echo "<label for='luv'><!-- Added by CommentLuv Plugin v1.0 - Andy Bailey @ www.fiddyp.co.uk-->".$cl_under_comment;
	return $id; // need to return what we got sent
}

if (!function_exists('file_get_contents')) {
      		function file_get_contents($filename, $incpath = false, $resource_context = null) {
          		if (false === $fh = fopen($filename, 'rb', $incpath)) {
              		trigger_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
		              return false;
        		}
 
          	clearstatcache();
          	if ($fsize = @filesize($filename)) {
              $data = fread($fh, $fsize);
          	} else {
              $data = '';
              while (!feof($fh)) {
                  $data .= fread($fh, 8192);
            	}
          	}
 
          	fclose($fh);
          	return $data;
      		}
  		}	

// this is the magic part.
// function to parse the users feed and add a link to last post after the rest of the comment content
function comment_luv($comment_data){
	$manual_feed=0;
	$debug=0; // for my own debugging, shows a breadcrumb of what is tried for parsing
	$luv = $_POST['luv']; // get checkbox value for commentluv
	// check for debug command
	if(strstr($comment_data['comment_content'],"[debugon]")){
		$debug=1;
	}
	if($luv=='luv' && $debug) {
		$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (noluv) ',strlen($comment_data['comment_content']),0);
	}

	// don't parse for admin posting comment reply,pingback or trackback and checks if last post already added and check for luv box checked
	get_currentuserinfo() ;
	global $user_level;
	if ($luv!='luv' || $user_level > 7 || $comment_data['comment_type'] == 'pingback' || $comment_data['comment_type'] == 'trackback' || strstr($comment_data['comment_content'],"'s last blog post")) {
		return $comment_data;
	}
	// get author url
	$author_url=$comment_data['comment_author_url'];
	// if no author url given, return
	if(!$author_url){
		return $comment_data;
	}
	// clean up author url if it has a trailing forward slash
	if(substr($author_url,-1)=="/") {
		$author_url = substr($author_url, 0, -1);  // remove trailing slash
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (remove slash) ',strlen($comment_data['comment_content']),0);
		}
	}

	// ***********************
	// *** fun starts here ***
	// ***********************


	// **************************
	// *** identify blog type ***
	// **************************
	// try and determine blog type and locate default location for feed.
	if(strstr($author_url,"blogspot")){						// blogspot blog
		$feed_url="$author_url/feeds/posts/default/";
		// debug
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (blogspot) ',strlen($comment_data['comment_content']),0);
		}

	} elseif(strstr($author_url,"typepad")){				// typepad blog
		$feed_url="$author_url/atom.xml";
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (typepad) ',strlen($comment_data['comment_content']),0);
		}
	} elseif(strstr($author_url,"livejournal")){			// livejournal
		$feed_url="$author_url/data/rss";
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (livejournal) ',strlen($comment_data['comment_content']),0);
		}
	} elseif(strstr($author_url,"web-log")){ // web-log blog
		// take only the name of the author of http://xxx.web-log.nl
		preg_match('|http://(.*?).web-log.nl|is', $author_url, $authorid);
		$feed_url = $author_url . "/" . $authorid[1] . "/rss.xml";
	} else {
		$feed_url="$author_url/feed/";						// own domain or wordpress blog
		// debug
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (wp_norm) ',strlen($comment_data['comment_content']),0);
		}

	}

	// ***************************
	// *** detect manual entry ***
	// ***************************
	// here we see if user manually entered their own feed url
	if(strstr($comment_data['comment_content'],"[feed]")){
		$feed_url=LL_TextBetween("[feed]","[/feed]",$comment_data['comment_content']);
		// now strip feed bit from comment
		$manual_feed_pos_start=strpos($comment_data['comment_content'],"[feed]");
		$comment_data['comment_content']=substr($comment_data['comment_content'],0,$manual_feed_pos_start);
		$manual_feed=1;
		// debug
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (manual feed specified) ',strlen($comment_data['comment_content']),0);
		}

	}

	// *******************************
	// *** time to do the fetching ***
	// *******************************
	$url="http://www.commentluv.com/commentluvinc/remoteCL4.php?type=single&url=".$author_url;
	// try curl if it is enabled
	if(extension_loaded('curl') ){
		// debug
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (using curl) ',strlen($comment_data['comment_content']),0);
		}
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_HEADER,0);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);
		curl_setopt($curl,CURLOPT_TIMEOUT,5);
		$content=curl_exec($curl);
		curl_close($curl);		
	} else { 
		// try file instead
		// debug
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (using filegetcontents) ',strlen($comment_data['comment_content']),0);
		}
		$content=file_get_contents($url);
	}

	// for compatibility with other comment plugins remove the wp_rel_nofollow functon call
	remove_filter('pre_comment_content', 'wp_rel_nofollow');

	// try and fix any single quotes that got changed to question marks
	if(strstr($content,"?")){
		$search = array("?s", "?t", "?l", "?v", "?m", "?d");
		$replace = array("'s", "'t", "'l", "'v", "'m", "'d");
		$newcontent=str_replace($search,$replace,$content);
		$content=$newcontent;
	}
	

	// ****************************
	// *** append the last post ***
	// ****************************

	
	
	$content=mysql_real_escape_string($content);
	
	$cl_comment_text=get_option('cl_comment_text');
	$cl_default_on=get_option('cl_default_on');
	
	$search=array('[name]','[site]','[lastpost]');
	$replace=array($comment_data['comment_author'],$author_url,$content);
	
	$cl_comment_text=str_replace($search,$replace,$cl_comment_text);
		
	// insert last post data onto the end of the comment content
	if(strstr($content,"href")){	// only output if last post found
		$comment_data['comment_content']=substr_replace($comment_data['comment_content'], "\n\n".$cl_comment_text,strlen($comment_data['comment_content']),0);
	}

	// thats it! pass back the new comment data to wordpress
	return $comment_data;


} // end function
?>