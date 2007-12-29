<?php /*
Plugin Name: Commentluv
Plugin URI: http://www.fiddyp.co.uk/commentluv-wordpress-plugin/
Description: Plugin to show a link to the last post from the commenters blog in their comment. Just activate and it's ready. Currently parses with wordpress, blogspot, typepad and blogs that have a feed link in the head section of their page.
Version: 0.999
Author: Andy Bailey
Author URI: http://www.fiddyp.co.uk/

*********************************************************************
You can change the message that is displayed under this change log...
*********************************************************************
updates:
0.999 - try and change the wrong use of ? in post titles.. - http://www.tarheelramblings.com
0.998 - try to make compatible with Shifter Theme System - http://www.jaynedarcy.us/
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
// *****************************************************************************
//************ you can edit the message that is displayed here ******************
//************ but be careful! only use single (') quotes not (") double ********
// *****************************************************************************

$cl_message="<em>Enable <a href='http://www.fiddyp.co.uk/commentluv-wordpress-plugin'>CommentLuv</a> which will try and get your last blog post, please be patient while it finds it for you</em>";


//************ you shouldn't edit below this line!*******************************

// text between function (to make it easy to parse different types of feeds and streams)
function LL_TextBetween($s1,$s2,$s){
	$s1 = strtolower($s1);
	$s2 = strtolower($s2);
	$L1 = strlen($s1);
	$scheck = strtolower($s);
	if($L1>0){$pos1 = strpos($scheck,$s1);} else {$pos1=0;}
	if($pos1 !== false){
		if($s2 == '') return substr($s,$pos1+$L1);
		$pos2 = strpos(substr($scheck,$pos1+$L1),$s2);
		if($pos2!==false) return substr($s,$pos1+$L1,$pos2);
	}
	return '';
}

// find feedburner feed function (parses a users page for a feed link)
function findfeedburner($page_url){
	// can't open default wordpress feed, use curl to parse users page for a relative link feed
	if(function_exists(curl_init)) {
		$ch=curl_init();
		$timeout = 10; // set to zero for no timeout
		curl_setopt ($ch, CURLOPT_URL, $page_url );
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data=curl_exec($ch);
		curl_close($ch);
		$lines=explode("\n",$data);
		// look for feedburner url
		foreach($lines as $line){
			if(strstr($line,"alternate")&&(strstr($line,"rss")||strstr($line,"xml"))){
				$pos=strpos($line,"href");
				$cut=substr($line,$pos+5);
				$feed_url=LL_TextBetween("\"","\"",$cut);
				break;
			}
		}
	}
	else // no curl here, borrow mine!
	{
		$rss=fetch_rss("http://www.commentluv.com/commentluvinc/cl_feedfind.php?url=$page_url");
		$items= array_slice($rss->items,0,1);
		foreach($items as $item){
			$feed_post=$item['link'];
		}
		return $feed_post;
	}
	return $feed_url;
}

// hooks, call comment_luv function just before comment is posted . gets passed array of comment fields
// hooks, call add_text when comment form is shown, gets passed id of post
add_filter('preprocess_comment','comment_luv',0);
add_action('comment_form','add_text');

// function to add text to bottom of form field
function add_text($id){
	global $cl_message;
	echo "<input name='luv' id='luv' value='luv' type='checkbox' style='width: auto;' checked='checked'/>";
	echo "<label for='luv'><!-- Added by CommentLuv Plugin v0.998 - Andy Bailey @ www.fiddyp.co.uk-->".$cl_message."</label>";
	return $id; // need to return what we got sent
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
		$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (luv) ',strlen($comment_data['comment_content']),0);
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
	// check for magpie timeout constant
	if(!defined('MAGPIE_FETCH_TIME_OUT')){
		define('MAGPIE_FETCH_TIME_OUT',5);
	}
	// set cache age to 5 minutes so it doesn't show an old last post if a commenter makes a new post and returns to comment again
	if(!defined('MAGPIE_CACHE_AGE')){
		define('MAGPIE_CACHE_AGE',300);
	}

	// use wp internal rss.php function (wp 2.1+ only)
	include_once(ABSPATH . WPINC . '/rss.php');

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
	// fetch feed with WP function
	$rss=fetch_rss("$feed_url");

	// couldn't find it try to parse users page if curl enabled
	if(!$rss && !$manual_feed){
		// debug
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (try parsing) ',strlen($comment_data['comment_content']),0);
		}
		$feed_url=findfeedburner($author_url);
		$rss=fetch_rss("$feed_url");
	}

	// couldn't find it! look in other places
	if(!$rss && !$manual_feed){
		// debug
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (try alternate) ',strlen($comment_data['comment_content']),0);
		}
		$feed_url="$author_url/?feed=rss";
		$rss=fetch_rss("$feed_url");
		// try own domain blogspot
		if(!$rss){
			if($debug) {
				$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (try blogspot location) ',strlen($comment_data['comment_content']),0);
			}
			$feed_url="$author_url/feeds/posts/default";
			$rss=fetch_rss("$feed_url");
		}
		// try typepad own domain
		if(!$rss) {
			if($debug) {
				$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (try typepad) ',strlen($comment_data['comment_content']),0);
			}
			$feed_url="$author_url/atom.xml";
			$rss=fetch_rss("$feed_url");
		}
	}


	// couldn't find it at all, just return with a sad face
	if(!$rss) {
		// debug
		if($debug) {
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (failed) ',strlen($comment_data['comment_content']),0);
		}
		return $comment_data;
	}

	// for compatibility with other comment plugins remove the wp_rel_nofollow functon call
	remove_filter('pre_comment_content', 'wp_rel_nofollow');

	// **************************
	// *** do the parse dance ***
	// **************************
	// now we must have a feed to parse, get last post title and link
	$items= array_slice($rss->items,0,1);
	foreach($items as $item){
		$feed_title=$item['title'];
		$feed_post=$item['link'];
	}
	
	// try and fix any single quotes that got changed to question marks
	$search = array("?s", "?t", "?l", "?v", "?m", "?d");
	$replace = array("'s", "'t", "'l", "'v", "'m", "'d");
	$feed_title=str_replace($search,$replace,$feed_title);

	// ****************************
	// *** append the last post ***
	// ****************************
	// insert last post data onto the end of the comment content
	if($feed_title && $feed_post){	// only output if last post found
		$author_excerpt="\n\n<em>".$comment_data['comment_author']."'s last blog post..<a href='$feed_post'>$feed_title</a></em>";
		$comment_data['comment_content']=substr_replace($comment_data['comment_content'], $author_excerpt,strlen($comment_data['comment_content']),0);
	}

	// thats it! pass back the new comment data to wordpress
	return $comment_data;


} // end function
?>